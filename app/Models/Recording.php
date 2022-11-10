<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recording extends BaseModel
{
    use HasFactory;

    protected $primaryKey = "id";

    protected $keyType =  'string';

    protected $rules = array(
        'name' => 'required|string|min:0|max:255',
        'description'  => 'required',
        'event_id'  => 'required',

        'main_image' => 'string|min:0|max:255|url|nullable',
        
        'is_private' => 'boolean|nullable', 

        'published_to_facebook' => 'boolean|nullable',
        'published_to_youtube' => 'boolean|nullable',
        'published_to_twitch' => 'boolean|nullable',

        'enable_auto_publish_to_facebook' => 'boolean|nullable',
        'enable_auto_publish_to_youtube' => 'boolean|nullable',
        'enable_auto_publish_to_twitch' => 'boolean|nullable',
    );

    protected $fillable = [
        'name',
        'description',
        'event_id',
        'is_private',
        'enable_auto_publish_to_facebook',
        'enable_auto_publish_to_youtube',
        'enable_auto_publish_to_twitch',
    ];

    protected $casts = [
        'id' => 'string',
    ];
}
