<?php

use App\Invirtu\InvirtuClient;
use App\Models\Competition;
use App\Models\Event;
use App\Models\Recording;
use App\Models\Team;
use App\Models\Waitlist;
use Tests\TestCase;

class WaitlistTest extends TestCase {

    public function testCreation() {

        $faker = \Faker\Factory::create();

        $data = array(
            'email' => $faker->email,
        );

        $webhook = new Waitlist();

        $tmp_field = $data['email'];
        unset($data['email']);
        $this->assertFalse($webhook->validate($data));
        $data['email'] = $tmp_field;

        $this->assertTrue($webhook->validate($data));

        $webhook = Waitlist::create($data);

        $this->assertNotNull($webhook->id);

        //Reload with data from the observer
        $webhook->refresh();

    }

}