<?php

namespace Tests\Routes;

use App\Facades\EventInvitesFacade;
use App\Facades\RolesFacade;
use App\Models\Event;
use App\Models\EventInvite;
use App\Models\EventOverlay;
use App\Models\User;
use Database\Factories\EventInviteFactory;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class EventControllerTest extends TestCase
{


    public function testList(){

        $events = Event::factory(30)->create();

        $user = User::factory()->create();

        $url = $this->_getApiRoute() . 'events';

        $response = $this->withHeaders([
            'Authorization Bearer' => $this->getAccessToken($user),
        ])->get($url);

        $this->assertEquals(200, $response->status());

        $json = $response->json();

        $eventData = $json['data'];

        $this->assertCount(25, $eventData);

        foreach($eventData as $event){

        }

    }

    public function testCreation(){

        $url = $this->_getApiRoute() . 'events';

        $user = User::factory()->create();

        /*$response = $this->withHeaders([
            'Authorization Bearer' => $this->getAccessToken($user),
        ])->post($url);

        $this->assertEquals(422, $response->status());*/

        $faker = \Faker\Factory::create();

        $user = User::factory()->create();

        $data = [
            'title' => $faker->title(),
            'description' => $faker->paragraphs(8, true),
        ];
        
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getAccessToken($user),
        ])->post($url, $data);

        $this->assertEquals(201, $response->status());

        $json = $response->json();

        $event = $json['data'];

        $this->assertEquals($event['title'], $data['title']);
        $this->assertEquals($event['description'], $data['description']);
        //$this->assertEquals($event['user']['id'], $user->id);

    }

    public function testView() {

        $event = Event::factory()->create();

        $url = $this->_getApiRoute() . 'events/' . $event->id;

        $response = $this->withHeaders([
            'Authorization Bearer' => $this->getAccessToken(),
        ])->get($url);

        $this->assertEquals(200, $response->status());

        $json = $response->json();

        $data = $json['data'];

        $this->assertEquals($event->id, $data['id']);
        $this->assertEquals($event->title, $data['title']);
        $this->assertEquals($event->description, $data['description']);
        //$this->assertEquals($event->user_id, $data['user']['id']);

    }

    public function testUpdate() {

        $user = User::factory()->create();

        $event = Event::factory()->create();

        $url = $this->_getApiRoute() . 'events/' . $event->id;

        RolesFacade::eventMakeAdmin($event, $user);

        $faker = \Faker\Factory::create();

        $data = [
            'title' => $faker->title(),
            'description' => $faker->paragraphs(8, true),
            'is_live' => 1,
            'is_public' => 1,
        ];

        $response = $this->withHeaders([
            'Authorization Bearer' => $this->getAccessToken($user),
        ])->put($url, $data);

        $this->assertEquals(200, $response->status());

        $json = $response->json();

        $jsonData = $json['data'];

        $this->assertEquals($event->id, $jsonData['id']);
        $this->assertEquals($jsonData['title'], $data['title']);
        $this->assertEquals($jsonData['description'], $data['description']);
        $this->assertEquals($jsonData['is_live'], $data['is_live']);

        //Test updating with empty fiels
        $response = $this->withHeaders([
            'Authorization Bearer' => $this->getAccessToken($user),
        ])->put($url, ['title' => 'Butt', 'description' => '']);

        $this->assertEquals(422, $response->status());

        //Test updating live
        $response = $this->withHeaders([
            'Authorization Bearer' => $this->getAccessToken($user),
        ])->put($url, ['is_live' => 0]);

        $this->assertEquals(200, $response->status());

        $json = $response->json();

        $jsonData = $json['data'];

        $this->assertEquals($jsonData['is_live'], 0);

        //Test Update is_public

        $response = $this->withHeaders([
            'Authorization Bearer' => $this->getAccessToken($user),
        ])->put($url, ['is_public' => 0]);

        $this->assertEquals(200, $response->status());

        $json = $response->json();

        $jsonData = $json['data'];

        $this->assertEquals($jsonData['is_public'], 0);


    }

    public function testDelete() {

        $user = User::factory()->create();

        $event = Event::factory()->create();

        RolesFacade::eventMakeAdmin($event, $user);

        $url = $this->_getApiRoute() . 'events/' . $event->id;

        $response = $this->withHeaders([
            'Authorization Bearer' => $this->getAccessToken($user),
        ])->delete($url);

        $this->assertEquals(204, $response->status());

    }

    public function testNoToken() {

        $event = Event::factory()->create();

        $url = $this->_getApiRoute() . 'events/' . $event->id;

        $response = $this->withHeaders([])->delete($url);

        $this->assertEquals(500, $response->status());

        $url = $this->_getApiRoute() . 'events/' . $event->id;

        $response = $this->withHeaders([])->put($url);

        $this->assertEquals(500, $response->status());

        $url = $this->_getApiRoute() . 'events/' . $event->id;
        
    }

    public function testMainImage(){

        $user = User::factory()->create();

        $event = Event::factory()->create();

        RolesFacade::eventMakeAdmin($event, $user);

        $url = $this->_getApiRoute() . 'events/' . $event->id. '/uploadMainImage';

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

        $event = Event::factory()->create();

        RolesFacade::eventMakeAdmin($event, $user);

        $url = $this->_getApiRoute() . 'events/' . $event->id. '/uploadBannerImage';

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

    public function testSyncAsLive(){

        $user = User::factory()->create();

        $event = Event::factory()->create();

        RolesFacade::eventMakeAdmin($event, $user);

        $this->assertEquals($event->is_live, 0);
        $this->assertEquals($event->live_last_checkin, null);

        $url = $this->_getApiRoute() . 'events/' . $event->id. '/syncAsLive';
        
        $response = $this->withHeaders([
            'Authorization' => $this->getAccessToken($user),
        ])->post($url, []);


        $this->assertEquals(200, $response->status());

        $event->refresh();

        $this->assertEquals($event->is_live, 1);
        $this->assertNotNull($event->live_last_checkin);

    }

    public function testSendInviteCohost() {

        $user = User::factory()->create();

        $event = Event::factory()->create();

        RolesFacade::eventMakeAdmin($event, $user);

        $url = $this->_getApiRoute() . 'events/' . $event->id. '/sendInvite';

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

        $event = Event::factory()->create();

        $invite = EventInvite::factory()->create(['email' => $user->email, 'event_id' => $event->id]);

        $data = array(
            'token' => $invite->token,
        );
        $url = $this->_getApiRoute() . 'events/' . $event->id. '/acceptInvite'; 

        $response = $this->withHeaders([
            'Authorization' => $this->getAccessToken($user),
        ])->post($url, $data);

        $this->assertEquals(204, $response->status());


    }

    public function testUploadOverlay(){

        $user = User::factory()->create();

        $event = Event::factory()->create();

        RolesFacade::eventMakeAdmin($event, $user);

        $url = $this->_getApiRoute() . 'events/' . $event->id. '/addOverlay';

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

        $event = Event::factory()->create();

        RolesFacade::eventMakeAdmin($event, $user);

        $overlay = EventOverlay::factory()->create(['event_id' => $event->id]);

        $url = $this->_getApiRoute() . 'events/' . $event->id .'/removeOverlay/' . $overlay->id;

        $response = $this->withHeaders([
            'Authorization Bearer' => $this->getAccessToken($user),
        ])->delete($url);

        $this->assertEquals(204, $response->status());

    }

    


}