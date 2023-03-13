<?php

namespace App\Http\Controllers;

use App\Enums\HttpStatusCodes;
use App\Facades\PermissionsFacade;
use App\Http\Resources\CompetitionUserResource;
use App\Models\Competition;
use App\Models\CompetitionUser;
use Illuminate\Http\Request;

class CompetitionUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    /**
     * @OA\Get(
     *     path="/competitions/{uuid}/users",
     *     summary="List all the users associated with a competition.",
     *     description="List all the users associated with a competition.",
     *     operationId="competitionUserList",
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
     *          type="array",
     *           @OA\Items(ref="#/components/schemas/CompetitionUser"),
     *          )
     *     ),
     *     @OA\Response(
     *         response=404,
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

        if(!$competition){
            return response()->json(['error' => 'The competition does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        $users = CompetitionUser::where('competition_id', '=', $competition->id);

        $data = $users->orderBy('competition_users.created_at', 'desc')->paginate(25);

        return CompetitionUserResource::collection($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    /**
     * @OA\Post(
     *     path="/competitions/{uuid}/users",
     *     summary="Associate a new users with the competition.",
     *     description="Associate a new users with the competition.",
     *     operationId="createCompetitionUser",
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
     *         required=true,
     *         @OA\JsonContent( ref="#/components/schemas/CompetitionUser")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *           @OA\JsonContent(
     *             ref="#/components/schemas/CompetitionUser"
     *         ),
     *     ),
     *     @OA\Response(
     *      response=404,
     *      description="The competition does not exist.",
     *      @OA\JsonContent(
     *          @OA\Property(property="message", type="The competition does not exist.")
     *       )
     *     ),
     *     @OA\Response(
     *      response=403,
     *      description="Access denied to competition.",
     *      @OA\JsonContent(
     *          @OA\Property(property="message", type="Access denied to competition.")
     *       )
     *     ),
     *     @OA\Response(
     *      response=422,
     *          description="Errors when creating user",
     *      ) 
     * )
     *  )
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

        $user = new CompetitionUser();

        $valid = $user->validate($input);

        if (!$valid) {
            return response()->json($user->getValidationErrors(), 422);
        }

        $user = CompetitionUser::create($input);

        return new CompetitionUserResource($user);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CompetitionUser  $competitionUser
     * @return \Illuminate\Http\Response
     */

    /**
     * @OA\Get(
     *     path="/competitions/{uuid}/users/{user_id}",
     *     summary="Show a single user by its ID.",
     *     description="Show a single user by its ID.",
     *     operationId="showCompetitionUser",
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
     *      @OA\Parameter(
     *         name="user_id",
     *         in="path",
     *         description="UUID of the user.",
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
     *             ref="#/components/schemas/CompetitionUser"
     *     ),
     *     @OA\Response(
     *      response=404,
     *      description="The competition does not exist.",
     *      @OA\JsonContent(
     *          @OA\Property(property="message", type="The competition does not exist.")
     *       )
     *     ),
     *     @OA\Response(
     *      response=405,
     *      description="Venue does not exist"
     *      ) 
     *     )
     *)
     *     
     */
    public function show(Request $request, $id, $subid)
    {
        $competition = Competition::where('id', $id)->first();

        if(!$competition){
            return response()->json(['error' => 'The competition does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        $user = CompetitionUser::where('competition_id', $id)
        ->where('user_id', $subid)
        ->first();

        if(!$user){
            return response()->json(['error' => 'The user does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        return new CompetitionUserResource($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CompetitionUser  $competitionUser
     * @return \Illuminate\Http\Response
     */

    /**
     * @OA\Put(
     *     path="/competitions/{uuid}/users/{user_id}",
     *     summary="Update the user associated with competition.",
     *     description="Update the user associated with competition.",
     *     operationId="updateCompetitionUser",
     *     tags={"Competitions Route"},
     *     security={{"bearer": {}}},
     *      @OA\Parameter(
     *         name="uuid",
     *         in="path",
     *         description="UUID of the competition.",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             format="uuid"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="user_id",
     *         in="path",
     *         description="UUID of the user.",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             format="uuid"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent( ref="#/components/schemas/CompetitionUser")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *          @OA\JsonContent( ref="#/components/schemas/CompetitionUser")
     *     ),
     *     @OA\Response(
     *      response=404,
     *      description="The competition does not exist.",
     *      @OA\JsonContent(
     *          @OA\Property(property="message", type="Access denined to venue.")
     *       )
     *     ),
     *     @OA\Response(
     *      response=403,
     *      description="Access denied to competition.",
     *      @OA\JsonContent(
     *          @OA\Property(property="message", type="Access denined to user.")
     *       )
     *     ) 
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

        $user = CompetitionUser::where('competition_id', $id)->where('user_id', $subid)->first();

        if(!$user){
            return response()->json(['error' => 'The competition/user relationship does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        $input = $request->all();

        $data = $input + $competition->toArray();

        $data['competition_id'] = $competition->id;

        $data['user_id'] = $user->user_id;

        $valid = $user->validate($data);

        if (!$valid) {
            return response()->json($user->getValidationErrors(), 422);
        }

        $user->update($data);

        return new CompetitionUserResource($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CompetitionUser  $competitionUser
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

        $user = CompetitionUser::where('competition_id', $id)->where('user_id', $subid)->first();

        if(!$user){
            return response()->json(['error' => 'The competition/user relationship does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        $user->delete();

        return response()->json(null, 204);
    }
}
