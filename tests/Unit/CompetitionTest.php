<?php

use App\Invirtu\InvirtuClient;
use App\Models\Competition;
use App\Models\Event;
use App\Models\Recording;
use Tests\TestCase;

class CompetitionTest extends TestCase {

    public function testCreation() {

        $faker = \Faker\Factory::create();

        $data = array(
            'name' => $faker->name(),
            'description' => $faker->text(),
        );

        $competition = new Competition();

        $tmp_field = $data['name'];
        unset($data['name']);
        $this->assertFalse($competition->validate($data));
        $data['name'] = $tmp_field;

        $tmp_field = $data['description'];
        unset($data['description']);
        $this->assertFalse($competition->validate($data));
        $data['description'] = $tmp_field;


        $this->assertTrue($competition->validate($data));

        $competition = Competition::create($data);

        $this->assertNotNull($competition->id);

        //Reload with data from the observer
        $competition->refresh();

    }

}