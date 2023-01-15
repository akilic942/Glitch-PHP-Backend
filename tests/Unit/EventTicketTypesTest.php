<?php

use App\Invirtu\InvirtuClient;
use App\Models\Competition;
use App\Models\CompetitionVenue;
use App\Models\CompetitionTeam;
use App\Models\CompetitionUser;
use App\Models\Event;
use App\Models\EventTicketType;
use App\Models\Recording;
use App\Models\Team;
use App\Models\TeamUser;
use App\Models\User;
use Tests\TestCase;

class EventTicketTypesTest extends TestCase {

    public function testCreation() {

        $faker = \Faker\Factory::create();

        $event = Event::factory()->create();

        $data = array(
            'event_id' => $event->id,
            'name' => $faker->title(),
        );

        $type = new EventTicketType();

        $tmp_field = $data['event_id'];
        unset($data['event_id']);
        $this->assertFalse($type->validate($data));
        $data['event_id'] = $tmp_field;

        $tmp_field = $data['name'];
        unset($data['name']);
        $this->assertFalse($type->validate($data));
        $data['name'] = $tmp_field;

        $this->assertTrue($type->validate($data));

        $type = EventTicketType::create($data);

        $this->assertEquals($type->event_id, $data['event_id']);
        $this->assertEquals($type->name, $data['name']);


    }

}