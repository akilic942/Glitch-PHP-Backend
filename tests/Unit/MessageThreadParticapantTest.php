<?php
namespace Tests\Unit;

use App\Models\Message;
use App\Models\MessageThread;
use App\Models\MessageThreadParticapant;
use App\Models\User;
use Tests\TestCase;

class MessageThreadParticapantTest extends TestCase {

    public function testCreation() {

        $user = User::factory()->create();

        $thread = MessageThread::factory()->create();

        $data = array(
            'thread_id' => $thread->id,
            'user_id' => $user->id,
        );

        $particanpant = new MessageThreadParticapant();

        $tmp_field = $data['user_id'];
        unset($data['user_id']);
        $this->assertFalse($particanpant->validate($data));
        $data['user_id'] = $tmp_field;

        $tmp_field = $data['thread_id'];
        unset($data['thread_id']);
        $this->assertFalse($particanpant->validate($data));
        $data['thread_id'] = $tmp_field;


        $this->assertTrue($particanpant->validate($data));

        $particanpant = MessageThreadParticapant::create($data);

        $this->assertNotNull($particanpant->user_id);
        $this->assertNotNull($particanpant->thread_id);

    }

    public function testUser() {

        $user = User::factory()->create();

        $particanpant = MessageThreadParticapant::factory()->create(['user_id' => $user->id]);

        $this->assertEquals($user->id, $particanpant->user->id);

    }

    public function testThread() {

        $thread = MessageThread::factory()->create();

        $particanpant = MessageThreadParticapant::factory()->create(['thread_id' => $thread->id]);

        $this->assertEquals($thread->id, $particanpant->thread->id);

    }


}