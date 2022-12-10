<?php

namespace Tests\Routes;

use App\Facades\CompetitionInvitesFacade;
use App\Facades\RolesFacade;
use App\Models\Competition;
use App\Models\CompetitionAddress;
use App\Models\User;
use Database\Factories\CompetitionInviteFactory;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class CompetitionAddressControllerTest extends TestCase
{


    public function testList(){

        $competition = Competition::factory()->create();

        $addresses = CompetitionAddress::factory(30)->create(['competition_id' => $competition->id]);

        $user = User::factory()->create();

        $url = $this->_getApiRoute() . 'competitions/' . $competition->id . '/venues';

        $response = $this->withHeaders([
            'Authorization Bearer' => $this->getAccessToken($user),
        ])->get($url);

        $this->assertEquals(200, $response->status());

        $json = $response->json();

        $addresssData = $json['data'];

        $this->assertCount(25, $addresssData);

    }

    public function testCreation(){

        $competition = Competition::factory()->create();

        $user = User::factory()->create();

        RolesFacade::competitionMakeAdmin($competition, $user);

        $url = $this->_getApiRoute() . 'competitions/' . $competition->id . '/venues';

        /*$response = $this->withHeaders([
            'Authorization Bearer' => $this->getAccessToken($user),
        ])->post($url);

        $this->assertEquals(422, $response->status());*/

        $faker = \Faker\Factory::create();

        $data = [
            'venue_name' => $faker->title(),
            'is_virtual_hybrid_remote' => rand(1,3),
        ];
        
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getAccessToken($user),
        ])->post($url, $data);

        $this->assertEquals(201, $response->status());

        $json = $response->json();

        $address = $json['data'];

        $this->assertEquals($address['competition_id'], $competition->id);
        $this->assertEquals($address['venue_name'], $data['venue_name']);
        $this->assertNotNull($address['id']);
        //$this->assertEquals($competition['user']['id'], $user->id);

    }

    public function testView() {

        $competition = Competition::factory()->create();

        $address = CompetitionAddress::factory()->create(['competition_id' => $competition->id]);

        $url = $this->_getApiRoute() .  'competitions/' . $competition->id . '/venues/' . $address->id;

        $response = $this->withHeaders([
            'Authorization Bearer' => $this->getAccessToken(),
        ])->get($url);

        $this->assertEquals(200, $response->status());

        $json = $response->json();

        $data = $json['data'];

        $this->assertEquals($address->id, $data['id']);
        $this->assertEquals($competition->id, $data['competition_id']);

    }

    public function testUpdate() {

        $user = User::factory()->create();

        $competition = Competition::factory()->create();

        RolesFacade::competitionMakeAdmin($competition, $user);

        $address = CompetitionAddress::factory()->create(['competition_id' => $competition->id]);

        $url = $this->_getApiRoute() .  'competitions/' . $competition->id . '/venues/' . $address->id;

        $faker = \Faker\Factory::create();

        $data = [
            'venue_name' => $faker->title(),
            'venue_description' => $faker->paragraphs(8, true),
            'address_line_1' => $faker->title(),
            'address_line_2' => $faker->title(),
            'postal_code' => $faker->title(),
            'locality' => $faker->title(),
            'province' => $faker->title(),
            'country' => $faker->title(),
            'venue_direction_instructions' => $faker->title(),
            'venue_access_instructions' => $faker->title(),
            'additional_notes' => $faker->title(),
            'is_virtual_hybrid_remote' => rand(1,3)
            
        ];

        $response = $this->withHeaders([
            'Authorization Bearer' => $this->getAccessToken($user),
        ])->put($url, $data);

        //print_r($response->json());
        $this->assertEquals(200, $response->status());

        $json = $response->json();

        $jsonData = $json['data'];

        $this->assertEquals($competition->id, $jsonData['competition_id']);
        $this->assertEquals($address->id, $jsonData['id']);
        $this->assertEquals($jsonData['venue_name'], $data['venue_name']);
        $this->assertEquals($jsonData['venue_description'], $data['venue_description']);
        $this->assertEquals($jsonData['address_line_1'], $data['address_line_1']);
        $this->assertEquals($jsonData['address_line_2'], $data['address_line_2']);
        $this->assertEquals($jsonData['postal_code'], $data['postal_code']);
        $this->assertEquals($jsonData['locality'], $data['locality']);
        $this->assertEquals($jsonData['province'], $data['province']);
        $this->assertEquals($jsonData['country'], $data['country']);
        $this->assertEquals($jsonData['venue_direction_instructions'], $data['venue_direction_instructions']);
        $this->assertEquals($jsonData['venue_access_instructions'], $data['venue_access_instructions']);
        $this->assertEquals($jsonData['additional_notes'], $data['additional_notes']);
        $this->assertEquals($jsonData['is_virtual_hybrid_remote'], $data['is_virtual_hybrid_remote']);

    }

    public function testDelete() {

        $user = User::factory()->create();

        $competition = Competition::factory()->create();

        RolesFacade::competitionMakeAdmin($competition, $user);

        $address = CompetitionAddress::factory()->create(['competition_id' => $competition->id]);

        $url = $this->_getApiRoute() .  'competitions/' . $competition->id . '/venues/' . $address->id;

        $response = $this->withHeaders([
            'Authorization Bearer' => $this->getAccessToken($user),
        ])->delete($url);

        $this->assertEquals(204, $response->status());

    }


}