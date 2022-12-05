<?php

use App\Facades\AuthenticationFacade;
use App\Facades\UsersFacade;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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
    //echo '123';
    //print_r(Storage::disk('local')->exists('public/banners/esports/offline_banner_1920x1080px.jpeg'));
    //print_r(Storage::disk('local')->get('public/banners/esports/offline_banner_1920x1080px.jpeg'));
    //exit();
    return view('welcome');
});

Route::get('/auth/facebook/redirect', function (Request $request) {

    $input = $request->all();

    if (isset($input['token']) && $input['token']) {
        AuthenticationFacade::useOneTimeLoginToken($input['token'], 'web');
    }

    if(isset($input['redirect']) && $input['redirect']) {
        AuthenticationFacade::setRedirectURI($input['redirect']);
    }

    return Socialite::driver('facebook')->redirect();
});

Route::get('/auth/facebook/callback', function () {

    $user = Socialite::driver('facebook')->user();

    //Check to see if the user is logged in
    $loggedInUser = Auth::guard('web')->user();

    $redirect_query = (AuthenticationFacade::getRedirectURI()) ? AuthenticationFacade::getRedirectURI() :  env('FACEBOOK_REDIRECT_BACK_TO_SITE');

    //If they are not logged in, we are going to authenticate them
    //and then use a one time token to login them when they return
    //to the frontend
    if (!$loggedInUser) {

        $name_parts = explode(" ", $user->name);

        $first_name = $name_parts[0];

        $last_name = '';

        if (isset($name_parts[1]) && $name_parts[1]) {
            $last_name = $name_parts[1];
        } else {
            $last_name = $name_parts[0];
        }

        $loggedInUser = UsersFacade::retrieveOrCreate($user->email, $first_name, $last_name, $user->name, $user->avatar);

        $loggedInUser = AuthenticationFacade::createOneTimeLoginToken($loggedInUser);

        $redirect_query = AuthenticationFacade::appendOneTimeLoginTokenToRedirect($redirect_query, $loggedInUser->one_time_login_token);
    }

    if ($loggedInUser) {

        $loggedInUser->forceFill([
            'facebook_auth_token' => $user->token,
            'facebook_id' => $user->id,
            'facebook_name' => $user->name,
            'facebook_email' => $user->email,
            'facebook_avatar' => $user->avatar,
            'facebook_token_expiration' => $user->expiresIn,
        ]);

        $loggedInUser->save();
    }

    AuthenticationFacade::clearRedirectURI();
    Auth::logout();
 
    return Redirect::to($redirect_query);
});

Route::get('/auth/youtube/redirect', function (Request $request) {

    $input = $request->all();

    if (isset($input['token']) && $input['token']) {
        AuthenticationFacade::useOneTimeLoginToken($input['token'], 'web');
    }

    if(isset($input['redirect']) && $input['redirect']) {
        AuthenticationFacade::setRedirectURI($input['redirect']);
    }

    return Socialite::driver('youtube')->redirect();
});

Route::get('/auth/youtube/callback', function () {

    $user = Socialite::driver('youtube')->user();

    //Check to see if the user is logged in
    $loggedInUser = Auth::guard('web')->user();

    $redirect_query = (AuthenticationFacade::getRedirectURI()) ? AuthenticationFacade::getRedirectURI() :  env('YOUTUBE_REDIRECT_BACK_TO_SITE');

    //If they are not logged in, we are going to authenticate them
    //and then use a one time token to login them when they return
    //to the frontend
    if (!$loggedInUser) {
        $loggedInUser = UsersFacade::retrieveOrCreate($user->email, $user->nickname, Str::random(10), $user->nickname, $user->avatar);

        $loggedInUser = AuthenticationFacade::createOneTimeLoginToken($loggedInUser);

        $redirect_query = AuthenticationFacade::appendOneTimeLoginTokenToRedirect($redirect_query, $loggedInUser->one_time_login_token);
    }

    if ($loggedInUser) {

        $loggedInUser->forceFill([
            'youtube_auth_token' => $user->token,
            'youtube_refresh_token' => $user->refreshToken,
            'youtube_token_expiration' => $user->expires_in,
            'youtube_id' => $user->id,
            'youtube_auth_token' => $user->token,
            'youtube_username' => $user->nickname,
            'youtube_avatar' => $user->avatar
        ]);

        $loggedInUser->save();
    }

    AuthenticationFacade::clearRedirectURI();
    Auth::logout();
 
    return Redirect::to($redirect_query);
});


Route::get('/auth/twitch/redirect', function (Request $request) {

    $input = $request->all();

    if (isset($input['token']) && $input['token']) {
        AuthenticationFacade::useOneTimeLoginToken($input['token'], 'web');
    }

    if(isset($input['redirect']) && $input['redirect']) {
        AuthenticationFacade::setRedirectURI($input['redirect']);
    }

    return Socialite::driver('twitch')->redirect();
});

