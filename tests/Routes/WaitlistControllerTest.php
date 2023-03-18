<?php

namespace Tests\Routes;

use App\Enums\Roles;
use App\Facades\WaitlistInvitesFacade;
use App\Facades\RolesFacade;
use App\Models\Waitlist;
use App\Models\WaitlistInvite;
use App\Models\User;
use App\Models\UserRole;
use Database\Factories\WaitlistInviteFactory;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class WaitlistControllerTest extends TestCase
{


    public function testList(){

        $waitlists = Waitlist::factory(30)->create();

        $user = User::factory()->create();

        UserRole::factory()->create(['user_id' => $user->id, 'user_role' => Roles::Administrator]);

        $url = $this->_getApiRoute() . 'waitlists';

        $response = $this->withHeaders([
            'Authorization Bearer' => $this->getAccessToken($user),
        ])->get($url);

        $this->assertEquals(200, $response->status());

        $json = $response->json();

        $waitlistData = $json['data'];

        $this->assertCount(25, $waitlistData);

        foreach($waitlistData as $waitlist){

        }

    }

    public function testCreation(){

        $url = $this->_getApiRoute() . 'waitlists';

        $user = User::factory()->create();

        /*$response = $this->withHeaders([
            'Authorization Bearer' => $this->getAccessToken($user),
        ])->post($url);

        $this->assertEquals(422, $response->status());*/

        $faker = \Faker\Factory::create();

        $user = User::factory()->create();

        $data = [
            'email' => $faker->email,
        ];
        
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getAccessToken($user),
        ])->post($url, $data);

        $this->assertEquals(201, $response->status());

        $json = $response->json();

        $waitlist = $json['data'];

        $this->assertEquals($waitlist['email'], $data['email']);


    }

    public function testView() {

        $waitlist = Waitlist::factory()->create();

        $url = $this->_getApiRoute() . 'waitlists/' . $waitlist->id;

        $user = User::factory()->create();

        UserRole::factory()->create(['user_id' => $user->id, 'user_role' => Roles::Administrator]);

        $response = $this->withHeaders([
            'Authorization Bearer' => $this->getAccessToken($user),
        ])->get($url);

        $this->assertEquals(200, $response->status());

        $json = $response->json();

        $data = $json['data'];

        $this->assertEquals($waitlist->id, $data['id']);
        $this->assertEquals($waitlist->email, $data['email']);
 

    }

    public function testUpdate() {

        $user = User::factory()->create();

        UserRole::factory()->create(['user_id' => $user->id, 'user_role' => Roles::Administrator]);

        $waitlist = Waitlist::factory()->create();

        $url = $this->_getApiRoute() . 'waitlists/' . $waitlist->id;

        $faker = \Faker\Factory::create();

        $data = [
            'email' => $faker->email,
            'first_name' => $faker->title(),
            'last_name' => $faker->title(),
            'full_name' => $faker->title(),
            'display_name' => $faker->title(),
            'username' => $faker->title(),
            'phone_number' => $faker->title(),
            'website' => $faker->title(),
            'title' => $faker->title(),
            'company' => $faker->title(),
        ];

        $response = $this->withHeaders([
            'Authorization Bearer' => $this->getAccessToken($user),
        ])->put($url, $data);

        $this->assertEquals(200, $response->status());

        $json = $response->json();

        $jsonData = $json['data'];

        $this->assertEquals($waitlist->id, $jsonData['id']);
        $this->assertEquals($jsonData['email'], $data['email']);
        $this->assertEquals($jsonData['first_name'], $data['first_name']);
        $this->assertEquals($jsonData['last_name'], $data['last_name']);
        $this->assertEquals($jsonData['full_name'], $data['full_name']);
        $this->assertEquals($jsonData['display_name'], $data['display_name']);
        $this->assertEquals($jsonData['username'], $data['username']);
        $this->assertEquals($jsonData['phone_number'], $data['phone_number']);
        $this->assertEquals($jsonData['website'], $data['website']);
        $this->assertEquals($jsonData['title'], $data['title']);
        $this->assertEquals($jsonData['company'], $data['company']);

  

    }

    public function testDelete() {

        $user = User::factory()->create();

        UserRole::factory()->create(['user_id' => $user->id, 'user_role' => Roles::Administrator]);

        $waitlist = Waitlist::factory()->create();

        $url = $this->_getApiRoute() . 'waitlists/' . $waitlist->id;

        $response = $this->withHeaders([
            'Authorization Bearer' => $this->getAccessToken($user),
        ])->delete($url);

        $this->assertEquals(204, $response->status());

    }

    public function testNoToken() {

        $waitlist = Waitlist::factory()->create();

        $url = $this->_getApiRoute() . 'waitlists/' . $waitlist->id;

        $response = $this->withHeaders([])->delete($url);

        $this->assertEquals(500, $response->status());

        $url = $this->_getApiRoute() . 'waitlists/' . $waitlist->id;

        $response = $this->withHeaders([])->put($url);

        $this->assertEquals(500, $response->status());

        $url = $this->_getApiRoute() . 'waitlists/' . $waitlist->id;
        
    }


}