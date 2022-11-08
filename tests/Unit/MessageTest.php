<?php

namespace Tests\Unit;

use App\Models\Message;
use App\Models\MessageImage;
use App\Models\MessageThread;
use App\Models\User;
use Tests\TestCase;

class MessageTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testCreation()
    {
        $faker = \Faker\Factory::create();

        $user = User::factory()->create();

        $thread = MessageThread::factory()->create();

        $data = array(
            'message' => $faker->paragraphs(8, true),
            'user_id' => $user->id,
            'thread_id' => $thread->id
        );

        $message = new Message();

        $tmp_field = $data['message'];
        unset($data['message']);
        $this->assertFalse($message->validate($data));
        $data['message'] = $tmp_field;

        $tmp_field = $data['user_id'];
        unset($data['user_id']);
        $this->assertFalse($message->validate($data));
        $data['user_id'] = $tmp_field;

        $tmp_field = $data['thread_id'];
        unset($data['thread_id']);
        $this->assertFalse($message->validate($data));
        $data['thread_id'] = $tmp_field;

        $this->assertTrue($message->validate($data));

        $message = Message::create($data);

        $this->assertNotNull($message->id);
    }

    public function testUser() {

        $user = User::factory()->create();

        $message = Message::factory()->create(['user_id' => $user->id]);

        $retrievedUser = $message->user;

        $this->assertEquals($user->id, $message->user_id);
        $this->assertEquals($user->id, $retrievedUser->id);
    }

    public function testThread() {

        $thread = MessageThread::factory()->create();

        $message = Message::factory()->create(['thread_id' => $thread->id]);

        $retrievedThread = $message->thread;

        $this->assertEquals($thread->id, $message->thread_id);
        $this->assertEquals($thread->id, $retrievedThread->id);
    }

}
