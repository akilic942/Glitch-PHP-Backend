<?php

namespace Tests\Routes;

use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class ImageControllerTest extends TestCase
{


    

    public function testCreation(){

        $url = $this->_getApiRoute() . 'images/upload';

        /*$response = $this->withHeaders([
            'Authorization' => $this->getAccessToken(),
        ])->post($url);

        print_r($response->getContent());

        $this->assertEquals(400, $response->status());*/

        $data = [
            'image' => UploadedFile::fake()->image('avatar.png')
        ];
        
        $response = $this->withHeaders([
            'Authorization' => $this->getAccessToken(),
        ])->post($url, $data);

        //print_r($response->getContent());

        $this->assertEquals(201, $response->status());

        $json = $response->json();

        //print_r($json);

        //$post = $json['data'];

        //print_r($post);

        //$this->assertEquals($post['content'], $data['content']);
        //$this->assertEquals($post['user']['id'], $data['user_id']);

    }

    

}