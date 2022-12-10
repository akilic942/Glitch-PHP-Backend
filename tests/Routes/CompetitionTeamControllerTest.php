<?php

namespace Tests\Routes;

use App\Facades\CompetitionInvitesFacade;
use App\Facades\RolesFacade;
use App\Models\Competition;
use App\Models\CompetitionTeam;
use App\Models\Team;
use App\Models\User;
use Database\Factories\CompetitionInviteFactory;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class CompetitionTeamControllerTest extends TestCase
{


    public function testList(){

        $competition = Competition::factory()->create();

        $teams = CompetitionTeam::factory(30)->create(['competition_id' => $competition->id]);

        $user = User::factory()->create();

        $url = $this->_getApiRoute() . 'competitions/' . $competition->id . '/teams';

        $response = $this->withHeaders([
            'Authorization Bearer' => $this->getAccessToken($user),
        ])->get($url);

        $this->assertEquals(200, $response->status());

        $json = $response->json();

        $teamsData = $json['data'];

        $this->assertCount(25, $teamsData);

    }

    public function testCreation(){

        $competition = Competition::factory()->create();

        $user = User::factory()->create();

        RolesFacade::competitionMakeAdmin($competition, $user);

        $url = $this->_getApiRoute() . 'competitions/' . $competition->id . '/teams';

        /*$response = $this->withHeaders([
            'Authorization Bearer' => $this->getAccessToken($user),
        ])->post($url);

        $this->assertEquals(422, $response->status());*/

        $faker = \Faker\Factory::create();

        $team = Team::factory()->create();

        $data = [
            'team_id' => $team->id,
        ];
        
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getAccessToken($user),
        ])->post($url, $data);

        $this->assertEquals(201, $response->status());

        $json = $response->json();

        $team = $json['data'];

        $this->assertEquals($team['competition_id'], $competition->id);
        $this->assertEquals($team['team_id'], $data['team_id']);
        //$this->assertEquals($competition['user']['id'], $user->id);

    }

    public function testView() {

        $competition = Competition::factory()->create();

        $team = CompetitionTeam::factory()->create(['competition_id' => $competition->id]);

        $url = $this->_getApiRoute() .  'competitions/' . $competition->id . '/teams/' . $team->team_id;

        $response = $this->withHeaders([
            'Authorization Bearer' => $this->getAccessToken(),
        ])->get($url);

        $this->assertEquals(200, $response->status());

        $json = $response->json();

        $data = $json['data'];

        $this->assertEquals($team->team_id, $data['team_id']);
        $this->assertEquals($competition->id, $data['competition_id']);

    }

    public function testUpdate() {

        $user = User::factory()->create();

        $competition = Competition::factory()->create();

        RolesFacade::competitionMakeAdmin($competition, $user);

        $team = CompetitionTeam::factory()->create(['competition_id' => $competition->id]);

        $url = $this->_getApiRoute() .  'competitions/' . $competition->id . '/teams/' . $team->team_id;

        $faker = \Faker\Factory::create();

        $data = [
            'user_role' => rand(1,9),
            'status' => rand(1,4),
            'entry_fee_paid' => rand(0,1),
            'checked_in' => rand(0,1),
            'checked_in_time' => \Carbon\Carbon::createFromTimeStamp($faker->dateTimeBetween('now', '+7 days')->getTimestamp())->toString(),
        ];

        $response = $this->withHeaders([
            'Authorization Bearer' => $this->getAccessToken($user),
        ])->put($url, $data);

        //print_r($response->json());
        $this->assertEquals(200, $response->status());

        $json = $response->json();

        $jsonData = $json['data'];

        $this->assertEquals($competition->id, $jsonData['competition_id']);
        $this->assertEquals($team->team_id, $jsonData['team_id']);
        $this->assertEquals($jsonData['status'], $data['status']);
        $this->assertEquals($jsonData['entry_fee_paid'], $data['entry_fee_paid']);
        $this->assertEquals($jsonData['checked_in'], $data['checked_in']);
        $this->assertEquals($jsonData['checked_in_time'], $data['checked_in_time']);

    }

    public function testDelete() {

        $user = User::factory()->create();

        $competition = Competition::factory()->create();

        RolesFacade::competitionMakeAdmin($competition, $user);

        $team = CompetitionTeam::factory()->create(['competition_id' => $competition->id]);

        $url = $this->_getApiRoute() .  'competitions/' . $competition->id . '/teams/' . $team->team_id;

        $response = $this->withHeaders([
            'Authorization Bearer' => $this->getAccessToken($user),
        ])->delete($url);

        $this->assertEquals(204, $response->status());

    }


}