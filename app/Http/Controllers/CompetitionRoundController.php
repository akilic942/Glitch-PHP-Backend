<?php

namespace App\Http\Controllers;

use App\Enums\HttpStatusCodes;
use App\Facades\PermissionsFacade;
use App\Http\Resources\CompetitionRoundResource;
use App\Models\Competition;
use App\Models\CompetitionRound;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CompetitionRoundController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

     /**
      * Competition round bracket index 
      *
      * @OA\Get(
      *     path="/competitions/{uuid}/rounds1",
      *     summary="Displays a listing of the resource.",
      *     description="Displays a listing of the resource.",
      *     operationId="resourceRoundList",
      *     tags={"Competitions Route"},
      *     security={ {"bearer": {} }},
      *     @OA\Response(
      *         response=200,
      *         description="Success",
      *         @OA\JsonContent(ref="app/Http/Resource/CompetitionResource")
      *     ),
      *     @OA\Response(
      *         response=403,
      *         description="No rounds listed",
      *         @OA\JsonContent(
      *              @OA\Property(property="message", type="Cannot list rounds.")
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

        $rounds = CompetitionRound::where('competition_id', '=', $competition->id);

        $data = $rounds->orderBy('competition_rounds.created_at', 'desc')->paginate(25);

        return CompetitionRoundResource::collection($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

     /**
      * Storage of competitions
      *
      * @OA\Post(
      *     path="/competitions/{uuid}/rounds",
      *     summary="Store a newly created resource in storage.",
      *     description="Store a newly created resource in storage.",
      *     operationId="resourceRoundStorage",
      *     tags={"Competitions Route"},
      *     security={ {"bearer": {} }},
      *     @OA\Response(
      *         response=200,
      *         description="Success",
      *         @OA\JsonContent(
      *         @OA\Property(
      *            property="data",
      *            ref="app/Http/Resource/CompetitionResource"
      *                 ),
      *             )
      *     ),
      *     @OA\Response(
      *         response=403,
      *         description="No rounds listed",
      *         @OA\JsonContent(
      *              @OA\Property(property="message", type="Cannot store round.")
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

        $round = new CompetitionRound();

        $unique_rule = ['round' => 'required|integer|unique:competition_rounds,round,NULL,id,competition_id,' . $competition->id];

        $valid = $round->validate($input, [], $unique_rule);

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

     /**
      * Show round brackets
      *
      * @OA\Get(
      *     path="/competitions/{uuid}/rounds/{round_id}",
      *     summary="Display the specific resource.",
      *     description="Display the specific resource.",
      *     operationId="resourceRoundShow",
      *     tags={"Competitions Route"},
      *     security={ {"bearer": {} }},
      *     @OA\Response(
      *         response=200,
      *         description="Success",
      *         @OA\JsonContent(
      *         @OA\Property(
      *            property="data",
      *            ref="app/Http/Resource/CompetitionRoundResource"
      *                 ),
      *             )
      *     ),
      *     @OA\Response(
      *         response=405,
      *         description="No rounds displayed",
      *         @OA\JsonContent(
      *              @OA\Property(property="message", type="Cannot display rounds.")
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

        $round = CompetitionRound::where('competition_id', $id)->where('round', $subid)->first();

        if(!$round){
            return response()->json(['error' => 'The round does not exist.'], HttpStatusCodes::HTTP_FOUND);
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

     /**
      *  Update
      *
      * @OA\Put(
      *     path="/competitions/{uuid}/rounds/{round_id}",
      *     summary="Updating resource in storage.",
      *     description="Updating resource in storage with new information.",
      *     operationId="updateRound",
      *     tags={"Competitions Route"},
      *     security={{"bearer": {}}},
      *     @OA\Response(
      *         response=200,
      *         description="Success",
      *     @OA\JsonContent(
      *         @OA\Property(
      *            property="data",
      *            ref="app/Http/Resource/CompetitionRoundResource"
      *                 ),
      *             )
      *     ),
      *     @OA\Response(
      *      response=403,
      *      description="Access denied to competition.",
      *      @OA\JsonContent(
      *              @OA\Property(property="message", type="Access denined to competition.")
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

        $round = CompetitionRound::where('competition_id', $id)->where('round', $subid)->first();

        if(!$round){
            return response()->json(['error' => 'The round does not exist.'], HttpStatusCodes::HTTP_FOUND);
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

     /**
      * Delete
      *
      * @OA\Delete(
      *     path="/competitions/{uuid}/rounds/{round_id}",
      *     summary="Removes a specific resource from storage.",
      *     description="Removes a specific resource from storage.",
      *     operationId="destoryRound",
      *     tags={"Competitions Route"},
      *     security={{"bearer": {}}},
      *     @OA\Response(
      *         response=200,
      *         description="Success",
      *     @OA\JsonContent(
      *         @OA\Property(
      *            ref="app/Http/Resource/CompetitionRoundResource"
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

        $round = CompetitionRound::where('competition_id', $id)->where('round', $subid)->first();

        if(!$round){
            return response()->json(['error' => 'The round does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        $round->delete();

        return response()->json(null, 204);
    }
}
