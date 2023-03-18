<?php
namespace App\Facades;
use App\Enums\Roles;
use App\Models\Competition;
use App\Models\CompetitionTicketPurchase;
use App\Models\CompetitionUser;
use App\Models\Event;
use App\Models\EventTicketPurchase;
use App\Models\EventUser;
use App\Models\Team;
use App\Models\TeamUser;
use App\Models\User;
use App\Models\UserRole;
use App\Models\Waitlist;

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

    public static function eventCanAccessTicketPurchase(EventTicketPurchase $purchase, string $token = null, User $user = null) {

        $can_access = false;

        if($token && ($purchase->access_token == $token || $purchase->admin_token == $token)) {
            $can_access = true;
        }

        if(!$can_access && $user && $user->id == $purchase->user_id) {
            $can_access = true;
        }

        if(!$can_access && $user) {

            $event = $purchase->ticketType->event;

            $can_access = self::eventCanUpdate($event, $user);
        }

        return $can_access;
        
    }

    public static function eventCanAdminTicketPurchase(EventTicketPurchase $purchase, string $token = null, User $user = null) {

        $can_access = false;

        if($token && $purchase->admin_token == $token) {
            $can_access = true;
        }
        
        if(!$can_access && $user) {

            $event = $purchase->ticketType->event;

            $can_access = self::eventCanUpdate($event, $user);
        }

        return $can_access;
        
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

        if($token && ($purchase->access_token == $token || $purchase->admin_token == $token)) {
            $can_access = true;
        }

        if(!$can_access && $user && $user->id == $purchase->user_id) {
            $can_access = true;
        }

        if(!$can_access && $user) {

            $competition = $purchase->ticketType->competition;

            $can_access = self::competitionCanUpdate($competition, $user);
        }

        return $can_access;
        
    }

    public static function competitionCanAdminTicketPurchase(CompetitionTicketPurchase $purchase, string $token = null, User $user = null) {

        $can_access = false;

        if($token && $purchase->admin_token == $token) {
            $can_access = true;
        }
        
        if(!$can_access && $user) {

            $competition = $purchase->ticketType->competition;

            $can_access = self::competitionCanUpdate($competition, $user);
        }

        return $can_access;
        
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

    public static function waitListCanUpdate(Waitlist $waitlist, User $user) {

        $roles = self::_retriveUserRoles($user);

        $role_found = false;

        foreach($roles as $current_role) {

            if($current_role->user_role == Roles::SuperAdministrator || $current_role->user_role == Roles::Administrator) {
                $role_found = true;
            }

        }


        return $role_found;
    }

    /**
     * Checks if a user belongs to one of the site wide roles.
     * 
     * @param User $user The user to check the fole
     * @param string $role The 
     * 
     * @return boolean
     */
    public static function userHasSiteRole(User $user, string $role) : bool {

        $roles = self::_retriveUserRoles($user);

        $role_found = false;

        foreach($roles as $current_role) {

            if($current_role->user_role == $role) {
                $role_found = true;
            }

        }

        return $role_found;

    }

    /**
     * Determines if a user belongs to a role. Multiple roles can be passed to this function.
     * 
     * @param User $user The user whose role is being checked
     * @param array $roles A list of roles from the Roles Enum
     * 
     * @return boolean
     */
    public static function userHasMultipltSiteRoles(User $user, array $roles = []) : bool {

        $found_roles = self::_retriveUserRoles($user);

        $role_found = false;

        foreach($found_roles as $current_role) {

            if(in_array($current_role->user_role, $roles)) {
                $role_found = true;
            }

        }

        return $role_found;

    }

    protected static function _retriveUserRoles(User $user) {

        return UserRole::where('user_id', $user->id)
        ->get();

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