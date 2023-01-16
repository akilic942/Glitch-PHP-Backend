<?php

namespace App\Observers;

use App\Models\CompetitionTicketPurchase;

use Illuminate\Support\Str;

class CompetitionTicketPurchaseObserver
{
    /**
     * Handle the CompetitionTicketPurchase "created" event.
     *
     * @param  \App\Models\CompetitionTicketPurchase  $competitionTicketPurchase
     * @return void
     */
    public function created(CompetitionTicketPurchase $competitionTicketPurchase)
    {
        $competitionTicketPurchase->forceFill([
            'access_token' => Str::random(120),
            'admin_token' => Str::random(120),
        ]);

        $competitionTicketPurchase->save();
    }

    /**
     * Handle the CompetitionTicketPurchase "updated" event.
     *
     * @param  \App\Models\CompetitionTicketPurchase  $competitionTicketPurchase
     * @return void
     */
    public function updated(CompetitionTicketPurchase $competitionTicketPurchase)
    {
        //
    }

    /**
     * Handle the CompetitionTicketPurchase "deleted" event.
     *
     * @param  \App\Models\CompetitionTicketPurchase  $competitionTicketPurchase
     * @return void
     */
    public function deleted(CompetitionTicketPurchase $competitionTicketPurchase)
    {
        //
    }

    /**
     * Handle the CompetitionTicketPurchase "restored" event.
     *
     * @param  \App\Models\CompetitionTicketPurchase  $competitionTicketPurchase
     * @return void
     */
    public function restored(CompetitionTicketPurchase $competitionTicketPurchase)
    {
        //
    }

    /**
     * Handle the CompetitionTicketPurchase "force deleted" event.
     *
     * @param  \App\Models\CompetitionTicketPurchase  $competitionTicketPurchase
     * @return void
     */
    public function forceDeleted(CompetitionTicketPurchase $competitionTicketPurchase)
    {
        //
    }
}
