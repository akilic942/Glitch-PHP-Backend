<?php

namespace Tests\Routes;

use App\Facades\CompetitionInvitesFacade;
use App\Facades\RolesFacade;
use App\Models\Competition;
use App\Models\CompetitionUser;
use App\Models\User;
use Database\Factories\CompetitionInviteFactory;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class CompetitionUserControllerTest extends TestCase
{

    public function testList(){

        $competition = Competition::factory()->create();

        $users = CompetitionUser::factory(30)->create(['competition_id' => $competition->id]);

        $user = User::factory()->create();

        $url = $this->_getApiRoute() . 'competitions/' . $competition->id . '/users';

        $response = $this->withHeaders([
            'Authorization Bearer' => $this->getAccessToken($user),
        ])->get($url);

        $this->assertEquals(200, $response->status());

        $json = $response->json();

        $usersData = $json['data'];

        $this->assertCount(25, $usersData);

    }

    public function testCreation(){

        $competition = Competition::factory()->create();

        $user = User::factory()->create();

        RolesFacade::competitionMakeAdmin($competition, $user);

        $url = $this->_getApiRoute() . 'competitions/' . $competition->id . '/users';

        /*$response = $this->withHeaders([
            'Authorization Bearer' => $this->getAccessToken($user),
        ])->post($url);

        $this->assertEquals(422, $response->status());*/

        $faker = \Faker\Factory::create();

        $added_user = User::factory()->create();

        $data = [
            'user_id' => $added_user->id,
        ];
        
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getAccessToken($user),
        ])->post($url, $data);

        $this->assertEquals(201, $response->status());

        $json = $response->json();

        $user = $json['data'];

        $this->assertEquals($user['competition_id'], $competition->id);
        $this->assertEquals($user['user_id'], $data['user_id']);
        //$this->assertEquals($competition['user']['id'], $user->id);

    }

    public function testView() {

        $competition = Competition::factory()->create();

        $user = CompetitionUser::factory()->create(['competition_id' => $competition->id]);

        $url = $this->_getApiRoute() .  'competitions/' . $competition->id . '/users/' . $user->user_id;

        $response = $this->withHeaders([
            'Authorization Bearer' => $this->getAccessToken(),
        ])->get($url);

        $this->assertEquals(200, $response->status());

        $json = $response->json();

        $data = $json['data'];

        $this->assertEquals($user->user_id, $data['user_id']);
        $this->assertEquals($competition->id, $data['competition_id']);

    }

    public function testUpdate() {

        $user = User::factory()->create();

        $competition = Competition::factory()->create();

        RolesFacade::competitionMakeAdmin($competition, $user);

        $update_user = CompetitionUser::factory()->create(['competition_id' => $competition->id]);

        $url = $this->_getApiRoute() .  'competitions/' . $competition->id . '/users/' . $update_user->user_id;

        $faker = \Faker\Factory::create();

        $data = [
            'user_role' => rand(1,9),
            'status' => rand(1,4),
            'entry_fee_paid' => rand(0,1),
            'checked_in' => rand(0,1),
            'waiver_signed' => rand(0,1),
            'checked_in_time' => \Carbon\Carbon::createFromTimeStamp($faker->dateTimeBetween('now', '+7 days')->getTimestamp())->toString(),
        ];

        $response = $this->withHeaders([
            'Authorization Bearer' => $this->getAccessToken($user),
        ])->put($url, $data);

        $this->assertEquals(200, $response->status());

        $json = $response->json();

        $jsonData = $json['data'];

        $this->assertEquals($competition->id, $jsonData['competition_id']);
        $this->assertEquals($update_user->user_id, $jsonData['user_id']);
        $this->assertEquals($jsonData['user_role'], $data['user_role']);
        $this->assertEquals($jsonData['status'], $data['status']);
        $this->assertEquals($jsonData['entry_fee_paid'], $data['entry_fee_paid']);
        $this->assertEquals($jsonData['checked_in'], $data['checked_in']);
        $this->assertEquals($jsonData['checked_in_time'], $data['checked_in_time']);
        $this->assertEquals($jsonData['waiver_signed'], $data['waiver_signed']);

    }

    public function testDelete() {

        $user = User::factory()->create();

        $competition = Competition::factory()->create();

        RolesFacade::competitionMakeAdmin($competition, $user);

        $delete_user = CompetitionUser::factory()->create(['competition_id' => $competition->id]);

        $url = $this->_getApiRoute() .  'competitions/' . $competition->id . '/users/' . $delete_user->user_id;

        $response = $this->withHeaders([
            'Authorization Bearer' => $this->getAccessToken($user),
        ])->delete($url);

        $this->assertEquals(204, $response->status());

    }


}