<?php

use App\Invirtu\InvirtuClient;
use App\Models\Competition;
use App\Models\Event;
use App\Models\Recording;
use App\Models\Team;
use App\Models\Webhook;
use Tests\TestCase;

class WebhookTest extends TestCase {

    public function testCreation() {

        $faker = \Faker\Factory::create();

        $data = array(
            'incoming_outgoing' => rand(0,2)
        );

        $webhook = new Webhook();

        $tmp_field = $data['incoming_outgoing'];
        unset($data['incoming_outgoing']);
        $this->assertFalse($webhook->validate($data));
        $data['incoming_outgoing'] = $tmp_field;

        $this->assertTrue($webhook->validate($data));

        $webhook = Webhook::create($data);

        $this->assertNotNull($webhook->id);

        //Reload with data from the observer
        $webhook->refresh();

    }

}