<?php

namespace App\Providers;

use App\Models\Competition;
use App\Models\CompetitionRound;
use App\Models\CompetitionRoundBracket;
use App\Models\Event;
use App\Models\User;
use App\Observers\CompetitionObserver;
use App\Observers\CompetitionRoundBracketObserver;
use App\Observers\CompetitionRoundObserver;
use App\Observers\EventObserver;
use App\Observers\UserObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //The observers to listen too
        User::observe(UserObserver::class);
        Event::observe(EventObserver::class);
        CompetitionRoundBracket::observe(CompetitionRoundBracketObserver::class);
        CompetitionRound::observe(CompetitionRoundObserver::class);
        Competition::observe(CompetitionObserver::class);
    }
}
