<?php

namespace App\Facades;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class AuthenticationFacade
{


    const OAUTH_REDIRECT_KEY = 'oauth_redirect_uri';

    public static function createOneTimeLoginToken(User $user): User
    {

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
    public static function useOneTimeLoginToken($token, string $guard = 'api'): User | bool
    {

        $user = User::where('one_time_login_token', $token)->first();

        if (!$user) {
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

    public static function appendOneTimeLoginTokenToRedirect(string $redirect_url, string $token)
    {

        $url_parts = parse_url($redirect_url);

        // If URL doesn't have a query string.
        if (isset($url_parts['query'])) { // Avoid 'Undefined index: query'
            parse_str($url_parts['query'], $params);
        } else {
            $params = array();
        }

        $params['loginToken'] = $token;     // Overwrite if exists

        // Note that this will url_encode all values
        $url_parts['query'] = http_build_query($params);

        // If you have pecl_http
        if(function_exists('http_build_url')) {
            return http_build_url($url_parts);
        } else {
            // If not
            return $url_parts['scheme'] . '://' . $url_parts['host'] . $url_parts['path'] . '?' . $url_parts['query'];
        }
    }

    public static function setRedirectURI(string $redirect_url, string $guard = 'web')
    {

        return Session::put(self::OAUTH_REDIRECT_KEY, $redirect_url);
    }

    public static function getRedirectURI()
    {
        return Session::get(self::OAUTH_REDIRECT_KEY);
    }

    public static function clearRedirectURI()
    {
        return Session::forget(self::OAUTH_REDIRECT_KEY);
    }
}
