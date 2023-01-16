<?php

namespace App\Enums;

use BenSampo\Enum\Enum;


final class TransactionProcessors extends Enum {

    const NONE = 0;

    const CASH = 1;

    const CHECK= 2;

    const STRIPE = 2;

    const BRAINTREE = 3;

    const PAYPAL = 4;

    const FLUTTERWAVE = 5;

    
}