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
     *     summary="Retrieve a list of teams associated with the competition.",
     *     description="Retrieve a list of teams associated with the competition.",
     *     operationId="resourceCompetitionTeamList",
     *     tags={"Competitions Route"},
     *     security={ {"bearer": {} }},
     *     @OA\Parameter(
     *         name="uuid",
     *         in="path",
     *         description="UUID of the competition",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             format="uuid"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/CompetitionTeam"),
     *          )
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

        if (!$competition) {
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
     *     summary="Associate a new team with the competition.",
     *     description="Associate a new team with the competition.",
     *     operationId="resourceCompetitionTeamStorage",
     *     tags={"Competitions Route"},
     *     security={ {"bearer": {} }},
     *     @OA\Parameter(
     *         name="uuid",
     *         in="path",
     *         description="UUID of the competition",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             format="uuid"
     *         )
     *      ),
     *     @OA\RequestBody(
     *         @OA\JsonContent( ref="#/components/schemas/CompetitionTeam")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             ref="#/components/schemas/CompetitionTeam"
     *         ),
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Access denied to competition.",
     *         @OA\JsonContent(
     *              @OA\Property(property="message", type="Access denied to competition.")
     *              )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="The competition does not exist.",
     *         @OA\JsonContent(
     *              @OA\Property(property="message", type="The competition does not exist.")
     *              )
     *     ),
     * )
     *     
     */
    public function store(Request $request, $id)
    {
        $competition = Competition::where('id', $id)->first();

        if (!$competition) {
            return response()->json(['error' => 'The competition does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        if (!PermissionsFacade::competitionCanUpdate($competition, $request->user())) {
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
     *     summary="Display the contents of a single team associated with the competition.",
     *     description="Display the contents of a single team associated with the competition.",
     *     operationId="resourceTeamShow",
     *     tags={"Competitions Route"},
     *     security={ {"bearer": {} }},
     *     @OA\Parameter(
     *         name="uuid",
     *         in="path",
     *         description="UUID of the competition.",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             format="uuid"
     *         )
     *      ),
     *     @OA\Parameter(
     *         name="team",
     *         in="path",
     *         description="UUID of the team.",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             format="uuid"
     *         )
     *      ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             ref="#/components/schemas/CompetitionTeam"
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="The competition/team relationship does not exist.",
     *         @OA\JsonContent(
     *              @OA\Property(property="message", type="The competition/team relationship does not exist.")
     *         )
     *     )
     * )
     *     
     */
    public function show(Request $request, $id, $subid)
    {
        $competition = Competition::where('id', $id)->first();

        if (!$competition) {
            return response()->json(['error' => 'The competition does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        $team = CompetitionTeam::where('competition_id', $id)->where('team_id', $subid)->first();

        if (!$team) {
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
     * @OA\Put(
     *     path="/competitions/{uuid}/teams/{team_id}",
     *     summary="Update the team information associated with the competition.",
     *     description="Update the team information associated with the competition.",
     *     operationId="updateTeam",
     *     tags={"Competitions Route"},
     *     security={{"bearer": {}}},
     *     @OA\Parameter(
     *         name="uuid",
     *         in="path",
     *         description="UUID of the competition.",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             format="uuid"
     *         )
     *      ),
     *     @OA\Parameter(
     *         name="team_id",
     *         in="path",
     *         description="UUID of the team.",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             format="uuid"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         @OA\JsonContent( ref="#/components/schemas/CompetitionTeam")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             ref="#/components/schemas/CompetitionTeam"
     *         ),
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

        if (!$competition) {
            return response()->json(['error' => 'The competition does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        if (!PermissionsFacade::competitionCanUpdate($competition, $request->user())) {
            return response()->json(['error' => 'Access denied to competition.'], 403);
        }

        $team = CompetitionTeam::where('competition_id', $id)->where('team_id', $subid)->first();

        if (!$team) {
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
     * @OA\Delete(
     *     path="/competitions/{uuid}/teams/{team_id}",
     *     summary="Removes the team from the competition.",
     *     description="Remoes the team from the competition.",
     *     operationId="destoryTeam",
     *     tags={"Competitions Route"},
     *     security={{"bearer": {}}},
     *     @OA\Parameter(
     *         name="uuid",
     *         in="path",
     *         description="UUID of the competition.",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             format="uuid"
     *         )
     *      ),
     *     @OA\Parameter(
     *         name="team_id",
     *         in="path",
     *         description="UUID of the team.",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             format="uuid"
     *         )
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Success",
     *     ),
     *     @OA\Response(
     *      response=403,
     *      description="Access denied to competition.",
     *      @OA\JsonContent(
     *              @OA\Property(property="message", type="Access denied to competition.")
     *          )
     *      ) 
     * )
     *     
     */
    public function destroy(Request $request, $id, $subid)
    {
        $competition = Competition::where('id', $id)->first();

        if (!$competition) {
            return response()->json(['error' => 'The competition does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        if (!PermissionsFacade::competitionCanUpdate($competition, $request->user())) {
            return response()->json(['error' => 'Access denied to competition.'], 403);
        }

        $team = CompetitionTeam::where('competition_id', $id)->where('team_id', $subid)->first();

        if (!$team) {
            return response()->json(['error' => 'The competition/team relationship does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        $team->delete();

        return response()->json(null, 204);
    }
}
