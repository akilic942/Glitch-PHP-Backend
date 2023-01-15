<?php

use App\Invirtu\InvirtuClient;
use App\Models\Competition;
use App\Models\CompetitionVenue;
use App\Models\CompetitionTeam;
use App\Models\CompetitionUser;
use App\Models\Event;
use App\Models\CompetitionTicketType;
use App\Models\Recording;
use App\Models\Team;
use App\Models\TeamUser;
use App\Models\User;
use Tests\TestCase;

class CompetitionTicketTypesTest extends TestCase {

    public function testCreation() {

        $faker = \Faker\Factory::create();

        $competition = Competition::factory()->create();

        $data = array(
            'competition_id' => $competition->id,
            'name' => $faker->title(),
        );

        $type = new CompetitionTicketType();

        $tmp_field = $data['competition_id'];
        unset($data['competition_id']);
        $this->assertFalse($type->validate($data));
        $data['competition_id'] = $tmp_field;

        $tmp_field = $data['name'];
        unset($data['name']);
        $this->assertFalse($type->validate($data));
        $data['name'] = $tmp_field;

        $type->validate($data);
        
        $this->assertTrue($type->validate($data));

        $type = CompetitionTicketType::create($data);

        $this->assertEquals($type->competition_id, $data['competition_id']);
        $this->assertEquals($type->name, $data['name']);


    }

}