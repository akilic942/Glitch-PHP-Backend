<?php

namespace Tests\Unit;


use App\Models\User;
use Tests\TestCase;

class UserTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testCreation()
    {
        $faker = \Faker\Factory::create();

        $data = array(
            'first_name' => $faker->firstName(),
            'last_name' => $faker->lastName(),
            'email' => $faker->email,
            'username' => $faker->userName,
            'password' => $faker->password(),
        );

        $user = new User();

        $tmp_field = $data['first_name'];
        unset($data['first_name']);
        $this->assertFalse($user->validate($data));
        $data['first_name'] = $tmp_field;

        $tmp_field = $data['last_name'];
        unset($data['last_name']);
        $this->assertFalse($user->validate($data));
        $data['last_name'] = $tmp_field;

        $tmp_field = $data['email'];
        unset($data['email']);
        $this->assertFalse($user->validate($data));
        $data['email'] = $tmp_field;

        $tmp_field = $data['username'];
        unset($data['username']);
        $this->assertFalse($user->validate($data));
        $data['username'] = $tmp_field;

        $tmp_field = $data['password'];
        unset($data['password']);
        $this->assertFalse($user->validate($data));
        $data['password'] = $tmp_field;

        $this->assertTrue($user->validate($data));

        $user = User::create($data);

        $this->assertNotNull($user->id);

        $user->refresh();

        /*$this->assertNotNull($user->invirtu_user_id);
        $this->assertNotNull($user->invirtu_user_jwt_token);
        $this->assertNotNull($user->invirtu_user_jwt_issued);
        $this->assertNotNull($user->invirtu_user_jwt_expiration);*/

    }

}
