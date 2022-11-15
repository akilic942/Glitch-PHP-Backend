<?php

use App\Invirtu\InvirtuClient;
use App\Models\Event;
use App\Models\PasswordReset;
use App\Models\User;
use Tests\TestCase;

class PasswordResetTest extends TestCase {

    public function testCreation() {

        $faker = \Faker\Factory::create();

        $fake_email = $faker->unique()->safeEmail();

        $user = User::factory()->create();

        $data = array(
            'email' => $user->email,
            'token' => 'sdfsds'
        );

        $password = new PasswordReset();

        $tmp_field = $data['email'];
        unset($data['email']);
        $this->assertFalse($password->validate($data));
        $data['email'] = $tmp_field;

        $tmp_field = $data['token'];
        unset($data['token']);
        $this->assertFalse($password->validate($data));
        $data['token'] = $tmp_field;

        $this->assertTrue($password->validate($data));

        $password = PasswordReset::create($data);

        $this->assertEquals($password->email, $data['email']);

        $this->assertFalse($password->validate(['email'=> $fake_email]));

        //Reload with data from the observer
        $password->refresh();

        //Test to make sure invirtu data is included
        /*$this->assertNotNull($event->invirtu_id);
        $this->assertNotNull($event->invirtu_webrtc_url);
        $this->assertNotNull($event->invirtu_broadcast_url);
        $this->assertNotNull($event->invirtu_rtmp_broadcast_endpoint);
        $this->assertNotNull($event->invirtu_rtmp_broadcast_key);*/
    }

}