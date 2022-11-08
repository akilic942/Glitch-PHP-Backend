<?php

namespace Tests\Routes;

use App\Models\Event;
use App\Models\User;
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

        $faker = \Faker\Factory::create();

        $data = [
            'title' => $faker->title(),
            'description' => $faker->paragraphs(8, true),
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

        $response = $this->withHeaders([
            'Authorization Bearer' => $this->getAccessToken($user),
        ])->put($url, ['title' => 'Butt', 'description' => '']);

        print_r($response);

        $this->assertEquals(422, $response->status());

    }

    public function testDelete() {

        $user = User::factory()->create();

        $event = Event::factory()->create();

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

        $response = $this->withHeaders([])->get($url);

        $this->assertEquals(500, $response->status());
        
    }


}