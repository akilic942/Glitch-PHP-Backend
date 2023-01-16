<?php

namespace App\Http\Controllers;

use App\Enums\HttpStatusCodes;
use App\Enums\Messages;
use App\Facades\EventTicketFacade;
use App\Facades\PermissionsFacade;
use App\Http\Resources\EventTicketPurchaseResource;
use App\Models\Event;
use App\Models\EventTicketPurchase;
use App\Models\EventTicketType;
use Exception;
use Illuminate\Http\Request;

class EventTicketPurchaseController extends Controller
{

    public function index(Request $request, $id, $type_id)
    {
        $event = Event::where('id', $id)->first();

        if(!$event){
            return response()->json(['error' => 'The event does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        $type = EventTicketType::where('event_id', '=', $event->id)
        ->where('id', '=', $type_id)
        ->first();

        if(!$type){
            return response()->json(['error' => 'The ticket type does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        $purchases = EventTicketPurchase::where('ticket_type_id', $type->id);

        $data = $purchases->orderBy('event_ticket_purchases.created_at', 'desc')->paginate(25);

        return EventTicketPurchaseResource::collection($data);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function purchase(Request $request, $id, $type_id)
    {
        $event = Event::where('id', $id)->first();

        if(!$event){
            return response()->json(['error' => Messages::ERROR_NOT_EXIST_COMPETITION], HttpStatusCodes::HTTP_FOUND);
        }

        $type = EventTicketType::where('event_id', '=', $event->id)
        ->where('id', '=', $type_id)
        ->first();

        if(!$type){
            return response()->json(['error' => Messages::ERROR_NOT_EXIST_TICKET_TYPE], HttpStatusCodes::HTTP_FOUND);
        }

        $purchase = new EventTicketPurchase();

        $input = $request->all();

        $input['ticket_type_id'] = $type->id;

        $valid = $purchase->validate($input);

        if (!$valid) {
            return response()->json($purchase->getValidationErrors(), 422);
        }

        $quantity = $input['quantity'];

        if(!EventTicketFacade::isOverMinPurchasable($type, $quantity)){
            return response()->json(['error' => Messages::ERROR_TICKET_QUANTITY_NOT_OVER_MIN], HttpStatusCodes::HTTP_NOT_ACCEPTABLE);
        }

        if(!EventTicketFacade::isUnderMaxAvailable($type, $quantity)){
            return response()->json(['error' => Messages::ERROR_TICKET_QUANTITY_NONE_AVAILABLE], HttpStatusCodes::HTTP_NOT_ACCEPTABLE);
        }

        if(!EventTicketFacade::isUnderMaxPurchasable($type, $quantity)){
            return response()->json(['error' => Messages::ERROR_TICKET_QUANTITY_OVER_MAX], HttpStatusCodes::HTTP_NOT_ACCEPTABLE);
        }

        $current_user = $request->user();

        $result = EventTicketFacade::validateFields($type, $input);

        if(!$result['status']) {
            return response()->json(['error' => $result['errors']], HttpStatusCodes::HTTP_NOT_ACCEPTABLE);
        }

        $forceFillData = [
            'quantity' => $quantity,
            'ticket_type_id' => $type->id
        ];

        if($type->requires_account && !$current_user) {
            return response()->json(['error' => Messages::ERROR_REQUIRES_SESSION], HttpStatusCodes::HTTP_NOT_ACCEPTABLE);
        }  else if($type->requires_account && $current_user) {
            $forceFillData['user_id'] = $current_user->id;
        }

        $purchase = new EventTicketPurchase();

        $purchase->forceFill($forceFillData);

        $purchase->save();

        $purchase = EventTicketFacade::saveInput($type, $purchase, $input);

        try {

            $purchase = EventTicketFacade::processPayment($type, $purchase, $quantity);

        } catch(Exception $e) {
            return response()->json(['error' => $e->getMessage()], HttpStatusCodes::HTTP_NOT_ACCEPTABLE);
        }


        return new EventTicketPurchaseResource($purchase);

    }

    public function show(Request $request, $id, $type_id, $purchase_id)
    {
        $event = Event::where('id', $id)->first();

        if(!$event){
            return response()->json(['error' => 'The event does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        $type = EventTicketType::where('event_id', '=', $event->id)
        ->where('id', '=', $type_id)
        ->first();

        if(!$type){
            return response()->json(['error' => 'The ticket type does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        $purchase = EventTicketPurchase::where('ticket_type_id', '=', $type->id)
        ->where('id', '=', $purchase_id)
        ->first();

        if(!$purchase){
            return response()->json(['error' => 'The field does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        $input = $request->all();

        $user = ($request->user()) ? $request->user() : null;

        $token = null;

        if(isset($input['admin_token'])) {
            $token = $input['admin_token'];
        }

        if(!$token && isset($input['access_token'])) {
            $token = $input['access_token'];
        }

        if(!PermissionsFacade::eventCanAccessTicketPurchase($purchase, $token, $user)) {
            return response()->json(['error' => Messages::ERROR_ACCESS_DENIED_TICKET_PURCHASE], HttpStatusCodes::HTTP_UNAUTHORIZED);
        }

        return new EventTicketPurchaseResource($purchase);
    }

}
