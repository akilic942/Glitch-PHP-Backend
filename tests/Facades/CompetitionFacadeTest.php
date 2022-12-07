<?php
namespace Tests\Facades;

use App\Facades\CompetitionFacade;
use App\Facades\EventsFacade;
use App\Models\Competition;
use App\Models\Event;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class CompetitionFacadeTest extends TestCase {

    public function testMakeRound() {

        $competition = Competition::factory()->create();

        $this->assertEquals(5, CompetitionFacade::makeRounds($competition, 12, 2));


        $competition = Competition::factory()->create();

        $this->assertEquals(4, CompetitionFacade::makeRounds($competition, 10, 2));

    }

}