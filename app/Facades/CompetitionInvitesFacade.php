<?php
namespace App\Facades;

use App\Enums\AcceptanceStatus;
use App\Enums\Roles;
use App\Models\Competition;
use App\Models\CompetitionInvite;
use App\Models\CompetitionUser;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class CompetitionInvitesFacade {


    public static function sendInvite(CompetitionInvite $invite) {

        $competition = Competition::where('id', $invite->competition_id)->first();

        $login_link = env('COHOST_LOGIN_REDIRECT') . '?iscohost=true&token=' . $invite->token . '&competition=' . $competition->id;;

        $registration_link = env('COHOST_REGISTER_REDIRECT') . '?iscohost=true&token=' . $invite->token . '&competition=' . $competition->id;

        Mail::send('email.competitionInvite', ['invite' => $invite, 'competition' => $competition, 'login_link' => $login_link, 'registration_link' => $registration_link ], function($message) use($invite, $competition){

            $message->to($invite->email);
            $message->subject('Invited To Competition ' . $competition->name);
            $message->from(env('MAIL_FROM_ADDRESS', 'noreply@glitch.fun'),env('MAIL_FROM_NAME', 'Glitch Gaming'));
        });
    }

    public static function acceptInvite(CompetitionInvite $invite, User $user) {

        $competition = CompetitionUser::where('competition_id', $invite->competition_id)
        ->where('user_id', $user->id)
        ->first();

        if(!$competition) {
            
            $competition = CompetitionUser::create([
                'user_role' => $invite->role,
                'competition_id' => $invite->competition_id,
                'user_id' => $user->id,
                'status' => AcceptanceStatus::APPROVED
            ]);

            if($competition) {
                $invite->forceFill([
                    'user_id' => $user->id,
                    'accepted_invite' => 1
                ]);

                $invite->save();
            }
        }

        return $invite;

    }
}