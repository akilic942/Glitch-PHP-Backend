<?php

namespace App\Http\Controllers;

use App\Enums\HttpStatusCodes;
use App\Facades\CompetitionInvitesFacade;
use App\Facades\PermissionsFacade;
use App\Http\Resources\CompetitionInviteResource;
use App\Models\Competition;
use App\Models\CompetitionInvite;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CompetitionInviteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    /**
     * Invites 
     *
     * @OA\Get(
     *     path="/competitions/{uuid}/invites",
     *     summary="Display a list of invites.",
     *     description="Display a list of invites.",
     *     operationId="resourceInviteList",
     *     tags={"Competitions Route"},
     *     security={ {"bearer": {} }},
     *   @OA\Parameter(
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
     *         @OA\Property(
     *            ref="#/components/schemas/CompetitionInvite"
     *                 ),
     *             )
     *     ),
     *      @OA\Response(
     *         response=401,
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
        $competition = Competition::where('id', $id)->first();

        if (!$competition) {
            return response()->json(['error' => 'The competition does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        // check if currently authenticated user is the owner of the book
        if (!PermissionsFacade::competitionCanUpdate($competition, $request->user())) {
            return response()->json(['error' => 'Accessed denied to listing invites.', 'message' => 'Accessed denied to listing invites.'], 403);
        }

        $invites = CompetitionInvite::where('competition_id', $id)->get();

        return CompetitionInviteResource::collection($invites);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    /**
     * @OA\Post(
     *     path="/competitions/{uuid}/sendInvite",
     *     summary="Send an invite to a user.",
     *     description="Send an invite to a user.",
     *     operationId="sendInvite",
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
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent( ref="#/components/schemas/CompetitionInvite")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             
     *            ref="#/components/schemas/CompetitionInvite"
     *               
     *             )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="The competition does not exist.",
     *         @OA\JsonContent(
     *              @OA\Property(property="message", type="The competition does not exist.")
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
        $competition = Competition::where('id', $id)->first();

        if (!$competition) {
            return response()->json(['error' => 'The competition does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        // check if currently authenticated user is the owner of the book
        if (!PermissionsFacade::competitionCanUpdate($competition, $request->user())) {
            return response()->json(['error' => 'Cannot invite user to the stream.', 'message' => 'Cannot invite user to the stream.'], 403);
        }

        $input = $request->all();

        $input['token'] = Str::random(30);
        $input['competition_id'] = $competition->id;

        $invite = new CompetitionInvite();

        $valid = $invite->validate($input);

        if (!$valid) {
            return response()->json($invite->getValidationErrors(), 422);
        }

        $invite = CompetitionInvite::create($input);

        if ($invite) {
            CompetitionInvitesFacade::sendInvite($invite);
        }

        return new CompetitionInviteResource($invite);
    }

    /**
     * Accept invite 
     *
     * @OA\Post(
     *     path="/acceptInvite",
     *     summary="Use this endpoint to accept the invite. Requires the users auth token to validate.",
     *     description="Use this endpoint to accept the invite. Requires the users auth token to validate.",
     *     operationId="acceptInvite",
     *     tags={"Competitions Route"},
     *     security={ {"bearer": {} }},
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(ref="#/components/schemas/CompetitionInvite")
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
     *         description="The competition does not exist.",
     *         @OA\JsonContent(
     *              @OA\Property(property="message", type="The competition does not exist.")
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
    public function acceptInvite(Request $request, $id)
    {

        $competition = Competition::where('id', $id)->first();

        if (!$competition) {
            return response()->json(['error' => 'The competition does not exist.', 'message' => 'The competition does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }


        $input = $request->all();

        if (!isset($input['token']) || (isset($input['token']) || !$input['token'])) {
            return response()->json(['message' => 'Invites require the token to accept.'], HttpStatusCodes::HTTP_NO_CONTENT);
        }

        $invite = CompetitionInvite::where('token', $input['token'])->first();

        if (!$invite) {
            return response()->json(['Invite not found.'], HttpStatusCodes::HTTP_NOT_FOUND);
        }

        if ($competition->id != $invite->competition_id) {
            return response()->json(['error' => 'Invite does not belong to this competition', 'message' => 'Invite does not belong to this event'], HttpStatusCodes::HTTP_FORBIDDEN);
        }

        $invite = CompetitionInvitesFacade::acceptInvite($invite, $request->user());

        if ($invite->user_id != $request->user()->id) {
            return response()->json(['message' => 'Invite is not associated with the current user.'], HttpStatusCodes::HTTP_FORBIDDEN);
        }


        return new CompetitionInviteResource($invite);
    }
}
