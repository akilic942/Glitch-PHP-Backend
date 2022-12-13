<?php

use App\Invirtu\InvirtuClient;
use App\Models\Competition;
use App\Models\CompetitionVenue;
use App\Models\Event;
use App\Models\Recording;
use Tests\TestCase;

class CompetitionVenueTest extends TestCase {

    public function testCreation() {

        $faker = \Faker\Factory::create();

        $competition = Competition::factory()->create();

        $data = array(
            'venue_name' => $faker->name(),
            'competition_id' => $competition->id,
            'is_virtual_hybrid_remote' => rand(1,3),
        );

        $address = new CompetitionVenue();

        $tmp_field = $data['venue_name'];
        unset($data['venue_name']);
        $this->assertFalse($address->validate($data));
        $data['venue_name'] = $tmp_field;

        $tmp_field = $data['competition_id'];
        unset($data['competition_id']);
        $this->assertFalse($address->validate($data));
        $data['competition_id'] = $tmp_field;

        $tmp_field = $data['is_virtual_hybrid_remote'];
        unset($data['is_virtual_hybrid_remote']);
        $this->assertFalse($address->validate($data));
        $data['is_virtual_hybrid_remote'] = $tmp_field;


        $this->assertTrue($address->validate($data));

        $address = Competition::create($data);

        $this->assertNotNull($address->id);

        //Reload with data from the observer
        $address->refresh();

    }

}