<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Webhook extends BaseModel
{
    use HasFactory;

    protected $primaryKey = "id";

    protected $keyType =  'string';

    protected $rules = array(
        'incoming_outgoing' => 'required|integer|min:0|max:2',
        'action'  => 'string|min:0|max:255|nullable',
        'url'  => 'string|min:0|max:255|url|nullable',
        'request_method'  => 'string|min:0|max:255|nullable',
        'signature_key'  => 'string|min:0|max:255|nullable',
        'signature_value'  => 'string|min:0|max:255|nullable',
       
        'processed'  => 'boolean|nullable',
    );

    protected $fillable = [
        //User Info
        'incoming_outgoing',
        'action',
        'data',
        'url',
        'request_method',
        'signature_key',
        'signature_value',
        'processed',
    ];

    protected $casts = [
        'id' => 'string',
    ];

}
