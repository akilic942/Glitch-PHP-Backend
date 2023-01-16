<?php

namespace App\Http\Controllers;

use App\Enums\HttpStatusCodes;
use App\Enums\Messages;
use App\Facades\CompetitionTicketFacade;
use App\Facades\PermissionsFacade;
use App\Http\Resources\CompetitionTicketPurchaseResource;
use App\Models\Competition;
use App\Models\CompetitionTicketPurchase;
use App\Models\CompetitionTicketType;
use Exception;
use Illuminate\Http\Request;

class CompetitionTicketPurchaseController extends Controller
{

    public function index(Request $request, $id, $type_id)
    {
        $competition = Competition::where('id', $id)->first();

        if(!$competition){
            return response()->json(['error' => 'The competition does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        $type = CompetitionTicketType::where('competition_id', '=', $competition->id)
        ->where('id', '=', $type_id)
        ->first();

        if(!$type){
            return response()->json(['error' => 'The ticket type does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        $purchases = CompetitionTicketPurchase::where('ticket_type_id', $type->id);

        $data = $purchases->orderBy('competition_ticket_purchases.created_at', 'desc')->paginate(25);

        return CompetitionTicketPurchaseResource::collection($data);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function purchase(Request $request, $id, $type_id)
    {
        $competition = Competition::where('id', $id)->first();

        if(!$competition){
            return response()->json(['error' => Messages::ERROR_NOT_EXIST_COMPETITION], HttpStatusCodes::HTTP_FOUND);
        }

        $type = CompetitionTicketType::where('competition_id', '=', $competition->id)
        ->where('id', '=', $type_id)
        ->first();

        if(!$type){
            return response()->json(['error' => Messages::ERROR_NOT_EXIST_TICKET_TYPE], HttpStatusCodes::HTTP_FOUND);
        }

        $purchase = new CompetitionTicketPurchase();

        $input = $request->all();

        $input['ticket_type_id'] = $type->id;

        $valid = $purchase->validate($input);

        if (!$valid) {
            return response()->json($purchase->getValidationErrors(), 422);
        }

        $quantity = $input['quantity'];

        if(!CompetitionTicketFacade::isOverMinPurchasable($type, $quantity)){
            return response()->json(['error' => Messages::ERROR_TICKET_QUANTITY_NOT_OVER_MIN], HttpStatusCodes::HTTP_NOT_ACCEPTABLE);
        }

        if(!CompetitionTicketFacade::isUnderMaxAvailable($type, $quantity)){
            return response()->json(['error' => Messages::ERROR_TICKET_QUANTITY_NONE_AVAILABLE], HttpStatusCodes::HTTP_NOT_ACCEPTABLE);
        }

        if(!CompetitionTicketFacade::isUnderMaxPurchasable($type, $quantity)){
            return response()->json(['error' => Messages::ERROR_TICKET_QUANTITY_OVER_MAX], HttpStatusCodes::HTTP_NOT_ACCEPTABLE);
        }

        $result = CompetitionTicketFacade::validateFields($type, $input);

        if(!$result['status']) {
            return response()->json(['error' => $result['errors']], HttpStatusCodes::HTTP_NOT_ACCEPTABLE);
        }

        $current_user = $request->user();

        $forceFillData = [
            'quantity' => $quantity,
            'ticket_type_id' => $type->id
        ];

        if($type->requires_account && !$current_user) {
            return response()->json(['error' => Messages::ERROR_REQUIRES_SESSION], HttpStatusCodes::HTTP_NOT_ACCEPTABLE);
        }  else if($type->requires_account && $current_user) {
            $forceFillData['user_id'] = $current_user->id;
        }

        $purchase = new CompetitionTicketPurchase();

        $purchase->forceFill($forceFillData);

        $purchase->save();

        $purchase = CompetitionTicketFacade::saveInput($type, $purchase, $input);

        try {

            $purchase = CompetitionTicketFacade::processPayment($type, $purchase, $quantity);

        } catch(Exception $e) {
            return response()->json(['error' => $e->getMessage()], HttpStatusCodes::HTTP_NOT_ACCEPTABLE);
        }


        return new CompetitionTicketPurchaseResource($purchase);

    }

    public function show(Request $request, $id, $type_id, $purchase_id)
    {
        $competition = Competition::where('id', $id)->first();

        if(!$competition){
            return response()->json(['error' => 'The competition does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        $type = CompetitionTicketType::where('competition_id', '=', $competition->id)
        ->where('id', '=', $type_id)
        ->first();

        if(!$type){
            return response()->json(['error' => 'The ticket type does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        $purchase = CompetitionTicketPurchase::where('ticket_type_id', '=', $type->id)
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

        if(!PermissionsFacade::competitionCanAccessTicketPurchase($purchase, $token, $user)) {
            return response()->json(['error' => Messages::ERROR_ACCESS_DENIED_TICKET_PURCHASE], HttpStatusCodes::HTTP_UNAUTHORIZED);
        }

        return new CompetitionTicketPurchaseResource($purchase);
    }

}
