<?php

use App\Invirtu\InvirtuClient;
use App\Models\Competition;
use App\Models\CompetitionAddress;
use App\Models\CompetitionTeam;
use App\Models\Event;
use App\Models\Recording;
use App\Models\Team;
use Illuminate\Database\Eloquent\Model;
use Tests\TestCase;

class CompetitionTeamTest extends TestCase {

    public function testCreation() {

        $faker = \Faker\Factory::create();

        $competition = Competition::factory()->create();

        $team = Team::factory()->create();

        $data = array(
            'team_id' => $team->id,
            'competition_id' => $competition->id,
        );

        $ct = new CompetitionTeam();

        $tmp_field = $data['team_id'];
        unset($data['team_id']);
        $this->assertFalse($ct->validate($data));
        $data['team_id'] = $tmp_field;

        $tmp_field = $data['competition_id'];
        unset($data['competition_id']);
        $this->assertFalse($ct->validate($data));
        $data['competition_id'] = $tmp_field;

        $this->assertTrue($ct->validate($data));

        $ct = CompetitionTeam::create($data);

        $this->assertEquals($ct->competition_id, $data['competition_id']);
        $this->assertEquals($ct->team_id, $data['team_id']);



    }

}