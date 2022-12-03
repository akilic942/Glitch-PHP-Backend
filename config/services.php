<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],
    'facebook' => [
        'client_id' => env('FACEBOOK_CLIENT_ID'), //USE FROM FACEBOOK DEVELOPER ACCOUNT
        'client_secret' => env('FACEBOOK_CLIENT_SECRET'), //USE FROM FACEBOOK DEVELOPER ACCOUNT
        'redirect' => env('FACEBOOK_REDIRECT_URI')
    ],
    'twitch' => [    
        'client_id' => env('TWITCH_CLIENT_ID'),  
        'client_secret' => env('TWITCH_CLIENT_SECRET'),  
        'redirect' => env('TWITCH_REDIRECT_URI') 
    ],
    'youtube' => [    
        'client_id' => env('YOUTUBE_CLIENT_ID'),  
        'client_secret' => env('YOUTUBE_CLIENT_SECRET'),  
        'redirect' => env('YOUTUBE_REDIRECT_URI') 
    ],
    'stripe' => [    
        'client_id' => env('STRIPE_CLIENT_ID'),  
        'client_secret' => env('STRIPE_CLIENT_SECRET'),  
        'redirect' => env('STRIPE_REDIRECT_URI') 
    ],
    'google' => [    
        'client_id' => env('GOOGLE_CLIENT_ID'),  
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),  
        'redirect' => env('GOOGLE_REDIRECT_URI') 
    ],
    'microsoft' => [    
        'client_id' => env('MICROSOFT_CLIENT_ID'),  
        'client_secret' => env('MICROSOFT_CLIENT_SECRET'),  
        'redirect' => env('MICROSOFT_REDIRECT_URI') 
    ],
    'teamservice' => [    
        'client_id' => env('TEAMSERVICE_CLIENT_ID'),  
        'client_secret' => env('TEAMSERVICE_CLIENT_SECRET'),  
        'redirect' => env('TEAMSERVICE_REDIRECT_URI') 
    ],

];
