<?php

namespace Tests\Routes;

use App\Facades\TeamInvitesFacade;
use App\Facades\RolesFacade;
use App\Models\Team;
use App\Models\TeamUser;
use App\Models\User;
use Database\Factories\TeamInviteFactory;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class TeamUserControllerTest extends TestCase
{

    public function testList(){

        $team = Team::factory()->create();

        $users = TeamUser::factory(30)->create(['team_id' => $team->id]);

        $user = User::factory()->create();

        $url = $this->_getApiRoute() . 'teams/' . $team->id . '/users';

        $response = $this->withHeaders([
            'Authorization Bearer' => $this->getAccessToken($user),
        ])->get($url);

        $this->assertEquals(200, $response->status());

        $json = $response->json();

        $usersData = $json['data'];

        $this->assertCount(25, $usersData);

    }

    public function testCreation(){

        $team = Team::factory()->create();

        $user = User::factory()->create();

        RolesFacade::teamMakeAdmin($team, $user);

        $url = $this->_getApiRoute() . 'teams/' . $team->id . '/users';

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

        $this->assertEquals($user['team_id'], $team->id);
        $this->assertEquals($user['user_id'], $data['user_id']);
        //$this->assertEquals($team['user']['id'], $user->id);

    }

    public function testView() {

        $team = Team::factory()->create();

        $user = TeamUser::factory()->create(['team_id' => $team->id]);

        $url = $this->_getApiRoute() .  'teams/' . $team->id . '/users/' . $user->user_id;

        $response = $this->withHeaders([
            'Authorization Bearer' => $this->getAccessToken(),
        ])->get($url);

        $this->assertEquals(200, $response->status());

        $json = $response->json();

        $data = $json['data'];

        $this->assertEquals($user->user_id, $data['user_id']);
        $this->assertEquals($team->id, $data['team_id']);

    }

    public function testUpdate() {

        $user = User::factory()->create();

        $team = Team::factory()->create();

        RolesFacade::teamMakeAdmin($team, $user);

        $update_user = TeamUser::factory()->create(['team_id' => $team->id]);

        $url = $this->_getApiRoute() .  'teams/' . $team->id . '/users/' . $update_user->user_id;

        $faker = \Faker\Factory::create();

        $data = [
            'user_role' => rand(1,9),
            'status' => rand(1,4),
            'waiver_signed' => rand(0,1),
        ];

        $response = $this->withHeaders([
            'Authorization Bearer' => $this->getAccessToken($user),
        ])->put($url, $data);

        $this->assertEquals(200, $response->status());

        $json = $response->json();

        $jsonData = $json['data'];

        $this->assertEquals($team->id, $jsonData['team_id']);
        $this->assertEquals($update_user->user_id, $jsonData['user_id']);
        $this->assertEquals($jsonData['user_role'], $data['user_role']);
        $this->assertEquals($jsonData['status'], $data['status']);
        $this->assertEquals($jsonData['waiver_signed'], $data['waiver_signed']);

    }

    public function testDelete() {

        $user = User::factory()->create();

        $team = Team::factory()->create();

        RolesFacade::teamMakeAdmin($team, $user);

        $delete_user = TeamUser::factory()->create(['team_id' => $team->id]);

        $url = $this->_getApiRoute() .  'teams/' . $team->id . '/users/' . $delete_user->user_id;

        $response = $this->withHeaders([
            'Authorization Bearer' => $this->getAccessToken($user),
        ])->delete($url);

        $this->assertEquals(204, $response->status());

    }


}