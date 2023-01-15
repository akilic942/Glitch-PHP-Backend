<?php

namespace App\Enums;

use BenSampo\Enum\Enum;


final class TicketTypes extends Enum {

    const VISIBLE = 1;

    const HIDDEN = 2;

    const HIDDEN_WHEN_NO_SALE = 3;

    //Show and hides based on dates
    const SCHEDULED = 4;

    
}