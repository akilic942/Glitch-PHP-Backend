<?php
namespace App\Facades;

use App\Enums\Roles;
use App\Enums\Widgets;
use App\Invirtu\InvirtuClient;
use App\Models\Event;
use App\Models\EventInvite;
use Carbon\Carbon;

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

    /**
     * Wigets can be enabled and disabled for users based on the their. Pass in the 
     * roles you want to enable or disable a user for.
     */
    public static function enableWidget(Event $event, string $widget_id, array $roles) {

        $organizer_token = env('INVIRTU_ORGANIZER_TOKEN', '');

        //This should be set run async
        if($organizer_token &&  $event->invirtu_id){
            $client = new InvirtuClient($organizer_token);

            $data = [];

            foreach($roles as $role) {
                
                if($role == Roles::SuperAdministrator || $role == Roles::Administrator || $role == Roles::Moderator) {
                    $data['accessible_for_host'] = 1;
                }

                if($role == Roles::Speaker) {
                    $data['accessible_for_panelist'] = 1;
                }

                if($role == Roles::Subscriber) {
                    $data['accessible_for_streamer'] = 1;
                }
            }

            
            return $client->events->updateWidgets($event->invirtu_id, $widget_id, $data);
           
        }
    }

    /**
     * Disables the widget for all roles
     */
    public static function disableWidget(Event $event, string $widget_id) {

        $organizer_token = env('INVIRTU_ORGANIZER_TOKEN', '');

        //This should be set run async
        if($organizer_token &&  $event->invirtu_id){
            $client = new InvirtuClient($organizer_token);

            $data = [
                'accessible_for_host' => 0,
                'accessible_for_panelist' => 0,
                'accessible_for_participants' => 0,
                'accessible_for_streamer' => 0,
                'accessible_for_broadcaster' => 0
            ];

            return $client->events->updateWidgets($event->invirtu_id, $widget_id, $data);
           
        }
        
    }

    /**
     * Livestream mode is whent the user is going to livestream through an
     * RTMP source. The widgets should be changed to reflect that.
     */
    public static function setToLivestreamMode(Event $event) {

        $results = [
            self::disableWidget($event, Widgets::BROADCAST_BUTTON),
           
            self::enableWidget($event, Widgets::RECORD_BUTTON, [Roles::Moderator]),
            
            //self::disableWidget($event, Widgets::SHARE_DESKTOP),
            //self::enableWidget($event, Widgets::LIVESTREAM_EMBED, [Roles::Moderator]),
        ];


        return $results;

    }

    /**
     * Broadcast mode is when the user is going to screenshare and broadcast a stream from their computer. 
     * The widgets should be changed to reflect that.
     */
    public static function setToBroadcastMode(Event $event) {

        $results = [
            self::disableWidget($event, Widgets::RECORD_BUTTON),
            self::enableWidget($event, Widgets::BROADCAST_BUTTON, [Roles::Moderator]),

            //self::disableWidget($event, Widgets::LIVESTREAM_EMBED),
            //self::enableWidget($event, Widgets::SHARE_DESKTOP, [Roles::Moderator]),

        ];

        return $results;
        
    }

    /**
     * All events that haven't had a check-in in 5 minutes are no longer
     * considered live
     */
    public static function checkIfLive() {

        Event::where('live_last_checkin', '>=', Carbon::now()->addMinutes(5)->toDateTimeString())
        ->update(array('live_last_checkin' => null, 'is_live' => 0));
 
     }


    public static function sendInvite(EventInvite $invite) {

    }

    
}