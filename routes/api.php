<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//Auth Routes
Route::post('auth/register', 'App\Http\Controllers\AuthController@register');
Route::post('auth/login', 'App\Http\Controllers\AuthController@login');
Route::post('auth/oneTimeLoginWithToken', 'App\Http\Controllers\AuthController@oneTimeLoginToken');
Route::post('auth/forgotpassword', 'App\Http\Controllers\ForgotPasswordController@requestNewPassword');
Route::post('auth/resetpassword', 'App\Http\Controllers\ForgotPasswordController@changePassword');

//Competition Routes
Route::get('competitions', 'App\Http\Controllers\CompetitionController@index');
Route::post('competitions', 'App\Http\Controllers\CompetitionController@store');
Route::get('competitions/{uuid}', 'App\Http\Controllers\CompetitionController@show');
Route::put('competitions/{uuid}', 'App\Http\Controllers\CompetitionController@update');
Route::delete('competitions/{uuid}', 'App\Http\Controllers\CompetitionController@destroy');

Route::post('competitions/{uuid}/registerUser', 'App\Http\Controllers\CompetitionController@registerParticipant');
Route::post('competitions/{uuid}/registerTeam', 'App\Http\Controllers\CompetitionController@registerTeam');

Route::post('competitions/{uuid}/uploadMainImage', 'App\Http\Controllers\CompetitionController@uploadMainImage');
Route::post('competitions/{uuid}/uploadBannerImage', 'App\Http\Controllers\CompetitionController@uploadBannerImage');


//Competition Rounds Routes
Route::get('competitions/{uuid}/rounds', 'App\Http\Controllers\CompetitionRoundController@index');
Route::post('competitions/{uuid}/rounds', 'App\Http\Controllers\CompetitionRoundController@store');
Route::get('competitions/{uuid}/rounds/{round_id}', 'App\Http\Controllers\CompetitionRoundController@show');
Route::put('competitions/{uuid}/rounds/{round_id}', 'App\Http\Controllers\CompetitionRoundController@update');
Route::delete('competitions/{uuid}/rounds/{round_id}', 'App\Http\Controllers\CompetitionRoundController@destroy');

//Competition Teams Routes
Route::get('competitions/{uuid}/teams', 'App\Http\Controllers\CompetitionTeamController@index');
Route::post('competitions/{uuid}/teams', 'App\Http\Controllers\CompetitionTeamController@store');
Route::get('competitions/{uuid}/teams/{team_id}', 'App\Http\Controllers\CompetitionTeamController@show');
Route::put('competitions/{uuid}/teams/{team_id}', 'App\Http\Controllers\CompetitionTeamController@update');
Route::delete('competitions/{uuid}/teams/{team_id}', 'App\Http\Controllers\CompetitionTeamController@destroy');

//Competition Venue Routes
Route::get('competitions/{uuid}/venues', 'App\Http\Controllers\CompetitionVenueController@index');
Route::post('competitions/{uuid}/venues', 'App\Http\Controllers\CompetitionVenueController@store');
Route::get('competitions/{uuid}/venues/{venue_id}', 'App\Http\Controllers\CompetitionVenueController@show');
Route::put('competitions/{uuid}/venues/{venue_id}', 'App\Http\Controllers\CompetitionVenueController@update');
Route::delete('competitions/{uuid}/venues/{venue_id}', 'App\Http\Controllers\CompetitionVenueController@destroy');
Route::post('competitions/{uuid}/venues/{venue_id}/uploadMainImage', 'App\Http\Controllers\CompetitionVenueController@uploadMainImage');


//Competition User Routes
Route::get('competitions/{uuid}/users', 'App\Http\Controllers\CompetitionUserController@index');
Route::post('competitions/{uuid}/users', 'App\Http\Controllers\CompetitionUserController@store');
Route::get('competitions/{uuid}/users/{user_id}', 'App\Http\Controllers\CompetitionUserController@show');
Route::put('competitions/{uuid}/users/{user_id}', 'App\Http\Controllers\CompetitionUserController@update');
Route::delete('competitions/{uuid}/users/{user_id}', 'App\Http\Controllers\CompetitionUserController@destroy');


//Competition Brackets Routes
Route::get('competitions/{uuid}/rounds/{round_id}/brackets', 'App\Http\Controllers\CompetitionRoundBracketController@index');
Route::post('competitions/{uuid}/rounds/{round_id}/brackets', 'App\Http\Controllers\CompetitionRoundBracketController@store');
Route::get('competitions/{uuid}/rounds/{round_id}/brackets/{bracket_id}', 'App\Http\Controllers\CompetitionRoundBracketController@show');
Route::put('competitions/{uuid}/rounds/{round_id}/brackets/{bracket_id}', 'App\Http\Controllers\CompetitionRoundBracketController@update');
Route::delete('competitions/{uuid}/rounds/{round_id}/brackets/{bracket_id}', 'App\Http\Controllers\CompetitionRoundBracketController@destroy');

