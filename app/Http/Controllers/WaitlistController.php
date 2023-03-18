<?php

namespace App\Http\Controllers;

use App\Enums\HttpStatusCodes;
use App\Enums\Roles;
use App\Facades\PermissionsFacade;
use App\Http\Resources\WaitlistResource;
use App\Models\Waitlist;
use Illuminate\Http\Request;

class WaitlistController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

     /**
     * @OA\Get(
     *     path="/waitlists",
     *     summary="Displays a list users that have signed up to the waitlist.",
     *     description="Displays a list users that have signed up to the waitlist.",
     *     operationId="waitlistList",
     *     tags={"Waitlist Route"},
     *     security={ {"bearer": {} }},
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent( 
     *              type="array",
     *              @OA\Items(ref="#/components/schemas/Waitlist")
     *         )
     *     ),
     * )
     *     
     */
    public function index(Request $request)
    {

        if(!PermissionsFacade::userHasMultipltSiteRoles($request->user(), [Roles::Administrator, Roles::SuperAdministrator])){
            return response()->json(['error' => 'Access denied waitlist.' , 'message' => 'Access denied waitlist.'], 403);
        }

        $waitlists = Waitlist::query();

        $data = $waitlists->orderBy('waitlists.created_at', 'desc')->paginate(25);

        return WaitlistResource::collection($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */


   /**
     * @OA\Post(
     *     path="/waitlists",
     *     summary="Sign-up a user to the waitlist.",
     *     description="Sign-up a user to the waitlist",
     *     operationId="waitlistCreate",
     *     tags={"Waitlist Route"},
     *     security={{"bearer": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent( ref="#/components/schemas/Waitlist")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *           @OA\JsonContent(
     *             ref="#/components/schemas/Waitlist"
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

        $waitlist = new Waitlist();

        $valid = $waitlist->validate($input);

        if (!$valid) {
            return response()->json($waitlist->getValidationErrors(), 422);
        }

        $waitlist = Waitlist::create($input);

        return new WaitlistResource($waitlist);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Waitlist  $waitlist
     * @return \Illuminate\Http\Response
     */

    /**
     * @OA\Get(
     *     path="/waitlists/{uuid}",
     *     summary="Retrieve a sign-up on the waitlist.",
     *     description="Retrieve a sign-up on the waitlist.",
     *     operationId="waitlistShow",
     *     tags={"Waitlist Route"},
     *     security={{"bearer": {}}},
     *     @OA\Parameter(
     *         name="uuid",
     *         in="path",
     *         description="UUID of the waitlist entry.",
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
     *             ref="#/components/schemas/Waitlist"
     *         ),
     *     @OA\Response(
     *      response=404,
     *      description="Waitlist entry does not exist."
     *      ) 
     *     )
     *)
     *     
     */
    public function show(Waitlist $waitlist, $id)
    {
        $waitlist = Waitlist::where('id', $id)->first();

        return new WaitlistResource($waitlist);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Waitlist  $waitlist
     * @return \Illuminate\Http\Response
     */

    /**
     * @OA\Put(
     *     path="/waitlists/{uuid}",
     *     summary="Update a waitlist entry.",
     *     description="Update a waitlist entry.",
     *     operationId="waitlistUpdate",
     *     tags={"Waitlist Route"},
     *     security={{"bearer": {}}},
     *      @OA\Parameter(
     *         name="uuid",
     *         in="path",
     *         description="UUID of the waitlist.",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             format="uuid"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent( ref="#/components/schemas/Waitlist")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *          @OA\JsonContent( ref="#/components/schemas/Waitlist")
     *     ),
     *     @OA\Response(
     *      response=403,
     *      description="Access denied to the waitlist resource.",
     *      @OA\JsonContent(
     *          @OA\Property(property="message", type="Access denined to waitlist resource.")
     *       )
     *     ) 
     * )
     *     
     */
    public function update(Request $request, string $id)
    {
        $waitlist = Waitlist::where('id', $id)->first();

        if(!$waitlist){
            return response()->json(['error' => 'The stream does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        if(!PermissionsFacade::waitListCanUpdate($waitlist, $request->user())){
            return response()->json(['error' => 'Access denied to waitlist.' , 'message' => 'Access denied to waitlist.'], 403);
        }

        $input = $request->all();

        $data = $input + $waitlist->toArray();

        $valid = $waitlist->validate($data);

        if (!$valid) {
            return response()->json($waitlist->getValidationErrors(), 422);
        }

        $waitlist->update($data);

        return new WaitlistResource($waitlist);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Waitlist  $waitlist
     * @return \Illuminate\Http\Response
     */

    /**
     * @OA\Delete(
     *     path="/waitlists/{uuid}",
     *     summary="Delete a waitlist entry.",
     *     description="Delete a waitlist entry.",
     *     operationId="waitlistDelete",
     *     tags={"Waitlist Route"},
     *     security={{"bearer": {}}},
     *      @OA\Parameter(
     *         name="uuid",
     *         in="path",
     *         description="UUID of the waitlist entry",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             format="uuid"
     *         )
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Waitlist entry successfully deleted",
     *     ),
     *     @OA\Response(
     *      response=403,
     *      description="Access denied to waitlist entry.",
     *      @OA\JsonContent(
     *         @OA\Property(property="message", type="Access denied to waitlist entry.")
     *        )
     *      ) 
     * )
     *     
     */
    public function destroy(Request $request, string $id)
    {

        if(!PermissionsFacade::userHasMultipltSiteRoles($request->user(), [Roles::Administrator, Roles::SuperAdministrator])){
            return response()->json(['error' => 'Access denied waitlist.' , 'message' => 'Access denied waitlist.'], 403);
        }

        $waitlist = Waitlist::where('id', $id)->first();
        
        $waitlist->delete();

        return response()->json(null, 204);
    }
}
