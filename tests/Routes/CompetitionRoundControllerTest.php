<?php

namespace Tests\Routes;

use App\Facades\CompetitionInvitesFacade;
use App\Facades\RolesFacade;
use App\Models\Competition;
use App\Models\CompetitionRound;
use App\Models\User;
use Database\Factories\CompetitionInviteFactory;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class CompetitionRoundControllerTest extends TestCase
{


    public function testList(){

        $competition = Competition::factory()->create();

        $rounds = CompetitionRound::factory(30)->create(['competition_id' => $competition->id]);

        $user = User::factory()->create();

        $url = $this->_getApiRoute() . 'competitions/' . $competition->id . '/rounds';

        $response = $this->withHeaders([
            'Authorization Bearer' => $this->getAccessToken($user),
        ])->get($url);

        $this->assertEquals(200, $response->status());

        $json = $response->json();

        $roundsData = $json['data'];

        $this->assertCount(25, $roundsData);

    }

    public function testCreation(){

        $competition = Competition::factory()->create();

        $user = User::factory()->create();

        RolesFacade::competitionMakeAdmin($competition, $user);

        $url = $this->_getApiRoute() . 'competitions/' . $competition->id . '/rounds';

        /*$response = $this->withHeaders([
            'Authorization Bearer' => $this->getAccessToken($user),
        ])->post($url);

        $this->assertEquals(422, $response->status());*/

        $faker = \Faker\Factory::create();

        $data = [
            'round' => rand(1,4),
        ];
        
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getAccessToken($user),
        ])->post($url, $data);

        $this->assertEquals(201, $response->status());

        $json = $response->json();

        $round = $json['data'];

        $this->assertEquals($round['competition_id'], $competition->id);
        $this->assertEquals($round['round'], $data['round']);
        //$this->assertEquals($competition['user']['id'], $user->id);

    }

    public function testView() {

        $competition = Competition::factory()->create();

        $round = CompetitionRound::factory()->create(['competition_id' => $competition->id]);

        $url = $this->_getApiRoute() .  'competitions/' . $competition->id . '/rounds/' . $round->round;

        $response = $this->withHeaders([
            'Authorization Bearer' => $this->getAccessToken(),
        ])->get($url);

        $this->assertEquals(200, $response->status());

        $json = $response->json();

        $data = $json['data'];

        $this->assertEquals($round->round, $data['round']);
        $this->assertEquals($competition->id, $data['competition_id']);

    }

    public function testUpdate() {

        $user = User::factory()->create();

        $competition = Competition::factory()->create();

        RolesFacade::competitionMakeAdmin($competition, $user);

        $round = CompetitionRound::factory()->create(['competition_id' => $competition->id]);

        $url = $this->_getApiRoute() .  'competitions/' . $competition->id . '/rounds/' . $round->round;

        $faker = \Faker\Factory::create();

        $data = [
            'title' => $faker->title(),
            'overview' => $faker->paragraphs(8, true),
            'round_start_date' => \Carbon\Carbon::createFromTimeStamp($faker->dateTimeBetween('now', '+7 days')->getTimestamp())->toString(),
            'round_end_date' => \Carbon\Carbon::createFromTimeStamp($faker->dateTimeBetween('+8 days', '+14 days')->getTimestamp())->toString(),
            'checkin_enabled' => rand(0,1),
            'checkin_mintues_prior' => rand(0,120),
            'elimination_type' => rand(1,9)
        ];

        $response = $this->withHeaders([
            'Authorization Bearer' => $this->getAccessToken($user),
        ])->put($url, $data);

        //print_r($response->json());
        $this->assertEquals(200, $response->status());

        $json = $response->json();

        $jsonData = $json['data'];

        $this->assertEquals($competition->id, $jsonData['competition_id']);
        $this->assertEquals($round->round, $jsonData['round']);
        $this->assertEquals($jsonData['title'], $data['title']);
        $this->assertEquals($jsonData['overview'], $data['overview']);
        $this->assertEquals($jsonData['round_start_date'], $data['round_start_date']);
        $this->assertEquals($jsonData['round_end_date'], $data['round_end_date']);
        $this->assertEquals($jsonData['checkin_enabled'], $data['checkin_enabled']);
        $this->assertEquals($jsonData['checkin_mintues_prior'], $data['checkin_mintues_prior']);
        $this->assertEquals($jsonData['elimination_type'], $data['elimination_type']);

    }

    public function testDelete() {

        $user = User::factory()->create();

        $competition = Competition::factory()->create();

        RolesFacade::competitionMakeAdmin($competition, $user);

        $round = CompetitionRound::factory()->create(['competition_id' => $competition->id]);

        $url = $this->_getApiRoute() .  'competitions/' . $competition->id . '/rounds/' . $round->round;

        $response = $this->withHeaders([
            'Authorization Bearer' => $this->getAccessToken($user),
        ])->delete($url);

        $this->assertEquals(204, $response->status());

    }


}