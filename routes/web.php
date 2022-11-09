<?php

use App\Facades\AuthenticationFacade;
use App\Facades\UsersFacade;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/auth/facebook/redirect', function (Request $request) {

    $input = $request->all();

    if(isset($input['token']) && $input['token']){
        AuthenticationFacade::useOneTimeLoginToken($input['token']);
    }

    return Socialite::driver('facebook')->redirect();
});
 
Route::get('/auth/faceook/callback', function () {

    $user = Socialite::driver('facebook')->user();

    echo '<pre>';
    print_r($user);
    exit();
    
    //Check to see if the user is logged in
    $loggedInUser = Auth::user();

    $redirect_query='';

    //If they are not logged in, we are going to authenticate them
    //and then use a one time token to login them when they return
    //to the frontend
    if(!$loggedInUser) {
        $loggedInUser = UsersFacade::retrieveOrCreate($user->email, $user->first_name, $user->last_name, $user->nickname);

        $loggedInUser = AuthenticationFacade::createOneTimeLoginToken($loggedInUser);

        $redirect_query = '?loginToken=' . $loggedInUser->one_time_login_token;
    }

    if($loggedInUser){

        $loggedInUser->forceFill([
            'facebook_auth_token' => $user->token
        ]);

        $loggedInUser->save();
    }
 
    return Redirect::to(env('FACEBOOK_REDIRECT_BACK_TO_SITE') . $redirect_query);
});

Route::get('/auth/youtube/redirect', function (Request $request) {

    $input = $request->all();

    if(isset($input['token']) && $input['token']){
        AuthenticationFacade::useOneTimeLoginToken($input['token']);
    }

    return Socialite::driver('youtube')->redirect();
});
 
Route::get('/auth/youtube/callback', function () {

    $user = Socialite::driver('youtube')->user();

    echo '<pre>';
    print_r($user);
    exit();

    //Check to see if the user is logged in
    $loggedInUser = Auth::user();

    $redirect_query='';

    //If they are not logged in, we are going to authenticate them
    //and then use a one time token to login them when they return
    //to the frontend
    if(!$loggedInUser) {
        $loggedInUser = UsersFacade::retrieveOrCreate($user->email, $user->nickname, Str::random(10), $user->nickname, $user->avatar);

        $loggedInUser = AuthenticationFacade::createOneTimeLoginToken($loggedInUser);

        $redirect_query = '?loginToken=' . $loggedInUser->one_time_login_token;
    }

    if(!$loggedInUser) {
        $loggedInUser = UsersFacade::retrieveOrCreate($user->email, $user->nickname, Str::random(10), $user->nickname);

        $loggedInUser = AuthenticationFacade::createOneTimeLoginToken($loggedInUser);

        $redirect_query = '?loginToken=' . $loggedInUser->one_time_login_token;
    }
 
    if($loggedInUser){
        
        $loggedInUser->forceFill([
            'youtube_auth_token' => $user->token,
            'youtube_refresh_token' => $user->refreshToken,
            'youtube_token_expiration' => $user->expires_in,
            'youtube_id' => $user->id,
            'youtube_auth_token' => $user->token,
            'youtube_username' => $user->nickname,
            'youtube_avatar' => $user -> avatar
        ]);

        $loggedInUser->save();
    }

    return Redirect::to(env('YOUTUBE_REDIRECT_BACK_TO_SITE') . $redirect_query);
});


Route::get('/auth/twitch/redirect', function (Request $request) {

    $input = $request->all();

    if(isset($input['token']) && $input['token']){
        AuthenticationFacade::useOneTimeLoginToken($input['token']);
    }

    return Socialite::driver('twitch')->redirect();
});
 
Route::get('/auth/twitch/callback', function () {
    
    $user = Socialite::driver('twitch')->user();

    //Check to see if the user is logged in
    $loggedInUser = Auth::user();

    $redirect_query='';

    //If they are not logged in, we are going to authenticate them
    //and then use a one time token to login them when they return
    //to the frontend
    if(!$loggedInUser) {
        $loggedInUser = UsersFacade::retrieveOrCreate($user->email, $user->nickname, Str::random(10), $user->nickname);

        $loggedInUser = AuthenticationFacade::createOneTimeLoginToken($loggedInUser);

        $redirect_query = '?loginToken=' . $loggedInUser->one_time_login_token;
    }

    if($loggedInUser){
        
        $loggedInUser->forceFill([
            'twitch_id' => $user->id,
            'twitch_auth_token' => $user->token,
            'twitch_refresh_token' => $user->refreshToken,
            'twitch_token_expiration' => $user->expiresIn,
            'twitch_username' => $user->nickname,
            'twitch_email' => $user->email,
            'twitch_avatar' => $user->avatar

        ]);

        $loggedInUser->save();
    }

    return Redirect::to(env('TWTICH_REDIRECT_BACK_TO_SITE') . $redirect_query);
});
