<?php

namespace App\Services\Payment;

interface PaymentInterface {

    public function charge(array $data = []);

    public function refund(array $data = []);


}