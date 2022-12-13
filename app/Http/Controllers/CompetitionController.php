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
    public function store(Request $request)
    {
        $input = $request->all();

        $competition = new Competition();

        $valid = $competition->validate($input);

        if (!$valid) {
            return response()->json($competition->getValidationErrors(), 422);
        }

        $competition = Competition::create($input);

        if($competition) {
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
    public function update(Request $request, $id)
    {
        $competition = Competition::where('id', $id)->first();

        if(!$competition){
            return response()->json(['error' => 'The competition does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        if(!PermissionsFacade::competitionCanUpdate($competition, $request->user())){
            return response()->json(['error' => 'Access denied to live stream.' , 'message' => 'Access denied to live stream.'], 403);
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
    public function destroy(Request $request, $id)
    {
        $competition = Competition::where('id', $id)->first();

        if(!PermissionsFacade::competitionCanUpdate($competition, $request->user())){
            return response()->json(['error' => 'Access denied to live stream.'], 403);
        }
        
        $competition->delete();

        return response()->json(null, 204);
    }

    public function addTeam(Request $request, $id) {

        $competition = Competition::where('id', $id)->first();

        if(!$competition){
            return response()->json(['error' => 'The competition does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        if(!PermissionsFacade::competitionCanUpdate($competition, $request->user())){
            return response()->json(['error' => 'Access denied to live stream.'], 403);
        }

        $input = $request->all();

        if(!isset($input['team_id']) || (isset($input['team_id']) && !$input['team_id'])) {
            return response()->json(['error' => 'Team ID is required.'],  HttpStatusCodes::HTTP_NO_CONTENT);
        }

        $team = Team::where('id', $input['team_id'])->first();

        if(!$team){
            return response()->json(['error' => 'The team does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        $isRegistered = CompetitionTeam::where('team_id', $team->id, 'competition_id', $competition->id)->first();

        if($isRegistered) {
            return response()->json(['error' => 'Team is already registered.'], HttpStatusCodes::HTTP_FOUND);
        }

        CompetitionFacade::registerTeam($competition, $team);

        return new CompetitionResource($competition);

    }

    public function addParticipant(Request $request, $id) {

        $competition = Competition::where('id', $id)->first();

        if(!$competition){
            return response()->json(['error' => 'The competition does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        if(!PermissionsFacade::competitionCanUpdate($competition, $request->user())){
            return response()->json(['error' => 'Access denied to live stream.'], 403);
        }

        $input = $request->all();

        if(!isset($input['user_id']) || (isset($input['user_id']) && !$input['user_id'])) {
            return response()->json(['error' => 'User ID is required.'],  HttpStatusCodes::HTTP_NO_CONTENT);
        }

        $user = User::where('id', $input['user_id'])->first();

        if(!$user){
            return response()->json(['error' => 'The user does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        $isRegistered = CompetitionUser::where('user_id', $user->id)->where('competition_id', $competition->id)->first();

        if($isRegistered) {
            return response()->json(['error' => 'User is already registered.'], HttpStatusCodes::HTTP_FOUND);
        }

        CompetitionFacade::registerUser($competition, $user);

        return new CompetitionResource($competition);

    }

    public function registerTeam(Request $request, $id) {

        $competition = Competition::where('id', $id)->first();

        if(!$competition){
            return response()->json(['error' => 'The competition does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        $input = $request->all();

        if(!isset($input['team_id']) || (isset($input['team_id']) && !$input['team_id'])) {
            return response()->json(['error' => 'Team ID is required.'],  HttpStatusCodes::HTTP_NO_CONTENT);
        }

        $team = Team::where('id', $input['team_id'])->first();

        if(!$team){
            return response()->json(['error' => 'The team does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        $canRegister = CompetitionFacade::canRegisterTeam($competition, $team, $request->user());

        if($canRegister['status'] == false) {
            return response()->json(['error' => $canRegister['message']], HttpStatusCodes::HTTP_FOUND);
        }

        CompetitionFacade::registerTeam($competition, $team);

        return new CompetitionResource($competition);
    }

    public function registerParticipant(Request $request, $id) {

        $competition = Competition::where('id', $id)->first();

        if(!$competition){
            return response()->json(['error' => 'The competition does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        $canRegister = CompetitionFacade::canRegisterUser($competition, $request->user());

        if($canRegister['status'] == false) {
            return response()->json(['error' => $canRegister['message']], HttpStatusCodes::HTTP_FOUND);
        }

        CompetitionFacade::registerUser($competition, $request->user());

        return new CompetitionResource($competition);
    }

    public function syncRounds(Request $request, $id) {

        $competition = Competition::where('id', $id)->first();

        if(!$competition){
            return response()->json(['error' => 'The competition does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        if(!PermissionsFacade::competitionCanUpdate($competition, $request->user())){
            return response()->json(['error' => 'Access denied to live stream.'], 403);
        }

        $input = $request->all();

        $number_of_competitors = null;

        if(isset($input['number_of_competitors']) && $input['number_of_competitors']) {
            $number_of_competitors = $input['number_of_competitors'];
        } else {
            $number_of_competitors = CompetitionUser::where('competition_id', $competition->id)
            ->where('user_role', Roles::Participant)
            ->count() + CompetitionTeam::where('competition_id', $competition->id)->count();
        }

        $competitors_per_bracket = null;

        if(isset($input['competitors_per_bracket']) && $input['competitors_per_bracket']) {
            $competitors_per_bracket = $input['competitors_per_bracket'];
        } else {
            $competitors_per_bracket = $competition->competitors_per_match;
        }


        CompetitionFacade::makeRounds($competition, $number_of_competitors, $competitors_per_bracket);

        return new CompetitionResource($competition);
    }

    public function autoGenerateTeamBrackets(Request $request, $id) {

        $competition = Competition::where('id', $id)->first();

        if(!$competition){
            return response()->json(['error' => 'The competition does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        if(!PermissionsFacade::competitionCanUpdate($competition, $request->user())){
            return response()->json(['error' => 'Access denied to live stream.'], 403);
        }

        $input = $request->all();

        if(!isset($input['round']) || (isset($input['round']) && !$input['round'])) {
            return response()->json(['error' => 'The round is required.'], HttpStatusCodes::HTTP_NO_CONTENT);
        }

        $round = $input['round'];

        $competitors_per_bracket = null;

        if(isset($input['competitors_per_bracket']) && $input['competitors_per_bracket']) {
            $competitors_per_bracket = $input['competitors_per_bracket'];
        } else {
            $competitors_per_bracket = $competition->competitors_per_match;
        }

        CompetitionFacade::autoAssignCompetitorsTeams($competition, $round, $competitors_per_bracket);

        return new CompetitionResource($competition);

    }

    public function autoGenerateUserBrackets(Request $request, $id) {

        $competition = Competition::where('id', $id)->first();

        if(!$competition){
            return response()->json(['error' => 'The competition does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        if(!PermissionsFacade::competitionCanUpdate($competition, $request->user())){
            return response()->json(['error' => 'Access denied to live stream.'], 403);
        }

        $input = $request->all();

        if(!isset($input['round']) || (isset($input['round']) && !$input['round'])) {
            return response()->json(['error' => 'The round is required.'], HttpStatusCodes::HTTP_NO_CONTENT);
        }

        $round = $input['round'];

        $competitors_per_bracket = null;

        if(isset($input['competitors_per_bracket']) && $input['competitors_per_bracket']) {
            $competitors_per_bracket = $input['competitors_per_bracket'];
        } else {
            $competitors_per_bracket = $competition->competitors_per_match;
        }

        CompetitionFacade::autoAssignCompetitorsUsers($competition, $round, $competitors_per_bracket);

        return new CompetitionResource($competition);

    }
}
