<?php

namespace Tests\Routes;

use App\Facades\EventInvitesFacade;
use App\Facades\RolesFacade;
use App\Models\Event;
use App\Models\EventTicketType;
use App\Models\EventTicketTypeSection;
use App\Models\User;
use Database\Factories\EventInviteFactory;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class EventTicketTypeSectionControllerTest extends TestCase
{


    public function testList(){

        $event = Event::factory()->create();

        $type = EventTicketType::factory()->create(['event_id' => $event->id]);

        $sections = EventTicketTypeSection::factory(30)->create(['ticket_type_id' => $type->id]);

        $user = User::factory()->create();

        $url = $this->_getApiRoute() . 'events/' . $event->id . '/tickettypes/' . $type->id . '/sections';

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

        $type = EventTicketType::factory()->create(['event_id' => $event->id]);

        $user = User::factory()->create();

        RolesFacade::eventMakeAdmin($event, $user);

        $url = $this->_getApiRoute() . 'events/' . $event->id . '/tickettypes/' . $type->id . '/sections';

        /*$response = $this->withHeaders([
            'Authorization Bearer' => $this->getAccessToken($user),
        ])->post($url);

        $this->assertEquals(422, $response->status());*/

        $faker = \Faker\Factory::create();

        $data = [
            'title' => $faker->name(),
        ];
        
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getAccessToken($user),
        ])->post($url, $data);

        $this->assertEquals(201, $response->status());

        $json = $response->json();

        $result = $json['data'];

        $this->assertEquals($result['ticket_type_id'], $type->id);
        $this->assertEquals($result['title'], $data['title']);
        $this->assertNotNull($result['id']);
        //$this->assertEquals($event['user']['id'], $user->id);

    }

    public function testView() {

        $event = Event::factory()->create();

        $type = EventTicketType::factory()->create(['event_id' => $event->id]);

        $section = EventTicketTypeSection::factory()->create(['ticket_type_id' => $type->id]);

        $url = $this->_getApiRoute() .  'events/' . $event->id . '/tickettypes/' . $type->id . '/sections/' . $section->id;

        $response = $this->withHeaders([
            'Authorization Bearer' => $this->getAccessToken(),
        ])->get($url);

        $this->assertEquals(200, $response->status());

        $json = $response->json();

        $data = $json['data'];

        $this->assertEquals($section->id, $data['id']);
        $this->assertEquals($type->id, $data['ticket_type_id']);

    }

    public function testUpdate() {

        $user = User::factory()->create();

        $event = Event::factory()->create();

        RolesFacade::eventMakeAdmin($event, $user);

        $type = EventTicketType::factory()->create(['event_id' => $event->id]);

        $section = EventTicketTypeSection::factory()->create(['ticket_type_id' => $type->id]);

        $url = $this->_getApiRoute() .  'events/' . $event->id . '/tickettypes/' . $type->id . '/sections/' . $section->id;
        
        $faker = \Faker\Factory::create();

        $data = [
            'title' => $faker->name(),
            'section_order' => rand(1,100),
            'instructions' => $faker->paragraph(5),
        ];

        $response = $this->withHeaders([
            'Authorization Bearer' => $this->getAccessToken($user),
        ])->put($url, $data);
        
        $this->assertEquals(200, $response->status());

        $json = $response->json();

        $jsonData = $json['data'];

        $this->assertEquals($section->id, $jsonData['id']);
        $this->assertEquals($jsonData['title'], $data['title']);
        $this->assertEquals($jsonData['instructions'], $data['instructions']);
        $this->assertEquals($jsonData['section_order'], $data['section_order']);

    }

    public function testDelete() {

        $user = User::factory()->create();

        $event = Event::factory()->create();

        RolesFacade::eventMakeAdmin($event, $user);

        $type = EventTicketType::factory()->create(['event_id' => $event->id]);

        $section = EventTicketTypeSection::factory()->create(['ticket_type_id' => $type->id]);

        $url = $this->_getApiRoute() .  'events/' . $event->id . '/tickettypes/' . $type->id . '/sections/' . $section->id;;

        $response = $this->withHeaders([
            'Authorization Bearer' => $this->getAccessToken($user),
        ])->delete($url);

        $this->assertEquals(204, $response->status());

    }


}