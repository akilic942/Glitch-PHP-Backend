<?php

use App\Models\Event;
use App\Models\EventOverlay;
use Tests\TestCase;

class EventOverlayTest extends TestCase {

    public function testCreation() {

        $faker = \Faker\Factory::create();

        $event = Event::factory()->create();

        $data = array(
            'label' => $faker->name(),
            'event_id' => $event->id
        );

        $overlay = new EventOverlay();

        $tmp_field = $data['label'];
        unset($data['label']);
        $this->assertFalse($overlay->validate($data));
        $data['label'] = $tmp_field;

        $tmp_field = $data['event_id'];
        unset($data['event_id']);
        $this->assertFalse($overlay->validate($data));
        $data['event_id'] = $tmp_field;

        $this->assertTrue($overlay->validate($data));

        $overlay = EventOverlay::create($data);

        $this->assertNotNull($overlay->id);
        
    }

}