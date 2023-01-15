<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompetitionInvite extends BaseModel
{
    use HasFactory;

    protected $primaryKey = "id";

    protected $keyType =  'string';

    protected $casts = [
        'id' => 'string',
        'invited_as_participant' => 'boolean',
        'invited_as_team_member' => 'boolean',
        'accepted_invite'  => 'boolean',
    ];

    protected $rules = array(
        'email' => 'required|email|string|min:0|max:255',
        'name' => 'required|string|min:0|max:255',
        'competition_id' => 'required|uuid',
        'user_id' => 'uuid|nullable',
        'token' => 'required|string|min:0|max:255',
        'invited_as_participant' => 'boolean|nullable',
        'invited_as_team_member' => 'boolean|nullable',
        'accepted_invite'  => 'boolean|nullable',
        'role' => 'integer|nullable',
    );

    protected $fillable = [
        'email',
        'name',
        'competition_id',
        'invited_as_participant',
        'invited_as_team_member',
        'token'
    ];

    public function setEmailAttribute($value)
    {
        $this->attributes['email'] = strtolower($value);
    }

    public function getEmailAttribute($value)
    {
        return strtolower($value);
    }
}
