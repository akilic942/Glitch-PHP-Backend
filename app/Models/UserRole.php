<?php

namespace App\Models;

use App\Traits\HasCompositePrimaryKeyTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * UserRole
 * 
 * Site was role that a user has.
 */
class UserRole extends BaseModel
{
    use HasFactory, HasCompositePrimaryKeyTrait;

    protected $primaryKey = ['user_id','user_role'];
    
    public $incrementing = false;

    protected $keyType =  'string';

    protected $casts = [
        'user_role' => 'string',
        'user_id' => 'string',
    ];

    protected $rules = array(
        'user_id' => 'required',
        'user_role' => 'required|integer|min:0|digits_between: 0,8',
    );

    protected $fillable = [
        'user_id',
        'user_role',

    ];
}
