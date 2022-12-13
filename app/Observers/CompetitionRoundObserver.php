<?php

namespace App\Observers;

use App\Facades\CompetitionFacade;
use App\Models\Competition;
use App\Models\CompetitionRound;
use App\Models\Event;

class CompetitionRoundObserver
{
    /**
     * Handle the CompetitionRound "created" event.
     *
     * @param  \App\Models\CompetitionRound  $competitionRound
     * @return void
     */
    public function created(CompetitionRound $competitionRound)
    {

    }

    /**
     * Handle the CompetitionRound "updated" event.
     *
     * @param  \App\Models\CompetitionRound  $competitionRound
     * @return void
     */
    public function updated(CompetitionRound $competitionRound)
    {
        $brackets = $competitionRound->brackets;

        foreach($brackets as $bracket) {
            
            if($bracket->event_id) {
                $competition = Competition::where('id', '=', $bracket->competition_id)->first();
    
                $event = Event::where('id', '=', $bracket->event_id)->first();;
    
                if($competition && $event) {
                    CompetitionFacade::setEventDate($competition, $event);
                }
            }
        }
    }

    /**
     * Handle the CompetitionRound "deleted" event.
     *
     * @param  \App\Models\CompetitionRound  $competitionRound
     * @return void
     */
    public function deleted(CompetitionRound $competitionRound)
    {
        //
    }

    /**
     * Handle the CompetitionRound "restored" event.
     *
     * @param  \App\Models\CompetitionRound  $competitionRound
     * @return void
     */
    public function restored(CompetitionRound $competitionRound)
    {
        //
    }

    /**
     * Handle the CompetitionRound "force deleted" event.
     *
     * @param  \App\Models\CompetitionRound  $competitionRound
     * @return void
     */
    public function forceDeleted(CompetitionRound $competitionRound)
    {
        //
    }
}
