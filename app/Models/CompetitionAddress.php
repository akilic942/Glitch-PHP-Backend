<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompetitionAddress extends BaseModel
{
    use HasFactory;

    protected $primaryKey = "id";

    protected $keyType =  'string';

    protected $casts = [
        'id' => 'string',
    ];

    protected $rules = array(
        'venue_name' => 'required|string|min:0|max:255',
        'address_line_1' => 'string|min:0|max:255|nullable',
        'address_line_2' => 'string|min:0|max:255|nullable',
        'postal_code' => 'string|min:0|max:255|nullable',
        'locality' => 'string|min:0|max:255|nullable', //city
        'province' => 'string|min:0|max:255|nullable', //state
        'country' => 'string|min:0|max:255|nullable',
        'is_virtual_hybrid_remote' => 'required|integer|min:1|digits_between: 1,3',
        'competition_id' => 'required|uuid',
    );

    protected $fillable = [
        'venue_name',
        'venue_description',
        'address_line_1',
        'address_line_2',
        'postal_code',
        'locality',
        'province',
        'country',
        'is_virtual_hybrid_remote'
    ];
 
}
