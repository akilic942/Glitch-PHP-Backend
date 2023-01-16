<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompetitionTicketPurchase extends BaseModel
{
    use HasFactory;

    protected $primaryKey = "id";

    protected $keyType =  'string';

    protected $casts = [
        'id' => 'string',
        'has_access' => 'boolean',
        'fully_paid' => 'boolean',
        'is_voided'  => 'boolean',
        'show_entry'  => 'boolean',
        'requires_account'  => 'boolean',
    ];

    protected $rules = array(
        'ticket_type_id' => 'required|uuid',
        'quantity' => 'required|integer|nullable',

        'user_id' => 'nullable|uuid',

        'subtotal' => 'numeric',
        'fees' => 'numeric',
        'taxes' => 'numeric',
        'total_price' => 'numeric',

        'access_token' => 'nullable|string|max:255',
        'admin_token' => 'nullable|string|max:255',
        'currency' => 'nullable|string|max:255',

        'platform_take' => 'numeric',
        'payment_processing_take' => 'numeric',
        'host_take' => 'numeric',

        'has_access'  => 'boolean|nullable',
        'fully_paid'  => 'boolean|nullable',
        'show_entry'  => 'boolean|nullable',
        'is_voided'  => 'boolean|nullable',
        
    );

    protected function fields(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => json_decode($value, true),
            set: fn ($value) => json_encode($value),
        );
    } 

    public function ticketType()
    {
        return $this->belongsTo(CompetitionTicketType::class);
    }

}
