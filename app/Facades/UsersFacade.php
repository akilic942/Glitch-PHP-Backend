<?php
namespace App\Facades;

use App\Models\PasswordReset;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class UsersFacade {

    public static function retrieveOrCreate(string $email, string $first_name, string $last_name, string $username, string $avatar = ''){

        $user = User::where('email',$email)->first();

        if(!$user) {

            $user = User::create([
                'email' => $email,
                'first_name' => $first_name,
                'last_name' => $last_name,
                'username' => $username,
                'password' => Str::random(30),
                'avatar' => $avatar
            ]);

            if($avatar) {

                $user->forceFill(['avatar' => $avatar]);

                $user->save();
            }
        }

        return $user;
    }

    public static function sendPasswordReset(string $email) {

        $token = Str::random(64);

        $password = PasswordReset::create([
            'email' => $email,
            'token' => $token, 
            'created_at' => Carbon::now()

        ]);

        Mail::send('email.forgetPassword', ['token' => $token], function($message) use($email){

            $message->to($email);
            $message->subject('Reset Password');
            $message->from(env('MAIL_FROM_ADDRESS', 'noreply@glitch.fun'),env('MAIL_FROM_NAME', 'Glitch Gaming'));
        });

        return $password;
    }

    public static function resetPassword(PasswordReset $password, string $new_password) {

        $result = User::where('email', $password->email)
                    ->update(['password' => Hash::make($new_password)]);

        $password->delete();

        return $result;
    }
}