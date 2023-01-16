<?php
namespace App\Facades;
use App\Enums\Roles;
use App\Models\Competition;
use App\Models\CompetitionTicketPurchase;
use App\Models\CompetitionUser;
use App\Models\Event;
use App\Models\EventUser;
use App\Models\Team;
use App\Models\TeamUser;
use App\Models\User;

class PermissionsFacade {

    //Event Permissions
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

    public static function eventCanJoinVideoSession(Event $event, User $user) {

        $eventUser = self::_retrieveEventUser($event, $user);

        if(!$eventUser) {
            return false;
        }

        if($eventUser->user_role == Roles::SuperAdministrator || $eventUser->user_role == Roles::Administrator || $eventUser->user_role == Roles::Moderator || $eventUser->user_role == Roles::Speaker) {
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

    //Competition Permission
    public static function competitionCanUpdate(Competition $competition, User $user) {

        $competitionUser = self::_retrieveCompetitionUser($competition, $user);

        if(!$competitionUser) {
            return false;
        }

        if($competitionUser->user_role == Roles::SuperAdministrator || $competitionUser->user_role == Roles::Administrator) {
            return true;
        }

        return false;
    }

    public static function competitionCanJoinVideoSession(Competition $competition, User $user) {

        $competitionUser = self::_retrieveCompetitionUser($competition, $user);

        if(!$competitionUser) {
            return false;
        }

        if($competitionUser->user_role == Roles::SuperAdministrator || $competitionUser->user_role == Roles::Administrator || $competitionUser->user_role == Roles::Moderator || $competitionUser->user_role == Roles::Speaker) {
            return true;
        }

        return false;
    }

    public static function competitionCanAccessTicketPurchase(CompetitionTicketPurchase $purchase, string $token = null, User $user = null) {

        $can_access = false;


        return $can_access;
        
    }

    public static function competitionCanModifyTicketPurchase() {
        
    }


    public static function competitionCanEngage(Competition $competition, User $user) {

        $competitionUser = self::_retrieveCompetitionUser($competition, $user);

        if(!$competitionUser) {
            return true;
        }

        if($competitionUser->user_role == Roles::Blocked) {
            return false;
        }

        return true;

    }

    //Team Permission

    public static function teamCanUpdate(Team $team, User $user) {

        $teamUser = self::_retrieveTeamUser($team, $user);

        if(!$teamUser) {
            return false;
        }

        if($teamUser->user_role == Roles::SuperAdministrator || $teamUser->user_role == Roles::Administrator) {
            return true;
        }

        return false;
    }

    public static function teamCanJoinVideoSession(Team $team, User $user) {

        $teamUser = self::_retrieveTeamUser($team, $user);

        if(!$teamUser) {
            return false;
        }

        if($teamUser->user_role == Roles::SuperAdministrator || $teamUser->user_role == Roles::Administrator || $teamUser->user_role == Roles::Moderator || $teamUser->user_role == Roles::Speaker) {
            return true;
        }

        return false;
    }


    public static function teamCanEngage(Team $team, User $user) {

        $teamUser = self::_retrieveTeamUser($team, $user);

        if(!$teamUser) {
            return true;
        }

        if($teamUser->user_role == Roles::Blocked) {
            return false;
        }

        return true;

    }


    protected static function _retrieveEventUser(Event $event, User $user) {

        return EventUser::where('event_id', $event->id)
        ->where('user_id', $user->id)
        ->first();
    }

    protected static function _retrieveCompetitionUser(Competition $competition, User $user) {

        return CompetitionUser::where('competition_id', $competition->id)
        ->where('user_id', $user->id)
        ->first();
    }

    protected static function _retrieveTeamUser(Team $team, User $user) {

        return TeamUser::where('team_id', $team->id)
        ->where('user_id', $user->id)
        ->first();
    }
}