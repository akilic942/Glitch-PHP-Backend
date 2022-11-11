<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends BaseAuthModel implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;
    
    protected $primaryKey = "id";

    protected $keyType =  'string';

    protected $rules = array(
        'first_name' => 'required|string|min:0|max:255',
        'last_name'  => 'required|string|min:0|max:255',
        'username'  => 'required|string|min:0|max:255|unique:users',
        'password'  => 'required',
        'email'  => 'required|email|unique:users|string|min:0|max:255',  
        //'date_of_birth' => 'date_format:"Y-m-d"|nullable',
        
        //Image validation
        'avatar' => 'string|min:0|max:255|url|nullable',
        'banner_image' => 'string|min:0|max:255|url|nullable',

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
    );

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        //User Info
        'username',
        'displayname',
        'email',
        'password',
        'first_name',
        'last_name',
        'date_of_birth',
        'phone_number',
        'phone_number_country_code',
        'bio',
        
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
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'id' => 'string',
        'email_verified_at' => 'datetime',
    ];

    public function getJWTIdentifier()
    {
      return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
      return [];
    }

    public function events() {
       return $this->hasManyThrough(Event::class, EventUser::class, 'user_id', 'id','id', 'event_id');
    }

    public function followers() {
        return $this->hasManyThrough(User::class, Follow::class, 'following_id', 'id','id', 'follower_id');
     }

     public function following() {
        return $this->hasManyThrough(User::class, Follow::class, 'follower_id', 'id','id', 'following_id');
     }
}
