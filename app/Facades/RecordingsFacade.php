<?php
namespace App\Facades;

use App\Invirtu\InvirtuClient;

class RecordingsFacade {

    public static function updateRecording(string $id, array $data){

        $organizer_token = env('INVIRTU_ORGANIZER_TOKEN', '');

        if($organizer_token){
            $client = new InvirtuClient($organizer_token);

            $client->videos->update($id, $data);
        }

    }
}