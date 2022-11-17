<?php

namespace App\Http\Controllers;

use App\Enums\HttpStatusCodes;
use App\Facades\EventInvitesFacade;
use App\Facades\PermissionsFacade;
use App\Http\Resources\EventInviteResource;
use App\Models\Event;
use App\Models\EventInvite;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EventInviteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
            return response()->json(['error' => 'The stream does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        // check if currently authenticated user is the owner of the book
        if(!PermissionsFacade::eventCanUpdate($event, $request->user())){
           return response()->json(['error' => 'Cannot invite user to the stream.', 'message' => 'Cannot invite user to the stream.'], 403);
        }

        $input = $request->all();

        $input['token'] = Str::random(30);
        $input['event_id'] = $event->id;

        $invite = new EventInvite();

        $valid = $invite->validate($input);

        if (!$valid) {
            return response()->json($invite->getValidationErrors(), 422);
        }

        $invite = EventInvite::create($input);

        if($invite) {
            EventInvitesFacade::sendInvite($invite);
        }

        return new EventInviteResource($invite);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\EventInvite  $eventInvite
     * @return \Illuminate\Http\Response
     */
    public function show(EventInvite $eventInvite)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\EventInvite  $eventInvite
     * @return \Illuminate\Http\Response
     */
    public function edit(EventInvite $eventInvite)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\EventInvite  $eventInvite
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, EventInvite $eventInvite)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\EventInvite  $eventInvite
     * @return \Illuminate\Http\Response
     */
    public function destroy(EventInvite $eventInvite)
    {
        //
    }

    public function acceptInvite(Request $request, $id){

        $event = Event::where('id', $id)->first();

        if(!$event){
            return response()->json(['error' => 'The stream does not exist.', 'message' => 'The stream does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }


        $input = $request->all();

        if(isset($input['token'])){
            return response()->json(['message' => 'Invites require the token to accept.'], HttpStatusCodes::HTTP_NO_CONTENT);
        }

        $invite = EventInvite::where('token', $input['token'])->first();

        if(!$invite) {
            return response()->json(['Invite not found.'], HttpStatusCodes::HTTP_NOT_FOUND);
        }

        if($event->id != $invite->event_id) {
            return response()->json(['error' => 'Invite does not belong to this event', 'message' => 'Invite does not belong to this event'], HttpStatusCodes::HTTP_FORBIDDEN);
        }

        $invite = EventInvitesFacade::acceptInvite($invite, $request->user());

        if($invite->user_id != $request->user()->id) {
            return response()->json(['message' => 'Invite is not associated with the current user.'], HttpStatusCodes::HTTP_FORBIDDEN);
        } 
  

        return new EventInviteResource($invite);

    }
}
