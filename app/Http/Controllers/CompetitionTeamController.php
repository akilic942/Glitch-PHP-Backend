<?php

namespace App\Http\Controllers;

use App\Enums\HttpStatusCodes;
use App\Facades\PermissionsFacade;
use App\Http\Resources\CompetitionTeamResource;
use App\Models\Competition;
use App\Models\CompetitionTeam;
use Illuminate\Http\Request;

class CompetitionTeamController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $id)
    {
        $competition = Competition::where('id', $id)->first();

        if(!$competition){
            return response()->json(['error' => 'The competition does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        $teams = CompetitionTeam::query();

        $data = $teams->orderBy('competition_teams.created_at', 'desc')->paginate(25);

        return CompetitionTeamResource::collection($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id)
    {
        $competition = Competition::where('id', $id)->first();

        if(!$competition){
            return response()->json(['error' => 'The competition does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        if(!PermissionsFacade::competitionCanUpdate($competition, $request->user())){
            return response()->json(['error' => 'Access denied to competition.'], 403);
        }

        $input = $request->all();

        $input['competition_id'] = $competition->id;

        $team = new CompetitionTeam();

        $valid = $team->validate($input);

        if (!$valid) {
            return response()->json($team->getValidationErrors(), 422);
        }

        $team = CompetitionTeam::create($input);

        return new CompetitionTeamResource($team);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CompetitionTeam  $competitionTeam
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id, $subid)
    {
        $competition = Competition::where('id', $id)->first();

        if(!$competition){
            return response()->json(['error' => 'The competition does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        $team = CompetitionTeam::where('competition_id', $id)->where('team_id', $subid)->first();

        if(!$team){
            return response()->json(['error' => 'The competition/team relationship does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        return new CompetitionTeamResource($team);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CompetitionTeam  $competitionTeam
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id, $subid)
    {
        $competition = Competition::where('id', $id)->first();

        if(!$competition){
            return response()->json(['error' => 'The competition does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        if(!PermissionsFacade::competitionCanUpdate($competition, $request->user())){
            return response()->json(['error' => 'Access denied to competition.'], 403);
        }

        $team = CompetitionTeam::where('competition_id', $id)->where('team_id', $subid)->first();

        if(!$team){
            return response()->json(['error' => 'The competition/team relationship does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        $input = $request->all();

        $data = $input + $competition->toArray();

        $data['competition_id'] = $competition->id;

        $data['team_id'] = $team->team_id;

        $valid = $team->validate($data);

        if (!$valid) {
            return response()->json($team->getValidationErrors(), 422);
        }

        $team->update($data);

        return new CompetitionTeamResource($team);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CompetitionTeam  $competitionTeam
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id, $subid)
    {
        $competition = Competition::where('id', $id)->first();

        if(!$competition){
            return response()->json(['error' => 'The competition does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        if(!PermissionsFacade::competitionCanUpdate($competition, $request->user())){
            return response()->json(['error' => 'Access denied to competition.'], 403);
        }

        $team = CompetitionTeam::where('competition_id', $id)->where('team_id', $subid)->first();

        if(!$team){
            return response()->json(['error' => 'The competition/team relationship does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        $team->delete();

        return response()->json(null, 204);
    }
}
