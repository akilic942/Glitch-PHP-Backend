<?php

use App\Facades\AuthenticationFacade;
use Illuminate\Support\Facades\Route;

use Laravel\Socialite\Facades\Socialite;

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

    if(isset($input['token']) && $input['token']){
        AuthenticationFacade::useOneTimeLoginToken($input['token']);
    }

    return Socialite::driver('facebook')->redirect();
});
 
Route::get('/auth/faceook/callback', function () {

    $user = Socialite::driver('facebook')->user();

    $loggedInUser = Auth::user();

    if($loggedInUser){

        $loggedInUser->forceFill([
            'facebook_auth_token' => $user->token
        ]);

        $loggedInUser->save();
    }
 
    // $user->token
});

Route::get('/auth/youtube/redirect', function (Request $request) {

    if(isset($input['token']) && $input['token']){
        AuthenticationFacade::useOneTimeLoginToken($input['token']);
    }

    return Socialite::driver('youtube')->redirect();
});
 
Route::get('/auth/youtube/callback', function () {
    $user = Socialite::driver('youtube')->user();

    $loggedInUser = Auth::user();
 
    if($loggedInUser){
        
        $loggedInUser->forceFill([
            'youtube_auth_token' => $user->token
        ]);

        $loggedInUser->save();
    }
});


Route::get('/auth/twitch/redirect', function (Request $request) {

    if(isset($input['token']) && $input['token']){
        AuthenticationFacade::useOneTimeLoginToken($input['token']);
    }

    return Socialite::driver('twitch')->redirect();
});
 
Route::get('/auth/twitch/callback', function () {

    print_r("Jere");
    exit();
    
    $user = Socialite::driver('twitch')->user();

    $loggedInUser = Auth::user();

    if($loggedInUser){
        
        $loggedInUser->forceFill([
            'twitich_auth_token' => $user->token
        ]);

        $loggedInUser->save();
    }
 
    // $user->token
});
