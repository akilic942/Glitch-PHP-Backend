<?php

use App\Invirtu\InvirtuClient;
use App\Models\Competition;
use App\Models\Event;
use App\Models\Recording;
use App\Models\Team;
use Tests\TestCase;

class TeamTest extends TestCase {

    public function testCreation() {

        $faker = \Faker\Factory::create();

        $data = array(
            'name' => $faker->name(),
            'description' => $faker->text(),
            'join_process' => rand(1,3)
        );

        $team = new Team();

        $tmp_field = $data['name'];
        unset($data['name']);
        $this->assertFalse($team->validate($data));
        $data['name'] = $tmp_field;

        $tmp_field = $data['description'];
        unset($data['description']);
        $this->assertFalse($team->validate($data));
        $data['description'] = $tmp_field;

        $tmp_field = $data['join_process'];
        unset($data['join_process']);
        $this->assertFalse($team->validate($data));
        $data['join_process'] = $tmp_field;


        $this->assertTrue($team->validate($data));

        $team = Team::create($data);

        $this->assertNotNull($team->id);

        //Reload with data from the observer
        $team->refresh();

    }

}