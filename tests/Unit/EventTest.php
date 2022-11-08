<?php

use App\Invirtu\InvirtuClient;
use App\Models\Event;
use Tests\TestCase;

class EventTest extends TestCase {

    public function testCreation() {

        $faker = \Faker\Factory::create();

        $data = array(
            'title' => $faker->name(),
            'description' => $faker->text(),
        );

        $event = new Event();

        $tmp_field = $data['title'];
        unset($data['title']);
        $this->assertFalse($event->validate($data));
        $data['title'] = $tmp_field;

        $tmp_field = $data['description'];
        unset($data['description']);
        $this->assertFalse($event->validate($data));
        $data['description'] = $tmp_field;

        $this->assertTrue($event->validate($data));

        $event = Event::create($data);

        $this->assertNotNull($event->id);

        //Reload with data from the observer
        $event->refresh();

        //Test to make sure invirtu data is included
        /*$this->assertNotNull($event->invirtu_id);
        $this->assertNotNull($event->invirtu_webrtc_url);
        $this->assertNotNull($event->invirtu_broadcast_url);
        $this->assertNotNull($event->invirtu_rtmp_broadcast_endpoint);
        $this->assertNotNull($event->invirtu_rtmp_broadcast_key);*/
    }

}