<?php

namespace App\Http\Controllers;

use App\Enums\HttpStatusCodes;
use App\Enums\Roles;
use App\Facades\CompetitionFacade;
use App\Facades\PermissionsFacade;
use App\Facades\RolesFacade;
use App\Http\Resources\CompetitionFullResource;
use App\Http\Resources\CompetitionResource;
use App\Http\Resources\CompetitionRoundResource;
use App\Models\Competition;
use App\Models\CompetitionRound;
use App\Models\CompetitionTeam;
use App\Models\CompetitionUser;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;

class CompetitionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    /**
     * @OA\Get(
     *     path="/competitions",
     *     summary="Displays a listing of the resource.",
     *     description="Displays a listing of the resource.",
     *     operationId="resourceList",
     *     tags={"Competitions Route"},
     *     security={ {"bearer": {} }},
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent( 
     *              type="array",
     *              @OA\Items(ref="#/components/schemas/Competition")
     *         )
     *     ),
     *     @OA\Response(
     *         response=405,
     *         description="No competitions listed",
     *         @OA\JsonContent(
     *              @OA\Property(property="message", type="No competitions listed")
     *              )
     *          )
     * )
     *     
     */
    public function index()
    {
        $competitions = Competition::query();

        $data = $competitions->orderBy('competitions.created_at', 'desc')->paginate(25);

        return CompetitionResource::collection($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    /**
     * @OA\Post(
     *     path="/competitions",
     *     summary="Creating new resource in storage.",
     *     description="Creating new resource in storage.",
     *     operationId="newResourceStorage",
     *     tags={"Competitions Route"},
     *     security={{"bearer": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent( ref="#/components/schemas/Competition")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *           @OA\JsonContent(
     *             ref="#/components/schemas/Competition"
     *         ),
     *     ),
     *     @OA\Response(
     *      response=422,
     *      description="New storage not created",
     *      ) 
     * )
     *  )
     */
    public function store(Request $request)
    {
        $input = $request->all();

        $competition = new Competition();

        $valid = $competition->validate($input);

        if (!$valid) {
            return response()->json($competition->getValidationErrors(), 422);
        }

        $competition = Competition::create($input);

        if ($competition) {
            RolesFacade::competitionMakeSuperAdmin($competition, $request->user());
        }

        return new CompetitionResource($competition);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Competition  $competition
     * @return \Illuminate\Http\Response
     */

    /**
     * @OA\Get(
     *     path="/competitions/{uuid}",
     *     summary="Displays specified resource.",
     *     description="Displays specified resource.",
     *     operationId="showStorage",
     *     tags={"Competitions Route"},
     *     security={{"bearer": {}}},
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
     *             ref="#/components/schemas/Competition"
     *         ),
     *     @OA\Response(
     *      response=405,
     *      description="Storage is not being displayed"
     *      ) 
     *     )
     *)
     *     
     */

    public function show($id)
    {
        $competition = Competition::where('id', $id)->first();

        return new CompetitionFullResource($competition);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Competition  $competition
     * @return \Illuminate\Http\Response
     */

    /**
     * @OA\Put(
     *     path="/competitions/{uuid}",
     *     summary="Updating resource in storage.",
     *     description="Updating resource in storage with new information.",
     *     operationId="updateStorage",
     *     tags={"Competitions Route"},
     *     security={{"bearer": {}}},
     *      @OA\Parameter(
     *         name="uuid",
     *         in="path",
     *         description="UUID of the competition",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             format="uuid"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent( ref="#/components/schemas/Competition")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *          @OA\JsonContent( ref="#/components/schemas/Competition")
     *     ),
     *     @OA\Response(
     *      response=403,
     *      description="Access denied to competition.",
     *      @OA\JsonContent(
     *          @OA\Property(property="message", type="Access denined to live stream.")
     *       )
     *     ) 
     * )
     *     
     */
    public function update(Request $request, $id)
    {
        $competition = Competition::where('id', $id)->first();

        if (!$competition) {
            return response()->json(['error' => 'The competition does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        if (!PermissionsFacade::competitionCanUpdate($competition, $request->user())) {
            return response()->json(['error' => 'Access denied to competition.', 'message' => 'Access denied to competition.'], 403);
        }

        $input = $request->all();

        $data = $input + $competition->toArray();

        $valid = $competition->validate($data);

        if (!$valid) {
            return response()->json($competition->getValidationErrors(), 422);
        }

        $competition->update($data);

        return new CompetitionResource($competition);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Competition  $competition
     * @return \Illuminate\Http\Response
     */

    /**
     * Delete
     *
     * @OA\Delete(
     *     path="/competitions/{uuid}",
     *     summary="Removes a specific resource from storage.",
     *     description="Removes a specific resource from storage.",
     *     operationId="destoryStorage",
     *     tags={"Competitions Route"},
     *     security={{"bearer": {}}},
     *      @OA\Parameter(
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
     *         response=204,
     *         description="Competition successfully deleted",
     *     ),
     *     @OA\Response(
     *      response=403,
     *      description="Access denied to competition.",
     *      @OA\JsonContent(
     *         @OA\Property(property="message", type="Access denied to competition.")
     *        )
     *      ) 
     * )
     *     
     */
    public function destroy(Request $request, $id)
    {
        $competition = Competition::where('id', $id)->first();

        if (!PermissionsFacade::competitionCanUpdate($competition, $request->user())) {
            return response()->json(['error' => 'Access denied to competition.'], 403);
        }

        $competition->delete();

        return response()->json(null, 204);
    }


    /**
     * Add team to competition.
     *
     * @param  \App\Models\Competition  $competition
     * @return \Illuminate\Http\Response
     */

    /**
     * @OA\Post(
     *     path="/competitions/{uuid}/addTeam",
     *     summary="adds a team to storage.",
     *     description="adds a team to storage.",
     *     operationId="addTeam",
     *     tags={"Competitions Route"},
     *     security={{"bearer": {}}},
     * 
     *      @OA\Parameter(
     *         name="uuid",
     *         in="path",
     *         description="UUID of the competition",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             format="uuid"
     *         )
     *     ),
     *     @OA\RequestBody(
     *      required=true,
     *      @OA\MediaType(
     *          mediaType="application/json",
     *          @OA\Schema(
     *              @OA\Property(
     *                  property="team_id",
     *                  type="string",
     *                  format="uuid",
     *                  description="The id of the team to add to the competition."
     *              ),
     *          )
     *      )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent( ref="#/components/schemas/Competition")
     *     ),
     *     @OA\Response(
     *      response=403,
     *      description="Cannot add this team",
     *      @OA\JsonContent(
     *              @OA\Property(property="message", type="Cannot add this team.")
     *              )
     *      ) 
     * )
     *     
     */
    public function addTeam(Request $request, $id)
    {

        $competition = Competition::where('id', $id)->first();

        if (!$competition) {
            return response()->json(['error' => 'The competition does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        if (!PermissionsFacade::competitionCanUpdate($competition, $request->user())) {
            return response()->json(['error' => 'Access denied to competition.'], 403);
        }

        $input = $request->all();

        if (!isset($input['team_id']) || (isset($input['team_id']) && !$input['team_id'])) {
            return response()->json(['error' => 'Team ID is required.'],  HttpStatusCodes::HTTP_NO_CONTENT);
        }

        $team = Team::where('id', $input['team_id'])->first();

        if (!$team) {
            return response()->json(['error' => 'The team does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        $isRegistered = CompetitionTeam::where('team_id', $team->id, 'competition_id', $competition->id)->first();

        if ($isRegistered) {
            return response()->json(['error' => 'Team is already registered.'], HttpStatusCodes::HTTP_FOUND);
        }

        CompetitionFacade::registerTeam($competition, $team);

        return new CompetitionResource($competition);
    }

    /**
     * Add addParticipant to competition.
     *
     * @param  \App\Models\Competition  $competition
     * @return \Illuminate\Http\Response
     */

    /**
     * @OA\Post(
     *     path="/competitions/{uuid}/addParticipant",
     *     summary="Adds a participant to storage.",
     *     description="Adds a participant to storage.",
     *     operationId="addParticipant",
     *     tags={"Competitions Route"},
     *     security={{"bearer": {}}},
     *      @OA\Parameter(
     *         name="uuid",
     *         in="path",
     *         description="UUID of the competition",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             format="uuid"
     *         )
     *     ),
     *     @OA\RequestBody(
     *      required=true,
     *      @OA\MediaType(
     *          mediaType="application/json",
     *          @OA\Schema(
     *              @OA\Property(
     *                  property="user_id",
     *                  type="string",
     *                  format="uuid",
     *                  description="The id of the user to add as a participant"
     *              ),
     *          )
     *      )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *     @OA\JsonContent(
     *             ref="#/components/schemas/Competition"
     *         )
     *     ),
     *     @OA\Response(
     *      response=403,
     *      description="Cannot add this participant",
     *      @OA\JsonContent(
     *              @OA\Property(property="message", type="Cannot add this participant.")
     *              )
     *      ) 
     * )
     *     
     */
    public function addParticipant(Request $request, $id)
    {

        $competition = Competition::where('id', $id)->first();

        if (!$competition) {
            return response()->json(['error' => 'The competition does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        if (!PermissionsFacade::competitionCanUpdate($competition, $request->user())) {
            return response()->json(['error' => 'Access denied to competition.'], 403);
        }

        $input = $request->all();

        if (!isset($input['user_id']) || (isset($input['user_id']) && !$input['user_id'])) {
            return response()->json(['error' => 'User ID is required.'],  HttpStatusCodes::HTTP_NO_CONTENT);
        }

        $user = User::where('id', $input['user_id'])->first();

        if (!$user) {
            return response()->json(['error' => 'The user does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        $isRegistered = CompetitionUser::where('user_id', $user->id)->where('competition_id', $competition->id)->first();

        if ($isRegistered) {
            return response()->json(['error' => 'User is already registered.'], HttpStatusCodes::HTTP_FOUND);
        }

        CompetitionFacade::registerUser($competition, $user);

        return new CompetitionResource($competition);
    }


    /**
     * Registers team for competition.
     *
     * @param  \App\Models\Competition  $competition
     * @return \Illuminate\Http\Response
     */

    /**
     * Register team
     * 
     * @OA\Post(
     *     path="/competitions/{uuid}/registerTeam",
     *     summary="Registers a team in storage.",
     *     description="Registers a team for competition.",
     *     operationId="registerTeam",
     *     tags={"Competitions Route"},
     *     security={{"bearer": {}}},
     *      @OA\Parameter(
     *         name="uuid",
     *         in="path",
     *         description="UUID of the competition",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             format="uuid"
     *         )
     *     ),
     *     @OA\RequestBody(
     *      required=true,
     *      @OA\MediaType(
     *          mediaType="application/json",
     *          @OA\Schema(
     *              @OA\Property(
     *                  property="team_id",
     *                  type="string",
     *                  format="uuid",
     *                  description="The id of the team to add to register."
     *              ),
     *          )
     *      )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *     @OA\JsonContent(
     *             ref="#/components/schemas/Competition"
     *         )
     *     ),
     *     @OA\Response(
     *      response=403,
     *      description="Cannot register this team for competition",
     *      @OA\JsonContent(
     *              @OA\Property(property="message", type="Cannotresigert this team for competition.")
     *              )
     *      ) 
     * )
     *     
     */
    public function registerTeam(Request $request, $id)
    {

        $competition = Competition::where('id', $id)->first();

        if (!$competition) {
            return response()->json(['error' => 'The competition does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        $input = $request->all();

        if (!isset($input['team_id']) || (isset($input['team_id']) && !$input['team_id'])) {
            return response()->json(['error' => 'Team ID is required.'],  HttpStatusCodes::HTTP_NO_CONTENT);
        }

        $team = Team::where('id', $input['team_id'])->first();

        if (!$team) {
            return response()->json(['error' => 'The team does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        $canRegister = CompetitionFacade::canRegisterTeam($competition, $team, $request->user());

        if ($canRegister['status'] == false) {
            return response()->json(['error' => $canRegister['message']], HttpStatusCodes::HTTP_FOUND);
        }

        CompetitionFacade::registerTeam($competition, $team);

        return new CompetitionResource($competition);
    }

    /**
     * Registers participant to team.
     *
     * @param  \App\Models\Competition  $competition
     * @return \Illuminate\Http\Response
     */

    /**
     * Register participant
     * 
     * @OA\Post(
     *     path="/competitions/{uuid}/registerUser",
     *     summary="Registers a participant for the competition. Use the current auth token when registering.",
     *     description="Registers a particpant for the competition. Uses the current auth token when registering.",
     *     operationId="registerParticipant",
     *     tags={"Competitions Route"},
     *     security={{"bearer": {}}},
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
     *     @OA\JsonContent(
     *             ref="#/components/schemas/Competition"
     *         )
     *     ),
     *     @OA\Response(
     *      response=403,
     *      description="Cannot register this participant for a team",
     *      @OA\JsonContent(
     *              @OA\Property(property="message", type="Cannot register this participant for a team.")
     *              )
     *      ) 
     * )
     *     
     */
    public function registerParticipant(Request $request, $id)
    {

        $competition = Competition::where('id', $id)->first();

        if (!$competition) {
            return response()->json(['error' => 'The competition does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        $canRegister = CompetitionFacade::canRegisterUser($competition, $request->user());

        if ($canRegister['status'] == false) {
            return response()->json(['error' => $canRegister['message']], HttpStatusCodes::HTTP_FOUND);
        }

        CompetitionFacade::registerUser($competition, $request->user());

        return new CompetitionResource($competition);
    }

    /**
     * Syncs rounds for competitions.
     *
     * @param  \App\Models\Competition  $competition
     * @return \Illuminate\Http\Response
     */

    /**
     * Sync rounds
     * 
     * @OA\Get(
     *     path="/competitions/{uuid}/rounds",
     *     summary="Syncs rounds for competitions.",
     *     description="Syncs rounds for competitions.",
     *     operationId="syncRounds",
     *     tags={"Competitions Route"},
     *     security={{"bearer": {}}},
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
     *     @OA\RequestBody(
     *      @OA\MediaType(
     *          mediaType="application/json",
     *          @OA\Schema(
     *              @OA\Property(
     *                  property="number_of_competitors",
     *                  type="integer",
     *                  description="Optional to pass the number of competitors. Default will be the number of competitors that have registered."
     *              ),
     *              @OA\Property(
     *                  property="competitors_per_bracket",
     *                  type="integer",
     *                  description="Optional to pass the competitors per bracket. Default will be the set competitors per match."
     *              ),
     *          )
     *      )
     * ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *     @OA\JsonContent(
     *             ref="#/components/schemas/Competition"
     *         )
     *     ),
     * 
     *     @OA\Response(
     *      response=401,
     *      description="Cannot sync these rounds",
     *      @OA\JsonContent(
     *              @OA\Property(property="message", type="The competition does not exist.")
     *              )
     *      ),
     *     @OA\Response(
     *      response=403,
     *      description="Cannot sync these rounds",
     *      @OA\JsonContent(
     *              @OA\Property(property="message", type="Access denied to competiton.")
     *              )
     *      ) 
     * )
     *     
     */
    public function syncRounds(Request $request, $id)
    {

        $competition = Competition::where('id', $id)->first();

        if (!$competition) {
            return response()->json(['error' => 'The competition does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        if (!PermissionsFacade::competitionCanUpdate($competition, $request->user())) {
            return response()->json(['error' => 'Access denied to competiton.'], 403);
        }

        $input = $request->all();

        $number_of_competitors = null;

        if (isset($input['number_of_competitors']) && $input['number_of_competitors']) {
            $number_of_competitors = $input['number_of_competitors'];
        } else {
            $number_of_competitors = CompetitionUser::where('competition_id', $competition->id)
                ->where('user_role', Roles::Participant)
                ->count() + CompetitionTeam::where('competition_id', $competition->id)->count();
        }

        $competitors_per_bracket = null;

        if (isset($input['competitors_per_bracket']) && $input['competitors_per_bracket']) {
            $competitors_per_bracket = $input['competitors_per_bracket'];
        } else {
            $competitors_per_bracket = $competition->competitors_per_match;
        }


        CompetitionFacade::makeRounds($competition, $number_of_competitors, $competitors_per_bracket);

        return new CompetitionResource($competition);
    }

    /**
     * Auto generates brackets for teams.
     *
     * @param  \App\Models\Competition  $competition
     * @return \Illuminate\Http\Response
     */

    /**
     * Auto generate brackets
     * 
     * @OA\Get(
     *     path="/competitions/{uuid}/rounds/{round_id}/brackets/autoGenerate",
     *     summary="Generates team brackets.",
     *     description="Generates team brackets.",
     *     operationId="autoGenerateTeamBrackets",
     *     tags={"Competitions Route"},
     *     security={{"bearer": {}}},
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
     *     @OA\Parameter(
     *         name="round_id",
     *         in="path",
     *         description="The round to update.",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *     @OA\JsonContent(
     *             ref="#/components/schemas/Competition"
     *         )
     *     ),
     *     @OA\Response(
     *      response=401,
     *      description="The competition does not exist.",
     *      @OA\JsonContent(
     *              @OA\Property(property="message", type="The competition does not exist.")
     *              )
     *      ),
     *     @OA\Response(
     *      response=403,
     *      description="Access denied to competition.",
     *      @OA\JsonContent(
     *              @OA\Property(property="message", type="Access denied to competition.")
     *              )
     *      ),
     *     @OA\Response(
     *      response=404,
     *      description="The round is required.",
     *      @OA\JsonContent(
     *              @OA\Property(property="message", type="The round is required.")
     *              )
     *      ) 
     *     )
     * )
     *     
     */
    public function autoGenerateTeamBrackets(Request $request, $id)
    {

        $competition = Competition::where('id', $id)->first();

        if (!$competition) {
            return response()->json(['error' => 'The competition does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        if (!PermissionsFacade::competitionCanUpdate($competition, $request->user())) {
            return response()->json(['error' => 'Access denied to competition.'], 403);
        }

        $input = $request->all();

        if (!isset($input['round']) || (isset($input['round']) && !$input['round'])) {
            return response()->json(['error' => 'The round is required.'], HttpStatusCodes::HTTP_NO_CONTENT);
        }

        $round = $input['round'];

        $competitors_per_bracket = null;

        if (isset($input['competitors_per_bracket']) && $input['competitors_per_bracket']) {
            $competitors_per_bracket = $input['competitors_per_bracket'];
        } else {
            $competitors_per_bracket = $competition->competitors_per_match;
        }

        CompetitionFacade::autoAssignCompetitorsTeams($competition, $round, $competitors_per_bracket);

        return new CompetitionResource($competition);
    }

    /**
     * Auto generates brackets for users.
     *
     * @param  \App\Models\Competition  $competition
     * @return \Illuminate\Http\Response
     */

    /**
     * Auto generate user brackets
     * 
     * @OA\Get(
     *     path="/competitions/{uuid}/autoGenerateUserBrackets",
     *     summary="Generates user brackets.",
     *     description="Generates user brackets.",
     *     operationId="autoGenerateUserBrackets",
     *     tags={"Competitions Route"},
     *     security={{"bearer": {}}},
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
     *     @OA\JsonContent(
     *             ref="#/components/schemas/CompetitionFull"
     *         )
     *     ),
     *     @OA\Response(
     *      response=401,
     *      description="The competition does not exist.",
     *      @OA\JsonContent(
     *              @OA\Property(property="message", type="The competition does not exist.")
     *              )
     *      ),
     *     @OA\Response(
     *      response=403,
     *      description="Access denied to competition.",
     *      @OA\JsonContent(
     *              @OA\Property(property="message", type="Access denied to competition.")
     *              )
     *      ),
     *     @OA\Response(
     *      response=404,
     *      description="The round is required.",
     *      @OA\JsonContent(
     *              @OA\Property(property="message", type="The round is required.")
     *              )
     *      ) 
     * )
     *     
     */
    public function autoGenerateUserBrackets(Request $request, $id)
    {

        $competition = Competition::where('id', $id)->first();

        if (!$competition) {
            return response()->json(['error' => 'The competition does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        if (!PermissionsFacade::competitionCanUpdate($competition, $request->user())) {
            return response()->json(['error' => 'Access denied to competition.'], 403);
        }

        $input = $request->all();

        if (!isset($input['round']) || (isset($input['round']) && !$input['round'])) {
            return response()->json(['error' => 'The round is required.'], HttpStatusCodes::HTTP_NO_CONTENT);
        }

        $round = $input['round'];

        $competitors_per_bracket = null;

        if (isset($input['competitors_per_bracket']) && $input['competitors_per_bracket']) {
            $competitors_per_bracket = $input['competitors_per_bracket'];
        } else {
            $competitors_per_bracket = $competition->competitors_per_match;
        }

        CompetitionFacade::autoAssignCompetitorsUsers($competition, $round, $competitors_per_bracket);

        return new CompetitionFullResource($competition);
    }

    /**
     * Upload main image to storage.
     *
     * @param  \App\Models\Competition  $competition
     * @return \Illuminate\Http\Response
     */

    /**
     * Upload main image
     * 
     * @OA\Post(
     *     path="/competitions/{uuid}/uploadMainImage",
     *     summary="Upload main image to storage.",
     *     description="Upload main image to storage.",
     *     operationId="uploadMainImage",
     *     tags={"Competitions Route"},
     *     security={{"bearer": {}}},
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
     *     @OA\RequestBody(
     *          required=true,
     *          description="Image file to upload",
     *          @OA\MediaType(
     *              mediaType="multipart/form-data",
     *              @OA\Schema(
     *                  @OA\Property(
     *                      property="image",
     *                      description="Image file to upload",
     *                      type="string",
     *                      format="binary"
     *                  )
     *              )
     *          )
     *      ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *     @OA\JsonContent(
     *             ref="#/components/schemas/CompetitionFull"
     *         )
     *     ),
     *     @OA\Response(
     *      response=401,
     *      description="The competition does not exist.",
     *      @OA\JsonContent(
     *              @OA\Property(property="message", type="The competition does not exist.")
     *              )
     *      ),
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
    public function uploadMainImage(Request $request, $id)
    {
        $competition = Competition::where('id', $id)->first();

        if (!$competition) {
            return response()->json(['error' => 'The competition does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        // check if currently authenticated user is the owner of the book
        if (!PermissionsFacade::competitionCanUpdate($competition, $request->user())) {
            return response()->json(['error' => 'Access denied to competition.'], 403);
        }

        $base_location = 'images';

        // Handle File Upload
        if ($request->hasFile('image')) {
            //Using store(), the filename will be hashed. You can use storeAs() to specify a name.
            //To specify the file visibility setting, you can update the config/filesystems.php s3 disk visibility key,
            //or you can specify the visibility of the file in the second parameter of the store() method like:
            //$imagePath = $request->file('document')->store($base_location, ['disk' => 's3', 'visibility' => 'public']);

            $imagePath = $request->file('image')->store($base_location, 's3');
        } else {
            return response()->json(['success' => false, 'message' => 'No file uploaded'], 400);
        }

        //We save new path
        $competition->forceFill([
            'main_image' => $imagePath
        ]);

        $competition->save();

        return CompetitionFullResource::make($competition);
    }

    /**
     * Upload main image to storage.
     *
     * @param  \App\Models\Competition  $competition
     * @return \Illuminate\Http\Response
     */

    /**
     * Upload banner image
     * 
     * @OA\Post(
     *     path="/competitions/{uuid}/uploadBannerImage",
     *     summary="Upload banner image to storage.",
     *     description="Upload banner image to storage.",
     *     operationId="uploadBannerImage",
     *     tags={"Competitions Route"},
     *     security={{"bearer": {}}},
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
     *     @OA\RequestBody(
     *          required=true,
     *          description="Image file to upload",
     *          @OA\MediaType(
     *              mediaType="multipart/form-data",
     *              @OA\Schema(
     *                  @OA\Property(
     *                      property="image",
     *                      description="Image file to upload",
     *                      type="string",
     *                      format="binary"
     *                  )
     *              )
     *          )
     *      ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *     @OA\JsonContent(
     *             ref="#/components/schemas/CompetitionFull"
     *         )
     *     ),
     *     @OA\Response(
     *      response=401,
     *      description="The competition does not exist.",
     *      @OA\JsonContent(
     *              @OA\Property(property="message", type="The competition does not exist.")
     *              )
     *      ),
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
    public function uploadBannerImage(Request $request, $id)
    {
        $competition = Competition::where('id', $id)->first();

        if (!$competition) {
            return response()->json(['error' => 'The competition does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        // check if currently authenticated user is the owner of the book
        if (!PermissionsFacade::competitionCanUpdate($competition, $request->user())) {
            return response()->json(['error' => 'Access denied to the competitionee.'], 403);
        }

        $base_location = 'images';

        // Handle File Upload
        if ($request->hasFile('image')) {
            //Using store(), the filename will be hashed. You can use storeAs() to specify a name.
            //To specify the file visibility setting, you can update the config/filesystems.php s3 disk visibility key,
            //or you can specify the visibility of the file in the second parameter of the store() method like:
            //$imagePath = $request->file('document')->store($base_location, ['disk' => 's3', 'visibility' => 'public']);

            $imagePath = $request->file('image')->store($base_location, ['disk' => 's3', 'visibility' => 'public']);
        } else {
            return response()->json(['success' => false, 'message' => 'No file uploaded'], 400);
        }

        //We save new path
        $competition->forceFill([
            'banner_image' => $imagePath
        ]);

        $competition->save();

        return CompetitionFullResource::make($competition);
    }
}
