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
     *     path="/competitions/{uuid}/rounds",
     *     summary="Display the rounds associated with a competition.",
     *     description="Display the rounds associated with a competition.",
     *     operationId="resourceRoundList",
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
     *             @OA\Items(ref="#/components/schemas/CompetitionRound"),
     *          )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="The competition does not exist.",
     *         @OA\JsonContent(
     *              @OA\Property(property="message", type="The competition does not exist.")
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
     * @OA\Post(
     *     path="/competitions/{uuid}/rounds",
     *     summary="Create a new round for the competition.",
     *     description="Create a new round for the competition.",
     *     operationId="resourceRoundStorage",
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
     *      @OA\RequestBody(
     *         @OA\JsonContent( ref="#/components/schemas/CompetitionRound")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             ref="#/components/schemas/CompetitionRound"
     *         ),
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="No rounds listed",
     *         @OA\JsonContent(
     *              @OA\Property(property="message", type="Cannot store round.")
     *              )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation errors",
     *         @OA\JsonContent(
     *              @OA\Property(property="message", type="Validation errors.")
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
     * @OA\Get(
     *     path="/competitions/{uuid}/rounds/{round_id}",
     *     summary="Retrieve the information for a single round.",
     *     description="Retrieve the information for a single round.",
     *     operationId="resourceRoundShow",
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
     *      @OA\Parameter(
     *         name="round_id",
     *         in="path",
     *         description="The round number.",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *         )
     *      ),
     * *      @OA\RequestBody(
     *         @OA\JsonContent( ref="#/components/schemas/CompetitionRound")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *         @OA\Property(
     *            property="data",
     *            ref="#/components/schemas/CompetitionRound"
     *                 ),
     *             )
     *     ),
     *     @OA\Response(
     *         response=404,
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

        if (!$competition) {
            return response()->json(['error' => 'The competition does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        $round = CompetitionRound::where('competition_id', $id)->where('round', $subid)->first();

        if (!$round) {
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
     * @OA\Put(
     *     path="/competitions/{uuid}/rounds/{round_id}",
     *     summary="Updating resource in storage.",
     *     description="Updating resource in storage with new information.",
     *     operationId="updateRound",
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
     *      ),
     *      @OA\Parameter(
     *         name="round_id",
     *         in="path",
     *         description="The round number.",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *         )
     *      ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *          @OA\JsonContent(
     *              ref="#/components/schemas/CompetitionRound"          
     *          )
     *     ),
     *     @OA\Response(
     *      response=403,
     *      description="Access denied to competition.",
     *      @OA\JsonContent(
     *              @OA\Property(property="message", type="Access denined to competition.")
     *              )
     *      ),
     *      @OA\Response(
     *      response=404,
     *      description="Competition/round not found.",
     *      @OA\JsonContent(
     *              @OA\Property(property="message", type="Competition/round not found")
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

        $round = CompetitionRound::where('competition_id', $id)->where('round', $subid)->first();

        if (!$round) {
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
     * @OA\Delete(
     *     path="/competitions/{uuid}/rounds/{round_id}",
     *     summary="Deletes the round for the competition.",
     *     description="Deletes the round for the competition.",
     *     operationId="destoryRound",
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
     *      ),
     *      @OA\Parameter(
     *         name="round_id",
     *         in="path",
     *         description="The round number.",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *         )
     *      ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *     @OA\JsonContent(
     *         @OA\Property(
     *            ref="#/components/schemas/CompetitionRound"
     *                 ),
     *             )
     *     ),
     *     @OA\Response(
     *          response=403,
     *          description="Access denied to competition.",
     *          @OA\JsonContent(
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

        $round = CompetitionRound::where('competition_id', $id)->where('round', $subid)->first();

        if (!$round) {
            return response()->json(['error' => 'The round does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        $round->delete();

        return response()->json(null, 204);
    }
}
