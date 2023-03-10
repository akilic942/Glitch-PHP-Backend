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

     /**
      * Competition round bracket index 
      *
      * @OA\Get(
      *     path="competitions/{uuid}/rounds/{round_id}/brackets",
      *     summary="Displays a listing of the resource.",
      *     description="Displays a listing of the resource.",
      *     operationId="resourceRoundBracketList",
      *     tags={"compRoundBracket"},
      *     security={ {"bearer": {} }},
      *     @OA\Response(
      *         response=200,
      *         description="Success",
      *         @OA\JsonContent(
      *         @OA\Property(
      *            ref="app/Http/Resource/CompetitionRoundBracketResource"
      *                 ),
      *             )
      *     ),
      *     @OA\Response(
      *         response=403,
      *         description="No round brackets listed",
      *         @OA\JsonContent(
      *              @OA\Property(property="message", type="Cannot list brackets.")
      *              )
      *          )
      * )
      *     
      */
    public function index(Request $request, $id, $subid)
    {
        $competition = Competition::where('id', $id)->first();

        if(!$competition){
            return response()->json(['error' => 'The competition does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        $round = CompetitionRound::where('competition_id', $id)->where('round', $subid)->first();

        if(!$round){
            return response()->json(['error' => 'The round does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        $brackets = CompetitionRoundBracket::where('competition_id', $id)->where('round', $subid);

        $data = $brackets->orderBy('competition_round_brackets.created_at', 'desc')->paginate(25);

        return CompetitionRoundBracketResource::collection($data);
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
      *     path="competitions/{uuid}/rounds/{round_id}/brackets",
      *     summary="Store a newly created resource in storage.",
      *     description="Store a newly created resource in storage.",
      *     operationId="resourceRoundBracketStorage",
      *     tags={"compRoundBracket"},
      *     security={ {"bearer": {} }},
      *     @OA\Response(
      *         response=200,
      *         description="Success",
      *         @OA\JsonContent(
      *         @OA\Property(
      *            ref="app/Http/Resource/CompetitionRoundBracketResource"
      *                 ),
      *             )
      *     ),
      *     @OA\Response(
      *         response=403,
      *         description="No round brackets listed",
      *         @OA\JsonContent(
      *              @OA\Property(property="message", type="Cannot store brackets.")
      *              )
      *          )
      * )
      *     
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
            return response()->json(['error' => 'The round does not exist.'], HttpStatusCodes::HTTP_FOUND);
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

     /**
      * Show round brackets
      *
      * @OA\Get(
      *     path="competitions/{uuid}/rounds/{round_id}/brackets/{bracket_id}",
      *     summary="Display the specific resource.",
      *     description="Display the specific resource.",
      *     operationId="resourceRoundBracketShow",
      *     tags={"compRoundBracket"},
      *     security={ {"bearer": {} }},
      *     @OA\Response(
      *         response=200,
      *         description="Success",
      *         @OA\JsonContent(
      *         @OA\Property(
      *            ref="app/Http/Resource/CompetitionRoundBracketResource"
      *                 ),
      *             ),
      *         @OA\Property(property="bid", type="int")
      *     ),
      *     @OA\Response(
      *         response=405,
      *         description="No round brackets displayed",
      *         @OA\JsonContent(
      *              @OA\Property(property="message", type="Cannot display brackets.")
      *              )
      *          )
      * )
      *     
      */
    public function show(Request $request, $id, $subid, $bid)
    {
        $competition = Competition::where('id', $id)->first();

        if(!$competition){
            return response()->json(['error' => 'The competition does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        $round = CompetitionRound::where('competition_id', $id)->where('round', $subid)->first();

        if(!$round){
            return response()->json(['error' => 'The round does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        $bracket = CompetitionRoundBracket::where('competition_id', $id)
        ->where('round', $subid)
        ->where('id', $bid)
        ->first();

        if(!$bracket){
            return response()->json(['error' => 'The bracket does not exist.'], HttpStatusCodes::HTTP_FOUND);
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

     /**
      *  Update
      *
      * @OA\Put(
      *     path="competitions/{uuid}/rounds/{round_id}/brackets/{bracket_id",
      *     summary="Updating resource in storage.",
      *     description="Updating resource in storage with new information.",
      *     operationId="updateRoundBracket",
      *     tags={"compRoundBracket"},
      *     security={{"bearer": {}}},
      *     @OA\Response(
      *         response=200,
      *         description="Success",
      *     @OA\JsonContent(
      *         @OA\Property(
      *            ref="app/Http/Resource/CompetitionRoundBracketResource"
      *                 ),
      *             ),
      *     @OA\Property(property="bid", type="int")
      *     ),
      *     @OA\Response(
      *      response=403,
      *      description="Access denied to live stream.",
      *      @OA\JsonContent(
      *              @OA\Property(property="message", type="Access denined to live stream.")
      *              )
      *      ) 
      * )
      *     
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
            return response()->json(['error' => 'The round does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        $bracket = CompetitionRoundBracket::where('competition_id', $id)
        ->where('round', $subid)
        ->where('id', $bid)
        ->first();

        if(!$bracket){
            return response()->json(['error' => 'The bracket does not exist.'], HttpStatusCodes::HTTP_FOUND);
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

     /**
      * Delete
      *
      * @OA\Delete(
      *     path="competitions/{uuid}/rounds/{round_id}/brackets/{bracket_id}",
      *     summary="Removes a specific resource from storage.",
      *     description="Removes a specific resource from storage.",
      *     operationId="destoryRoundBracket",
      *     tags={"compRoundBracket"},
      *     security={{"bearer": {}}},
      *     @OA\Response(
      *         response=200,
      *         description="Success",
      *     @OA\JsonContent(
      *         @OA\Property(
      *            ref="app/Http/Resource/CompetitionRoundBracketResource"
      *                 ),
      *             ),
      *     @OA\Property(property="bid", type="int")
      *     ),
      *     @OA\Response(
      *      response=403,
      *      description="Access denied to live stream.",
      *      @OA\JsonContent(
      *              @OA\Property(property="message", type="Access denied to live stream.")
      *              )
      *      ) 
      * )
      *     
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
            return response()->json(['error' => 'The round does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        $bracket = CompetitionRoundBracket::where('competition_id', $id)
        ->where('round', $subid)
        ->where('bracket', $bid)
        ->first();

        if(!$bracket){
            return response()->json(['error' => 'The bracket does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        $bracket->delete();

        return response()->json(null, 204);
    }
}
