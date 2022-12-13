<?php 

namespace App\Enums;

use BenSampo\Enum\Enum;

final class AddressLocationType extends Enum {
    
    const VIRTUAL = 1;

    const IN_PERSON = 2;
    
    const HYBRID = 3;
    
}