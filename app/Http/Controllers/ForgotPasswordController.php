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

    /**
     * @OA\Post(
     *      path="/auth/forgotpassword",
     *      summary="Request a new password for a user.",
     *      description="Request a new password for a user.",
     *      operationId="authForgotPassword",
     *      tags={"Authentication Route"},
     *      @OA\RequestBody(
     *          required=true,
     *          description="Send users information to start the reset procress.",
     *          @OA\JsonContent(
     *              required={"email"},
     *              @OA\Property(property="email", type="string", format="email", example="user1@mail.com", description="The users email."),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful Password Reset Request",
     *           @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="New password request sent.")
     *          )
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="There is no user with that email.",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="There is no user with that email.")
     *          )
     *      ),
     * ),
     * 
     */
    public function requestNewPassword(Request $request) {

        $result = $request->validate([
            'email' => 'required|email|exists:users',
        ]);

        if(!$result) {
            return response()->json(['There is no user with that email.'], 422);
        }

        $input = $request->all();

        UsersFacade::sendPasswordReset($input['email']);

        return response()->json(['New password request sent.'], 201);
    }

    /**
     * @OA\Post(
     *      path="/auth/resetpassword",
     *      summary="Reset the users password.",
     *      description="Reset the users password.",
     *      operationId="authResetPassword",
     *      tags={"Authentication Route"},
     *      @OA\RequestBody(
     *          required=true,
     *          description="Reset the users password",
     *          @OA\JsonContent(
     *              required={"email", "token", "new_password"},
     *              @OA\Property(property="email", type="string", format="email", example="user1@mail.com", description="The user's email."),
     *              @OA\Property(property="token", type="string", description="The token that was sent with the request to reset the password."),
     *              @OA\Property(property="new_password", type="string", description="The new password to set for the user."),

     *          ),
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Succesful Password Reset",
     *           @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Password has been reset.")
     *          )
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="There was an error resetting your password..",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="There was an error resetting your password.")
     *          )
     *      ),
     * ),
     * 
     */
    public function changePassword(Request $request) {

        $input = $request->all();

        if(!isset($input['email']) || isset($input['email']) && !$input['email']) {
            return response()->json(['message' => 'An email is required to reset the password'], 422);
        }

        if(!isset($input['token']) || isset($input['token']) && !$input['token']) {
            return response()->json(['message' => 'A valid token is required reset the password'], 422);
        }

        if(!isset($input['new_password']) || isset($input['new_password']) && !$input['new_password']) {
            return response()->json(['message' => 'A new password is required to update the old password.'], 422);
        }

        $password = PasswordReset::where('email', $input['email']) -> where('token', $input['token']) -> first();

        if(!$password) {
            return response()->json(['No password reset request was found.'], 422);
        }

        UsersFacade::resetPassword($password, $input['new_password']);

        return response()->json(['Password has been reset.'], 201);
    }
}
