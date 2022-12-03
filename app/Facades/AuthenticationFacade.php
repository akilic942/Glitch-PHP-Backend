<?php
namespace App\Facades;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class AuthenticationFacade {


    static const OAUTH_REDIRECT_KEY = 'oauth_redirect_uri';

    public static function createOneTimeLoginToken(User $user) : User {

        $random_string = Str::random(100);

        $user->forceFill([
            'one_time_login_token' => $random_string,
            'one_time_login_token_date' => 'now()'
        ]);

        $user->save();

        return $user;
    }

    /**
     * @todo have a timeout feature that counts the token as expired after a certian period of time.
     */
    public static function useOneTimeLoginToken($token, string $guard = 'api') : User | bool {

        $user = User::where('one_time_login_token', $token)->first();

        if(!$user) {
            return false;
        }

        Auth::guard($guard)->login($user, true);

        $user->forceFill([
            'one_time_login_token' => null,
            'one_time_login_token_date' => null
        ]);

        $user->save();

        return $user;
    }

    public static function setRedirectURI(string $redirect_url, string $guard = 'web') : User | bool {

        return Session::set(self::OAUTH_REDIRECT_KEY, $redirect_url);
    }

    public static function getRedirectURI() {
        return Session::get(self::OAUTH_REDIRECT_KEY);
    }

    public static function clearRedirectURI() {
        return Session::forget(self::OAUTH_REDIRECT_KEY);
    }


}