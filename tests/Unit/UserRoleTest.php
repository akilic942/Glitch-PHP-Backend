<?php

use App\Invirtu\InvirtuClient;
use App\Models\Competition;
use App\Models\CompetitionVenue;
use App\Models\CompetitionTeam;
use App\Models\CompetitionUser;
use App\Models\Event;
use App\Models\Recording;
use App\Models\Team;
use App\Models\UserRole;
use App\Models\User;
use Tests\TestCase;

class UserRoleTest extends TestCase {

    public function testCreation() {

        $faker = \Faker\Factory::create();

        $user = User::factory()->create();

        $data = array(
            'user_id' => $user->id,
            'user_role' => rand(0,8),
        );

        $ur = new UserRole();

        $tmp_field = $data['user_id'];
        unset($data['user_id']);
        $this->assertFalse($ur->validate($data));
        $data['user_id'] = $tmp_field;

        $tmp_field = $data['user_role'];
        unset($data['user_role']);
        $this->assertFalse($ur->validate($data));
        $data['user_role'] = $tmp_field;

        $this->assertTrue($ur->validate($data));

        $ur = UserRole::create($data);

        $this->assertEquals($ur->user_id, $data['user_id']);
        $this->assertEquals($ur->user_role, $data['user_role']);


    }

}