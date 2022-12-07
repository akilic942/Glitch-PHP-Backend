<?php

namespace App\Observers;

use App\Enums\Roles;
use App\Facades\EventsFacade;
use App\Facades\RolesFacade;
use App\Models\CompetitionRoundBracket;
use App\Models\Event;
use App\Models\Team;
use App\Models\User;

class CompetitionRoundBracketObserver
{
    /**
     * Handle the CompetitionRoundBracket "created" event.
     *
     * @param  \App\Models\CompetitionRoundBracket  $competitionRoundBracket
     * @return void
     */
    public function created(CompetitionRoundBracket $competitionRoundBracket)
    {
        
        $competition = $competitionRoundBracket->competition;

        $title_ending = '';

        if($competitionRoundBracket->user_id) {
            $user = User::where('id', $competitionRoundBracket->user_id)->first();
            $title_ending  = $user->username;
        } else if($competitionRoundBracket->team_id) {
            $team = Team::where('team_id', $competitionRoundBracket->team_id)->first();
            $title_ending  = $team->name;
        }

        $title = $competition->name . ' Round ' . $competitionRoundBracket->round . ' Bracket ' . $competitionRoundBracket->bracket . ' ' .$title_ending;

        if(strlen($title)>=255) {
            $title = substr($title,0,254);
        }

        $data = [
            'title' => $title,
            'description' => 'A showcase associated with ' . $competition->name . ' with ' . $title_ending . '.'
        ];

        $event = Event::create($data);

        if($event) {

            $competitionRoundBracket->forceFill([
                'event_id' => $event->id
            ]);

            $competitionRoundBracket->save();

            if($competitionRoundBracket->user_id) {
                $user = User::where('id', $competitionRoundBracket->user_id)->first();
                RolesFacade::eventMakeSuperAdmin($event, $user);
            }

            if($competitionRoundBracket->team_id) {

                $users = User::where('team_id', $competitionRoundBracket->team_id)
                ->where('user_role', Roles::SuperAdministrator)
                ->join('team_users', 'users.id', '=', 'team_users.user_id')
                ->get();

                foreach($users as $user){
                    RolesFacade::eventMakeSuperAdmin($event, $user);
                }
            }

        }
    }

    /**
     * Handle the CompetitionRoundBracket "updated" event.
     *
     * @param  \App\Models\CompetitionRoundBracket  $competitionRoundBracket
     * @return void
     */
    public function updated(CompetitionRoundBracket $competitionRoundBracket)
    {
        //
    }

    /**
     * Handle the CompetitionRoundBracket "deleted" event.
     *
     * @param  \App\Models\CompetitionRoundBracket  $competitionRoundBracket
     * @return void
     */
    public function deleted(CompetitionRoundBracket $competitionRoundBracket)
    {
        //
    }

    /**
     * Handle the CompetitionRoundBracket "restored" event.
     *
     * @param  \App\Models\CompetitionRoundBracket  $competitionRoundBracket
     * @return void
     */
    public function restored(CompetitionRoundBracket $competitionRoundBracket)
    {
        //
    }

    /**
     * Handle the CompetitionRoundBracket "force deleted" event.
     *
     * @param  \App\Models\CompetitionRoundBracket  $competitionRoundBracket
     * @return void
     */
    public function forceDeleted(CompetitionRoundBracket $competitionRoundBracket)
    {
        //
    }
}
