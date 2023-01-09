<?php

use App\Facades\WebhookInvirtuFacade;
use App\Models\Event;
use Tests\TestCase;


class WebhookInvirtuFacadeTest extends TestCase {

    public function testBroadcastEnded() {

        $event = Event::factory()->create();

        $faker = \Faker\Factory::create();

        $data = ['is_live' => 1];

        if(!$event->invirtu_id) {
            $data['invirtu_id'] = $faker->uuid();
        }

        $event->forceFill($data);
        $event->save();

        $this->assertEquals($event->is_live, 1);

        WebhookInvirtuFacade::process('broadcasting_recording_finished', ['id' => $event-> invirtu_id]);

        $event->refresh();

        $this->assertEquals($event->is_live, 0);
    }

}