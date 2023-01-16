<?php

namespace App\Http\Controllers;

use App\Enums\HttpStatusCodes;
use App\Facades\PermissionsFacade;
use App\Http\Resources\EventTicketTypeFieldResource;
use App\Models\Event;
use App\Models\EventTicketType;
use App\Models\EventTicketTypeField;
use Illuminate\Http\Request;

class EventTicketTypeFieldController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $id, $type_id)
    {
        $event = Event::where('id', $id)->first();

        if(!$event){
            return response()->json(['error' => 'The event does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        if(!PermissionsFacade::eventCanUpdate($event, $request->user())){
            return response()->json(['error' => 'Access denied to event.'], 403);
        }

        $type = EventTicketType::where('event_id', '=', $event->id)
        ->where('id', '=', $type_id)
        ->first();

        if(!$type){
            return response()->json(['error' => 'The ticket type does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        $fields = EventTicketTypeField::where('ticket_type_id', $type->id);

        $data = $fields->orderBy('event_ticket_type_fields.field_order', 'desc')->paginate(25);

        return EventTicketTypeFieldResource::collection($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id, $type_id)
    {
        $event = Event::where('id', $id)->first();

        if(!$event){
            return response()->json(['error' => 'The event does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        if(!PermissionsFacade::eventCanUpdate($event, $request->user())){
            return response()->json(['error' => 'Access denied to event.'], 403);
        }

        $type = EventTicketType::where('event_id', '=', $event->id)
        ->where('id', '=', $type_id)
        ->first();

        if(!$type){
            return response()->json(['error' => 'The ticket type does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        $input = $request->all();

        $input['event_id'] = $event->id;

        $input['ticket_type_id'] = $type->id;

        $field = new EventTicketTypeField();

        $valid = $field->validate($input);

        if (!$valid) {
            return response()->json($field->getValidationErrors(), 422);
        }

        $field = EventTicketTypeField::create($input);

        return new EventTicketTypeFieldResource($field);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\EventTicketType  $EventTicketType
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id, $type_id, $field_id)
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

        $field = EventTicketTypeField::where('ticket_type_id', '=', $type->id)
        ->where('id', '=', $field_id)
        ->first();

        if(!$field){
            return response()->json(['error' => 'The field does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        return new EventTicketTypeFieldResource($field);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\EventTicketType  $EventTicketType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id, $type_id, $field_id)
    {
        $event = Event::where('id', $id)->first();

        if(!$event){
            return response()->json(['error' => 'The event does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        if(!PermissionsFacade::eventCanUpdate($event, $request->user())){
            return response()->json(['error' => 'Access denied to event.'], 403);
        }

        $type = EventTicketType::where('event_id', '=', $event->id)
        ->where('id', '=', $type_id)
        ->first();

        if(!$type){
            return response()->json(['error' => 'The ticket type does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        $field = EventTicketTypeField::where('ticket_type_id', '=', $type->id)
        ->where('id', '=', $field_id)
        ->first();

        if(!$field){
            return response()->json(['error' => 'The field does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        $input = $request->all();

        $data = $input + $event->toArray();

        $data['ticket_type_id'] = $type->id;

        $data['id'] = $field->id;

        $valid = $field->validate($data);

        if (!$valid) {
            return response()->json($field->getValidationErrors(), 422);
        }

        $field->update($data);

        return new EventTicketTypeFieldResource($field);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\EventTicketType  $EventTicketType
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id, $type_id, $field_id)
    {
        $event = Event::where('id', $id)->first();

        if(!$event){
            return response()->json(['error' => 'The event does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        if(!PermissionsFacade::eventCanUpdate($event, $request->user())){
            return response()->json(['error' => 'Access denied to event.'], 403);
        }

        $type = EventTicketType::where('event_id', '=', $event->id)
        ->where('id', '=', $type_id)
        ->first();

        if(!$type){
            return response()->json(['error' => 'The ticket type does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        $field = EventTicketTypeField::where('ticket_type_id', '=', $type->id)
        ->where('id', '=', $field_id)
        ->first();

        if(!$field){
            return response()->json(['error' => 'The field does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        $field->delete();

        return response()->json(null, 204);
    }
}
