<?php
namespace App\Facades;

use App\Invirtu\InvirtuClient;
use App\Models\Event;

class EventsFacade {

    public static function addRestream(Event $event, string $rtmp_url) {

        $organizer_token = env('INVIRTU_ORGANIZER_TOKEN', '');

        //This should be set run async
        if($organizer_token &&  $event->invirtu_id){
            $client = new InvirtuClient($organizer_token);

            $client->events->addRestream($event->invirtu_id, ['stream_url' => $rtmp_url]);
           
        }

    }

    public static function getRestreams(Event $event) {

        $organizer_token = env('INVIRTU_ORGANIZER_TOKEN', '');

        //This should be set run async
        if($organizer_token &&  $event->invirtu_id){
            $client = new InvirtuClient($organizer_token);

            $client->events->getRestreams($event->invirtu_id);
           
        }

    }

    public static function removeRestream(Event $event, string $stream_id) {

        $organizer_token = env('INVIRTU_ORGANIZER_TOKEN', '');

        //This should be set run async
        if($organizer_token &&  $event->invirtu_id){
            $client = new InvirtuClient($organizer_token);

            $client->events->removeRestream($event->invirtu_id, $stream_id, []);
           
        }

    }

    
}