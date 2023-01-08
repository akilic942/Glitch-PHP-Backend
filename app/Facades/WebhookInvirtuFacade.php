<?php
namespace App\Facades;

use App\Models\Event;
use Exception;

class WebhookInvirtuFacade {


    public static function process(string $action, array $data = []) {

        if($action == 'broadcasting_recording_finished') {
            return self::_broadcastEnded($data);
        }

        throw new Exception('Action was not found.');
    }

    private static function _broadcastEnded(array $data = []) {


        $event_id  = (isset($data['event_id'])) ? $data['event_id'] : null;

        if($event_id) {

            $event = Event::where('invirtu_id', $event_id)->first();

            if($event) {

                $event->forceFill(['is_live' => 0]);
                $event->save();

                return $event;
            } else {
                throw new Exception('No Event Found.');
            }
        } else {
            throw new Exception('No Event ID. Must have an event id.');
        }
    }
}