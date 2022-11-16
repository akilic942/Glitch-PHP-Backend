<?php

namespace Tests\Routes;

use App\Facades\AuthenticationFacade;
use App\Facades\UsersFacade;
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

    public function testOneTimeLoginWithToken() {

        $url = $this->_getApiRoute() . 'auth/oneTimeLoginWithToken';

        $faker = \Faker\Factory::create();

        $password = $faker->regexify('[A-Za-z0-9]{20}');

        $user = User::factory()->create(['password' => bcrypt($password)]);

        $user = AuthenticationFacade::createOneTimeLoginToken($user);

        $response = $this->withHeaders([
            'X-Header' => 'Value',
        ])->post($url, []);

        $this->assertEquals(422, $response->status());

        $response = $this->withHeaders([
            'X-Header' => 'Value',
        ])->post($url, ['token' => $password]);

        $this->assertEquals(422, $response->status());

        $response = $this->withHeaders([
            'X-Header' => 'Value',
        ])->post($url, ['token' => $user->one_time_login_token]);

        $this->assertEquals(200, $response->status());

        $json = $response->json();
        $this->assertEquals($json['data']['id'], $user->id);
        $this->assertEquals($json['data']['email'], $user->email);
        $this->assertNotNull($json['data']['token']['access_token']);

    }


    public function testForgotPassword() {

        $url = $this->_getApiRoute() . 'auth/forgotpassword';

        $faker = \Faker\Factory::create();

        $fake_email = $faker->unique()->safeEmail();

        $user = User::factory()->create();

        $response = $this->withHeaders([
            'X-Header' => 'Value',
        ])->post($url, ['email' => $fake_email]);

        $this->assertEquals(302, $response->status());

        $response = $this->withHeaders([
            'X-Header' => 'Value',
        ])->post($url, ['email' => $user->email]);


        $this->assertEquals(201, $response->status());

    }

    public function testRestPassword() {

        $url = $this->_getApiRoute() . 'auth/resetpassword';

        $faker = \Faker\Factory::create();

        $fake_email = $faker->unique()->safeEmail();

        $user_without_reset = User::factory()->create();

        $user_with_reset = User::factory()->create();

        $password = UsersFacade::sendPasswordReset($user_with_reset->email);

        $response = $this->withHeaders([
            'X-Header' => 'Value',
        ])->post($url, ['email' => $fake_email, 'new_password' => 'sdfsdsf', 'token' => $password->token]);

        $this->assertEquals(422, $response->status());

        $response = $this->withHeaders([
            'X-Header' => 'Value',
        ])->post($url, ['email' => $user_without_reset, 'new_password' => 'sdfsdsf', 'token' => $password->token]);

        $this->assertEquals(422, $response->status());

        $response = $this->withHeaders([
            'X-Header' => 'Value',
        ])->post($url, ['email' => $user_with_reset->email, 'new_password' => 'sdfsdsf', 'token' => $password->token]);

        $this->assertEquals(201, $response->status());

    }
}
