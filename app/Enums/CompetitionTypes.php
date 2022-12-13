<?php 

namespace App\Enums;

use BenSampo\Enum\Enum;

final class CompetitionTypes extends Enum {
    
    const SINGLE_ELIMINATION = 1;

    const DOUBLE_ELIMINATION = 2;

    const MULTILEVEL = 3;

    const STRAIGHT_ROUND_ROBIN = 4; 

    const ROUND_ROBIN_DOUBLE_SPLIT = 5;

    const ROUND_ROBIN_TRIPLE_SPLIT = 6;

    const ROUND_ROBIN_QUADRUPLE_SPLIT = 7;

    const SEMI_ROUND_ROBINS = 8;

    const EXTENDED = 9;
    
}