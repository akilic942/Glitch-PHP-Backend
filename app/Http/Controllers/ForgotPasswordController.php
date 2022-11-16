<?php

namespace App\Http\Controllers;

use App\Facades\UsersFacade;
use App\Http\Controllers\Controller;
use App\Models\PasswordReset;
use Illuminate\Http\Request; 
use Carbon\Carbon; 
use App\Models\User; 
use Mail; 
use Hash;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{
    public function requestNewPassword(Request $request) {

        $result = $request->validate([
            'email' => 'required|email|exists:users',
        ]);

        if(!$result) {
            return response()->json(['There is no user with that email.'], 422);
        }

        $input = $request->all();

        UsersFacade::sendPasswordReset($input['email']);

        return response()->json(['New Password request send'], 201);
    }

    public function changePassword(Request $request) {

        $input = $request->all();

        if(!isset($input['email']) || isset($input['email']) && !$input['email']) {
            return response()->json(['An email is required to reset the password'], 422);
        }

        if(!isset($input['token']) || isset($input['token']) && !$input['token']) {
            return response()->json(['A valid token is required reset the password'], 422);
        }

        if(!isset($input['new_password']) || isset($input['new_password']) && !$input['new_password']) {
            return response()->json(['A valid token is required reset the password'], 422);
        }

        $password = PasswordReset::where('email', $input['email']) -> where('token', $input['token']) -> first();

        if(!$password) {
            return response()->json(['No password reset request was found.'], 422);
        }

        UsersFacade::resetPassword($password, $input['new_password']);

        return response()->json(['Password has been reset.'], 201);
    }
}
