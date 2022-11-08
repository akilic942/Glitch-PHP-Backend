<?php

namespace Tests\Facades;

use App\Facades\AuthenticationFacade;


use App\Models\User;
use Tests\TestCase;

class AuthenticationFacadeTest extends TestCase {

    public function testOneTimeLoginSuccess() {

        $user = User::factory()->create();

        $this->assertNull($user->one_time_login_token);
        $this->assertNull($user->one_time_login_token_date);

        $userReturned = AuthenticationFacade::createOneTimeLoginToken($user);

        $this->assertEquals($user->id, $userReturned->id);
        $this->assertNotNull($userReturned->one_time_login_token);
        $this->assertNotNull($userReturned->one_time_login_token_date);

        $userAuthenticate = AuthenticationFacade::useOneTimeLoginToken($userReturned->one_time_login_token);


        $this->assertEquals($user->id, $userAuthenticate->id);
        $this->assertNull($userAuthenticate->one_time_login_token);
        $this->assertNull($userAuthenticate->one_time_login_token_date);
    }
}