<?php

namespace Tests\Routes;

use App\Facades\CompetitionInvitesFacade;
use App\Facades\RolesFacade;
use App\Models\Competition;
use App\Models\CompetitionVenue;
use App\Models\CompetitionRound;
use App\Models\CompetitionRoundBracket;
use App\Models\Team;
use App\Models\User;
use Database\Factories\CompetitionInviteFactory;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class CompetitionRoundBracketControllerTest extends TestCase
{


    public function testList(){

        $competition = Competition::factory()->create();

        $round = CompetitionRound::factory()->create(['competition_id' => $competition->id]);

        $brackets = CompetitionRoundBracket::factory(30)->create(['competition_id' => $competition->id, 'round'=> $round->round]);

        $user = User::factory()->create();

        $url = $this->_getApiRoute() . 'competitions/' . $competition->id . '/rounds/' . $round->round . '/brackets';

        $response = $this->withHeaders([
            'Authorization Bearer' => $this->getAccessToken($user),
        ])->get($url);


        $this->assertEquals(200, $response->status());

        $json = $response->json();

        $bracketsData = $json['data'];

        $this->assertCount(25, $bracketsData);

    }

    public function testCreation(){

        $competition = Competition::factory()->create();

        $user = User::factory()->create();

        RolesFacade::competitionMakeSuperAdmin($competition, $user);

        $round = CompetitionRound::factory()->create(['competition_id' => $competition->id]);

        $url = $this->_getApiRoute() . 'competitions/' . $competition->id . '/rounds/' . $round->round . '/brackets';

        $faker = \Faker\Factory::create();

        $data = [
            'bracket' => rand(1,10),
        ];
        
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getAccessToken($user),
        ])->post($url, $data);

        $this->assertEquals(201, $response->status());

        $json = $response->json();

        $response = $json['data'];

        $this->assertEquals($response['competition_id'], $competition->id);
        $this->assertEquals($response['round'], $round->round);
        $this->assertEquals($response['bracket'], $data['bracket']);
        //$this->assertEquals($competition['user']['id'], $user->id);

    }

    public function testView() {

        $competition = Competition::factory()->create();

        $round = CompetitionRound::factory()->create(['competition_id' => $competition->id]);

        $bracket = CompetitionRoundBracket::factory()->create(['competition_id' => $competition->id, 'round' => $round->round]);

        $url = $this->_getApiRoute() .  'competitions/' . $competition->id . '/rounds/' . $round->round .'/brackets/' . $bracket->bracket;

        $response = $this->withHeaders([
            'Authorization Bearer' => $this->getAccessToken(),
        ])->get($url);

        $this->assertEquals(200, $response->status());

        $json = $response->json();

        $data = $json['data'];

        $this->assertEquals($bracket->bracket, $data['bracket']);
        $this->assertEquals($round->round, $data['round']);
        $this->assertEquals($competition->id, $data['competition_id']);

    }

    public function testUpdate() {

        $user = User::factory()->create();

        $competition = Competition::factory()->create();

        RolesFacade::competitionMakeAdmin($competition, $user);

        $round = CompetitionRound::factory()->create(['competition_id' => $competition->id]);

        $bracket = CompetitionRoundBracket::factory()->create(['competition_id' => $competition->id, 'round' => $round->round]);

        $url = $this->_getApiRoute() .  'competitions/' . $competition->id . '/rounds/' . $round->round .'/brackets/' . $bracket->bracket;

        $faker = \Faker\Factory::create();

        $tmp_user = User::factory()->create();

        $tmp_team = Team::factory()->create();

        $tmp_address = CompetitionVenue::factory()->create(['competition_id' => $competition->id]);

        $data = [
            'user_id' => $tmp_user->id,
            'team_id' => $tmp_team->id,
            'venue_id' => $tmp_address->id,
            'bracket_start_date' => \Carbon\Carbon::createFromTimeStamp($faker->dateTimeBetween('now', '+7 days')->getTimestamp())->toString(),
            'bracket_end_date' => \Carbon\Carbon::createFromTimeStamp($faker->dateTimeBetween('now', '+7 days')->getTimestamp())->toString(),
            'points_awarded' => rand(0,120),
            'cash_awarded' => rand(0,120),
        ];

        $response = $this->withHeaders([
            'Authorization Bearer' => $this->getAccessToken($user),
        ])->put($url, $data);

        $this->assertEquals(200, $response->status());

        $json = $response->json();

        $jsonData = $json['data'];

        $this->assertEquals($competition->id, $jsonData['competition_id']);
        $this->assertEquals($round->round, $jsonData['round']);
        $this->assertEquals($bracket->bracket, $jsonData['bracket']);
        $this->assertEquals($jsonData['user_id'], $data['user_id']);
        $this->assertEquals($jsonData['team_id'], $data['team_id']);
        $this->assertEquals($jsonData['bracket_start_date'], $data['bracket_start_date']);
        $this->assertEquals($jsonData['bracket_end_date'], $data['bracket_end_date']);
        $this->assertEquals($jsonData['points_awarded'], $data['points_awarded']);
        $this->assertEquals($jsonData['cash_awarded'], $data['cash_awarded']);

    }

    public function testDelete() {

        $user = User::factory()->create();

        $competition = Competition::factory()->create();

        RolesFacade::competitionMakeAdmin($competition, $user);

        $round = CompetitionRound::factory()->create(['competition_id' => $competition->id]);

        $bracket = CompetitionRoundBracket::factory()->create(['competition_id' => $competition->id, 'round' => $round->round]);

        $url = $this->_getApiRoute() .  'competitions/' . $competition->id . '/rounds/' . $round->round .'/brackets/' . $bracket->bracket;

        $response = $this->withHeaders([
            'Authorization Bearer' => $this->getAccessToken($user),
        ])->delete($url);

        $this->assertEquals(204, $response->status());

    }


}