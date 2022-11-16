<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventInvite extends BaseModel
{
    use HasFactory;

    protected $primaryKey = "id";

    protected $keyType =  'string';

    protected $casts = [
        'id' => 'string',
    ];

    protected $rules = array(
        'email' => 'required|string|min:0|max:255',
        'name' => 'required|string|min:0|max:255',
        'event_id' => 'required|uuid',
        'token' => 'required|string|min:0|max:255',
        'invited_as_cohost' => 'boolean|nullable',
        'accepted_invite'  => 'boolean|nullable',
    );

    protected $fillable = [
        'email',
        'name',
        'event_id',
        'invited_as_cohost',
    ];
}
