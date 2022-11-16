<?php

namespace App\Console\Commands;

use App\Facades\EventsFacade;
use Illuminate\Console\Command;

class ExpireLiveEvents extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'glitch:expire_events';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Will expire events that are no longer live.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        EventsFacade::checkIfLive();

        return Command::SUCCESS;
    }
}
