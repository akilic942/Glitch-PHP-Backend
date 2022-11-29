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

//Event Routes
Route::get('events', 'App\Http\Controllers\EventController@index');
Route::post('events', 'App\Http\Controllers\EventController@store');
Route::get('events/{uuid}', 'App\Http\Controllers\EventController@show');
Route::put('events/{uuid}', 'App\Http\Controllers\EventController@update');
Route::put('events/{uuid}/invirtu', 'App\Http\Controllers\EventController@updateInvirtuEvent');
Route::delete('events/{uuid}', 'App\Http\Controllers\EventController@destroy');
Route::post('events/{uuid}/addRTMPSource', 'App\Http\Controllers\EventController@addRTMPSource');
Route::delete('events/{uuid}/removeRTMPSource/{subid}', 'App\Http\Controllers\EventController@removeRTMPSource');
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
Route::post('events/{uuid}/enableDonations', 'App\Http\Controllers\EventController@enableOverlay');
Route::post('events/{uuid}/disableDonations', 'App\Http\Controllers\EventController@disableOverlay');



Route::post('events/{uuid}/sendInvite', 'App\Http\Controllers\EventInviteController@store');
Route::post('events/{uuid}/acceptInvite', 'App\Http\Controllers\EventInviteController@acceptInvite');


//Recording Routes
Route::put('events/{uuid}/recording/{subid}', 'App\Http\Controllers\RecordingController@update');


//Messenging Routes
Route::get('messages', 'App\Http\Controllers\MessageController@getConversations');
Route::get('messages/threads', 'App\Http\Controllers\MessageController@getConversations');
Route::post('messages/makeThread', 'App\Http\Controllers\MessageController@conversations');
Route::post('messages', 'App\Http\Controllers\MessageController@store');
Route::put('messages/{uuid}', 'App\Http\Controllers\MessageController@update');
Route::delete('messages/{uuid}', 'App\Http\Controllers\MessageController@destroy');

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
