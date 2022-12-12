<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamInvite extends BaseModel
{
    use HasFactory;

    protected $primaryKey = "id";

    protected $keyType =  'string';

    protected $casts = [
        'id' => 'string',
    ];

    protected $rules = array(
        'email' => 'required|email|string|min:0|max:255',
        'name' => 'required|string|min:0|max:255',
        'team_id' => 'required|uuid',
        'user_id' => 'uuid|nullable',
        'token' => 'required|string|min:0|max:255',
        'accepted_invite'  => 'boolean|nullable',
        'role' => 'integer|nullable',
    );

    protected $fillable = [
        'email',
        'name',
        'team_id',
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
