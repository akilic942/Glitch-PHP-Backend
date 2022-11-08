<?php

namespace Tests\Routes;

use App\Models\Affirmation;
use App\Models\Discussion;
use App\Models\Post;
use App\Models\School;
use App\Models\User;
use App\Models\UserSchool;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;

use function PHPUnit\Framework\assertCount;

class UserControllerTest extends TestCase {


    public function testList(){

        $users = User::factory(20)->create();

        $url = $this->_getApiRoute() . 'users';

        $response = $this->withHeaders([
            'Authorization' => $this->getAccessToken(),
        ])->get($url);

        $this->assertEquals(200, $response->status());

        $json = $response->json();

        $usersData = $json['data'];

        $this->assertCount(21, $usersData);

    }

    public function testMe() {

        $user = User::factory()->create();


        $url = $this->_getApiRoute() . 'users/me';

        $response = $this->withHeaders([
            'Authorization' => $this->getAccessToken($user),
        ])->get($url, []);

        $json = $response->json();

        $jsonData = $json['data'];

        $this->assertEquals($user->id, $jsonData['id']);
    }

    public function testOneTimeToken() {

        $user = User::factory()->create();

        $url = $this->_getApiRoute() . 'users/oneTimeToken';

        $response = $this->withHeaders([
            'Authorization' => $this->getAccessToken($user),
        ])->get($url, []);

        $json = $response->json();

        $jsonData = $json['data'];

        $this->assertEquals($user->id, $jsonData['id']);
        $this->assertNotNull($jsonData['one_time_login_token']);
        $this->assertNotNull($jsonData['one_time_login_token_date']);
    }

    public function testUpdate() {

        $user = User::factory()->create();

        $url = $this->_getApiRoute() . 'users';

        $faker = \Faker\Factory::create();

        $data = array(
            'first_name' => $faker->firstName(),
            'last_name' => $faker->lastName(),   
            'username' => $faker->userName,
        );

        $response = $this->withHeaders([
            'Authorization' => $this->getAccessToken($user),
        ])->put($url, $data);

        $user->refresh();

        $this->assertEquals($user->first_name, $data['first_name']);
        $this->assertEquals($user->last_name, $data['last_name']);
        $this->assertEquals($user->username, $data['username']);
    }

    public function testProfile() {

        $user = User::factory()->create();

        $url = $this->_getApiRoute() . 'users/' . $user->id . '/profile';

        $response = $this->withHeaders([
            'Authorization' => $this->getAccessToken(),
        ])->get($url, []);

        $json = $response->json();

        $jsonData = $json['data'];

        $this->assertEquals($user->id, $jsonData['id']);
        $this->assertEquals($user->email, $jsonData['email']);

    }

    public function testToggleFollow() {

        $following = User::factory()->create();

        $user1 = User::factory()->create();

        $user2 = User::factory()->create();

        $url = $this->_getApiRoute() . 'users/' . $following->id . '/follow';

        $response = $this->withHeaders([
            'Authorization' => $this->getAccessToken($user1),
        ])->post($url, []);

        $this->assertEquals(201, $response->status());

        $json = $response->json();

        $jsonData = $json['data'];

        $this->assertEquals($following->id, $jsonData['following']['id']);
        $this->assertEquals($user1->id, $jsonData['follower']['id']);

        $response = $this->withHeaders([
            'Authorization' => $this->getAccessToken($user1),
        ])->post($url, []);

        $this->assertEquals(200, $response->status());

        $json = $response->json();

        $jsonData = $json['data'];

    }

}