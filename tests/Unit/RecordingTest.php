<?php

use App\Invirtu\InvirtuClient;
use App\Models\Event;
use App\Models\Recording;
use Tests\TestCase;

class RecordingTest extends TestCase {

    public function testCreation() {

        $faker = \Faker\Factory::create();

        $event = Event::factory()->create();

        $data = array(
            'name' => $faker->name(),
            'description' => $faker->text(),
            'event_id' => $event->id,
        );

        $recording = new Recording();

        $tmp_field = $data['name'];
        unset($data['name']);
        $this->assertFalse($recording->validate($data));
        $data['name'] = $tmp_field;

        $tmp_field = $data['description'];
        unset($data['description']);
        $this->assertFalse($recording->validate($data));
        $data['description'] = $tmp_field;

        $tmp_field = $data['event_id'];
        unset($data['event_id']);
        $this->assertFalse($recording->validate($data));
        $data['event_id'] = $tmp_field;

        $this->assertTrue($recording->validate($data));

        $recording = Recording::create($data);

        $this->assertNotNull($recording->id);

        //Reload with data from the observer
        $recording->refresh();

        //Test to make sure invirtu data is included
        /*$this->assertNotNull($recording->invirtu_id);
        $this->assertNotNull($recording->invirtu_webrtc_url);
        $this->assertNotNull($recording->invirtu_broadcast_url);
        $this->assertNotNull($recording->invirtu_rtmp_broadcast_endpoint);
        $this->assertNotNull($recording->invirtu_rtmp_broadcast_key);*/
    }

}