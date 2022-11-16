<?php

namespace Tests\Facades;

use App\Facades\UsersFacade;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Support\Str;

class UsersFacadeTest extends TestCase {

    public function testRetrieveOrCreate() {

        $faker = \Faker\Factory::create();

        $email = $faker->unique()->safeEmail();

        $user = User::where('email', $email)->first();

        $this->assertNull($user);

        $data = [
            'first_name' => $faker->firstName(),
            'last_name' => $faker->lastName(),
            'email' => $email,
            'username' => $faker->userName,
        ];

        $compareUser = UsersFacade::retrieveOrCreate($email, $data['first_name'], $data['last_name'], $data['username']);

        $this->assertEquals($email, $compareUser->email);
        $this->assertEquals($data['first_name'], $compareUser->first_name);
        $this->assertEquals($data['last_name'], $compareUser->last_name);
        $this->assertEquals($data['username'], $compareUser->username);

        $user = User::where('email', $email)->first();
        $this->assertNotNull($user);
        $this->assertEquals($user->id, $compareUser->id);

        $compareUser2 = UsersFacade::retrieveOrCreate($email, $data['first_name'], $data['last_name'], $data['username']);
        $this->assertEquals($compareUser2->id, $compareUser->id);
    }

    public function testSendPasswordReset() {

        $user = User::factory()->create();

        $password = UsersFacade::sendPasswordReset($user->email);

        $this->assertEquals($user->email, $password->email);

    }

    public function testResetPassword() {

        $user = User::factory()->create();

        $password = UsersFacade::sendPasswordReset($user->email);

        $old_password = $user->password;

        $new_password = Str::random();

        UsersFacade::resetPassword($password, $new_password);

        $user->refresh();

        $this->assertNotEquals($old_password, $user->password);


    }
}