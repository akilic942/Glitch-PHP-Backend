<?php

namespace App\Observers;

use App\Facades\CompetitionFacade;
use App\Models\Competition;
use App\Models\Event;

class CompetitionObserver
{
    /**
     * Handle the Competition "created" event.
     *
     * @param  \App\Models\Competition  $competition
     * @return void
     */
    public function created(Competition $competition)
    {
        //
    }

    /**
     * Handle the Competition "updated" event.
     *
     * @param  \App\Models\Competition  $competition
     * @return void
     */
    public function updated(Competition $competition)
    {
        //When the competition is updated, we also want to update any events
        $rounds = $competition->rounds;

        foreach ($rounds as $round) {

            $brackets = $round->brackets;

            foreach ($brackets as $bracket) {

                if ($bracket->event_id) {
                    $competition = Competition::where('id', '=', $bracket->competition_id)->first();

                    $event = Event::where('id', '=', $bracket->event_id)->first();;

                    if ($competition && $event) {
                        CompetitionFacade::setEventDate($competition, $event);
                    }
                }
            }
        }
    }

    /**
     * Handle the Competition "deleted" event.
     *
     * @param  \App\Models\Competition  $competition
     * @return void
     */
    public function deleted(Competition $competition)
    {
        //
    }

    /**
     * Handle the Competition "restored" event.
     *
     * @param  \App\Models\Competition  $competition
     * @return void
     */
    public function restored(Competition $competition)
    {
        //
    }

    /**
     * Handle the Competition "force deleted" event.
     *
     * @param  \App\Models\Competition  $competition
     * @return void
     */
    public function forceDeleted(Competition $competition)
    {
        //
    }
}
