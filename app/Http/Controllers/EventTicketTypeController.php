<?php

namespace App\Http\Controllers;

use App\Enums\HttpStatusCodes;
use App\Facades\PermissionsFacade;
use App\Http\Resources\EventTicketTypeResource;
use App\Models\Event;
use App\Models\EventTicketType;
use Illuminate\Http\Request;

class EventTicketTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $id)
    {
        $event = Event::where('id', $id)->first();

        if(!$event){
            return response()->json(['error' => 'The event does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        $typees = EventTicketType::where('event_id', '=', $event->id);

        $data = $typees->orderBy('event_ticket_types.created_at', 'desc')->paginate(25);

        return EventTicketTypeResource::collection($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id)
    {
        $event = Event::where('id', $id)->first();

        if(!$event){
            return response()->json(['error' => 'The event does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        if(!PermissionsFacade::eventCanUpdate($event, $request->user())){
            return response()->json(['error' => 'Access denied to event.'], 403);
        }

        $input = $request->all();

        $input['event_id'] = $event->id;

        $type = new EventTicketType();

        $valid = $type->validate($input);

        if (!$valid) {
            return response()->json($type->getValidationErrors(), 422);
        }

        $type = EventTicketType::create($input);

        return new EventTicketTypeResource($type);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\EventTicketType  $EventTicketType
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id, $subid)
    {
        $event = Event::where('id', $id)->first();

        if(!$event){
            return response()->json(['error' => 'The event does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        $type = EventTicketType::where('event_id', $id)->where('id', $subid)->first();

        if(!$type){
            return response()->json(['error' => 'The ticket type does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        return new EventTicketTypeResource($type);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\EventTicketType  $EventTicketType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id, $subid)
    {
        $event = Event::where('id', $id)->first();

        if(!$event){
            return response()->json(['error' => 'The event does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        if(!PermissionsFacade::eventCanUpdate($event, $request->user())){
            return response()->json(['error' => 'Access denied to event.'], 403);
        }

        $type = EventTicketType::where('event_id', $id)->where('id', $subid)->first();

        if(!$type){
            return response()->json(['error' => 'The ticket type does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        $input = $request->all();

        $data = $input + $event->toArray();

        $data['event_id'] = $event->id;

        $data['id'] = $type->id;

        $valid = $type->validate($data);

        if (!$valid) {
            return response()->json($type->getValidationErrors(), 422);
        }

        $type->update($data);

        return new EventTicketTypeResource($type);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\EventTicketType  $EventTicketType
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id, $subid)
    {
        $event = Event::where('id', $id)->first();

        if(!$event){
            return response()->json(['error' => 'The event does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        if(!PermissionsFacade::eventCanUpdate($event, $request->user())){
            return response()->json(['error' => 'Access denied to event.'], 403);
        }

        $type = EventTicketType::where('event_id', $id)->where('id', $subid)->first();

        if(!$type){
            return response()->json(['error' => 'The ticket type does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        $type->delete();

        return response()->json(null, 204);
    }
}
