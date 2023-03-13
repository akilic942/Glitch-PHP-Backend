<?php

namespace App\Http\Controllers;

use App\Facades\AuthenticationFacade;
use App\Http\Resources\UserFullResource;
use App\Http\Resources\UserResource;
use App\Invirtu\InvirtuClient;
use App\Models\User;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     *
     * @OA\Post(
     *     path="/auth/register",
     *     tags={"Authentication Route"},
     *     operationId="authRegister",
     *     description="Register a new user",
     *     summary="Register a new user",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent( ref="#/components/schemas/User")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             ref="#/components/schemas/UserFull"
     *          )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation Errors"
     *     )
     *    
     * )
     */

    public function register(Request $request)
    {

        $input = $request->all();

        $user = new User();

        $valid = $user->validate($input);

        if(!$valid){
            return response()->json($user->getValidationErrors(), 422);
        }

        $input['password'] = Hash::make($input['password']);

        $user = User::create($input);

        $token = auth()->login($user);

        $user->token = $this->respondWithToken($token);

        $resource = UserFullResource::make($user);

        $resource['token'] = $this->respondWithToken($token);
        $resource['email'] = $user->email;
        
        return $resource;
    }

    /**
     * @OA\Post(
     *      path="/auth/login",
     *      summary="Sign in",
     *      description="Login by email, password",
     *      operationId="authLogin",
     *      tags={"Authentication Route"},
     *      @OA\RequestBody(
     *          required=true,
     *          description="Pass user credentials",
     *          @OA\JsonContent(
     *              required={"password"},
     *              @OA\Property(property="email", type="string", format="email", example="user1@mail.com", description="The users email. Login can use either the email or username."),
     *              @OA\Property(property="username", type="string", example="johndoe", description="The users username. Login can use either be email or username."),
     *              @OA\Property(property="password", type="string", format="password", example="PassWord12345"),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Succesfull Login",
     *          @OA\JsonContent(
      *             ref="#/components/schemas/UserFull"
      *         )
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Wrong credentials response",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Sorry, wrong email address or password. Please try again")
     *          )
     *      ),
     *       @OA\Response(
     *          response=401,
     *          description="Invalid Login Credentials",
     *      )
     * ),
     * 

     * 

     */
    public function login(Request $request)
    {
        $credentials = $request->only(['email', 'password']);

        if(isset($credentials['email']) && $credentials['email']){
            $credentials['email'] = mb_strtolower($credentials['email']);
        }

        $token = null;

        $query = 'email';

        if (!$token = auth()->attempt($credentials)) {

            $query = 'username';

            $credentials = $request->only(['username', 'password']);

            if(isset($credentials['username']) && $credentials['username']){
                $credentials['username'] = mb_strtolower($credentials['username']);
            }

            if (!$token = auth()->attempt($credentials)) {
                return response()->json(['error' => 'Invalid Login Credentials'], 401);
            }
        }

        $input = $request->all();

        if($query == 'email'){
            $user = User::where('email', mb_strtolower($input['email']))->first();
        } else {
            $user = User::where('username', mb_strtolower($input['username']))->first();
        }

        $user->token = $this->respondWithToken($token);

        $resource = UserFullResource::make($user);

        $resource['token'] = $this->respondWithToken($token);
        $resource['email'] = $user->email;

        $organizer_token = env('INVIRTU_ORGANIZER_TOKEN', '');

        if($organizer_token && $user->invirtu_user_id) {

            $client = new InvirtuClient($organizer_token);

            $client->accounts->setSecurePreference($user->invirtu_user_id, ['key' => 'glitch_auth_token', 'value' => $token]);
        }

        return $resource;
    }

    /**
     * oneTimeLoginToken
     *
     * @OA\Post(
     *     path="/auth/oneTimeLoginToken",
     *     summary="Attempts to login with a one time login token.",
     *     description="Attempts to login with a one time login token.",
     *     operationId="oneTimeLoginToken",
     *     tags={"Authentication Route"},
     *     security={ {"bearer": {} }},
     *     @OA\RequestBody(
     *      required=true,
     *      @OA\MediaType(
     *          mediaType="application/json",
     *          @OA\Schema(
     *              @OA\Property(
     *                  property="token",
     *                  type="string",
     *                  description="The token to log the user in with."
     *              ),
     *          )
     *      )
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="Succesfull Login",
     *          @OA\JsonContent(
      *             ref="#/components/schemas/UserFull"
      *         )
     *      ),
     *      @OA\Response(
     *      response=404,
     *      description="No Token Supplied.",
     *      ),
     *     @OA\Response(
     *      response=422,
     *      description="Unable to authenticate with provided token",
     *      )  
     * )
     */
    public function oneTimeLoginToken(Request $request) {

        $input = $request->all();

        if(!isset($input['token'])) {
            return response()->json('No Token Supplied', 404);
        }

        $user = AuthenticationFacade::useOneTimeLoginToken($input['token']);

        if(!$user){
            return response()->json('Unable to authenticate with provided token', 422);
        }

        $token = auth()->login($user);

        $user->token = $this->respondWithToken($token);

        $resource = UserFullResource::make($user);

        $resource['token'] = $this->respondWithToken($token);
        $resource['email'] = $user->email;

        return $resource;

    }

    protected function respondWithToken($token)
    {
        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * env('JWT_TTL', 500)
        ];
    }
}
