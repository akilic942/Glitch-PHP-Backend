<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class SocialAction extends Enum
{
    const LIKE =   1;
    const HEART =   2;
    const UPVOTE = 3;
    const DOWNVOTE = 4;
}
