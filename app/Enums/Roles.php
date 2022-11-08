<?php 

namespace App\Enums;

use BenSampo\Enum\Enum;

final class Roles extends Enum {
    
    const NONE = 0;
    const SuperAdministrator = 1;
    const Administrator = 2;
    const Moderator = 3;
    const Speaker = 4;
    const Subscriber = 5;
    const Blocked = 6;
    
}