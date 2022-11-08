<?php
namespace App\Facades;
use App\Enums\Roles;
use App\Models\Event;
use App\Models\EventUser;
use App\Models\User;

class PermissionsFacade {

    public static function eventCanUpdate(Event $event, User $user) {

        $eventUser = self::_retrieveEventUser($event, $user);

        if(!$eventUser) {
            return false;
        }

        if($eventUser->user_role == Roles::SuperAdministrator || $eventUser->user_role == Roles::Administrator) {
            return true;
        }

        return false;
    }

    public static function eventCanEngage(Event $event, User $user) {

        $eventUser = self::_retrieveEventUser($event, $user);

        if(!$eventUser) {
            return true;
        }

        if($eventUser->user_role == Roles::Blocked) {
            return false;
        }

        return true;

    }

    protected static function _retrieveEventUser(Event $event, User $user) {

        return EventUser::where('event_id', $event->id)
        ->where('user_id', $user->id)
        ->first();
    }
}