<?php
namespace App\Facades;

use App\Enums\Roles;
use App\Invirtu\InvirtuClient;
use App\Models\Competition;
use App\Models\CompetitionUser;
use App\Models\Event;
use App\Models\EventUser;
use App\Models\Team;
use App\Models\TeamUser;
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

    public static function eventMakeProducer(Event $event, User $user) : EventUser {

        $eventUser = self::_getOrCreateEventUser($event, $user);

        $eventUser->update(['user_role' => Roles::Producer]);
        
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


    //Competition Roles

    public static function competitionMakeSuperAdmin(Competition $competition, User $user) : CompetitionUser {

        $competitionUser = self::_getOrCreateCompetitionUser($competition, $user);

        $competitionUser->update(['user_role' => Roles::SuperAdministrator]);


        return $competitionUser;

    }

    public static function competitionMakeAdmin(Competition $competition, User $user) : CompetitionUser {

        $competitionUser = self::_getOrCreateCompetitionUser($competition, $user);

        $competitionUser->update(['user_role' => Roles::Administrator]);

        return $competitionUser;

    }

    public static function competitionMakeSpeaker(Competition $competition, User $user) : CompetitionUser {

        $competitionUser = self::_getOrCreateCompetitionUser($competition, $user);

        $competitionUser->update(['user_role' => Roles::Speaker]);

        return $competitionUser;

    }

    public static function competitionMakeModerator(Competition $competition, User $user) : CompetitionUser {

        $competitionUser = self::_getOrCreateCompetitionUser($competition, $user);

        $competitionUser->update(['user_role' => Roles::Moderator]);

        return $competitionUser;

    }

    public static function competitionMakeProducer(Competition $competition, User $user) : CompetitionUser {

        $competitionUser = self::_getOrCreateCompetitionUser($competition, $user);

        $competitionUser->update(['user_role' => Roles::Producer]);

        return $competitionUser;

    }

    public static function competitionMakeSubscriber(Competition $competition, User $user) : CompetitionUser {

        $competitionUser = self::_getOrCreateCompetitionUser($competition, $user);

        $competitionUser->update(['user_role' => Roles::Subscriber]);

        return $competitionUser;

    }

    public static function competitionMakeParticipant(Competition $competition, User $user) : CompetitionUser {

        $competitionUser = self::_getOrCreateCompetitionUser($competition, $user);

        $competitionUser->update(['user_role' => Roles::Participant]);

        return $competitionUser;

    }

    public static function competitionMakeBlocked(Competition $competition, User $user) : CompetitionUser {

        $competitionUser = self::_getOrCreateCompetitionUser($competition, $user);

        $competitionUser->update(['user_role' => Roles::Blocked]);

        $organizer_token = env('INVIRTU_ORGANIZER_TOKEN', '');


        return $competitionUser;

    }

    public static function competitionMakeNone(Competition $competition, User $user) : CompetitionUser {

        $competitionUser = self::_getOrCreateCompetitionUser($competition, $user);

        $competitionUser->update(['user_role' => Roles::NONE]);

        $organizer_token = env('INVIRTU_ORGANIZER_TOKEN', '');


        return $competitionUser;

    }

    //Team

    public static function teamMakeSuperAdmin(Team $team, User $user) : TeamUser {

        $teamUser = self::_getOrCreateTeamUser($team, $user);

        $teamUser->update(['user_role' => Roles::SuperAdministrator]);

        return $teamUser;

    }

    public static function teamMakeAdmin(Team $team, User $user) : TeamUser {

        $teamUser = self::_getOrCreateTeamUser($team, $user);

        $teamUser->update(['user_role' => Roles::Administrator]);

        return $teamUser;

    }

    public static function teamMakeSpeaker(Team $team, User $user) : TeamUser {

        $teamUser = self::_getOrCreateTeamUser($team, $user);

        $teamUser->update(['user_role' => Roles::Speaker]);

        return $teamUser;

    }

    public static function teamMakeModerator(Team $team, User $user) : TeamUser {

        $teamUser = self::_getOrCreateTeamUser($team, $user);

        $teamUser->update(['user_role' => Roles::Moderator]);

        return $teamUser;

    }

    public static function teamMakeProducer(Team $team, User $user) : TeamUser {

        $teamUser = self::_getOrCreateTeamUser($team, $user);

        $teamUser->update(['user_role' => Roles::Producer]);

        return $teamUser;

    }

    public static function teamMakeSubscriber(Team $team, User $user) : TeamUser {

        $teamUser = self::_getOrCreateTeamUser($team, $user);

        $teamUser->update(['user_role' => Roles::Subscriber]);

        return $teamUser;

    }

    public static function teamMakeParticpant(Team $team, User $user) : TeamUser {

        $teamUser = self::_getOrCreateTeamUser($team, $user);

        $teamUser->update(['user_role' => Roles::Participant]);

        return $teamUser;

    }

    public static function teamMakeBlocked(Team $team, User $user) : TeamUser {

        $teamUser = self::_getOrCreateTeamUser($team, $user);

        $teamUser->update(['user_role' => Roles::Blocked]);

        return $teamUser;

    }

    public static function teamMakeNone(Team $team, User $user) : TeamUser {

        $teamUser = self::_getOrCreateTeamUser($team, $user);

        $teamUser->update(['user_role' => Roles::NONE]);


        return $teamUser;

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


    protected static function _getOrCreateCompetitionUser(Competition $competition, User $user) : CompetitionUser {

        $competitionUser = CompetitionUser::where('competition_id', $competition->id)
        ->where('user_id', $user->id)
        ->first();

        if($competitionUser) {
            return $competitionUser;
        } else {

            $data = ['competition_id' => $competition->id, 'user_id' => $user->id];
            
            $competitionUser = CompetitionUser::create($data);

            return $competitionUser;
        }

    }

    protected static function _getOrCreateTeamUser(Team $team, User $user) : TeamUser {

        $teamUser = TeamUser::where('team_id', $team->id)
        ->where('user_id', $user->id)
        ->first();

        if($teamUser) {
            return $teamUser;
        } else {

            $data = ['team_id' => $team->id, 'user_id' => $user->id];
            
            $teamUser = TeamUser::create($data);

            return $teamUser;
        }

    }

}