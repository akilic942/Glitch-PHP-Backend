<?php

namespace App\Http\Controllers;

use App\Enums\HttpStatusCodes;
use App\Facades\PermissionsFacade;
use App\Http\Resources\CompetitionVenueResource;
use App\Models\Competition;
use App\Models\CompetitionVenue;
use Illuminate\Http\Request;

class CompetitionVenueController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    /**
     * @OA\Get(
     *     path="/competitions/{uuid}/venues",
     *     summary="List all the venues associated with a competition.",
     *     description="List all the venues associated with a competition.",
     *     operationId="venueList",
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
     *           @OA\Items(ref="#/components/schemas/CompetitionVenue"),
     *          )
     *     ),
     *     @OA\Response(
     *         response=405,
     *         description="No competitions listed",
     *         @OA\JsonContent(
     *              @OA\Property(property="message", type="No competitions listed")
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

        $addresses = CompetitionVenue::where('competition_id', '=', $competition->id);

        $data = $addresses->orderBy('competition_venues.created_at', 'desc')->paginate(25);

        return CompetitionVenueResource::collection($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    /**
     * @OA\Post(
     *     path="/competitions/{uuid}/venues",
     *     summary="Creating a new venue.",
     *     description="Creating a new venue.",
     *     operationId="createVenue",
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
     *         @OA\JsonContent( ref="#/components/schemas/CompetitionVenue")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *           @OA\JsonContent(
     *             ref="#/components/schemas/CompetitionVenue"
     *         ),
     *     ),
     *     @OA\Response(
     *      response=404,
     *      description="The competition does not exist.",
     *      @OA\JsonContent(
     *          @OA\Property(property="message", type="Access denined to venue.")
     *       )
     *     ),
     *     @OA\Response(
     *      response=422,
     *      description="New venue not created",
     *      ) 
     * )
     *  )
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

        $address = new CompetitionVenue();

        $valid = $address->validate($input);

        if (!$valid) {
            return response()->json($address->getValidationErrors(), 422);
        }

        $address = CompetitionVenue::create($input);

        return new CompetitionVenueResource($address);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CompetitionVenue  $CompetitionVenue
     * @return \Illuminate\Http\Response
     */

    /**
     * @OA\Get(
     *     path="/competitions/{uuid}/venues/{venue_id}",
     *     summary="Show a single venue by its ID.",
     *     description="Show a single venue by its ID.",
     *     operationId="showVenue",
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
     *         name="venue_id",
     *         in="path",
     *         description="UUID of the venue.",
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
     *             ref="#/components/schemas/CompetitionVenue"
     *     ),
     *     @OA\Response(
     *      response=404,
     *      description="The competition does not exist.",
     *      @OA\JsonContent(
     *          @OA\Property(property="message", type="Access denined to venue.")
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

        if (!$competition) {
            return response()->json(['error' => 'The competition does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        $address = CompetitionVenue::where('competition_id', $id)->where('id', $subid)->first();

        if (!$address) {
            return response()->json(['error' => 'The address does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        return new CompetitionVenueResource($address);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CompetitionVenue  $CompetitionVenue
     * @return \Illuminate\Http\Response
     */

    /**
     * @OA\Put(
     *     path="/competitions/{uuid}/venues/{venue_id}",
     *     summary="Update the venue.",
     *     description="Update the venue.",
     *     operationId="updateVenue",
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
     *         name="venue_id",
     *         in="path",
     *         description="UUID of the venue.",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             format="uuid"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent( ref="#/components/schemas/CompetitionVenue")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *          @OA\JsonContent( ref="#/components/schemas/CompetitionVenue")
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
     *          @OA\Property(property="message", type="Access denined to venue.")
     *       )
     *     ) 
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

        $address = CompetitionVenue::where('competition_id', $id)->where('id', $subid)->first();

        if (!$address) {
            return response()->json(['error' => 'The address does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        $input = $request->all();

        $data = $input + $competition->toArray();

        $data['competition_id'] = $competition->id;

        $data['id'] = $address->id;

        $valid = $address->validate($data);

        if (!$valid) {
            return response()->json($address->getValidationErrors(), 422);
        }

        $address->update($data);

        return new CompetitionVenueResource($address);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CompetitionVenue  $CompetitionVenue
     * @return \Illuminate\Http\Response
     */

    /**
     * @OA\Delete(
     *     path="/competitions/{uuid}/venues/{venue_id}",
     *     summary="Deletes the venue from the competition.",
     *     description="Deletes the venue from the competition.",
     *     operationId="removeCompetitionVenue",
     *     tags={"Competitions Route"},
     *     security={{"bearer": {}}},
     *      @OA\Parameter(
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
     *         name="venue_id",
     *         in="path",
     *         description="UUID of the venue.",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             format="uuid"
     *         )
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Venue successfully deleted from competition.",
     *     ),
     *     @OA\Response(
     *      response=403,
     *      description="Access denied to competition.",
     *      @OA\JsonContent(
     *         @OA\Property(property="message", type="Access denied to competition.")
     *        )
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

        $address = CompetitionVenue::where('competition_id', $id)->where('id', $subid)->first();

        if (!$address) {
            return response()->json(['error' => 'The venue does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        $address->delete();

        return response()->json(null, 204);
    }

    /**
     * Upload main image
     * 
     * @OA\Post(
     *     path="/competitions/{uuid}/venues/{venue_id}/uploadMainImage",
     *     summary="Upload venue main image to storage.",
     *     description="Upload venue main image to storage.",
     *     operationId="uploadVenueMainImage",
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
     *         name="venue_id",
     *         in="path",
     *         description="UUID of the venue",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             format="uuid"
     *         )
     *     ),
     *     @OA\RequestBody(
     *          required=true,
     *          description="Image file to upload",
     *          @OA\MediaType(
     *              mediaType="multipart/form-data",
     *              @OA\Schema(
     *                  @OA\Property(
     *                      property="image",
     *                      description="Image file to upload",
     *                      type="string",
     *                      format="binary"
     *                  )
     *              )
     *          )
     *      ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *     @OA\JsonContent(
     *             ref="#/components/schemas/CompetitionFull"
     *         )
     *     ),
     *     @OA\Response(
     *      response=401,
     *      description="The competition does not exist.",
     *      @OA\JsonContent(
     *              @OA\Property(property="message", type="The competition does not exist.")
     *              )
     *      ),
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
    public function uploadMainImage(Request $request, $id, $subid)
    {
        $competition = Competition::where('id', $id)->first();

        if (!$competition) {
            return response()->json(['error' => 'The competition does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        // check if currently authenticated user is the owner of the book
        if (!PermissionsFacade::competitionCanUpdate($competition, $request->user())) {
            return response()->json(['error' => 'Access denied to competition.'], 403);
        }

        $venue = CompetitionVenue::where('id', $subid)->first();

        if (!$venue) {
            return response()->json(['error' => 'The venue does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        $base_location = 'images';

        // Handle File Upload
        if ($request->hasFile('image')) {
            //Using store(), the filename will be hashed. You can use storeAs() to specify a name.
            //To specify the file visibility setting, you can update the config/filesystems.php s3 disk visibility key,
            //or you can specify the visibility of the file in the second parameter of the store() method like:
            //$imagePath = $request->file('document')->store($base_location, ['disk' => 's3', 'visibility' => 'public']);

            $imagePath = $request->file('image')->store($base_location, 's3');
        } else {
            return response()->json(['success' => false, 'message' => 'No file uploaded'], 400);
        }

        //We save new path
        $venue->forceFill([
            'venue_image' => $imagePath
        ]);

        $venue->save();

        return CompetitionVenueResource::make($venue);
    }
}
