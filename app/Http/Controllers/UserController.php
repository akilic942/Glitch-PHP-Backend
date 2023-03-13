<?php

namespace App\Http\Controllers;

use App\Enums\HttpStatusCodes;
use App\Facades\AuthenticationFacade;
use App\Facades\FollowFacade;
use App\Facades\UsersFacade;
use App\Http\Requests\StoreImageRequest;
use App\Http\Resources\AffirmationResource;
use App\Http\Resources\DiscussionResource;
use App\Http\Resources\FollowResource;
use App\Http\Resources\PostResource;
use App\Http\Resources\UnfollowResource;
use App\Http\Resources\UserFullResource;
use App\Http\Resources\UserOneTimeTokenResource;
use App\Http\Resources\UserResource;
use App\Http\Resources\UserSchoolResource;
use App\Models\Affirmation;
use App\Models\Discussion;
use App\Models\Post;
use App\Models\Story;
use App\Models\User;
use App\Models\UserImage;
use App\Models\UserSchool;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{

    /**
     * @OA\Get(
     *     path="/users",
     *     summary="Retrieve a list of users.",
     *     description="Retrieve a list of users.",
     *     operationId="userList",
     *     tags={"Users Route"},
     *     security={ {"bearer": {} }},
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent( 
     *          type="array",
     *           @OA\Items(ref="#/components/schemas/User"),
     *          )
     *     ),
     * )
     *     
     */
    public function index()
    {
        return UserResource::collection(User::orderBy('created_at', 'desc')->paginate(25));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */


    /**
     * @OA\Put(
     *     path="/users",
     *     summary="Updates a user but requires the JWT of the user being updated.",
     *     description="Updates a user but requires the JWT of the user being updated.",
     *     operationId="updateUser",
     *     tags={"Users Route"},
     *     security={{"bearer": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent( ref="#/components/schemas/User")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *          @OA\JsonContent( ref="#/components/schemas/User")
     *     ),
     *     @OA\Response(
     *      response=404,
     *      description="The user does not exist.",
     *      @OA\JsonContent(
     *          @OA\Property(property="message", type="The user does not exist..")
     *       )
     *     ),
     *     @OA\Response(
     *      response=422,
     *      description="Validation errors occured.",
     *      @OA\JsonContent(
     *          @OA\Property(property="message", type="Validation errors occured.")
     *       )
     *     ) 
     * )
     *     
     */
    public function update(Request $request)
    {
        $user = User::where('id', $request->user()->id)->first();

        if (!$user) {
            return response()->json(['User does not exist'], 404);
        }

        $input = $request->all();

        if (isset($input['email'])) {
            unset($input['email']);
        }

        if (isset($input['password'])) {
            unset($input['password']);
        }

        if (isset($input['avatar'])) {
            unset($input['avatar']);
        }

        $data = $input + $user->toArray();

        $valid = $user->validate($data, ['email', 'password', 'username', 'avatar'], ['username' => Rule::unique('users')->ignore($user->id), 'email' => Rule::unique('users')->ignore($user->id)]);

        if (!$valid) {
            return response()->json($user->getValidationErrors(), 422);
        }

        // For some weird reason, I have to get a new uer object.

        $user = User::where('id', $request->user()->id)->first();

        //return response()->json($data, 422);
        $user->update($input + $user->toArray());

        return UserResource::make($user);
    }

    /**
     * @OA\Post(
     *     path="/users/{uuid}/follow",
     *     summary="Toggle following a user. So if not following, accessing this route will follow the user. And if the user is being followed, it will unfollow them.",
     *     description="Toggle following a user. So if not following, accessing this route will follow the user. And if the user is being followed, it will unfollow them.",
     *     operationId="userToggleFollow",
     *     tags={"Users Route"},
     *     security={{"bearer": {}}},
     *      @OA\Parameter(
     *         name="uuid",
     *         in="path",
     *         description="UUID of the user to follow/unfollow.",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             format="uuid"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *          @OA\JsonContent( ref="#/components/schemas/Follow")
     *     ),
     * )
     *     
     */
    public function toggleFollow(Request $request, $id)
    {

        $following = User::where('id', $id)->first();

        $follower = FollowFacade::toggleFollowing($following, $request->user());

        if ($follower) {
            return new FollowResource($follower);
        }

        return  new UnfollowResource($following);
    }

    /**
     * @OA\Get(
     *     path="/users/{uuid}/profile",
     *     summary="Show a single user by their ID.",
     *     description="Show a single user by their ID.",
     *     operationId="showUser",
     *     tags={"Users Route"},
     *     security={{"bearer": {}}},
     *     @OA\Parameter(
     *         name="uuid",
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
     *             ref="#/components/schemas/UserFull"
     *     ),
     *     @OA\Response(
     *      response=404,
     *      description="The user does not exist.",
     *      @OA\JsonContent(
     *          @OA\Property(property="message", type="The competition does not exist.")
     *       )
     *     ),
     *     )
     *)
     *     
     */
    public function profile(Request $request, $id)
    {

        $user = User::where('id', $id)->first();

        return UserFullResource::make($user);
    }

    /**
     * @OA\Get(
     *     path="/users/me",
     *     summary="Retrieves the user based on the current auth token being used.",
     *     description="Retrieves the user based on the current auth token being used.",
     *     operationId="showMe",
     *     tags={"Users Route"},
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             ref="#/components/schemas/UserFull"
     *     ),
     *     @OA\Response(
     *      response=401,
     *      description="Unauthorized.",
     *      @OA\JsonContent(
     *          @OA\Property(property="message", type="Unauthorized.")
     *       )
     *     ),
     *     )
     *)
     *     
     */
    public function me(Request $request)
    {

        $user = $request->user();

        if (!$user) {
            return response()->json(['Unauthorized'], 401);
        }

        $user = User::where('id', $user->id)->first();

        return UserFullResource::make($user);
    }

    /**
     * @OA\Get(
     *     path="/users/oneTimeToken",
     *     summary="Retrieves the one time login token based on the current auth token being used.",
     *     description="Retrieves the one time login token based on the current auth token being used.",
     *     operationId="userOneTimeLoginToken",
     *     tags={"Users Route"},
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             ref="#/components/schemas/UserOneTimeToken"
     *     ),
     *     @OA\Response(
     *      response=401,
     *      description="Unauthorized.",
     *      @OA\JsonContent(
     *          @OA\Property(property="message", type="Unauthorized.")
     *       )
     *     ),
     *     )
     *)
     *     
     */
    public function onetimetoken(Request $request)
    {

        $user = User::where('id', $request->user()->id)->first();

        $user = AuthenticationFacade::createOneTimeLoginToken($user);

        return UserOneTimeTokenResource::make($user);
    }

    /**
     * @OA\Post(
     *     path="/users/uploadAvatarImage",
     *     summary="Upload the user's avatar. Uses the current users auth token to select which user being updated.",
     *     description="Upload the user's avatar. Uses the current users auth token to select which user being updated.",
     *     operationId="userUploadAvatarImage",
     *     tags={"Users Route"},
     *     security={{"bearer": {}}},
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
     *             ref="#/components/schemas/UserFull"
     *         )
     *     ),
     *     @OA\Response(
     *      response=401,
     *      description="Unauthorized access to user.",
     *      @OA\JsonContent(
     *              @OA\Property(property="message", type="Unauthorized access to user.")
     *              )
     *      ),
     * )
     *     
     */
    public function uploadAvatarImage(StoreImageRequest $request)
    {
        /*$this->validate($request, [
            'image' => 'required|mimes:png,jpg,gif|max:9999',
        ]);*/

        $user = $request->user();

        if (!$user) {
            return response()->json(['Unauthorized'], 401);
        }

        $base_location = 'images';

        // Handle File Upload
        if ($request->hasFile('image')) {
            //Using store(), the filename will be hashed. You can use storeAs() to specify a name.
            //To specify the file visibility setting, you can update the config/filesystems.php s3 disk visibility key,
            //or you can specify the visibility of the file in the second parameter of the store() method like:
            //$imagePath = $request->file('document')->store($base_location, ['disk' => 's3', 'visibility' => 'public']);

            $imagePath = $request->file('image')->store($base_location, ['disk' => 's3', 'visibility' => 'public']);
        } else {
            return response()->json(['success' => false, 'message' => 'No file uploaded'], 400);
        }

        //We save new path
        $user->forceFill([
            'avatar' => $imagePath
        ]);

        $user->save();

        return UserFullResource::make($user);
    }

    /**
     * @OA\Post(
     *     path="/users/uploadBannerImage",
     *     summary="Upload the user's banner. Uses the current users auth token to select which user being updated.",
     *     description="Upload the user's banner. Uses the current users auth token to select which user being updated.",
     *     operationId="userUploadBannerImage",
     *     tags={"Users Route"},
     *     security={{"bearer": {}}},
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
     *             ref="#/components/schemas/UserFull"
     *         )
     *     ),
     *     @OA\Response(
     *      response=401,
     *      description="Unauthorized access to user.",
     *      @OA\JsonContent(
     *              @OA\Property(property="message", type="Unauthorized access to user.")
     *              )
     *      ),
     * )
     *     
     */
    public function uploadBannerImage(StoreImageRequest $request)
    {
        /*$this->validate($request, [
            'image' => 'required|mimes:png,jpg,gif|max:9999',
        ]);*/

        $user = $request->user();

        if (!$user) {
            return response()->json(['Unauthorized'], 401);
        }

        $base_location = 'images';

        // Handle File Upload
        if ($request->hasFile('image')) {
            //Using store(), the filename will be hashed. You can use storeAs() to specify a name.
            //To specify the file visibility setting, you can update the config/filesystems.php s3 disk visibility key,
            //or you can specify the visibility of the file in the second parameter of the store() method like:
            //$imagePath = $request->file('document')->store($base_location, ['disk' => 's3', 'visibility' => 'public']);

            $imagePath = $request->file('image')->store($base_location, ['disk' => 's3', 'visibility' => 'public']);
        } else {
            return response()->json(['success' => false, 'message' => 'No file uploaded'], 400);
        }

        //We save new path
        $user->forceFill([
            'banner_image' => $imagePath
        ]);

        $user->save();

        return UserFullResource::make($user);
    }

    /**
     * @OA\Post(
     *     path="/users/createDonationPage",
     *     summary="Creates a donation page for the user to accept donations. Users current auth token.",
     *     description="Creates a donation page for the user to accept donations. Users current auth token.",
     *     operationId="userCreateDonationPage",
     *     tags={"Users Route"},
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             ref="#/components/schemas/UserFull"
     *     ),
     *     @OA\Response(
     *      response=401,
     *      description="Unauthorized.",
     *      @OA\JsonContent(
     *          @OA\Property(property="message", type="Unauthorized.")
     *       )
     *     ),
     *     )
     *)
     *     
     */
    public function createDonationPage(StoreImageRequest $request)
    {

        $user = $request->user();

        if (!$user) {
            return response()->json(['Unauthorized'], 401);
        }

        if (!$user->stripe_express_account_id) {
            return response()->json(['Must be authenticated with Stripe first.'], HttpStatusCodes::HTTP_NO_CONTENT);
        }

        UsersFacade::runAllDonationLinkCreation($user);

        return UserFullResource::make($user);
    }
}
