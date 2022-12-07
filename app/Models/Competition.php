<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Competition extends BaseModel
{
    use HasFactory;

    protected $primaryKey = "id";

    protected $keyType =  'string';

    protected $casts = [
        'id' => 'string',
    ];

    protected $rules = array(
        'name' => 'required|string|min:0|max:255',
        'description' => 'required',

        //Image validation
        'main_image' => 'string|min:0|max:255|url|nullable',
        'banner_image' => 'string|min:0|max:255|url|nullable',
        'logo' => 'string|min:0|max:255|url|nullable',

        //Social Pages
        'twitter_page'  => 'string|min:0|max:255|url|nullable',
        'facebook_page'  => 'string|min:0|max:255|url|nullable',
        'instagram_page'  => 'string|min:0|max:255|url|nullable',
        'snapchat_page'  => 'string|min:0|max:255|url|nullable',
        'tiktok_page'  => 'string|min:0|max:255|url|nullable',
        'twitch_page'  => 'string|min:0|max:255|url|nullable',
        'youtube_page'  => 'string|min:0|max:255|url|nullable',
        'paetron_page'  => 'string|min:0|max:255|url|nullable',

        //Social Handles
        'twitter_handle' => 'string|min:0|max:255|nullable',
        'facebook_handle' => 'string|min:0|max:255|nullable',
        'instagram_handle' => 'string|min:0|max:255|nullable',
        'snapchat_handle' => 'string|min:0|max:255|nullable',
        'tiktok_handle' => 'string|min:0|max:255|nullable',
        'twitch_handle' => 'string|min:0|max:255|nullable',
        'youtube_handle' => 'string|min:0|max:255|nullable',
        'paetron_handle' => 'string|min:0|max:255|nullable',
        'github_handle' => 'string|min:0|max:255|nullable',

        'contact_name' => 'string|min:0|max:255|nullable',
        'contact_email' => 'email|string|min:0|max:255|nullable',
        'contact_phone_number' => 'string|min:0|max:255|nullable',
        'website' => 'string|min:0|max:255|url|nullable',

        //'start_date' => 'string|min:0|max:255|nullable',
        //'end_date' => 'string|min:0|max:255|nullable',
        //'registration_start_date' => 'string|min:0|max:255|nullable',
        //'registration_end_date' => 'string|min:0|max:255|nullable',
        
        'allow_team_signup' => 'boolean|nullable',
        'allow_individual_signup' => 'boolean|nullable',
        'is_private' => 'boolean|nullable',

        'competitors_per_match' => 'integer',
        'winners_per_match' => 'integer',
        
        'team_registration_price' => 'double|nullable',
        'individual_registration_price' => 'double|nullable',

        
    );

    protected $fillable = [
        'name',
        'description',
        'rules',
        'agreement',

        //Social Pages
        'twitter_page',
        'facebook_page',
        'instagram_page',
        'snapchat_page',
        'tiktok_page',
        'twitch_page',
        'youtube_page',
        'paetron_page',
        'github_page',

        //Social Handles
        'twitter_handle',
        'facebook_handle',
        'instagram_handle',
        'snapchat_handle',
        'tiktok_handle',
        'twitch_handle',
        'youtube_handle',
        'paetron_handle',
        'github_handle',

        'contact_name',
        'contact_email',
        'contact_phone_number',
        'website',

        'start_date',
        'end_date',
        'registration_start_date',
        'registration_end_date',

        'allow_team_signup',
        'allow_individual_signup',
        'is_private',

        'team_registration_price',
        'individual_registration_price',
    ];
    

}
