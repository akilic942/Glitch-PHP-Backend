<?php

namespace Tests\Routes;

use App\Models\Message;
use App\Models\MessageThread;
use App\Models\MessageThreadParticapant;
use App\Models\Post;
use App\Models\PostComment;
use App\Models\User;
use Egulias\EmailValidator\Warning\Comment;
use Tests\TestCase;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\UploadedFile;

class MessageControllerTest extends TestCase
{


    public function testListThreads(){

        $thread1 = MessageThread::factory()->create();

        $thread2 = MessageThread::factory()->create();

        $thread3 = MessageThread::factory()->create();

        $user1 = User::factory()->create();

        $user2 = User::factory()->create();

        $user3 = User::factory()->create();

        MessageThreadParticapant::factory()->create(['thread_id' => $thread1->id, 'user_id' => $user1->id]);

        MessageThreadParticapant::factory()->create(['thread_id' => $thread2->id, 'user_id' => $user1->id]);

        MessageThreadParticapant::factory()->create(['thread_id' => $thread3->id, 'user_id' => $user1->id]);

        MessageThreadParticapant::factory()->create(['thread_id' => $thread1->id, 'user_id' => $user2->id]);

        MessageThreadParticapant::factory()->create(['thread_id' => $thread2->id, 'user_id' => $user2->id]);

        $url = $this->_getApiRoute() . 'messages/threads';

        $response = $this->withHeaders([
            'Authorization' => $this->getAccessToken($user1),
        ])->get($url);

        $json = $response->json();

        $threadData = $json['data'];

        $this->assertCount(3, $threadData );

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getAccessToken($user2),
        ])->get($url);

        $json = $response->json();

        $threadData = $json['data'];

        $this->assertCount(2, $threadData );

        $response = $this->withHeaders([
            'Authorization' => $this->getAccessToken($user3),
        ])->get($url);

        $json = $response->json();

        $threadData = $json['data'];

        $this->assertCount(0, $threadData );



    }

    public function testThreadCreation(){

        $url = $this->_getApiRoute() . 'messages/makeThread';

        /*$response = $this->withHeaders([
            'Authorization' => $this->getAccessToken(),
        ])->post($url);

        $this->assertEquals(422, $response->status());

        $faker = \Faker\Factory::create();*/

        $user1 = User::factory()->create();

        $user2 = User::factory()->create();

        $data = [
            'users' => [$user1->id, $user2->id],
        ];
        
        $response = $this->withHeaders([
            'Authorization' => $this->getAccessToken(),
        ])->post($url, $data);

        $this->assertEquals(201, $response->status());

        $json = $response->json();

        $post = $json['data'];

        $this->assertCount(2, $data['users']);

    }

    public function testMessageCreation() {

        $url = $this->_getApiRoute() . 'messages';

        $response = $this->withHeaders([
            'Authorization' => $this->getAccessToken(),
        ])->post($url);

        $this->assertEquals(422, $response->status());

        $thread = MessageThread::factory() -> create();

        $user1 = User::factory()->create();

        $user2 = User::factory()->create();

        MessageThreadParticapant::factory()->create(['thread_id' => $thread->id, 'user_id' => $user1->id]);

        MessageThreadParticapant::factory()->create(['thread_id' => $thread->id, 'user_id' => $user2->id]);

        $faker = \Faker\Factory::create();

        $data = [
            'message' => $faker->paragraphs(8, true),
            'thread_id' => $thread->id
        ];

        $response = $this->withHeaders([
            'Authorization' => $this->getAccessToken($user1),
        ])->post($url, $data);

        $this->assertEquals(201, $response->status());

        $thread->refresh();

        $this->assertCount(1, $thread->messages);

        $data['message'] = $faker->paragraphs(8, true);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getAccessToken($user2),
        ])->post($url, $data);

        $this->assertEquals(201, $response->status());

        $thread->refresh();

        $this->assertCount(2, $thread->messages);

    }

    public function testUpdate() {

        $user = User::factory()->create();

        $message = Message::factory()->create(['user_id' => $user->id]);

        $url = $this->_getApiRoute() . 'messages/' . $message->id;

        $faker = \Faker\Factory::create();

        $data = [
            'message' => $faker->paragraphs(8, true),
        ];

        $response = $this->withHeaders([
            'Authorization' => $this->getAccessToken($user),
        ])->put($url, $data);


        $this->assertEquals(200, $response->status());

        $json = $response->json();

        $jsonData = $json['data'];

        $this->assertEquals($message->id, $jsonData['id']);
        $this->assertEquals($jsonData['message'], $data['message']);

        $response = $this->withHeaders([
            'Authorization' => $this->getAccessToken($user),
        ])->put($url, [ 'message' => '']);


        $this->assertEquals(422, $response->status());

    }

    public function testDelete() {

        $user = User::factory()->create();

        $message = Message::factory()->create(['user_id' => $user->id]);

        $url = $this->_getApiRoute() . 'messages/' . $message->id;

        $response = $this->withHeaders([
            'Authorization' => $this->getAccessToken($user),
        ])->delete($url);

        $this->assertEquals(204, $response->status());

    }

}