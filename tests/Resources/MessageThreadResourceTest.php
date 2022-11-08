<?php

namespace Tests\Resources;

use App\Http\Resources\MessageThreadResource;
use App\Models\Message;
use App\Models\MessageThread;
use App\Models\MessageThreadParticapant;
use Tests\TestCase;

class MessageThreadResourceTest extends TestCase {


    public function testResource() {

        $thread = MessageThread::factory()->create();

        MessageThreadParticapant::factory()->create(['thread_id' => $thread->id]);
        MessageThreadParticapant::factory()->create(['thread_id' => $thread->id]);


        Message::factory()->create(['thread_id' => $thread->id]);
        Message::factory()->create(['thread_id' => $thread->id]);
        Message::factory()->create(['thread_id' => $thread->id]);

        $resource = MessageThreadResource::make($thread);

        $this->assertEquals($thread->id, $resource->id);
        $this->assertCount(2, $resource->users);
        $this->assertCount(3, $resource->messages);

    }
}