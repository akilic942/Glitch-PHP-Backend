<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventTicketType extends BaseModel
{
    use HasFactory;

    protected $primaryKey = "id";

    protected $keyType =  'string';

    protected $casts = [
        'id' => 'string',
        'disabled' => 'boolean',
    ];

    protected $rules = array(
        'name' => 'required|string|min:0|max:255',
        'event_id' => 'required',
        
        'ticket_type' => 'nullable|numeric|min:1|max:3',
        'visibility' => 'nullable|numeric|min:1|max:4',
        
        'max_available' => 'integer',
        'min_purchasable' => 'integer',
        'max_purchasable' => 'integer',
        
        'price' => 'numeric',
        'disabled' => 'boolean',

        'sales_start_date'    => 'nullable|date',
        'sales_end_date'    => 'nullable|date|after_or_equal:sales_end_date',

        'visibility_start_date'    => 'nullable|date',
        'visibility_end_date'    => 'nullable|date|after_or_equal:visibility_start_date',

        'ticket_usage_date'    => 'nullable|date',
    );

    protected $fillable = array(
        'name',
        'description',
        'event_id',
        
        'ticket_type',
        'visibility',
        
        'max_available',
        'min_purchasable',
        'max_purchasable',
        
        'price',
        'disabled',

        'sales_start_date',
        'sales_end_date',

        'visibility_start_date',
        'visibility_end_date',

        'ticket_usage_date',
    );
}
