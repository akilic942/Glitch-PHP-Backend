<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventTicketTypeField extends BaseModel
{
    use HasFactory;

    protected $primaryKey = "id";

    protected $keyType =  'string';

    protected $casts = [
        'id' => 'string',
        'is_required' => 'boolean',
        'is_disabled' => 'boolean'
    ];

    protected $rules = array(
        'label' => 'required|string|min:0|max:255',
        'name' => 'required|string|min:0|max:255',
        'field_type' => 'required|integer|min:0|max:4',
        'ticket_type_id' => 'required',
        'section_id' => 'uuid|nullable',
        'field_order' => 'integer',
        'is_required' => 'boolean',
        'is_disabled' => 'boolean'
    );

    protected $fillable = array(
        'label',
        'name',
        'field_type',
        'ticket_type_id',
        'section_id',
        'field_order',
        'is_required',
        'is_disabled'
    );
}
