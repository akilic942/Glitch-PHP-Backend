<?php
namespace App\Facades;

use App\Enums\AcceptanceStatus;
use App\Enums\Roles;
use App\Models\Team;
use App\Models\TeamInvite;
use App\Models\TeamUser;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class TeamInvitesFacade {


    public static function sendInvite(TeamInvite $invite) {

        $team = Team::where('id', $invite->team_id)->first();

        $login_link = env('COHOST_LOGIN_REDIRECT') . '?iscohost=true&token=' . $invite->token . '&team=' . $team->id;;

        $registration_link = env('COHOST_REGISTER_REDIRECT') . '?iscohost=true&token=' . $invite->token . '&team=' . $team->id;

        Mail::send('email.teamInvite', ['invite' => $invite, 'team' => $team, 'login_link' => $login_link, 'registration_link' => $registration_link ], function($message) use($invite, $team){

            $message->to($invite->email);
            $message->subject('Invited To Team ' . $team->name);
            $message->from(env('MAIL_FROM_ADDRESS', 'noreply@glitch.fun'),env('MAIL_FROM_NAME', 'Glitch Gaming'));
        });
    }

    public static function acceptInvite(TeamInvite $invite, User $user) {

        $team = TeamUser::where('team_id', $invite->team_id)
        ->where('user_id', $user->id)
        ->first();

        if(!$team) {

            $team = TeamUser::create([
                'user_role' => $invite->role,
                'team_id' => $invite->team_id,
                'user_id' => $user->id,
                'status' => AcceptanceStatus::APPROVED
            ]);

            if($team) {
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