Route::get('competitions/{uuid}/invites', 'App\Http\Controllers\CompetitionInviteController@index');
Route::post('competitions/{uuid}/sendInvite', 'App\Http\Controllers\CompetitionInviteController@store');
Route::post('competitions/{uuid}/acceptInvite', 'App\Http\Controllers\CompetitionInviteController@acceptInvite');

//Ticket Types
Route::get('competitions/{uuid}/tickettypes', 'App\Http\Controllers\CompetitionTicketTypeController@index');
Route::post('competitions/{uuid}/tickettypes', 'App\Http\Controllers\CompetitionTicketTypeController@store');
Route::get('competitions/{uuid}/tickettypes/{type_id}', 'App\Http\Controllers\CompetitionTicketTypeController@show');
Route::put('competitions/{uuid}/tickettypes/{type_id}', 'App\Http\Controllers\CompetitionTicketTypeController@update');
Route::delete('competitions/{uuid}/tickettypes/{type_id}', 'App\Http\Controllers\CompetitionTicketTypeController@destroy');

//Ticket Type Sections
Route::get('competitions/{uuid}/tickettypes/{type_id}/sections', 'App\Http\Controllers\CompetitionTicketTypeSectionController@index');
Route::post('competitions/{uuid}/tickettypes/{type_id}/sections', 'App\Http\Controllers\CompetitionTicketTypeSectionController@store');
Route::get('competitions/{uuid}/tickettypes/{type_id}/sections/{section_id}', 'App\Http\Controllers\CompetitionTicketTypeSectionController@show');
Route::put('competitions/{uuid}/tickettypes/{type_id}/sections/{section_id}', 'App\Http\Controllers\CompetitionTicketTypeSectionController@update');
Route::delete('competitions/{uuid}/tickettypes/{type_id}/sections/{section_id}', 'App\Http\Controllers\CompetitionTicketTypeSectionController@destroy');

//Ticket Type Fields
Route::get('competitions/{uuid}/tickettypes/{type_id}/fields', 'App\Http\Controllers\CompetitionTicketTypeFieldController@index');
Route::post('competitions/{uuid}/tickettypes/{type_id}/fields', 'App\Http\Controllers\CompetitionTicketTypeFieldController@store');
Route::get('competitions/{uuid}/tickettypes/{type_id}/fields/{section_id}', 'App\Http\Controllers\CompetitionTicketTypeFieldController@show');
Route::put('competitions/{uuid}/tickettypes/{type_id}/fields/{section_id}', 'App\Http\Controllers\CompetitionTicketTypeFieldController@update');
Route::delete('competitions/{uuid}/tickettypes/{type_id}/fields/{section_id}', 'App\Http\Controllers\CompetitionTicketTypeFieldController@destroy');

//Payment
Route::get('competitions/{uuid}/tickettypes/{type_id}/purchases', 'App\Http\Controllers\CompetitionTicketPurchaseController@index');
Route::post('competitions/{uuid}/tickettypes/{type_id}/purchases', 'App\Http\Controllers\CompetitionTicketPurchaseController@purchase');
Route::get('competitions/{uuid}/tickettypes/{type_id}/purchases/{purchase_id}', 'App\Http\Controllers\CompetitionTicketPurchaseController@show');

//Event Routes
Route::get('events', 'App\Http\Controllers\EventController@index');
Route::post('events', 'App\Http\Controllers\EventController@store');
Route::get('events/{uuid}', 'App\Http\Controllers\EventController@show');
Route::put('events/{uuid}', 'App\Http\Controllers\EventController@update');
Route::put('events/{uuid}/invirtu', 'App\Http\Controllers\EventController@updateInvirtuEvent');
Route::delete('events/{uuid}', 'App\Http\Controllers\EventController@destroy');

//Event RTMP
Route::post('events/{uuid}/addRTMPSource', 'App\Http\Controllers\EventController@addRTMPSource');
Route::put('events/{uuid}/updateRTMPSource/{subid}', 'App\Http\Controllers\EventController@updateRTMPSource');
Route::delete('events/{uuid}/removeRTMPSource/{subid}', 'App\Http\Controllers\EventController@removeRTMPSource');

//Main Image
Route::post('events/{uuid}/uploadMainImage', 'App\Http\Controllers\EventController@uploadMainImage');
Route::post('events/{uuid}/uploadBannerImage', 'App\Http\Controllers\EventController@uploadBannerImage');
Route::post('events/{uuid}/enableBroadcastMode', 'App\Http\Controllers\EventController@enableBroadcastMode');
Route::post('events/{uuid}/enableLivestreamMode', 'App\Http\Controllers\EventController@enableLivestreamMode');
Route::post('events/{uuid}/syncAsLive', 'App\Http\Controllers\EventController@syncAsLive');
Route::post('events/{uuid}/sendOnScreenContent', 'App\Http\Controllers\EventController@sendOnScreenContent');

