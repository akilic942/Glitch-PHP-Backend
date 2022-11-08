<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
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

        $resource = UserResource::make($user);

        $resource['token'] = $this->respondWithToken($token);
        
        return $resource;
    }

    public function login(Request $request)
    {
        $credentials = $request->only(['email', 'password']);

        if (!$token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Invalid Login Credentials'], 401);
        }

        $input = $request->all();

        $user = User::where('email', $input['email'])->first();

        $user->token = $this->respondWithToken($token);

        $resource = UserResource::make($user);

        $resource['token'] = $this->respondWithToken($token);

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
