<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * When applying to a team, tournmanet, etc, this class will
 * standardize the status of application.
 */
final class AcceptanceStatus extends Enum {

    const UNAPPROVED = 0;

    const APPROVED = 1;

    const IN_REVIEW = 2;

    const PENDING = 3;

    const REQUIRE_MORE_INFORMATION =4;

    const DENIED =5;

    const BANNED =6;

    const PROBATION =7;
}