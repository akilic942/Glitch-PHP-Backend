<?php

use App\Invirtu\InvirtuClient;
use App\Models\Competition;
use App\Models\CompetitionAddress;
use App\Models\CompetitionTeam;
use App\Models\CompetitionUser;
use App\Models\Event;
use App\Models\Recording;
use App\Models\Team;
use App\Models\User;
use Tests\TestCase;

class CompetitionUserTest extends TestCase {

    public function testCreation() {

        $faker = \Faker\Factory::create();

        $competition = Competition::factory()->create();

        $user = User::factory()->create();

        $data = array(
            'user_id' => $user->id,
            'competition_id' => $competition->id,
        );

        $cu = new CompetitionUser();

        $tmp_field = $data['user_id'];
        unset($data['user_id']);
        $this->assertFalse($cu->validate($data));
        $data['user_id'] = $tmp_field;

        $tmp_field = $data['competition_id'];
        unset($data['competition_id']);
        $this->assertFalse($cu->validate($data));
        $data['competition_id'] = $tmp_field;

        $this->assertTrue($cu->validate($data));

        $cu = CompetitionUser::create($data);

        $this->assertEquals($cu->competition_id, $data['competition_id']);
        $this->assertEquals($cu->user_id, $data['user_id']);

    }

}