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

     /**
      * Competition team index 
      *
      * @OA\Get(
      *     path="/competitions/{uuid}/teams",
      *     summary="Displays a listing of the resource.",
      *     description="Displays a listing of the resource.",
      *     operationId="resourceTeamList",
      *     tags={"Competitions Route"},
      *     security={ {"bearer": {} }},
      *     @OA\Response(
      *         response=200,
      *         description="Success",
      *         @OA\JsonContent(
      *             required={"id"},
      *             @OA\Property(property="id", type="string"),
      *         )
      *     ),
      *     @OA\Response(
      *         response=403,
      *         description="No rounds listed",
      *         @OA\JsonContent(
      *              @OA\Property(property="message", type="Cannot list this team.")
      *              )
      *          )
      * )
      *     
      */
    public function index(Request $request, $id)
    {
        $competition = Competition::where('id', $id)->first();

        if(!$competition){
            return response()->json(['error' => 'The competition does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        $teams = CompetitionTeam::where('competition_id', '=', $competition->id);

        $data = $teams->orderBy('competition_teams.created_at', 'desc')->paginate(25);

        return CompetitionTeamResource::collection($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

     /**
      * Storage of teams
      *
      * @OA\Post(
      *     path="/competitions/{uuid}/teams",
      *     summary="Store a newly created resource in storage.",
      *     description="Store a newly created resource in storage.",
      *     operationId="resourceTeamStorage",
      *     tags={"Competitions Route"},
      *     security={ {"bearer": {} }},
      *     @OA\Response(
      *         response=200,
      *         description="Success",
      *         @OA\JsonContent(
      *             required={"id"},
      *             @OA\Property(property="id", type="string"),
      *         )
      *     ),
      *     @OA\Response(
      *         response=403,
      *         description="No rounds listed",
      *         @OA\JsonContent(
      *              @OA\Property(property="message", type="Cannot store this team.")
      *              )
      *          )
      * )
      *     
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

     /**
      * Display the specified resource.
      *
      * @OA\Get(
      *     path="/competitions/{uuid}/teams/{team_id}",
      *     summary="Display the specified resource.",
      *     description="Display the specified resource.",
      *     operationId="resourceTeamShow",
      *     tags={"Competitions Route"},
      *     security={ {"bearer": {} }},
      *     @OA\Response(
      *         response=200,
      *         description="Success",
      *         @OA\JsonContent(
      *             ref="app/Http/Resource/CompetitionTeamResource"
      *         )
      *     ),
      *     @OA\Response(
      *         response=403,
      *         description="No teams showed",
      *         @OA\JsonContent(
      *              @OA\Property(property="message", type="Cannot show this team.")
      *              )
      *          )
      * )
      *     
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

     /**
      *  Update
      *
      * @OA\Put(
      *     path="/competitions/{uuid}/teams/{team_id}",
      *     summary="Updating resource in storage.",
      *     description="Updating resource in storage with new information.",
      *     operationId="updateTeam",
      *     tags={"Competitions Route"},
      *     security={{"bearer": {}}},
      *     @OA\Response(
      *         response=200,
      *         description="Success",
      *     @OA\JsonContent(
      *         @OA\Property(
      *            property="data",
      *            ref="app/Http/Resource/CompetitionTeamResource"
      *                 ),
      *             )
      *     ),
      *     @OA\Response(
      *      response=403,
      *      description="Access denied to team.",
      *      @OA\JsonContent(
      *              @OA\Property(property="message", type="Access denined to team.")
      *              )
      *      ) 
      * )
      *     
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

     /**
      * Delete
      *
      * @OA\Delete(
      *     path="/competitions/{uuid}/teams/{team_id}",
      *     summary="Removes a specific resource from storage.",
      *     description="Removes a specific resource from storage.",
      *     operationId="destoryTeam",
      *     tags={"Competitions Route"},
      *     security={{"bearer": {}}},
      *     @OA\Response(
      *         response=200,
      *         description="Success",
      *     @OA\JsonContent(
      *         @OA\Property(
      *            ref="app/Http/Resource/CompetitionTeamResource"
      *                 ),
      *             )
      *     ),
      *     @OA\Response(
      *      response=403,
      *      description="Access denied to competition.",
      *      @OA\JsonContent(
      *              @OA\Property(property="message", type="Access denied to competition.")
      *              )
      *      ) 
      * )
      *     
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
