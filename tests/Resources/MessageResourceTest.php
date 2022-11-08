<?php

namespace Tests\Resources;

use App\Http\Resources\MessageResource;
use App\Models\Message;
use App\Models\MessageThread;
use App\Models\User;
use Tests\TestCase;

class MessageResourceTest extends TestCase {

    public function testResource() {

        $thread = MessageThread::factory()->create();

        $user = User::factory()->create();

        $faker = \Faker\Factory::create();

        $content = $faker->paragraphs(rand(1, 10), true);

        $message = Message::factory()->create([
            'thread_id' => $thread->id,
            'user_id' => $user->id,
            'message' => $content
        ]);

        $resource = MessageResource::make($message);

        $this->assertEquals($message->id, $resource->id);
        $this->assertEquals($user->id, $resource->user->id);
        $this->assertEquals($thread->id, $resource->thread->id);
        $this->assertEquals($content, $resource->message);
    }

}