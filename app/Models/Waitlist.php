<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Waitlist extends BaseModel
{
    use HasFactory;

    protected $primaryKey = "id";

    protected $keyType =  'string';

    protected $casts = [
        'id' => 'string',
    ];

    protected $rules = array(
        'email'  => 'required|email|string|min:0|max:255',
        'first_name'  => 'string|min:0|max:255|nullable',
        'last_name'  => 'string|min:0|max:255|nullable',
        'full_name'  => 'string|min:0|max:255|nullable',
        'display_name'  => 'string|min:0|max:255|nullable',
        'username'  => 'string|min:0|max:255|nullable',
        'phone_number'  => 'string|min:0|max:255|nullable',
        'website'  => 'string|min:0|max:255|nullable',
        'title'  => 'string|min:0|max:255|nullable',
        'company'  => 'string|min:0|max:255|nullable',
       
    );

    protected $fillable = [
        //User Info
        'email',
        'first_name',
        'last_name',
        'full_name',
        'display_name',
        'username',
        'phone_number',
        'website',
        'title',
        'company'
    ];


    protected function meta(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => json_decode($value, true),
            set: fn ($value) => json_encode($value),
        );
    } 


}
