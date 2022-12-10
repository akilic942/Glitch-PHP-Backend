<?php

namespace App\Models;

use App\Traits\HasCompositePrimaryKeyTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamUser extends BaseModel
{
    use HasFactory, HasCompositePrimaryKeyTrait;

    protected $primaryKey = ['user_id','team_id'];
    
    public $incrementing = false;

    protected $keyType =  'string';

    protected $casts = [
        'team_id' => 'string',
        'user_id' => 'string',
    ];

    protected $rules = array(
        'user_id' => 'required',
        'team_id'  => 'required',
        'user_role' => 'integer|nullable',
        'status' => 'nullable|numeric|min:0|max:7'
    );

    protected $fillable = [
        'user_id',
        'team_id',
        'user_role',
        'status'
    ];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
