<?php

use App\Models\Competition;
use App\Models\CompetitionInvite;
use Tests\TestCase;
use Illuminate\Support\Str;

class CompetitionInviteTest extends TestCase {

    public function testCreation() {

        $faker = \Faker\Factory::create();

        $competition = Competition::factory()->create();

        $data = array(
            'email' => $faker->unique()->safeEmail(),
            'name' => $faker->name(),
            'token' => Str::random(20),
            'competition_id' => $competition->id
        );

        $invite = new CompetitionInvite();

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

        $tmp_field = $data['competition_id'];
        unset($data['competition_id']);
        $this->assertFalse($invite->validate($data));
        $data['competition_id'] = $tmp_field;

        $this->assertTrue($invite->validate($data));

        $invite = CompetitionInvite::create($data);

        $this->assertNotNull($invite->id);
    }
}