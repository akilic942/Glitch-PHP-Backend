<?php
namespace App\Facades;

use App\Models\User;

use Illuminate\Support\Str;

class UsersFacade {

    public static function retrieveOrCreate(string $email, string $first_name, string $last_name, string $username){

        $user = User::where('email',$email)->first();

        if(!$user) {

            $user = User::create([
                'email' => $email,
                'first_name' => $first_name,
                'last_name' => $last_name,
                'username' => $username,
                'password' => Str::random(30)
            ]);
        }

        return $user;
    }
}