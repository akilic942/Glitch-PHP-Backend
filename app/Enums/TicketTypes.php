<?php

namespace App\Enums;

use BenSampo\Enum\Enum;


final class TicketTypes extends Enum {

    //Regulr ticket
    const PAID = 1;

    //Ticket for a entire single day
    const FREE = 2;

    //Ticket for a pass of selected events
    const DONATION = 3;

    
}