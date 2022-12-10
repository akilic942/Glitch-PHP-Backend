<?php

namespace App\Http\Controllers;

use App\Enums\HttpStatusCodes;
use App\Facades\PermissionsFacade;
use App\Http\Resources\CompetitionRoundBracketResource;
use App\Models\Competition;
use App\Models\CompetitionRound;
use App\Models\CompetitionRoundBracket;
use Illuminate\Http\Request;

class CompetitionRoundBracketController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $id, $subid)
    {
        $competition = Competition::where('id', $id)->first();

        if(!$competition){
            return response()->json(['error' => 'The competition does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        $round = CompetitionRound::where('competition_id', $id)->where('round', $subid)->first();

        if(!$round){
            return response()->json(['error' => 'The round does not exit.'], HttpStatusCodes::HTTP_FOUND);
        }

        $brackets = CompetitionRoundBracket::query();

        $data = $brackets->orderBy('competition_round_brackets.created_at', 'desc')->paginate(25);

        return CompetitionRoundBracketResource::collection($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id, $subid)
    {
        $competition = Competition::where('id', $id)->first();

        if(!$competition){
            return response()->json(['error' => 'The competition does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        if(!PermissionsFacade::competitionCanUpdate($competition, $request->user())){
            return response()->json(['error' => 'Access denied to competition.', 'competition' => $competition->id, 'user' => $request->user()->id], 403);
        }

        $round = CompetitionRound::where('competition_id', $id)->where('round', $subid)->first();

        if(!$round){
            return response()->json(['error' => 'The round does not exit.'], HttpStatusCodes::HTTP_FOUND);
        }

        $input = $request->all();

        $input['competition_id'] = $competition->id;
        $input['round'] = $round->round;

        $bracket = new CompetitionRoundBracket();

        $valid = $bracket->validate($input);

        if (!$valid) {
            return response()->json($bracket->getValidationErrors(), 422);
        }

        $bracket = CompetitionRoundBracket::create($input);

        return new CompetitionRoundBracketResource($bracket);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CompetitionRoundBracket  $competitionRoundBracket
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id, $subid, $bid)
    {
        $competition = Competition::where('id', $id)->first();

        if(!$competition){
            return response()->json(['error' => 'The competition does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        $round = CompetitionRound::where('competition_id', $id)->where('round', $subid)->first();

        if(!$round){
            return response()->json(['error' => 'The round does not exit.'], HttpStatusCodes::HTTP_FOUND);
        }

        $bracket = CompetitionRoundBracket::where('competition_id', $id)
        ->where('round', $subid)
        ->where('bracket', $bid)
        ->first();

        if(!$bracket){
            return response()->json(['error' => 'The bracket does not exit.'], HttpStatusCodes::HTTP_FOUND);
        }

        return new CompetitionRoundBracketResource($bracket);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CompetitionRoundBracket  $competitionRoundBracket
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id, $subid, $bid)
    {
        $competition = Competition::where('id', $id)->first();

        if(!$competition){
            return response()->json(['error' => 'The competition does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        if(!PermissionsFacade::competitionCanUpdate($competition, $request->user())){
            return response()->json(['error' => 'Access denied to competition.'], 403);
        }

        $round = CompetitionRound::where('competition_id', $id)->where('round', $subid)->first();

        if(!$round){
            return response()->json(['error' => 'The round does not exit.'], HttpStatusCodes::HTTP_FOUND);
        }

        $bracket = CompetitionRoundBracket::where('competition_id', $id)
        ->where('round', $subid)
        ->where('bracket', $bid)
        ->first();

        if(!$bracket){
            return response()->json(['error' => 'The bracket does not exit.'], HttpStatusCodes::HTTP_FOUND);
        }

        $input = $request->all();

        $data = $input + $competition->toArray();

        $data['competition_id'] = $competition->id;

        $data['round'] = $round->round;

        $data['bracket'] = $bracket->bracket;

        $valid = $bracket->validate($data);

        if (!$valid) {
            return response()->json($bracket->getValidationErrors(), 422);
        }

        $bracket->update($data);

        return new CompetitionRoundBracketResource($bracket);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CompetitionRoundBracket  $competitionRoundBracket
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id, $subid, $bid)
    {
        $competition = Competition::where('id', $id)->first();

        if(!$competition){
            return response()->json(['error' => 'The competition does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        if(!PermissionsFacade::competitionCanUpdate($competition, $request->user())){
            return response()->json(['error' => 'Access denied to competition.'], 403);
        }

        $round = CompetitionRound::where('competition_id', $id)->where('round', $subid)->first();

        if(!$round){
            return response()->json(['error' => 'The round does not exit.'], HttpStatusCodes::HTTP_FOUND);
        }

        $bracket = CompetitionRoundBracket::where('competition_id', $id)
        ->where('round', $subid)
        ->where('bracket', $bid)
        ->first();

        if(!$bracket){
            return response()->json(['error' => 'The bracket does not exit.'], HttpStatusCodes::HTTP_FOUND);
        }

        $bracket->delete();

        return response()->json(null, 204);
    }
}
