<?php
namespace Tests\Facades;

use App\Facades\EventsFacade;
use App\Models\Event;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class EventsFacadeTest extends TestCase {

    public function testCheckIfLive() {

        $number_of_live_events_old = rand(2, 10);

        $number_of_live_events_new = rand(2, 10);

        Event::factory()->count($number_of_live_events_old)->create(['live_last_checkin' => Carbon::now()->addMinutes(10)->toDateTimeString()]);

        Event::factory()->count($number_of_live_events_new)->create(['live_last_checkin' => Carbon::now()->addMinutes(1)->toDateTimeString()]);

        $total_events = DB::table('events')->get();

        $young_events = Event::where('live_last_checkin', '<=', Carbon::now()->addMinutes(5)->toDateTimeString())->get();

        $old_events = Event::where('live_last_checkin', '>=', Carbon::now()->addMinutes(5)->toDateTimeString())->get();

        $this->assertCount($number_of_live_events_old + $number_of_live_events_new, $total_events);
        $this->assertCount($number_of_live_events_new, $young_events );
        $this->assertCount($number_of_live_events_old, $old_events);

        EventsFacade::checkIfLive();

        $total_events = DB::table('events')->get();

        $young_events = Event::where('live_last_checkin', '<=', Carbon::now()->addMinutes(5)->toDateTimeString())->get();

        $old_events = Event::where('live_last_checkin', '>=', Carbon::now()->addMinutes(5)->toDateTimeString())->get();

        $this->assertCount($number_of_live_events_new, $young_events );
        $this->assertCount(0, $old_events);


    }

    public function testCreateDefaultOverlays() {

        $event = Event::factory()->create();

        $overlays = EventsFacade::createDefaultOverlays($event);

        $this->assertCount(5, $overlays);
    }

}