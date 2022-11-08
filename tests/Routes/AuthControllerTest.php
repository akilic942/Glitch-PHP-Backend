<?php

namespace Tests\Routes;

use App\Models\User;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{

    public function testRegister()
    {

        $url = $this->_getApiRoute() . 'auth/register';

        $response = $this->withHeaders([
            'X-Header' => 'Value',
        ])->post($url, ['name' => 'Sally']);

        $this->assertEquals(422, $response->status());

        $faker = \Faker\Factory::create();

        $data = array(
            'first_name' => $faker->firstName(),
            'last_name' => $faker->lastName(),
            'email' => $faker->email,
            'username' => $faker->userName,
            'password' => $faker->password(),
        );

        $response = $this->withHeaders([
            'X-Header' => 'Value',
        ])->post($url, $data);

        $this->assertEquals(201, $response->status());

        $json = $response->json();
        $this->assertEquals($json['data']['email'], $data['email']);
        $this->assertNotNull($json['data']['token']['access_token']);
    }

    public function testLogin() {

        $url = $this->_getApiRoute() . 'auth/login';

        $faker = \Faker\Factory::create();

        $password = $faker->regexify('[A-Za-z0-9]{20}');

        $user = User::factory()->create(['password' => bcrypt($password)]);

        $response = $this->withHeaders([
            'X-Header' => 'Value',
        ])->post($url, ['email' => $user->email, 'password' => 'abc123']);

        $this->assertEquals(401, $response->status());

        $response = $this->withHeaders([
            'X-Header' => 'Value',
        ])->post($url, ['email' => $user->email, 'password' => $password]);

        $this->assertEquals(200, $response->status());

        $json = $response->json();
        $this->assertEquals($json['data']['id'], $user->id);
        $this->assertEquals($json['data']['email'], $user->email);
        $this->assertNotNull($json['data']['token']['access_token']);

    }
}
