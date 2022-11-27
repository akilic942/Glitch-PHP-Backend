<?php
namespace App\Facades;

use App\Enums\Roles;
use App\Enums\Widgets;
use App\Invirtu\InvirtuClient;
use App\Models\Event;
use App\Models\EventInvite;
use App\Models\EventOverlay;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

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
                    $data['accessible_for_broadcaster'] = 1;
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

        return false;
        
    }

    public static function setWidgetEnvOptions(Event $event, string $widget_id, array $options) {

        $organizer_token = env('INVIRTU_ORGANIZER_TOKEN', '');

        //This should be set run async
        if($organizer_token &&  $event->invirtu_id){
            $client = new InvirtuClient($organizer_token);

            return $client->events->setWidgetEnvironmentVariable($event->invirtu_id, $widget_id, $options);
           
        }

        return false;
    }

    public static function activateOverlay(Event $event, EventOverlay $overlay) {

        //Set all overally for event to inactive
        EventOverlay::where('event_id', $event->id)->update(['is_active' => 0]);

        //First set the overlay image
        $image_url = $overlay->getImageUrl();

        //Update the Widget
        self::setWidgetEnvOptions($event, Widgets::GLITCH_FULL_SCREEN_OVERLAY, ['key' => 'overlay_image', 'value' => $image_url]);

        //Activate Widget For Everyone
        self::enableWidget($event, Widgets::GLITCH_FULL_SCREEN_OVERLAY, [Roles::Administrator, Roles::Speaker, Roles::Moderator, Roles::Subscriber]);

        $overlay->forceFill(['is_active' => 1]);
        
        $overlay->save();

        return $overlay;
        
    }

    public static function deactivateOverlay(Event $event){

          //Set all overally for event to inactive
          EventOverlay::where('event_id', $event->id)->update(['is_active' => 0]);

          //Activate Widget
          self::disableWidget($event, Widgets::GLITCH_FULL_SCREEN_OVERLAY);

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

     public static function sendOnScreenMessage(Event $event, string $message, array $options = []) {

        $organizer_token = env('INVIRTU_ORGANIZER_TOKEN', '');

        //This should be set run async
        if($organizer_token &&  $event->invirtu_id){
            $client = new InvirtuClient($organizer_token);

            $data = [
                'message' => $message
            ];

            $data += $options;

            return $client->events->sendOnScreenMessage($event->invirtu_id, $data);
           
        }
    }

    public static function updateInvirtuEvent(Event $event, array $data) {

        $organizer_token = env('INVIRTU_ORGANIZER_TOKEN', '');

        //This should be set run async
        if($organizer_token &&  $event->invirtu_id){
            $client = new InvirtuClient($organizer_token);

            return $client->events->update($event->invirtu_id, $data);
           
        }

        return false;


    }

    /**
     * @todo Eventually, this should work with a themes model
     * that the user passes in their own themes
     */
    public static function createDefaultOverlays(Event $event) {

        $overlays = [];

        //Offline Line
        if(Storage::disk('local')->exists('public/banners/esports/offline_banner_1920x1080px.jpeg')){

            $filename = 'overlays/default_offline_overlay_' . $event->id;

            Storage::disk('s3')->put($filename, Storage::disk('local')->get('public/banners/esports/offline_banner_1920x1080px.jpeg'), 'public');

            $data = [
                'event_id' => $event->id,
                'label' => 'Currently Offline',
                'image_url' =>   Storage::disk('s3')->path($filename),          
            ];

            $overlay = EventOverlay::create($data);

            if($overlay) {
                $overlays[] = $overlay;
            }
        }

        //Startng Soon
        if(Storage::disk('local')->exists('public/banners/esports/starting_soon 1920x1080px.jpeg')){

            $filename = 'overlays/default_starting_soon_overlay_' . $event->id;

            Storage::disk('s3')->put($filename, Storage::disk('local')->get('public/banners/esports/starting_soon 1920x1080px.jpeg'), 'public');

            $data = [
                'event_id' => $event->id,
                'label' => 'Starting Soon',
                'image_url' =>   Storage::disk('s3')->path($filename),          
            ];

            $overlay = EventOverlay::create($data);

            if($overlay) {
                $overlays[] = $overlay;
            }
        }

        //Intermission
        if(Storage::disk('local')->exists('public/banners/esports/intermission_1920x1080px.jpeg')){

            $filename = 'overlays/default_intermission_overlay_' . $event->id;

            Storage::disk('s3')->put($filename, Storage::disk('local')->get('public/banners/esports/intermission_1920x1080px.jpeg'), 'public');

            $data = [
                'event_id' => $event->id,
                'label' => 'Intermission',
                'image_url' =>   Storage::disk('s3')->path($filename),          
            ];

            $overlay = EventOverlay::create($data);

            if($overlay) {
                $overlays[] = $overlay;
            }
        }

        //Be Right Right
        if(Storage::disk('local')->exists('public/banners/esports/be_right_back_1920x1080px.jpeg')){

            $filename = 'overlays/default_be_right_back_overlay_' . $event->id;

            Storage::disk('s3')->put($filename, Storage::disk('local')->get('public/banners/esports/be_right_back_1920x1080px.jpeg'), 'public');

            $data = [
                'event_id' => $event->id,
                'label' => 'Be Right Back',
                'image_url' =>   Storage::disk('s3')->path($filename),          
            ];

            $overlay = EventOverlay::create($data);

            if($overlay) {
                $overlays[] = $overlay;
            }
        }

        //Be Right Right
        if(Storage::disk('local')->exists('public/banners/esports/finished_1920x1080px.jpeg')){

            $filename = 'overlays/default_finished_overlay_' . $event->id;

            Storage::disk('s3')->put($filename, Storage::disk('local')->get('public/banners/esports/finished_1920x1080px.jpeg'), 'public');

            $data = [
                'event_id' => $event->id,
                'label' => 'Finished',
                'image_url' =>   Storage::disk('s3')->path($filename),          
            ];

            $overlay = EventOverlay::create($data);

            if($overlay) {
                $overlays[] = $overlay;
            }
        }

        return $overlays;
    }


    
}