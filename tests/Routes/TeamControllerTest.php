<?php

namespace Tests\Routes;

use App\Facades\TeamInvitesFacade;
use App\Facades\RolesFacade;
use App\Models\Team;
use App\Models\TeamInvite;
use App\Models\User;
use Database\Factories\TeamInviteFactory;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class TeamControllerTest extends TestCase
{


    public function testList(){

        $teams = Team::factory(30)->create();

        $user = User::factory()->create();

        $url = $this->_getApiRoute() . 'teams';

        $response = $this->withHeaders([
            'Authorization Bearer' => $this->getAccessToken($user),
        ])->get($url);

        $this->assertEquals(200, $response->status());

        $json = $response->json();

        $teamData = $json['data'];

        $this->assertCount(25, $teamData);

        foreach($teamData as $team){

        }

    }

    public function testCreation(){

        $url = $this->_getApiRoute() . 'teams';

        $user = User::factory()->create();

        /*$response = $this->withHeaders([
            'Authorization Bearer' => $this->getAccessToken($user),
        ])->post($url);

        $this->assertEquals(422, $response->status());*/

        $faker = \Faker\Factory::create();

        $user = User::factory()->create();

        $data = [
            'name' => $faker->title(),
            'description' => $faker->paragraphs(8, true),
            'join_process' => rand(1,3)
        ];
        
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getAccessToken($user),
        ])->post($url, $data);

        $this->assertEquals(201, $response->status());

        $json = $response->json();

        $team = $json['data'];

        $this->assertEquals($team['name'], $data['name']);
        $this->assertEquals($team['description'], $data['description']);
        $this->assertEquals($team['join_process'], $data['join_process']);
        //$this->assertEquals($team['user']['id'], $user->id);

    }

    public function testView() {

        $team = Team::factory()->create();

        $url = $this->_getApiRoute() . 'teams/' . $team->id;

        $response = $this->withHeaders([
            'Authorization Bearer' => $this->getAccessToken(),
        ])->get($url);

        $this->assertEquals(200, $response->status());

        $json = $response->json();

        $data = $json['data'];

        $this->assertEquals($team->id, $data['id']);
        $this->assertEquals($team->name, $data['name']);
        $this->assertEquals($team->description, $data['description']);
        //$this->assertEquals($team->user_id, $data['user']['id']);

    }

    public function testUpdate() {

        $user = User::factory()->create();

        $team = Team::factory()->create();

        RolesFacade::teamMakeAdmin($team, $user);

        $url = $this->_getApiRoute() . 'teams/' . $team->id;

        $faker = \Faker\Factory::create();

        $data = [
            'name' => $faker->title(),
            'description' => $faker->paragraphs(8, true),
        ];

        $response = $this->withHeaders([
            'Authorization Bearer' => $this->getAccessToken($user),
        ])->put($url, $data);

        //print_r($response->json());
        $this->assertEquals(200, $response->status());

        $json = $response->json();

        $jsonData = $json['data'];

        $this->assertEquals($team->id, $jsonData['id']);
        $this->assertEquals($jsonData['name'], $data['name']);
        $this->assertEquals($jsonData['description'], $data['description']);

        $response = $this->withHeaders([
            'Authorization Bearer' => $this->getAccessToken($user),
        ])->put($url, ['name' => 'Butt', 'description' => '']);

        //print_r($response);

        $this->assertEquals(422, $response->status());

    }

    public function testDelete() {

        $user = User::factory()->create();

        $team = Team::factory()->create();

        RolesFacade::teamMakeAdmin($team, $user);

        $url = $this->_getApiRoute() . 'teams/' . $team->id;

        $response = $this->withHeaders([
            'Authorization Bearer' => $this->getAccessToken($user),
        ])->delete($url);

        $this->assertEquals(204, $response->status());

    }

    public function testNoToken() {

        $team = Team::factory()->create();

        $url = $this->_getApiRoute() . 'teams/' . $team->id;

        $response = $this->withHeaders([])->delete($url);

        $this->assertEquals(500, $response->status());

        $url = $this->_getApiRoute() . 'teams/' . $team->id;

        $response = $this->withHeaders([])->put($url);

        $this->assertEquals(500, $response->status());

        $url = $this->_getApiRoute() . 'teams/' . $team->id;
        
    }

    public function testListInvites() {

        $user = User::factory()->create();

        $team = Team::factory()->create();

        RolesFacade::teamMakeAdmin($team, $user);

        $url = $this->_getApiRoute() . 'teams/' . $team->id. '/invites';

        $invite_count = rand(5, 25);

        TeamInvite::factory($invite_count)->create(['team_id' => $team->id]);

        TeamInvite::factory($invite_count)->create();

        $response = $this->withHeaders([
            'Authorization Bearer' => $this->getAccessToken($user),
        ])->get($url);

        $this->assertEquals(200, $response->status());

        $json = $response->json();

        $teamData = $json['data'];

        $this->assertCount($invite_count, $teamData);

    }

    public function testSendInvite() {

        $user = User::factory()->create();

        $team = Team::factory()->create();

        RolesFacade::teamMakeAdmin($team, $user);

        $url = $this->_getApiRoute() . 'teams/' . $team->id. '/sendInvite';

        $faker = \Faker\Factory::create();

        $data = array(
            'name' => $faker->firstName(),
            'email' => $faker->email,
        );

        $response = $this->withHeaders([
            'Authorization' => $this->getAccessToken($user),
        ])->post($url, $data);

        $this->assertEquals(201, $response->status());

        $json = $response->json();

        $this->assertEquals($data['email'], $json['data']['email']);
        $this->assertEquals($data['name'], $json['data']['name']);

    }

    public function testAcceptInvite() {

        $user = User::factory()->create();

        $team = Team::factory()->create();

        $invite = TeamInvite::factory()->create(['email' => $user->email, 'team_id' => $team->id]);

        $data = array(
            'token' => $invite->token,
        );
        $url = $this->_getApiRoute() . 'teams/' . $team->id. '/acceptInvite'; 

        $response = $this->withHeaders([
            'Authorization' => $this->getAccessToken($user),
        ])->post($url, $data);

        $this->assertEquals(204, $response->status());


    }

    public function testMainImage(){

        $user = User::factory()->create();

        $team = Team::factory()->create();

        RolesFacade::teamMakeAdmin($team, $user);

        $url = $this->_getApiRoute() . 'teams/' . $team->id. '/uploadMainImage';

        $data = [
            'image' => UploadedFile::fake()->image('avatar.png')
        ];
        
        $response = $this->withHeaders([
            'Authorization' => $this->getAccessToken($user),
        ])->post($url, $data);


        $this->assertEquals(200, $response->status());

        $json = $response->json();

        $this->assertNotNull($json['data']['main_image']);
        $this->assertNotEmpty($json['data']['main_image']);

    }

    public function testBannerImage(){

        $user = User::factory()->create();

        $team = Team::factory()->create();

        RolesFacade::teamMakeAdmin($team, $user);

        $url = $this->_getApiRoute() . 'teams/' . $team->id. '/uploadBannerImage';

        $data = [
            'image' => UploadedFile::fake()->image('avatar.png')
        ];
        
        $response = $this->withHeaders([
            'Authorization' => $this->getAccessToken($user),
        ])->post($url, $data);


        $this->assertEquals(200, $response->status());

        $json = $response->json();

        $this->assertNotNull($json['data']['banner_image']);
        $this->assertNotEmpty($json['data']['banner_image']);

    }

    /*
    public function testMainImage(){

        $user = User::factory()->create();

        $team = Team::factory()->create();

        RolesFacade::eventMakeAdmin($team, $user);

        $url = $this->_getApiRoute() . 'events/' . $team->id. '/uploadMainImage';

        $data = [
            'image' => UploadedFile::fake()->image('avatar.png')
        ];
        
        $response = $this->withHeaders([
            'Authorization' => $this->getAccessToken($user),
        ])->post($url, $data);


        $this->assertEquals(200, $response->status());

        $json = $response->json();

        $this->assertNotNull($json['data']['image_main']);
        $this->assertNotEmpty($json['data']['image_main']);

    }

    public function testBannerImage(){

        $user = User::factory()->create();

        $team = Team::factory()->create();

        RolesFacade::eventMakeAdmin($team, $user);

        $url = $this->_getApiRoute() . 'events/' . $team->id. '/uploadBannerImage';

        $data = [
            'image' => UploadedFile::fake()->image('avatar.png')
        ];
        
        $response = $this->withHeaders([
            'Authorization' => $this->getAccessToken($user),
        ])->post($url, $data);


        $this->assertEquals(200, $response->status());

        $json = $response->json();

        $this->assertNotNull($json['data']['image_banner']);
        $this->assertNotEmpty($json['data']['image_banner']);

    }


    public function testSendInviteCohost() {

        $user = User::factory()->create();

        $team = Team::factory()->create();

        RolesFacade::eventMakeAdmin($team, $user);

        $url = $this->_getApiRoute() . 'events/' . $team->id. '/sendInvite';

        $faker = \Faker\Factory::create();

        $data = array(
            'name' => $faker->firstName(),
            'email' => $faker->email,
        );

        $response = $this->withHeaders([
            'Authorization' => $this->getAccessToken($user),
        ])->post($url, $data);

        $this->assertEquals(201, $response->status());

        $json = $response->json();

        $this->assertEquals($data['email'], $json['data']['email']);
        $this->assertEquals($data['name'], $json['data']['name']);

    }

    public function testAcceptInviteCohost() {

        $user = User::factory()->create();

        $team = Team::factory()->create();

        $invite = TeamInvite::factory()->create(['email' => $user->email, 'event_id' => $team->id]);

        $data = array(
            'token' => $invite->token,
        );
        $url = $this->_getApiRoute() . 'events/' . $team->id. '/acceptInvite'; 

        $response = $this->withHeaders([
            'Authorization' => $this->getAccessToken($user),
        ])->post($url, $data);

        $this->assertEquals(204, $response->status());


    }

    public function testUploadOverlay(){

        $user = User::factory()->create();

        $team = Team::factory()->create();

        RolesFacade::eventMakeAdmin($team, $user);

        $url = $this->_getApiRoute() . 'events/' . $team->id. '/addOverlay';

        $faker = \Faker\Factory::create();

        $data = [
            'label' => $faker->name(),
            'image' => UploadedFile::fake()->image('avatar.png')
        ];
        
        $response = $this->withHeaders([
            'Authorization' => $this->getAccessToken($user),
        ])->post($url, $data);


        $this->assertEquals(200, $response->status());

        $json = $response->json();

        //$this->assertNotNull($json['data']['image_main']);
        //$this->assertNotEmpty($json['data']['image_main']);

    }

    public function testDeleteOverlay() {

        $user = User::factory()->create();

        $team = Team::factory()->create();

        RolesFacade::eventMakeAdmin($team, $user);

        $overlay = TeamOverlay::factory()->create(['event_id' => $team->id]);

        $url = $this->_getApiRoute() . 'events/' . $team->id .'/removeOverlay/' . $overlay->id;

        $response = $this->withHeaders([
            'Authorization Bearer' => $this->getAccessToken($user),
        ])->delete($url);

        $this->assertEquals(204, $response->status());

    }

    */


}