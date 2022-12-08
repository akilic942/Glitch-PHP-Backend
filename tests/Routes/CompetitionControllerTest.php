<?php

namespace Tests\Routes;

use App\Facades\CompetitionInvitesFacade;
use App\Facades\RolesFacade;
use App\Models\Competition;

use App\Models\User;
use Database\Factories\CompetitionInviteFactory;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class CompetitionControllerTest extends TestCase
{


    public function testList(){

        $competitions = Competition::factory(30)->create();

        $user = User::factory()->create();

        $url = $this->_getApiRoute() . 'competitions';

        $response = $this->withHeaders([
            'Authorization Bearer' => $this->getAccessToken($user),
        ])->get($url);

        $this->assertEquals(200, $response->status());

        $json = $response->json();

        $competitionData = $json['data'];

        $this->assertCount(25, $competitionData);

        foreach($competitionData as $competition){

        }

    }

    public function testCreation(){

        $url = $this->_getApiRoute() . 'competitions';

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
        ];
        
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getAccessToken($user),
        ])->post($url, $data);

        $this->assertEquals(201, $response->status());

        $json = $response->json();

        $competition = $json['data'];

        $this->assertEquals($competition['name'], $data['name']);
        $this->assertEquals($competition['description'], $data['description']);
        //$this->assertEquals($competition['user']['id'], $user->id);

    }

    public function testView() {

        $competition = Competition::factory()->create();

        $url = $this->_getApiRoute() . 'competitions/' . $competition->id;

        $response = $this->withHeaders([
            'Authorization Bearer' => $this->getAccessToken(),
        ])->get($url);

        $this->assertEquals(200, $response->status());

        $json = $response->json();

        $data = $json['data'];

        $this->assertEquals($competition->id, $data['id']);
        $this->assertEquals($competition->name, $data['name']);
        $this->assertEquals($competition->description, $data['description']);
        //$this->assertEquals($competition->user_id, $data['user']['id']);

    }

    public function testUpdate() {

        $user = User::factory()->create();

        $competition = Competition::factory()->create();

        RolesFacade::competitionMakeAdmin($competition, $user);

        $url = $this->_getApiRoute() . 'competitions/' . $competition->id;

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

        $this->assertEquals($competition->id, $jsonData['id']);
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

        $competition = Competition::factory()->create();

        RolesFacade::competitionMakeAdmin($competition, $user);

        $url = $this->_getApiRoute() . 'competitions/' . $competition->id;

        $response = $this->withHeaders([
            'Authorization Bearer' => $this->getAccessToken($user),
        ])->delete($url);

        $this->assertEquals(204, $response->status());

    }

    public function testNoToken() {

        $competition = Competition::factory()->create();

        $url = $this->_getApiRoute() . 'competitions/' . $competition->id;

        $response = $this->withHeaders([])->delete($url);

        $this->assertEquals(500, $response->status());

        $url = $this->_getApiRoute() . 'competitions/' . $competition->id;

        $response = $this->withHeaders([])->put($url);

        $this->assertEquals(500, $response->status());

        $url = $this->_getApiRoute() . 'competitions/' . $competition->id;
        
    }

    /*
    public function testMainImage(){

        $user = User::factory()->create();

        $competition = Competition::factory()->create();

        RolesFacade::eventMakeAdmin($competition, $user);

        $url = $this->_getApiRoute() . 'events/' . $competition->id. '/uploadMainImage';

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

        $competition = Competition::factory()->create();

        RolesFacade::eventMakeAdmin($competition, $user);

        $url = $this->_getApiRoute() . 'events/' . $competition->id. '/uploadBannerImage';

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

        $competition = Competition::factory()->create();

        RolesFacade::eventMakeAdmin($competition, $user);

        $url = $this->_getApiRoute() . 'events/' . $competition->id. '/sendInvite';

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

        $competition = Competition::factory()->create();

        $invite = CompetitionInvite::factory()->create(['email' => $user->email, 'event_id' => $competition->id]);

        $data = array(
            'token' => $invite->token,
        );
        $url = $this->_getApiRoute() . 'events/' . $competition->id. '/acceptInvite'; 

        $response = $this->withHeaders([
            'Authorization' => $this->getAccessToken($user),
        ])->post($url, $data);

        $this->assertEquals(204, $response->status());


    }

    public function testUploadOverlay(){

        $user = User::factory()->create();

        $competition = Competition::factory()->create();

        RolesFacade::eventMakeAdmin($competition, $user);

        $url = $this->_getApiRoute() . 'events/' . $competition->id. '/addOverlay';

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

        $competition = Competition::factory()->create();

        RolesFacade::eventMakeAdmin($competition, $user);

        $overlay = CompetitionOverlay::factory()->create(['event_id' => $competition->id]);

        $url = $this->_getApiRoute() . 'events/' . $competition->id .'/removeOverlay/' . $overlay->id;

        $response = $this->withHeaders([
            'Authorization Bearer' => $this->getAccessToken($user),
        ])->delete($url);

        $this->assertEquals(204, $response->status());

    }

    */


}