<?php

namespace App\Http\Controllers;

use App\Enums\HttpStatusCodes;
use App\Facades\PermissionsFacade;
use App\Http\Resources\TeamUserResource;
use App\Models\Team;
use App\Models\TeamUser;
use Illuminate\Http\Request;

class TeamUserController extends Controller
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

        $users = TeamUser::query();

        $data = $users->orderBy('team_users.created_at', 'desc')->paginate(25);

        return TeamUserResource::collection($data);
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

        if(!PermissionsFacade::teamCanUpdate($team, $request->user())){
            return response()->json(['error' => 'Access denied to team.'], 403);
        }

        $input = $request->all();

        $input['team_id'] = $team->id;

        $user = new TeamUser();

        $valid = $user->validate($input);

        if (!$valid) {
            return response()->json($user->getValidationErrors(), 422);
        }

        $user = TeamUser::create($input);

        return new TeamUserResource($user);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TeamUser  $teamUser
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id, $subid)
    {
        $team = Team::where('id', $id)->first();

        if(!$team){
            return response()->json(['error' => 'The team does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        $user = TeamUser::where('team_id', $id)->where('user_id', $subid)->first();

        if(!$user){
            return response()->json(['error' => 'The team/user relationship does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        return new TeamUserResource($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TeamUser  $teamUser
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id, $subid)
    {
        $team = Team::where('id', $id)->first();

        if(!$team){
            return response()->json(['error' => 'The team does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        if(!PermissionsFacade::teamCanUpdate($team, $request->user())){
            return response()->json(['error' => 'Access denied to team.'], 403);
        }

        $user = TeamUser::where('team_id', $id)->where('user_id', $subid)->first();

        if(!$user){
            return response()->json(['error' => 'The team/user relationship does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        $input = $request->all();

        $data = $input + $team->toArray();

        $data['team_id'] = $team->id;

        $data['user_id'] = $user->user_id;

        $valid = $user->validate($data);

        if (!$valid) {
            return response()->json($user->getValidationErrors(), 422);
        }

        $user->update($data);

        return new TeamUserResource($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TeamUser  $teamUser
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id, $subid)
    {
        $team = Team::where('id', $id)->first();

        if(!$team){
            return response()->json(['error' => 'The team does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        if(!PermissionsFacade::teamCanUpdate($team, $request->user())){
            return response()->json(['error' => 'Access denied to team.'], 403);
        }

        $user = TeamUser::where('team_id', $id)->where('user_id', $subid)->first();

        if(!$user){
            return response()->json(['error' => 'The team/user relationship does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        $user->delete();

        return response()->json(null, 204);
    }
}
