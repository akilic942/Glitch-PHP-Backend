<?php

use App\Models\Event;
use App\Models\EventInvite;
use Tests\TestCase;
use Illuminate\Support\Str;

class EventInviteTest extends TestCase {

    public function testCreation() {

        $faker = \Faker\Factory::create();

        $event = Event::factory()->create();

        $data = array(
            'email' => $faker->unique()->safeEmail(),
            'name' => $faker->name(),
            'token' => Str::random(20),
            'event_id' => $event->id
        );

        $invite = new EventInvite();

        $tmp_field = $data['email'];
        unset($data['email']);
        $this->assertFalse($invite->validate($data));
        $data['email'] = $tmp_field;

        $tmp_field = $data['name'];
        unset($data['name']);
        $this->assertFalse($invite->validate($data));
        $data['name'] = $tmp_field;

        $tmp_field = $data['token'];
        unset($data['token']);
        $this->assertFalse($invite->validate($data));
        $data['token'] = $tmp_field;

        $tmp_field = $data['event_id'];
        unset($data['event_id']);
        $this->assertFalse($invite->validate($data));
        $data['event_id'] = $tmp_field;

        $this->assertTrue($invite->validate($data));

        $invite = EventInvite::create($data);

        $this->assertNotNull($invite->id);
    }
}