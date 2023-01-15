<?php

namespace Tests\Routes;

use App\Facades\EventInvitesFacade;
use App\Facades\RolesFacade;
use App\Models\Event;
use App\Models\EventTicketType;
use App\Models\User;
use Database\Factories\EventInviteFactory;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class EventTicketTypeControllerTest extends TestCase
{


    public function testList(){

        $event = Event::factory()->create();

        $typees = EventTicketType::factory(30)->create(['event_id' => $event->id]);

        $user = User::factory()->create();

        $url = $this->_getApiRoute() . 'events/' . $event->id . '/tickettypes';

        $response = $this->withHeaders([
            'Authorization Bearer' => $this->getAccessToken($user),
        ])->get($url);

        $this->assertEquals(200, $response->status());

        $json = $response->json();

        $typesData = $json['data'];

        $this->assertCount(25, $typesData);

    }

    public function testCreation(){

        $event = Event::factory()->create();

        $user = User::factory()->create();

        RolesFacade::eventMakeAdmin($event, $user);

        $url = $this->_getApiRoute() . 'events/' . $event->id . '/tickettypes';

        /*$response = $this->withHeaders([
            'Authorization Bearer' => $this->getAccessToken($user),
        ])->post($url);

        $this->assertEquals(422, $response->status());*/

        $faker = \Faker\Factory::create();

        $data = [
            'name' => $faker->title(),
        ];
        
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getAccessToken($user),
        ])->post($url, $data);

        $this->assertEquals(201, $response->status());

        $json = $response->json();

        $type = $json['data'];

        $this->assertEquals($type['event_id'], $event->id);
        $this->assertEquals($type['name'], $data['name']);
        $this->assertNotNull($type['id']);
        //$this->assertEquals($event['user']['id'], $user->id);

    }

    public function testView() {

        $event = Event::factory()->create();

        $type = EventTicketType::factory()->create(['event_id' => $event->id]);

        $url = $this->_getApiRoute() .  'events/' . $event->id . '/tickettypes/' . $type->id;

        $response = $this->withHeaders([
            'Authorization Bearer' => $this->getAccessToken(),
        ])->get($url);

        $this->assertEquals(200, $response->status());

        $json = $response->json();

        $data = $json['data'];

        $this->assertEquals($type->id, $data['id']);
        $this->assertEquals($event->id, $data['event_id']);

    }

    public function testUpdate() {

        $user = User::factory()->create();

        $event = Event::factory()->create();

        RolesFacade::eventMakeAdmin($event, $user);

        $type = EventTicketType::factory()->create(['event_id' => $event->id]);

        $url = $this->_getApiRoute() .  'events/' . $event->id . '/tickettypes/' . $type->id;

        $faker = \Faker\Factory::create();

        $data = [
            'name' => $faker->title(),
            'description' => $faker->paragraphs(8, true),
            
            'ticket_type' => rand(1,3),
            'visibility' => rand(1,3),

            'max_available' => rand(1,120),
            'min_purchasable' => rand(1,120),
            'max_purchasable' => rand(1,120),

            'price' => $faker->randomFloat(2),
            'disabled' => rand(0,1),

            'sales_start_date' => \Carbon\Carbon::createFromTimeStamp($faker->dateTimeBetween('now', '+7 days')->getTimestamp())->toString(),
            'sales_end_date' => \Carbon\Carbon::createFromTimeStamp($faker->dateTimeBetween('+8 days', '+14 days')->getTimestamp())->toString(),

            'visibility_start_date' => \Carbon\Carbon::createFromTimeStamp($faker->dateTimeBetween('now', '+7 days')->getTimestamp())->toString(),
            'visibility_end_date' => \Carbon\Carbon::createFromTimeStamp($faker->dateTimeBetween('+8 days', '+14 days')->getTimestamp())->toString(),

            'ticket_usage_date' => \Carbon\Carbon::createFromTimeStamp($faker->dateTimeBetween('now', '+7 days')->getTimestamp())->toString(),
             
        ];

        $response = $this->withHeaders([
            'Authorization Bearer' => $this->getAccessToken($user),
        ])->put($url, $data);

        $this->assertEquals(200, $response->status());

        $json = $response->json();

        $jsonData = $json['data'];

        $this->assertEquals($event->id, $jsonData['event_id']);
        $this->assertEquals($type->id, $jsonData['id']);
        $this->assertEquals($jsonData['name'], $data['name']);
        $this->assertEquals($jsonData['description'], $data['description']);
        $this->assertEquals($jsonData['ticket_type'], $data['ticket_type']);
        $this->assertEquals($jsonData['visibility'], $data['visibility']);
        $this->assertEquals($jsonData['max_available'], $data['max_available']);
        $this->assertEquals($jsonData['min_purchasable'], $data['min_purchasable']);
        $this->assertEquals($jsonData['max_purchasable'], $data['max_purchasable']);
        $this->assertEquals($jsonData['price'], $data['price']);
        $this->assertEquals($jsonData['disabled'], $data['disabled']);
        $this->assertEquals($jsonData['sales_start_date'], $data['sales_start_date']);
        $this->assertEquals($jsonData['sales_end_date'], $data['sales_end_date']);
        $this->assertEquals($jsonData['visibility_start_date'], $data['visibility_start_date']);
        $this->assertEquals($jsonData['visibility_end_date'], $data['visibility_end_date']);
        $this->assertEquals($jsonData['ticket_usage_date'], $data['ticket_usage_date']);

    }

    public function testDelete() {

        $user = User::factory()->create();

        $event = Event::factory()->create();

        RolesFacade::eventMakeAdmin($event, $user);

        $type = EventTicketType::factory()->create(['event_id' => $event->id]);

        $url = $this->_getApiRoute() .  'events/' . $event->id . '/tickettypes/' . $type->id;

        $response = $this->withHeaders([
            'Authorization Bearer' => $this->getAccessToken($user),
        ])->delete($url);

        $this->assertEquals(204, $response->status());

    }


}