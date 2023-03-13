<?php

namespace App\Http\Controllers;

use App\Enums\HttpStatusCodes;
use App\Facades\PermissionsFacade;
use App\Facades\RolesFacade;
use App\Http\Resources\TeamResource;
use App\Models\Team;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    /**
     * @OA\Get(
     *     path="/teams",
     *     summary="Displays a list of teams",
     *     description="Displays a list of teams.",
     *     operationId="teamsList",
     *     tags={"Teams Route"},
     *     security={ {"bearer": {} }},
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent( 
     *              type="array",
     *              @OA\Items(ref="#/components/schemas/Team")
     *         )
     *     ),
     * )
     *     
     */
    public function index()
    {
        $teams = Team::query();

        $data = $teams->orderBy('teams.created_at', 'desc')->paginate(25);

        return TeamResource::collection($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    /**
     * @OA\Post(
     *     path="/teams",
     *     summary="Create new team.",
     *     description="Create a new team.",
     *     operationId="teamCreate",
     *     tags={"Teams Route"},
     *     security={{"bearer": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent( ref="#/components/schemas/Team")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *           @OA\JsonContent(
     *             ref="#/components/schemas/Team"
     *         ),
     *     ),
     *     @OA\Response(
     *      response=422,
     *      description="Validation errors",
     *      ) 
     * )
     *  )
     */
    public function store(Request $request)
    {
        $input = $request->all();

        $team = new Team();

        $valid = $team->validate($input);

        if (!$valid) {
            return response()->json($team->getValidationErrors(), 422);
        }

        $team = Team::create($input);

        if($team) {
            RolesFacade::teamMakeSuperAdmin($team, $request->user());
        }

        return new TeamResource($team);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Team  $team
     * @return \Illuminate\Http\Response
     */

         /**
     * @OA\Get(
     *     path="/teams/{uuid}",
     *     summary="Retrieve a single teams resource.",
     *     description="Retrieve a single teams resource.",
     *     operationId="teamShow",
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
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             ref="#/components/schemas/Team"
     *         ),
     *     @OA\Response(
     *      response=404,
     *      description="Team does not exist."
     *      ) 
     *     )
     *)
     *     
     */
    public function show(Team $team, $id)
    {
        $team = Team::where('id', $id)->first();

        return new TeamResource($team);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Team  $team
     * @return \Illuminate\Http\Response
     */

    /**
     * @OA\Put(
     *     path="/teams/{uuid}",
     *     summary="Update a team.",
     *     description="Update a team.",
     *     operationId="teamUpdate",
     *     tags={"Teams Route"},
     *     security={{"bearer": {}}},
     *      @OA\Parameter(
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
     *         @OA\JsonContent( ref="#/components/schemas/Team")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *          @OA\JsonContent( ref="#/components/schemas/Team")
     *     ),
     *     @OA\Response(
     *      response=403,
     *      description="Access denied to the team resource.",
     *      @OA\JsonContent(
     *          @OA\Property(property="message", type="Access denined to team resource.")
     *       )
     *     ) 
     * )
     *     
     */
    public function update(Request $request, $id)
    {
        $team = Team::where('id', $id)->first();

        if(!$team){
            return response()->json(['error' => 'The stream does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        if(!PermissionsFacade::teamCanUpdate($team, $request->user())){
            return response()->json(['error' => 'Access denied to team.' , 'message' => 'Access denied to team.'], 403);
        }

        $input = $request->all();

        $data = $input + $team->toArray();

        $valid = $team->validate($data);

        if (!$valid) {
            return response()->json($team->getValidationErrors(), 422);
        }

        $team->update($data);

        return new TeamResource($team);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Team  $team
     * @return \Illuminate\Http\Response
     */

    /**
     * @OA\Delete(
     *     path="/teams/{uuid}",
     *     summary="Delete a team.",
     *     description="Delete a team.",
     *     operationId="teamDelete",
     *     tags={"Teams Route"},
     *     security={{"bearer": {}}},
     *      @OA\Parameter(
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
     *         response=204,
     *         description="Team successfully deleted",
     *     ),
     *     @OA\Response(
     *      response=403,
     *      description="Access denied to team.",
     *      @OA\JsonContent(
     *         @OA\Property(property="message", type="Access denied to team.")
     *        )
     *      ) 
     * )
     *     
     */
    public function destroy(Request $request, $id)
    {
        $team = Team::where('id', $id)->first();

        if(!PermissionsFacade::teamCanUpdate($team, $request->user())){
            return response()->json(['error' => 'Access denied to team.'], 403);
        }
        
        $team->delete();

        return response()->json(null, 204);
    }

    /**
     * @OA\Post(
     *     path="/teams/{uuid}/uploadMainImage",
     *     summary="Upload main image to team.",
     *     description="Upload main image to team.",
     *     operationId="teamUploadMainImage",
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
     *             ref="#/components/schemas/Team"
     *         )
     *     ),
     *     @OA\Response(
     *      response=401,
     *      description="The team does not exist.",
     *      @OA\JsonContent(
     *              @OA\Property(property="message", type="The team does not exist.")
     *              )
     *      ),
     *     @OA\Response(
     *      response=403,
     *      description="Access denied to team.",
     *      @OA\JsonContent(
     *              @OA\Property(property="message", type="Access denied to team.")
     *              )
     *      ) 
     * )
     *     
     */
    public function uploadMainImage(Request $request, $id)
    {
        $team = Team::where('id', $id)->first();

        if(!$team){
            return response()->json(['error' => 'The team does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        // check if currently authenticated user is the owner of the book
        if(!PermissionsFacade::teamCanUpdate($team, $request->user())){
            return response()->json(['error' => 'Access denied to team.'], 403);
        }

        $base_location = 'images';

        // Handle File Upload
        if($request->hasFile('image')) {              
            //Using store(), the filename will be hashed. You can use storeAs() to specify a name.
            //To specify the file visibility setting, you can update the config/filesystems.php s3 disk visibility key,
            //or you can specify the visibility of the file in the second parameter of the store() method like:
            //$imagePath = $request->file('document')->store($base_location, ['disk' => 's3', 'visibility' => 'public']);
            
            $imagePath = $request->file('image')->store($base_location, 's3');
          
        } else {
            return response()->json(['success' => false, 'message' => 'No file uploaded'], 400);
        }
        
        //We save new path
        $team->forceFill([
            'main_image' => $imagePath
        ]);

        $team->save();
       
        return TeamResource::make($team);
    }

    /**
     * @OA\Post(
     *     path="/teams/{uuid}/uploadBannerImag",
     *     summary="Upload banner image to team.",
     *     description="Upload banner image to team.",
     *     operationId="teamUploadBannerImage",
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
     *             ref="#/components/schemas/Team"
     *         )
     *     ),
     *     @OA\Response(
     *      response=401,
     *      description="The team does not exist.",
     *      @OA\JsonContent(
     *              @OA\Property(property="message", type="The team does not exist.")
     *              )
     *      ),
     *     @OA\Response(
     *      response=403,
     *      description="Access denied to team.",
     *      @OA\JsonContent(
     *              @OA\Property(property="message", type="Access denied to team.")
     *              )
     *      ) 
     * )
     *     
     */
    public function uploadBannerImage(Request $request, $id)
    {
        $team = Team::where('id', $id)->first();

        if(!$team){
            return response()->json(['error' => 'The team does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        // check if currently authenticated user is the owner of the book
        if(!PermissionsFacade::teamCanUpdate($team, $request->user())){
            return response()->json(['error' => 'Access denied to the team.'], 403);
        }

        $base_location = 'images';

        // Handle File Upload
        if($request->hasFile('image')) {              
            //Using store(), the filename will be hashed. You can use storeAs() to specify a name.
            //To specify the file visibility setting, you can update the config/filesystems.php s3 disk visibility key,
            //or you can specify the visibility of the file in the second parameter of the store() method like:
            //$imagePath = $request->file('document')->store($base_location, ['disk' => 's3', 'visibility' => 'public']);
            
            $imagePath = $request->file('image')->store($base_location, ['disk' => 's3', 'visibility' => 'public']);
          
        } else {
            return response()->json(['success' => false, 'message' => 'No file uploaded'], 400);
        }
    
        //We save new path
        $team->forceFill([
            'banner_image' => $imagePath
        ]);

        $team->save();
       
        return TeamResource::make($team);
    }
}
