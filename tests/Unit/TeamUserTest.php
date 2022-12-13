<?php

use App\Invirtu\InvirtuClient;
use App\Models\Competition;
use App\Models\CompetitionVenue;
use App\Models\CompetitionTeam;
use App\Models\CompetitionUser;
use App\Models\Event;
use App\Models\Recording;
use App\Models\Team;
use App\Models\TeamUser;
use App\Models\User;
use Tests\TestCase;

class TeamUserTest extends TestCase {

    public function testCreation() {

        $faker = \Faker\Factory::create();

        $team = Team::factory()->create();

        $user = User::factory()->create();

        $data = array(
            'user_id' => $user->id,
            'team_id' => $team->id,
        );

        $tu = new TeamUser();

        $tmp_field = $data['user_id'];
        unset($data['user_id']);
        $this->assertFalse($tu->validate($data));
        $data['user_id'] = $tmp_field;

        $tmp_field = $data['team_id'];
        unset($data['team_id']);
        $this->assertFalse($tu->validate($data));
        $data['team_id'] = $tmp_field;

        $this->assertTrue($tu->validate($data));

        $tu = TeamUser::create($data);

        $this->assertEquals($tu->user_id, $data['user_id']);
        $this->assertEquals($tu->team_id, $data['team_id']);


    }

}