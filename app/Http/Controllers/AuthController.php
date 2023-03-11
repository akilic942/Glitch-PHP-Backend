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
     * Add a new pet to the store.
     *
     * @OA\Post(
     *     path="/auth/register",
     *     tags={"Authentication Route"},
     *     operationId="register",
     *     @OA\Response(
     *         response=422,
     *         description="Invalid input"
     *     ),
     *     security={
     *         {"petstore_auth": {"write:pets", "read:pets"}}
     *     },
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
     *              required={"email","password"},
     *              @OA\Property(property="email", type="string", format="email", example="user1@mail.com"),
     *              @OA\Property(property="password", type="string", format="password", example="PassWord12345"),
     *              @OA\Property(property="persistent", type="boolean", example="true"),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Succesfuly Login",
     *          @OA\JsonContent(
      *             ref="#/components/schemas/User"
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
     *     summary="One time login token.",
     *     description="One time token to login",
     *     operationId="oneTimeLoginToken",
     *     tags={"Authentication Route"},
     *     security={ {"bearer": {} }},
     *     @OA\Response(
     *         response=200,
     *         description="Success"
     *     ),
     *     @OA\Response(
     *      response=405,
     *      description="Login with one time login token not given",
     *      )  
     * )
     */
    public function oneTimeLoginToken(Request $request) {

        $input = $request->all();

        if(!isset($input['token'])) {
            return response()->json('No Token Supplied', 422);
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

    /**
     * respondWithToken
     *
     * @OA\Post(
     *     path="/auth/respondWithToken",
     *     summary="Responds with a token.",
     *     description="Responds with a token.",
     *     operationId="respondWithToken",
     *     tags={"Authentication Route"},
     *     security={ {"bearer": {} }},
     *     @OA\Response(
     *         response=200,
     *         description="Success"
     *     ),
     *     @OA\Response(
     *      response=401,
     *      description="Resonds with a temporary token",
     *      @OA\JsonContent(
     *          @OA\Property(property="message", type="string", example="Not authorized"),
     *          )
     *      )  
     * )
     */
    protected function respondWithToken($token)
    {
        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * env('JWT_TTL', 500)
        ];
    }
}
