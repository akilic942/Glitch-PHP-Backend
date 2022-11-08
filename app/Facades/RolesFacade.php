<?php
namespace App\Facades;

use App\Enums\Roles;
use App\Invirtu\InvirtuClient;
use App\Models\Event;
use App\Models\EventUser;
use App\Models\User;

class RolesFacade {

    public static function eventMakeSuperAdmin(Event $event, User $user) : EventUser {

        $eventUser = self::_getOrCreateEventUser($event, $user);

        $eventUser->update(['user_role' => Roles::SuperAdministrator]);

        $organizer_token = env('INVIRTU_ORGANIZER_TOKEN', '');

        //This should be set run async
        if($organizer_token && $user->invirtu_user_id && $event->invirtu_id){
            $client = new InvirtuClient($organizer_token);

            $client->events->makeModerator($event->invirtu_id, ['account_id' => $user->invirtu_user_id]);
            $client->events->removePanelist($event->invirtu_id, ['account_id' => $user->invirtu_user_id]);
            $client->events->removeParticipant($event->invirtu_id, ['account_id' => $user->invirtu_user_id]);
        }

        return $eventUser;

    }

    public static function eventMakeAdmin(Event $event, User $user) : EventUser {

        $eventUser = self::_getOrCreateEventUser($event, $user);

        $eventUser->update(['user_role' => Roles::Administrator]);

        $organizer_token = env('INVIRTU_ORGANIZER_TOKEN', '');

        //This should be set run async
        if($organizer_token && $user->invirtu_user_id && $event->invirtu_id){
            $client = new InvirtuClient($organizer_token);

            $client->events->makeModerator($event->invirtu_id, ['account_id' => $user->invirtu_user_id]);
            $client->events->removePanelist($event->invirtu_id, ['account_id' => $user->invirtu_user_id]);
            $client->events->removeParticipant($event->invirtu_id, ['account_id' => $user->invirtu_user_id]);
        }

        return $eventUser;

    }

    public static function eventMakeSpeaker(Event $event, User $user) : EventUser {

        $eventUser = self::_getOrCreateEventUser($event, $user);

        $eventUser->update(['user_role' => Roles::Speaker]);

        $organizer_token = env('INVIRTU_ORGANIZER_TOKEN', '');

        //This should be set run async
        if($organizer_token && $user->invirtu_user_id && $event->invirtu_id){
            $client = new InvirtuClient($organizer_token);

            $client->events->removeModerator($event->invirtu_id, ['account_id' => $user->invirtu_user_id]);
            $client->events->makePanelist($event->invirtu_id, ['account_id' => $user->invirtu_user_id]);
            $client->events->removeParticipant($event->invirtu_id, ['account_id' => $user->invirtu_user_id]);
        }

        return $eventUser;

    }

    public static function eventMakeModerator(Event $event, User $user) : EventUser {

        $eventUser = self::_getOrCreateEventUser($event, $user);

        $eventUser->update(['user_role' => Roles::Moderator]);
        
        $organizer_token = env('INVIRTU_ORGANIZER_TOKEN', '');

        //This should be set run async
        if($organizer_token && $user->invirtu_user_id && $event->invirtu_id){
            $client = new InvirtuClient($organizer_token);

            $client->events->makeModerator($event->invirtu_id, ['account_id' => $user->invirtu_user_id]);
            $client->events->removePanelist($event->invirtu_id, ['account_id' => $user->invirtu_user_id]);
            $client->events->removeParticipant($event->invirtu_id, ['account_id' => $user->invirtu_user_id]);
        }

        return $eventUser;

    }

    public static function eventMakeSubscriber(Event $event, User $user) : EventUser {

        $eventUser = self::_getOrCreateEventUser($event, $user);

        $eventUser->update(['user_role' => Roles::Subscriber]);

        $organizer_token = env('INVIRTU_ORGANIZER_TOKEN', '');

        //This should be set run async
        if($organizer_token && $user->invirtu_user_id && $event->invirtu_id){
            $client = new InvirtuClient($organizer_token);

            $client->events->removeModerator($event->invirtu_id, ['account_id' => $user->invirtu_user_id]);
            $client->events->removePanelist($event->invirtu_id, ['account_id' => $user->invirtu_user_id]);
            $client->events->makeParticipant($event->invirtu_id, ['account_id' => $user->invirtu_user_id]);
        }

        return $eventUser;

    }

    public static function eventMakeBlocked(Event $event, User $user) : EventUser {

        $eventUser = self::_getOrCreateEventUser($event, $user);

        $eventUser->update(['user_role' => Roles::Blocked]);

        $organizer_token = env('INVIRTU_ORGANIZER_TOKEN', '');

        //This should be set run async
        if($organizer_token && $user->invirtu_user_id && $event->invirtu_id){
            $client = new InvirtuClient($organizer_token);

            $client->events->blockAccount($event->invirtu_id, ['account_id' => $user->invirtu_user_id]);
        }

        return $eventUser;

    }

    public static function eventMakeNone(Event $event, User $user) : EventUser {

        $eventUser = self::_getOrCreateEventUser($event, $user);

        $eventUser->update(['user_role' => Roles::NONE]);

        $organizer_token = env('INVIRTU_ORGANIZER_TOKEN', '');

        //This should be set run async
        if($organizer_token && $user->invirtu_user_id && $event->invirtu_id){
            $client = new InvirtuClient($organizer_token);

            $client->events->unblockAccount($event->invirtu_id, ['account_id' => $user->invirtu_user_id]);
        }

        return $eventUser;

    }

    protected static function _getOrCreateEventUser(Event $event, User $user) : EventUser {

        $eventUser = EventUser::where('event_id', $event->id)
        ->where('user_id', $user->id)
        ->first();

        if($eventUser) {
            return $eventUser;
        } else {

            $data = ['event_id' => $event->id, 'user_id' => $user->id];
            
            $eventUser = EventUser::create($data);

            return $eventUser;
        }

    }
}