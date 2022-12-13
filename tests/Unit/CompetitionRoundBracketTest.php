<?php

use App\Invirtu\InvirtuClient;
use App\Models\Competition;
use App\Models\CompetitionRound;
use App\Models\CompetitionRoundBracket;
use App\Models\Event;
use App\Models\Recording;
use App\Models\Team;
use App\Models\User;
use Tests\TestCase;

class CompetitionRoundBracketTest extends TestCase {

    public function testCreationWithUser() {

        $faker = \Faker\Factory::create();

        $competition = Competition::factory()->create();

        $user = User::factory()->create();

        $data = array(
            'competition_id' => $competition->id,
            'round' => rand(1,10),
            'user_id' => $user->id,
            'bracket' => rand(1,10)
        );

        $bracket = new CompetitionRoundBracket();

        $tmp_field = $data['competition_id'];
        unset($data['competition_id']);
        $this->assertFalse($bracket->validate($data));
        $data['competition_id'] = $tmp_field;

        $tmp_field = $data['round'];
        unset($data['round']);
        $this->assertFalse($bracket->validate($data));
        $data['round'] = $tmp_field;

        $tmp_field = $data['bracket'];
        unset($data['bracket']);
        $this->assertFalse($bracket->validate($data));
        $data['bracket'] = $tmp_field;

        $tmp_field = $data['user_id'];
        unset($data['user_id']);
        $this->assertFalse($bracket->validate($data));
        $data['user_id'] = $tmp_field;

        $this->assertTrue($bracket->validate($data));

        $bracket = CompetitionRoundBracket::create($data);

        $this->assertEquals($bracket->round, $data['round']);
        $this->assertEquals($bracket->bracket, $data['bracket']);
        $this->assertEquals($bracket->competition_id, $data['competition_id']);

        $bracket->refresh();

        $this->assertNotNull($bracket->event_id);


    }

    public function testCreationWithTeam() {

        $faker = \Faker\Factory::create();

        $competition = Competition::factory()->create();

        $team = Team::factory()->create();

        $data = array(
            'competition_id' => $competition->id,
            'round' => rand(1,10),
            'team_id' => $team->id,
            'bracket' => rand(1,10)
        );

        $bracket = new CompetitionRoundBracket();

        $tmp_field = $data['competition_id'];
        unset($data['competition_id']);
        $this->assertFalse($bracket->validate($data));
        $data['competition_id'] = $tmp_field;

        $tmp_field = $data['round'];
        unset($data['round']);
        $this->assertFalse($bracket->validate($data));
        $data['round'] = $tmp_field;

        $tmp_field = $data['bracket'];
        unset($data['bracket']);
        $this->assertFalse($bracket->validate($data));
        $data['bracket'] = $tmp_field;

        $tmp_field = $data['team_id'];
        unset($data['team_id']);
        $this->assertFalse($bracket->validate($data));
        $data['team_id'] = $tmp_field;

        $this->assertTrue($bracket->validate($data));

        $bracket = CompetitionRoundBracket::create($data);

        $this->assertEquals($bracket->round, $data['round']);
        $this->assertEquals($bracket->bracket, $data['bracket']);
        $this->assertEquals($bracket->competition_id, $data['competition_id']);

        $bracket->refresh();

        $this->assertNotNull($bracket->event_id);


    }

}