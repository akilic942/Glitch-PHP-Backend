<?php 

namespace App\Enums;

use BenSampo\Enum\Enum;

final class Messages extends Enum {
    
    const ERROR_COMPETITION_BANNED = "You have been banned from registering.";
    const ERROR_COMPETITION_TEAM_MAX_REGISTRATIONS = "The maximun amount of teams have registered.";
    const ERROR_COMPETITION_TEAM_ALREADY_REGISTERED = "The team is already registered.";
    const ERROR_COMPETITION_TEAM_TOO_SMALL = "The team size is too small to register.";
    const ERROR_COMPETITION_USER_MAX_REGISTRATIONS = "The maximun amount of users have registered.";
    const ERROR_COMPETITION_USER_ALREADY_REGISTERED = "The user is already registered.";

    const ERROR_NOT_EXIST_TICKET_TYPE = 'The ticket type does not exist.';
    const ERROR_NOT_EXIST_COMPETITION = 'The competition does not exist.';
    const ERROR_NOT_EXIST_FIELD = 'The field does not exist.';
    const ERROR_NOT_EXIST_SECTION = 'The section does not exist.';
    const ERROR_NOT_EXIST_EVENT = 'The event does not exist.';

    const ERROR_TICKET_QUANTITY_NOT_OVER_MIN = 'The ticket quantity must be over the minimum';
    const ERROR_TICKET_QUANTITY_OVER_MAX = 'The ticket quantity is over the maximum allowed';
    const ERROR_TICKET_QUANTITY_NONE_AVAILABLE = 'There are no more tickets available.';
    
    
}