<?php

use App\Models\Team;
use App\Models\TeamInvite;
use Tests\TestCase;
use Illuminate\Support\Str;

class TeamInviteTest extends TestCase {

    public function testCreation() {

        $faker = \Faker\Factory::create();

        $team = Team::factory()->create();

        $data = array(
            'email' => $faker->unique()->safeEmail(),
            'name' => $faker->name(),
            'token' => Str::random(20),
            'team_id' => $team->id
        );

        $invite = new TeamInvite();

        $tmp_field = $data['email'];
        unset($data['email']);
        $this->assertFalse($invite->validate($data));
        $data['email'] = $tmp_field;

        $tmp_field = $data['name'];
        unset($data['name']);
        $this->assertFalse($invite->validate($data));
        $data['name'] = $tmp_field;

        $tmp_field = $data['token'];
        unset($data['token']);
        $this->assertFalse($invite->validate($data));
        $data['token'] = $tmp_field;

        $tmp_field = $data['team_id'];
        unset($data['team_id']);
        $this->assertFalse($invite->validate($data));
        $data['team_id'] = $tmp_field;

        $this->assertTrue($invite->validate($data));

        $invite = TeamInvite::create($data);

        $this->assertNotNull($invite->id);
    }
}