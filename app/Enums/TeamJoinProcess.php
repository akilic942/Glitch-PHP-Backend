<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class TeamJoinProcess extends Enum {

    //anyone can join
    const ANYONE = 1;

    //Invite Only
    const INVITE = 2;

    //Anyone can apply, but must be approved
    const APPROVAL =3;
}