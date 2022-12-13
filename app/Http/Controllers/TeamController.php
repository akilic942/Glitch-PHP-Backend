<?php

namespace App\Http\Controllers;

use App\Enums\HttpStatusCodes;
use App\Facades\PermissionsFacade;
use App\Facades\RolesFacade;
use App\Http\Resources\TeamResource;
use App\Models\Team;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $teams = Team::query();

        $data = $teams->orderBy('teams.created_at', 'desc')->paginate(25);

        return TeamResource::collection($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();

        $team = new Team();

        $valid = $team->validate($input);

        if (!$valid) {
            return response()->json($team->getValidationErrors(), 422);
        }

        $team = Team::create($input);

        if($team) {
            RolesFacade::teamMakeSuperAdmin($team, $request->user());
        }

        return new TeamResource($team);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Team  $team
     * @return \Illuminate\Http\Response
     */
    public function show(Team $team, $id)
    {
        $team = Team::where('id', $id)->first();

        return new TeamResource($team);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Team  $team
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $team = Team::where('id', $id)->first();

        if(!$team){
            return response()->json(['error' => 'The stream does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        if(!PermissionsFacade::teamCanUpdate($team, $request->user())){
            return response()->json(['error' => 'Access denied to live stream.' , 'message' => 'Access denied to live stream.'], 403);
        }

        $input = $request->all();

        $data = $input + $team->toArray();

        $valid = $team->validate($data);

        if (!$valid) {
            return response()->json($team->getValidationErrors(), 422);
        }

        $team->update($data);

        return new TeamResource($team);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Team  $team
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $team = Team::where('id', $id)->first();

        if(!PermissionsFacade::teamCanUpdate($team, $request->user())){
            return response()->json(['error' => 'Access denied to live stream.'], 403);
        }
        
        $team->delete();

        return response()->json(null, 204);
    }
}
