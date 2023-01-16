<?php

namespace App\Observers;

use App\Models\EventTicketPurchase;

use Illuminate\Support\Str;

class EventTicketPurchaseObserver
{
    /**
     * Handle the EventTicketPurchase "created" event.
     *
     * @param  \App\Models\EventTicketPurchase  $eventTicketPurchase
     * @return void
     */
    public function created(EventTicketPurchase $eventTicketPurchase)
    {
        $eventTicketPurchase->forceFill([
            'access_token' => Str::random(120),
            'admin_token' => Str::random(120),
        ]);

        $eventTicketPurchase->save();
    }

    /**
     * Handle the EventTicketPurchase "updated" event.
     *
     * @param  \App\Models\EventTicketPurchase  $eventTicketPurchase
     * @return void
     */
    public function updated(EventTicketPurchase $eventTicketPurchase)
    {
        //
    }

    /**
     * Handle the EventTicketPurchase "deleted" event.
     *
     * @param  \App\Models\EventTicketPurchase  $eventTicketPurchase
     * @return void
     */
    public function deleted(EventTicketPurchase $eventTicketPurchase)
    {
        //
    }

    /**
     * Handle the EventTicketPurchase "restored" event.
     *
     * @param  \App\Models\EventTicketPurchase  $eventTicketPurchase
     * @return void
     */
    public function restored(EventTicketPurchase $eventTicketPurchase)
    {
        //
    }

    /**
     * Handle the EventTicketPurchase "force deleted" event.
     *
     * @param  \App\Models\EventTicketPurchase  $eventTicketPurchase
     * @return void
     */
    public function forceDeleted(EventTicketPurchase $eventTicketPurchase)
    {
        //
    }
}
