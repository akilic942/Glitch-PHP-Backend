<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventTicketTypeSection extends BaseModel
{
    use HasFactory;

    protected $primaryKey = "id";

    protected $keyType =  'string';

    protected $casts = [
        'id' => 'string',
    ];

    protected $rules = array(
        'title' => 'required|string|min:0|max:255',
        'ticket_type_id' => 'required',
        'section_order' => 'integer',
    );

    protected $fillable = array(
        'title',
        'ticket_type_id',
        'instructions',
        'section_order',
    );
}
