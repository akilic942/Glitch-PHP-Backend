<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompetitionTicketPurchaseTransaction extends BaseModel
{
    use HasFactory;

    protected $casts = [
        'id' => 'string',
        'transaction_voided' => 'boolean',
        'transaction_successful' => 'boolean',
    ];

    protected $rules = array(
        'purchase_id' => 'required|uuid',
        'payment_processor' => 'required|integer|min:1|max:6',
        'payment_or_refund' => 'required|integer|min:1|max:2',

        'transaction_id' => 'string|min:0|max:255',
        'transaction_to_currency' => 'string|min:0|max:255',
        'transaction_from_currency' => 'string|min:0|max:255',

        'transaction_conversion_rate' => 'numeric',
        'transaction_amount' => 'numeric',
        'transaction_processing_fee' => 'numeric',

        'transaction_successful' => 'boolean',
        'transaction_voided' => 'boolean',
    );


    protected function meta(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => json_decode($value, true),
            set: fn ($value) => json_encode($value),
        );
    } 

}