//Overlay Ayouts
Route::post('events/{uuid}/addOverlay', 'App\Http\Controllers\EventController@uploadOverlay');
Route::delete('events/{uuid}/removeOverlay/{subid}', 'App\Http\Controllers\EventController@removeOverlay');
Route::post('events/{uuid}/enableOverlay/{subid}', 'App\Http\Controllers\EventController@enableOverlay');
Route::post('events/{uuid}/disableOverlay', 'App\Http\Controllers\EventController@disableOverlay');

//Donations Buttons
Route::post('events/{uuid}/enableDonations', 'App\Http\Controllers\EventController@enableDonations');
Route::post('events/{uuid}/disableDonations', 'App\Http\Controllers\EventController@disableDonations');

Route::post('events/{uuid}/sendInvite', 'App\Http\Controllers\EventInviteController@store');
Route::post('events/{uuid}/acceptInvite', 'App\Http\Controllers\EventInviteController@acceptInvite');

//Ticket Types
Route::get('events/{uuid}/tickettypes', 'App\Http\Controllers\EventTicketTypeController@index');
Route::post('events/{uuid}/tickettypes', 'App\Http\Controllers\EventTicketTypeController@store');
Route::get('events/{uuid}/tickettypes/{type_id}', 'App\Http\Controllers\EventTicketTypeController@show');
Route::put('events/{uuid}/tickettypes/{type_id}', 'App\Http\Controllers\EventTicketTypeController@update');
Route::delete('events/{uuid}/tickettypes/{type_id}', 'App\Http\Controllers\EventTicketTypeController@destroy');

//Recording Routes
Route::put('events/{uuid}/recording/{subid}', 'App\Http\Controllers\RecordingController@update');


//Messenging Routes
Route::get('messages', 'App\Http\Controllers\MessageController@getConversations');
Route::get('messages/threads', 'App\Http\Controllers\MessageController@getConversations');
Route::post('messages/makeThread', 'App\Http\Controllers\MessageController@conversations');
Route::post('messages', 'App\Http\Controllers\MessageController@store');
Route::put('messages/{uuid}', 'App\Http\Controllers\MessageController@update');
Route::delete('messages/{uuid}', 'App\Http\Controllers\MessageController@destroy');

//Teams Routes
Route::get('teams', 'App\Http\Controllers\TeamController@index');
Route::post('teams', 'App\Http\Controllers\TeamController@store');
Route::get('teams/{uuid}', 'App\Http\Controllers\TeamController@show');
Route::put('teams/{uuid}', 'App\Http\Controllers\TeamController@update');
Route::delete('teams/{uuid}', 'App\Http\Controllers\TeamController@destroy');

Route::post('teams/{uuid}/uploadMainImage', 'App\Http\Controllers\TeamController@uploadMainImage');
Route::post('teams/{uuid}/uploadBannerImage', 'App\Http\Controllers\TeamController@uploadBannerImage');

//Team User Routes
Route::get('teams/{uuid}/users', 'App\Http\Controllers\TeamUserController@index');
Route::post('teams/{uuid}/users', 'App\Http\Controllers\TeamUserController@store');
Route::get('teams/{uuid}/users/{user_id}', 'App\Http\Controllers\TeamUserController@show');
Route::put('teams/{uuid}/users/{user_id}', 'App\Http\Controllers\TeamUserController@update');
Route::delete('teams/{uuid}/users/{user_id}', 'App\Http\Controllers\TeamUserController@destroy');

Route::get('teams/{uuid}/invites', 'App\Http\Controllers\TeamInviteController@index');
Route::post('teams/{uuid}/sendInvite', 'App\Http\Controllers\TeamInviteController@store');
Route::post('teams/{uuid}/acceptInvite', 'App\Http\Controllers\TeamInviteController@acceptInvite');

//User Routes
Route::get('users', 'App\Http\Controllers\UserController@index');
Route::put('users', 'App\Http\Controllers\UserController@update');
Route::get('users/{uuid}/profile/', 'App\Http\Controllers\UserController@profile');
Route::get('users/me', 'App\Http\Controllers\UserController@me');
Route::get('users/oneTimeToken', 'App\Http\Controllers\UserController@onetimetoken');
Route::get('users/{uuid}/followers', 'App\Http\Controllers\UserController@followers');
Route::get('users/{uuid}/following', 'App\Http\Controllers\UserController@following');
Route::post('users/{uuid}/follow', 'App\Http\Controllers\UserController@toggleFollow');
Route::post('users/uploadAvatarImage', 'App\Http\Controllers\UserController@uploadAvatarImage');
Route::post('users/uploadBannerImage', 'App\Http\Controllers\UserController@uploadBannerImage');
Route::post('users/createDonationPage', 'App\Http\Controllers\UserController@createDonationPage');


Route::post('images/upload', 'App\Http\Controllers\ImageController@store');
Route::delete('images/{uuid}', 'App\Http\Controllers\ImageController@destroy');


Route::post('webhooks/invirtu', 'App\Http\Controllers\WebhookController@invirtuWebhook');