Route::get('/auth/twitch/callback', function () {

    $user = Socialite::driver('twitch')->user();

    //Check to see if the user is logged in
    $loggedInUser = Auth::guard('web')->user();

    $redirect_query = (AuthenticationFacade::getRedirectURI()) ? AuthenticationFacade::getRedirectURI() :  env('TWITCH_REDIRECT_BACK_TO_SITE');

    //If they are not logged in, we are going to authenticate them
    //and then use a one time token to login them when they return
    //to the frontend
    if (!$loggedInUser) {
        $loggedInUser = UsersFacade::retrieveOrCreate($user->email, $user->nickname, Str::random(10), $user->nickname);

        $loggedInUser = AuthenticationFacade::createOneTimeLoginToken($loggedInUser);

        $redirect_query = AuthenticationFacade::appendOneTimeLoginTokenToRedirect($redirect_query, $loggedInUser->one_time_login_token);
    }

    if ($loggedInUser) {

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

    AuthenticationFacade::clearRedirectURI();
    Auth::logout();

    return Redirect::to($redirect_query);
});

Route::get('/auth/stripe/redirect', function (Request $request) {

    $input = $request->all();

    if (isset($input['token']) && $input['token']) {
        AuthenticationFacade::useOneTimeLoginToken($input['token'], 'web');
    }

    $loggedInUser = Auth::guard('web')->user();

    if(!$loggedInUser) {
        echo "Error: An authenticated user is required to connect with Stripe.";
        exit();
    }

    if(isset($input['redirect']) && $input['redirect']) {
        AuthenticationFacade::setRedirectURI($input['redirect']);
    }

    return Socialite::driver('stripe')->redirect();
});

Route::get('/auth/stripe/callback', function () {

    $user = Socialite::driver('stripe')->user();

    //Check to see if the user is logged in
    $loggedInUser = Auth::guard('web')->user();

    if(!$loggedInUser) {
        echo "Error: An authenticated user is required to connect with Stripe.";
        exit();
    }

    $redirect_query = (AuthenticationFacade::getRedirectURI()) ? AuthenticationFacade::getRedirectURI() :  env('STRIPE_REDIRECT_BACK_TO_SITE');

    //Login User is required for stripe

    if ($loggedInUser) {

        $loggedInUser->forceFill([
            'stripe_express_account_id' => $user->id,
            'stripe_express_email' => $user->email,
            'stripe_express_currency' => $user->user['default_currency'],
            'stripe_express_country' => $user->user['country'],
            'stripe_express_token' => $user->token,
            'stripe_express_refresh_token' => $user->refreshToken,
        ]);

        $loggedInUser->save();
    }

    AuthenticationFacade::clearRedirectURI();
    Auth::logout();

    return Redirect::to($redirect_query);
});


Route::get('/auth/google/redirect', function (Request $request) {

    $input = $request->all();

    if (isset($input['token']) && $input['token']) {
        AuthenticationFacade::useOneTimeLoginToken($input['token'], 'web');
    }

    if(isset($input['redirect']) && $input['redirect']) {
        AuthenticationFacade::setRedirectURI($input['redirect']);
    }

    return Socialite::driver('google')->redirect();
});

Route::get('/auth/google/callback', function () {

    $user = Socialite::driver('google')->user();

    echo '<pre>';
    print_r($user);
    exit();

    //Check to see if the user is logged in
    $loggedInUser = Auth::guard('web')->user();

    $redirect_query = (AuthenticationFacade::getRedirectURI()) ? AuthenticationFacade::getRedirectURI() :  env('GOOGLE_REDIRECT_BACK_TO_SITE');

    //If they are not logged in, we are going to authenticate them
    //and then use a one time token to login them when they return
    //to the frontend
    if (!$loggedInUser) {

        $name_parts = explode(" ", $user->name);

        $first_name = $name_parts[0];

        $last_name = '';

        if (isset($name_parts[1]) && $name_parts[1]) {
            $last_name = $name_parts[1];
        } else {
            $last_name = $name_parts[0];
        }

        $loggedInUser = UsersFacade::retrieveOrCreate($user->email, $first_name, $last_name, $user->nickname, $user->avatar);

        $loggedInUser = AuthenticationFacade::createOneTimeLoginToken($loggedInUser);

        $redirect_query = AuthenticationFacade::appendOneTimeLoginTokenToRedirect($redirect_query, $loggedInUser->one_time_login_token);
    }

    if ($loggedInUser) {

        $loggedInUser->forceFill([
            'google_auth_token' => $user->token,
            'google_id' => $user->id,
            'google_name' => $user->name,
            'google_email' => $user->email,
            'google_avatar' => $user->avatar,
            'google_token_expiration' => $user->expiresIn,
        ]);

        $loggedInUser->save();
    }

    AuthenticationFacade::clearRedirectURI();
    Auth::logout();

    return Redirect::to($redirect_query);
});


Route::get('/auth/microsoft/redirect', function (Request $request) {

    $input = $request->all();

    if (isset($input['token']) && $input['token']) {
        AuthenticationFacade::useOneTimeLoginToken($input['token'], 'web');
    }

    if(isset($input['redirect']) && $input['redirect']) {
        AuthenticationFacade::setRedirectURI($input['redirect']);
    }

    return Socialite::driver('microsoft')->redirect();
});

Route::get('/auth/microsoft/callback', function () {

    $user = Socialite::driver('microsoft')->user();

    echo '<pre>';
    print_r($user);
    exit();

    //Check to see if the user is logged in
    $loggedInUser = Auth::guard('web')->user();

    $redirect_query = (AuthenticationFacade::getRedirectURI()) ? AuthenticationFacade::getRedirectURI() :  env('MICROSOFT_REDIRECT_BACK_TO_SITE');

    //If they are not logged in, we are going to authenticate them
    //and then use a one time token to login them when they return
    //to the frontend
    if (!$loggedInUser) {

        $name_parts = explode(" ", $user->name);

        $first_name = $name_parts[0];

        $last_name = '';

        if (isset($name_parts[1]) && $name_parts[1]) {
            $last_name = $name_parts[1];
        } else {
            $last_name = $name_parts[0];
        }

        $loggedInUser = UsersFacade::retrieveOrCreate($user->email, $first_name, $last_name, $user->nickname, $user->avatar);

        $loggedInUser = AuthenticationFacade::createOneTimeLoginToken($loggedInUser);

        $redirect_query = AuthenticationFacade::appendOneTimeLoginTokenToRedirect($redirect_query, $loggedInUser->one_time_login_token);
    }

    if ($loggedInUser) {

        $loggedInUser->forceFill([
            'microsoft_auth_token' => $user->token,
            'microsoft_id' => $user->id,
            'microsoft_name' => $user->name,
            'microsoft_email' => $user->email,
            'microsoft_avatar' => $user->avatar,
            'microsoft_token_expiration' => $user->expiresIn,
        ]);

        $loggedInUser->save();
    }

    AuthenticationFacade::clearRedirectURI();
    Auth::logout();

    return Redirect::to($redirect_query);
});


Route::get('/auth/teams/redirect', function (Request $request) {

    $input = $request->all();

    if (isset($input['token']) && $input['token']) {
        AuthenticationFacade::useOneTimeLoginToken($input['token'], 'web');
    }

    if(isset($input['redirect']) && $input['redirect']) {
        AuthenticationFacade::setRedirectURI($input['redirect']);
    }

    return Socialite::driver('teamservice')->redirect();
});

Route::get('/auth/teams/callback', function () {

    $user = Socialite::driver('teamservice')->user();

    echo '<pre>';
    print_r($user);
    exit();

    //Check to see if the user is logged in
    $loggedInUser = Auth::guard('web')->user();

    $redirect_query = (AuthenticationFacade::getRedirectURI()) ? AuthenticationFacade::getRedirectURI() :  env('TEAMSERVICE_REDIRECT_BACK_TO_SITE');

    //If they are not logged in, we are going to authenticate them
    //and then use a one time token to login them when they return
    //to the frontend
    if (!$loggedInUser) {

        $name_parts = explode(" ", $user->name);

        $first_name = $name_parts[0];

        $last_name = '';

        if (isset($name_parts[1]) && $name_parts[1]) {
            $last_name = $name_parts[1];
        } else {
            $last_name = $name_parts[0];
        }

        $loggedInUser = UsersFacade::retrieveOrCreate($user->email, $first_name, $last_name, $user->nickname, $user->avatar);

        $loggedInUser = AuthenticationFacade::createOneTimeLoginToken($loggedInUser);

        $redirect_query = AuthenticationFacade::appendOneTimeLoginTokenToRedirect($redirect_query, $loggedInUser->one_time_login_token);
    }

    if ($loggedInUser) {

        $loggedInUser->forceFill([
            'microsoft_teams_auth_token' => $user->token,
            'microsoft_teamsid' => $user->id,
            'microsoft_teams_name' => $user->name,
            'microsoft_teams_email' => $user->email,
            'microsoft_teams_avatar' => $user->avatar,
            'microsoft_teams_token_expiration' => $user->expiresIn,
        ]);

        $loggedInUser->save();
    }

    AuthenticationFacade::clearRedirectURI();
    Auth::logout();

    return Redirect::to($redirect_query);
});
