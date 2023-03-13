<?php

namespace App\Http\Controllers;

use App\Enums\HttpStatusCodes;
use App\Facades\PermissionsFacade;
use App\Facades\TeamInvitesFacade;
use App\Http\Resources\TeamInviteResource;
use App\Models\Team;
use App\Models\TeamInvite;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TeamInviteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    /**
     * @OA\Get(
     *     path="/teams/{uuid}/invites",
     *     summary="Display a list of invites.",
     *     description="Display a list of invites.",
     *     operationId="teamsUserInviteList",
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
     *            type="array",
     *            @OA\Items(ref="#/components/schemas/TeamInvite")
     *          ),
     *            
     *     ),
     *      @OA\Response(
     *         response=404,
     *         description="The competition does not exist.",
     *         @OA\JsonContent(
     *              @OA\Property(property="message", type="The competition does not exist.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Accessed denied to listing invites.",
     *         @OA\JsonContent(
     *              @OA\Property(property="message", type="Accessed denied to listing invites.")
     *         )
     *     )
     * )
     *     
     */
    public function index(Request $request, $id)
    {
        $team = Team::where('id', $id)->first();

        if(!$team){
            return response()->json(['error' => 'The team does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        // check if currently authenticated user is the owner of the book
        if(!PermissionsFacade::teamCanUpdate($team, $request->user())){
           return response()->json(['error' => 'Access denied to team resource.', 'message' => 'Access denied to team resource.'], 403);
        }

        $invites = TeamInvite::where('team_id', $id)->get();

        return TeamInviteResource::collection($invites);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    /**
     * @OA\Post(
     *     path="/teams/{uuid}/sendInvite",
     *     summary="Send an invite to a user.",
     *     description="Send an invite to a user.",
     *     operationId="teamSendInvite",
     *     tags={"Teams Route"},
     *     security={ {"bearer": {} }},
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
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent( ref="#/components/schemas/TeamInvite")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             
     *            ref="#/components/schemas/TeamInvite"
     *               
     *             )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="The team does not exist.",
     *         @OA\JsonContent(
     *              @OA\Property(property="message", type="The team does not exist.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="No invites listed",
     *         @OA\JsonContent(
     *              @OA\Property(property="message", type="Cannot send invite to user.")
     *              )
     *          )
     * )
     *     
     */
    public function store(Request $request, $id)
    {
        $team = Team::where('id', $id)->first();

        if(!$team){
            return response()->json(['error' => 'The team does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        // check if currently authenticated user is the owner of the book
        if(!PermissionsFacade::teamCanUpdate($team, $request->user())){
           return response()->json(['error' => 'Cannot invite user to the stream.', 'message' => 'Cannot invite user to the stream.'], 403);
        }

        $input = $request->all();

        $input['token'] = Str::random(30);
        $input['team_id'] = $team->id;

        $invite = new TeamInvite();

        $valid = $invite->validate($input);

        if (!$valid) {
            return response()->json($invite->getValidationErrors(), 422);
        }

        $invite = TeamInvite::create($input);

        if($invite) {
            TeamInvitesFacade::sendInvite($invite);
        }

        return new TeamInviteResource($invite);
    }

    /**
     * @OA\Post(
     *     path="/teams/{uuid}/acceptInvite",
     *     summary="Use this endpoint to accept the invite. Requires the users auth token to validate.",
     *     description="Use this endpoint to accept the invite. Requires the users auth token to validate.",
     *     operationId="teamAcceptInvite",
     *     tags={"Teams Route"},
     *     security={ {"bearer": {} }},
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(ref="#/components/schemas/TeamInvite")
     *     ),
     *     @OA\RequestBody(
     *      required=true,
     *      @OA\MediaType(
     *          mediaType="application/json",
     *          @OA\Schema(
     *              @OA\Property(
     *                  property="token",
     *                  type="string",
     *                  description="The token that was created with the invite."
     *              ),
     *          )
     *      )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="The team does not exist.",
     *         @OA\JsonContent(
     *              @OA\Property(property="message", type="The team does not exist.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="No invites listed",
     *         @OA\JsonContent(
     *              @OA\Property(property="message", type="Invite is not associated with the current user.")
     *              )
     *      ),
     *      @OA\Response(
     *         response=405,
     *         description="No invites listed",
     *         @OA\JsonContent(
     *              @OA\Property(property="message", type="Invite does not belong to this competition.")
     *              )
     *      ),
     * )
     *     
     */
    public function acceptInvite(Request $request, $id){

        $team = Team::where('id', $id)->first();

        if(!$team){
            return response()->json(['error' => 'The team does not exist.', 'message' => 'The team does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }


        $input = $request->all();

        if(!isset($input['token']) || (isset($input['token']) || !$input['token'])){
            return response()->json(['message' => 'Invites require the token to accept.'], HttpStatusCodes::HTTP_NO_CONTENT);
        }

        $invite = TeamInvite::where('token', $input['token'])->first();

        if(!$invite) {
            return response()->json(['Invite not found.'], HttpStatusCodes::HTTP_NOT_FOUND);
        }

        if($team->id != $invite->team_id) {
            return response()->json(['error' => 'Invite does not belong to this team', 'message' => 'Invite does not belong to this event'], HttpStatusCodes::HTTP_FORBIDDEN);
        }

        $invite = TeamInvitesFacade::acceptInvite($invite, $request->user());

        if($invite->user_id != $request->user()->id) {
            return response()->json(['message' => 'Invite is not associated with the current user.'], HttpStatusCodes::HTTP_FORBIDDEN);
        } 
  

        return new TeamInviteResource($invite);

    }
}
