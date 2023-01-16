<?php

namespace App\Http\Controllers;

use App\Enums\HttpStatusCodes;
use App\Facades\PermissionsFacade;
use App\Http\Resources\EventTicketTypeSectionResource;
use App\Models\Event;
use App\Models\EventTicketType;
use App\Models\EventTicketTypeSection;
use Illuminate\Http\Request;

class EventTicketTypeSectionController extends Controller
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

        $type = EventTicketType::where('event_id', '=', $event->id)
        ->where('id', '=', $type_id)
        ->first();

        if(!$type){
            return response()->json(['error' => 'The ticket type does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        $sections = EventTicketTypeSection::where('ticket_type_id', $type->id);

        $data = $sections->orderBy('event_ticket_type_sections.section_order', 'desc')->paginate(25);

        return EventTicketTypeSectionResource::collection($data);
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

        $section = new EventTicketTypeSection();

        $valid = $section->validate($input);

        if (!$valid) {
            return response()->json($section->getValidationErrors(), 422);
        }

        $section = EventTicketTypeSection::create($input);

        return new EventTicketTypeSectionResource($section);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\EventTicketType  $EventTicketType
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id, $type_id, $section_id)
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

        $section = EventTicketTypeSection::where('ticket_type_id', '=', $type->id)
        ->where('id', '=', $section_id)
        ->first();

        if(!$section){
            return response()->json(['error' => 'The section does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        return new EventTicketTypeSectionResource($section);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\EventTicketType  $EventTicketType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id, $type_id, $section_id)
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

        $section = EventTicketTypeSection::where('ticket_type_id', '=', $type->id)
        ->where('id', '=', $section_id)
        ->first();

        if(!$section){
            return response()->json(['error' => 'The section does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        $input = $request->all();

        $data = $input + $event->toArray();

        $data['ticket_type_id'] = $type->id;

        $data['id'] = $section->id;

        $valid = $section->validate($data);

        if (!$valid) {
            return response()->json($section->getValidationErrors(), 422);
        }

        $section->update($data);

        return new EventTicketTypeSectionResource($section);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\EventTicketType  $EventTicketType
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id, $type_id, $section_id)
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

        $section = EventTicketTypeSection::where('ticket_type_id', '=', $type->id)
        ->where('id', '=', $section_id)
        ->first();

        if(!$section){
            return response()->json(['error' => 'The section does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        $section->delete();

        return response()->json(null, 204);
    }
}
