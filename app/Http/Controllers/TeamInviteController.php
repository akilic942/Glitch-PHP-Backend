<?php

namespace App\Http\Controllers;

use App\Enums\HttpStatusCodes;
use App\Facades\PermissionsFacade;
use App\Facades\TeamInvitesFacade;
use App\Http\Resources\TeamInviteResource;
use App\Models\Team;
use App\Models\TeamInvite;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TeamInviteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $id)
    {
        $team = Team::where('id', $id)->first();

        if(!$team){
            return response()->json(['error' => 'The team does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        // check if currently authenticated user is the owner of the book
        if(!PermissionsFacade::teamCanUpdate($team, $request->user())){
           return response()->json(['error' => 'Cannot invite user to the stream.', 'message' => 'Cannot invite user to the stream.'], 403);
        }

        $invites = TeamInvite::where('team_id', $id)->get();

        return TeamInviteResource::collection($invites);
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
        $team = Team::where('id', $id)->first();

        if(!$team){
            return response()->json(['error' => 'The team does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        // check if currently authenticated user is the owner of the book
        if(!PermissionsFacade::teamCanUpdate($team, $request->user())){
           return response()->json(['error' => 'Cannot invite user to the stream.', 'message' => 'Cannot invite user to the stream.'], 403);
        }

        $input = $request->all();

        $input['token'] = Str::random(30);
        $input['team_id'] = $team->id;

        $invite = new TeamInvite();

        $valid = $invite->validate($input);

        if (!$valid) {
            return response()->json($invite->getValidationErrors(), 422);
        }

        $invite = TeamInvite::create($input);

        if($invite) {
            TeamInvitesFacade::sendInvite($invite);
        }

        return new TeamInviteResource($invite);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TeamInvite  $teamInvite
     * @return \Illuminate\Http\Response
     */
    public function show(TeamInvite $teamInvite)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\TeamInvite  $teamInvite
     * @return \Illuminate\Http\Response
     */
    public function edit(TeamInvite $teamInvite)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TeamInvite  $teamInvite
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TeamInvite $teamInvite)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TeamInvite  $teamInvite
     * @return \Illuminate\Http\Response
     */
    public function destroy(TeamInvite $teamInvite)
    {
        //
    }

    public function acceptInvite(Request $request, $id){

        $team = Team::where('id', $id)->first();

        if(!$team){
            return response()->json(['error' => 'The team does not exist.', 'message' => 'The team does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }


        $input = $request->all();

        if(!isset($input['token']) || (isset($input['token']) || !$input['token'])){
            return response()->json(['message' => 'Invites require the token to accept.'], HttpStatusCodes::HTTP_NO_CONTENT);
        }

        $invite = TeamInvite::where('token', $input['token'])->first();

        if(!$invite) {
            return response()->json(['Invite not found.'], HttpStatusCodes::HTTP_NOT_FOUND);
        }

        if($team->id != $invite->team_id) {
            return response()->json(['error' => 'Invite does not belong to this team', 'message' => 'Invite does not belong to this event'], HttpStatusCodes::HTTP_FORBIDDEN);
        }

        $invite = TeamInvitesFacade::acceptInvite($invite, $request->user());

        if($invite->user_id != $request->user()->id) {
            return response()->json(['message' => 'Invite is not associated with the current user.'], HttpStatusCodes::HTTP_FORBIDDEN);
        } 
  

        return new TeamInviteResource($invite);

    }
}
