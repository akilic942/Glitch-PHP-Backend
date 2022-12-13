<?php

use App\Invirtu\InvirtuClient;
use App\Models\Competition;
use App\Models\CompetitionRound;
use App\Models\Event;
use App\Models\Recording;
use Tests\TestCase;

class CompetitionRoundTest extends TestCase {

    public function testCreation() {

        $faker = \Faker\Factory::create();

        $competition = Competition::factory()->create();

        $data = array(
            'competition_id' => $competition->id,
            'round' => rand(1,10),
        );

        $round = new CompetitionRound();

        $tmp_field = $data['competition_id'];
        unset($data['competition_id']);
        $this->assertFalse($round->validate($data));
        $data['competition_id'] = $tmp_field;

        $tmp_field = $data['round'];
        unset($data['round']);
        $this->assertFalse($round->validate($data));
        $data['round'] = $tmp_field;


        $this->assertTrue($round->validate($data));

        $round = CompetitionRound::create($data);

        $this->assertEquals($round->round, $data['round']);
        $this->assertEquals($round->competition_id, $data['competition_id']);


    }

}