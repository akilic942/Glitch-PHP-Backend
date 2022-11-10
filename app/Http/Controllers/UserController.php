<?php

namespace App\Http\Controllers;

use App\Facades\AuthenticationFacade;
use App\Facades\FollowFacade;
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

class UserController extends Controller
{
    
    public function index()
    {
        return UserResource::collection(User::orderBy('created_at', 'desc')->paginate(25));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */


    public function update(Request $request)
    {
        $user = User::where('id', $request->user()->id)->first();

        if(!$user){
            return response()->json(['User does not exist'], 404);
        }

        $input = $request->all();

        if(isset($input['email'])) {
            unset($input['email']);
        }

        if(isset($input['password'])) {
            unset($input['password']);
        }

        $data = $input + $user->toArray();

        //unset($data['email']);
        //unset($data['password']);

        //$valid = $user->validate($data, ['email', 'password']);

        //if (!$valid) {
         //   return response()->json($user->getValidationErrors(), 422);
        //}

       
        $user->update($data);

        return UserResource::make($user);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }

    public function toggleFollow(Request $request, $id) {

        $following = User::where('id', $id)->first();

        $follower = FollowFacade::toggleFollowing($following, $request->user());

        if($follower){
            return new FollowResource($follower);
        }

        return  new UnfollowResource($following);
    }

    public function profile(Request $request, $id) {

        $user = User::where('id', $id)->first();

        return UserFullResource::make($user);
    }


  public function me(Request $request) {

    $user = $request->user();

    if(!$user){
        return response()->json(['Unauthorized'], 401);
    }

    $user = User::where('id', $user->id)->first();

    return UserFullResource::make($user);
  }

  public function onetimetoken(Request $request) {

    $user = User::where('id', $request->user()->id)->first();

    $user = AuthenticationFacade::createOneTimeLoginToken($user);

    return UserOneTimeTokenResource::make($user);
  }

}
