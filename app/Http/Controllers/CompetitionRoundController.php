<?php

namespace App\Http\Controllers;

use App\Enums\HttpStatusCodes;
use App\Facades\PermissionsFacade;
use App\Http\Resources\CompetitionRoundResource;
use App\Models\Competition;
use App\Models\CompetitionRound;
use Illuminate\Http\Request;

class CompetitionRoundController extends Controller
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

        $rounds = CompetitionRound::query();

        $data = $rounds->orderBy('competition_rounds.created_at', 'desc')->paginate(25);

        return CompetitionRoundResource::collection($data);
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
            return response()->json(['error' => 'Access denied to live stream.'], 403);
        }

        $input = $request->all();

        $input['competition_id'] = $competition->id;

        $round = new CompetitionRound();

        $valid = $round->validate($input);

        if (!$valid) {
            return response()->json($round->getValidationErrors(), 422);
        }

        $round = CompetitionRound::create($input);

        return new CompetitionRoundResource($round);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CompetitionRound  $competitionRound
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id, $subid)
    {
        $competition = Competition::where('id', $id)->first();

        if(!$competition){
            return response()->json(['error' => 'The competition does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        $round = CompetitionRound::where('competition_id', $id)->where('round', $subid)->first();

        if(!$round){
            return response()->json(['error' => 'The round does not exit.'], HttpStatusCodes::HTTP_FOUND);
        }

        return new CompetitionRoundResource($round);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CompetitionRound  $competitionRound
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id, $subid)
    {
        $competition = Competition::where('id', $id)->first();

        if(!$competition){
            return response()->json(['error' => 'The competition does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        if(!PermissionsFacade::competitionCanUpdate($competition, $request->user())){
            return response()->json(['error' => 'Access denied to live stream.'], 403);
        }

        $round = CompetitionRound::where('competition_id', $id)->where('round', $subid)->first();

        if(!$round){
            return response()->json(['error' => 'The round does not exit.'], HttpStatusCodes::HTTP_FOUND);
        }

        $input = $request->all();

        $data = $input + $competition->toArray();

        $data['competition_id'] = $competition->id;

        $data['round'] = $round->round;

        $valid = $round->validate($data);

        if (!$valid) {
            return response()->json($round->getValidationErrors(), 422);
        }

        $round->update($data);

        return new CompetitionRoundResource($round);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CompetitionRound  $competitionRound
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id, $subid)
    {
        $competition = Competition::where('id', $id)->first();

        if(!$competition){
            return response()->json(['error' => 'The competition does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        if(!PermissionsFacade::competitionCanUpdate($competition, $request->user())){
            return response()->json(['error' => 'Access denied to live stream.'], 403);
        }

        $round = CompetitionRound::where('competition_id', $id)->where('round', $subid)->first();

        if(!$round){
            return response()->json(['error' => 'The round does not exit.'], HttpStatusCodes::HTTP_FOUND);
        }

        $round->delete();

        return response()->json(null, 204);
    }
}
