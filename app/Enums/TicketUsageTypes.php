<?php

namespace App\Enums;

use BenSampo\Enum\Enum;


final class TicketUsageTypes extends Enum {

    //Regulr ticket
    const REGULAR = 1;

    //Ticket for a entire single day
    const DAY_PASS = 2;

    //Ticket for a pass of selected events
    const TRACK_PASS = 3;

    //Ticket for the entire event
    const WHOLE_EVENT_PASS =4;
}