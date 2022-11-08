<?php
namespace Tests\Unit;

use App\Models\Message;
use App\Models\MessageThread;
use App\Models\MessageThreadParticapant;
use App\Models\User;
use Tests\TestCase;

class MessageThreadTest extends TestCase {


    public function testUsers() {

        $thread1 = MessageThread::factory()->create();

        $thread2 = MessageThread::factory()->create();

        $this->assertCount(0, $thread1->users);

        $this->assertCount(0, $thread2->users);

        $user1 = User::factory()->create();

        $user2 = User::factory()->create();

        $user3 = User::factory()->create();

        $user4 = User::factory()->create();

        MessageThreadParticapant::factory()->create([
            'user_id' => $user1->id,
            'thread_id' => $thread1->id
        ]);

        MessageThreadParticapant::factory()->create([
            'user_id' => $user2->id,
            'thread_id' => $thread1->id
        ]);

        MessageThreadParticapant::factory()->create([
            'user_id' => $user2->id,
            'thread_id' => $thread2->id
        ]);

        MessageThreadParticapant::factory()->create([
            'user_id' => $user3->id,
            'thread_id' => $thread2->id
        ]);

        MessageThreadParticapant::factory()->create([
            'user_id' => $user4->id,
            'thread_id' => $thread2->id
        ]);

        $thread1->refresh();

        $thread2->refresh();

        $this->assertCount(2, $thread1->users);

        $this->assertCount(3, $thread2->users);
    }

    public function testMessages() {

        $thread1 = MessageThread::factory()->create();

        $thread2 = MessageThread::factory()->create();

        $this->assertCount(0, $thread1->messages);

        $this->assertCount(0, $thread2->messages);

        $message1 = Message::factory()->create(['thread_id' => $thread1->id]);

        $message2 = Message::factory()->create(['thread_id' => $thread1->id]);

        $message3 = Message::factory()->create(['thread_id' => $thread2->id]);
        
        $message4 = Message::factory()->create(['thread_id' => $thread2->id]);

        $message5 = Message::factory()->create(['thread_id' => $thread2->id]);

        $thread1->refresh();

        $thread2->refresh();

        $this->assertCount(2, $thread1->messages);

        $this->assertCount(3, $thread2->messages);
    }
}