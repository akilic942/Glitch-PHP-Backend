<?php 

namespace App\Enums;

use BenSampo\Enum\Enum;

final class Messages extends Enum {
    
    const ERROR_COMPETITION_BANNED = "You have been banned from registering.";
    const ERROR_COMPETITION_TEAM_MAX_REGISTRATIONS = "The maximun amount of teams have registered.";
    const ERROR_COMPETITION_TEAM_ALREADY_REGISTERED = "The team is already registered.";
    const ERROR_COMPETITION_USER_MAX_REGISTRATIONS = "The maximun amount of users have registered.";
    const ERROR_COMPETITION_USER_ALREADY_REGISTERED = "The user is already registered.";
    
    
}