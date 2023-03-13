<?php

namespace App\Http\Controllers;

use App\Enums\HttpStatusCodes;
use App\Facades\PermissionsFacade;
use App\Http\Resources\TeamUserResource;
use App\Models\Team;
use App\Models\TeamUser;
use Illuminate\Http\Request;

class TeamUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    /**
     * @OA\Get(
     *     path="/teams/{uuid}/users",
     *     summary="List all the users associated with a team.",
     *     description="List all the users associated with a team.",
     *     operationId="teamUserList",
     *     tags={"Teams Route"},
     *     security={ {"bearer": {} }},
     *     @OA\Parameter(
     *         name="uuid",
     *         in="path",
     *         description="UUID of the team",
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
     *           @OA\Items(ref="#/components/schemas/TeamUser"),
     *          )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="The team does not exist.",
     *         @OA\JsonContent(
     *              @OA\Property(property="message", type="The team does not exist.")
     *              )
     *          )
     * )
     *     
     */
    public function index(Request $request, $id)
    {
        $team = Team::where('id', $id)->first();

        if(!$team){
            return response()->json(['error' => 'The team does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        $users = TeamUser::query();

        $data = $users->orderBy('team_users.created_at', 'desc')->paginate(25);

        return TeamUserResource::collection($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    /**
     * @OA\Post(
     *     path="/teams/{uuid}/users",
     *     summary="Associate a new users with the team.",
     *     description="Associate a new users with the team.",
     *     operationId="createTeamUser",
     *     tags={"Teams Route"},
     *     security={{"bearer": {}}},
     *     @OA\Parameter(
     *         name="uuid",
     *         in="path",
     *         description="UUID of the team",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             format="uuid"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent( ref="#/components/schemas/TeamUser")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *           @OA\JsonContent(
     *             ref="#/components/schemas/TeamUser"
     *         ),
     *     ),
     *     @OA\Response(
     *      response=404,
     *      description="The team does not exist.",
     *      @OA\JsonContent(
     *          @OA\Property(property="message", type="The team does not exist.")
     *       )
     *     ),
     *     @OA\Response(
     *      response=403,
     *      description="Access denied to the team.",
     *      @OA\JsonContent(
     *          @OA\Property(property="message", type="Access denied to the team.")
     *       )
     *     ),
     *     @OA\Response(
     *      response=422,
     *          description="Errors when creating user",
     *      ) 
     *  )
     * )
     */
    public function store(Request $request, $id)
    {
        $team = Team::where('id', $id)->first();

        if(!$team){
            return response()->json(['error' => 'The team does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        if(!PermissionsFacade::teamCanUpdate($team, $request->user())){
            return response()->json(['error' => 'Access denied to team.'], 403);
        }

        $input = $request->all();

        $input['team_id'] = $team->id;

        $user = new TeamUser();

        $valid = $user->validate($input);

        if (!$valid) {
            return response()->json($user->getValidationErrors(), 422);
        }

        $user = TeamUser::create($input);

        return new TeamUserResource($user);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TeamUser  $teamUser
     * @return \Illuminate\Http\Response
     */

    /**
     * @OA\Get(
     *     path="/teams/{uuid}/users/{user_id}",
     *     summary="Show a single user by its ID.",
     *     description="Show a single user by its ID.",
     *     operationId="showTeamUser",
     *     tags={"Teams Route"},
     *     security={{"bearer": {}}},
     *     @OA\Parameter(
     *         name="uuid",
     *         in="path",
     *         description="UUID of the team.",
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
     *             ref="#/components/schemas/TeamUser"
     *     ),
     *     @OA\Response(
     *      response=404,
     *      description="The team does not exist.",
     *      @OA\JsonContent(
     *          @OA\Property(property="message", type="The team does not exist.")
     *       )
     *     ),
     *     )
     *)
     *     
     */
    public function show(Request $request, $id, $subid)
    {
        $team = Team::where('id', $id)->first();

        if(!$team){
            return response()->json(['error' => 'The team does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        $user = TeamUser::where('team_id', $id)->where('user_id', $subid)->first();

        if(!$user){
            return response()->json(['error' => 'The team/user relationship does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        return new TeamUserResource($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TeamUser  $teamUser
     * @return \Illuminate\Http\Response
     */


    /**
     * @OA\Put(
     *     path="/teams/{uuid}/users/{user_id}",
     *     summary="Update the user associated with team.",
     *     description="Update the user associated with team.",
     *     operationId="updateTeamUser",
     *     tags={"Teams Route"},
     *     security={{"bearer": {}}},
     *      @OA\Parameter(
     *         name="uuid",
     *         in="path",
     *         description="UUID of the team.",
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
     *         @OA\JsonContent( ref="#/components/schemas/TeamUser")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *          @OA\JsonContent( ref="#/components/schemas/TeamUser")
     *     ),
     *     @OA\Response(
     *      response=404,
     *      description="The team does not exist.",
     *      @OA\JsonContent(
     *          @OA\Property(property="message", type="Access denined to venue.")
     *       )
     *     ),
     *     @OA\Response(
     *      response=403,
     *      description="Access denied to team.",
     *      @OA\JsonContent(
     *          @OA\Property(property="message", type="Access denined to user.")
     *       )
     *     ) 
     * )
     *     
     */
    public function update(Request $request, $id, $subid)
    {
        $team = Team::where('id', $id)->first();

        if(!$team){
            return response()->json(['error' => 'The team does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        if(!PermissionsFacade::teamCanUpdate($team, $request->user())){
            return response()->json(['error' => 'Access denied to team.'], 403);
        }

        $user = TeamUser::where('team_id', $id)->where('user_id', $subid)->first();

        if(!$user){
            return response()->json(['error' => 'The team/user relationship does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        $input = $request->all();

        $data = $input + $team->toArray();

        $data['team_id'] = $team->id;

        $data['user_id'] = $user->user_id;

        $valid = $user->validate($data);

        if (!$valid) {
            return response()->json($user->getValidationErrors(), 422);
        }

        $user->update($data);

        return new TeamUserResource($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TeamUser  $teamUser
     * @return \Illuminate\Http\Response
     */


    /**
     * @OA\Delete(
     *     path="/teams/{uuid}/users/{user_id}",
     *     summary="Remove the associated user from the team.",
     *     description="Remove the associated user from the team.",
     *     operationId="removeTeamUser",
     *     tags={"Teams Route"},
     *     security={{"bearer": {}}},
     *      @OA\Parameter(
     *         name="uuid",
     *         in="path",
     *         description="UUID of the team.",
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
     *     @OA\Response(
     *         response=204,
     *         description="Success",
     *     ),
     *     @OA\Response(
     *      response=404,
     *      description="The team does not exist.",
     *      @OA\JsonContent(
     *          @OA\Property(property="message", type="Access denined to team.")
     *       )
     *     ),
     *     @OA\Response(
     *      response=403,
     *      description="Access denied to team.",
     *      @OA\JsonContent(
     *          @OA\Property(property="message", type="Access denined to user.")
     *       )
     *     ) 
     * )
     *     
     */
    public function destroy(Request $request, $id, $subid)
    {
        $team = Team::where('id', $id)->first();

        if(!$team){
            return response()->json(['error' => 'The team does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        if(!PermissionsFacade::teamCanUpdate($team, $request->user())){
            return response()->json(['error' => 'Access denied to team.'], 403);
        }

        $user = TeamUser::where('team_id', $id)->where('user_id', $subid)->first();

        if(!$user){
            return response()->json(['error' => 'The team/user relationship does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        $user->delete();

        return response()->json(null, 204);
    }
}
