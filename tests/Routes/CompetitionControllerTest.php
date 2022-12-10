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
            'type' => rand(1,9),
            
            'rules' => $faker->paragraphs(8, true),
            'agreement' => $faker->paragraphs(8, true),
            'schedule' => $faker->paragraphs(8, true),
            'disqualifiers' => $faker->paragraphs(8, true),

            'twitter_page' => $faker->url(),
            'facebook_page' => $faker->url(),
            'instagram_page' => $faker->url(),
            'snapchat_page' => $faker->url(),
            'tiktok_page' => $faker->url(),
            'twitch_page' => $faker->url(),
            'youtube_page' => $faker->url(),
            'paetron_page' => $faker->url(),

            'contact_name' => $faker->title(),
            'contact_email' => $faker->email,
            'contact_phone_number' => $faker->phoneNumber(),
            'website' => $faker->url(),

            'start_date' => \Carbon\Carbon::createFromTimeStamp($faker->dateTimeBetween('now', '+7 days')->getTimestamp())->toString(),
            'end_date' => \Carbon\Carbon::createFromTimeStamp($faker->dateTimeBetween('now', '+7 days')->getTimestamp())->toString(),
            'registration_start_date' => \Carbon\Carbon::createFromTimeStamp($faker->dateTimeBetween('now', '+7 days')->getTimestamp())->toString(),
            'registration_end_date' => \Carbon\Carbon::createFromTimeStamp($faker->dateTimeBetween('now', '+7 days')->getTimestamp())->toString(),

            'allow_team_signup' => rand(0,1),
            'allow_individual_signup' => rand(0,1),
            'is_private' => rand(0,1),

            'checkin_enabled' => rand(0,1),
            'checkin_mintues_prior' => rand(0,300),

            'competitors_per_match' => rand(2,100),
            'winners_per_match' => rand(1,100),

            'max_registration_for_teams' => rand(0,100),
            'max_registration_for_users' => rand(0,100),
            'minimum_team_size' => rand(0,100),

            'team_registration_price' => rand(0,100),
            'individual_registration_price' => rand(0,100),
            
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
        $this->assertEquals($jsonData['type'], $data['type']);

        $this->assertEquals($jsonData['rules'], $data['rules']);
        $this->assertEquals($jsonData['agreement'], $data['agreement']);
        $this->assertEquals($jsonData['schedule'], $data['schedule']);
        $this->assertEquals($jsonData['disqualifiers'], $data['disqualifiers']);

        $this->assertEquals($jsonData['twitter_page'], $data['twitter_page']);
        $this->assertEquals($jsonData['facebook_page'], $data['facebook_page']);
        $this->assertEquals($jsonData['instagram_page'], $data['instagram_page']);
        $this->assertEquals($jsonData['snapchat_page'], $data['snapchat_page']);
        $this->assertEquals($jsonData['tiktok_page'], $data['tiktok_page']);
        $this->assertEquals($jsonData['youtube_page'], $data['youtube_page']);
        $this->assertEquals($jsonData['paetron_page'], $data['paetron_page']);

        $this->assertEquals($jsonData['contact_name'], $data['contact_name']);
        $this->assertEquals($jsonData['contact_email'], $data['contact_email']);
        $this->assertEquals($jsonData['contact_phone_number'], $data['contact_phone_number']);
        $this->assertEquals($jsonData['website'], $data['website']);

        $this->assertEquals($jsonData['start_date'], $data['start_date']);
        $this->assertEquals($jsonData['end_date'], $data['end_date']);
        $this->assertEquals($jsonData['registration_start_date'], $data['registration_start_date']);
        $this->assertEquals($jsonData['registration_end_date'], $data['registration_end_date']);

        $this->assertEquals($jsonData['allow_team_signup'], $data['allow_team_signup']);
        $this->assertEquals($jsonData['allow_individual_signup'], $data['allow_individual_signup']);
        $this->assertEquals($jsonData['is_private'], $data['is_private']);

        $this->assertEquals($jsonData['checkin_enabled'], $data['checkin_enabled']);
        $this->assertEquals($jsonData['checkin_mintues_prior'], $data['checkin_mintues_prior']);

        $this->assertEquals($jsonData['competitors_per_match'], $data['competitors_per_match']);
        $this->assertEquals($jsonData['winners_per_match'], $data['winners_per_match']);

        $this->assertEquals($jsonData['max_registration_for_teams'], $data['max_registration_for_teams']);
        $this->assertEquals($jsonData['max_registration_for_users'], $data['max_registration_for_users']);
        $this->assertEquals($jsonData['minimum_team_size'], $data['minimum_team_size']);
        

        $this->assertEquals($jsonData['team_registration_price'], $data['team_registration_price']);
        $this->assertEquals($jsonData['individual_registration_price'], $data['individual_registration_price']);

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