<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PasswordReset extends BaseModel
{
    use HasFactory;

    public $timestamps = false;

    protected $primaryKey = "email";

    protected $keyType =  'string';

    protected $rules = array(
        'email' => 'required|email|exists:users',
        'token' => 'required',
    );

    protected $fillable = [
        'email',
        'token',
        'created_at',
    ];
}
