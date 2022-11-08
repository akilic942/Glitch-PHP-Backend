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
        'first_name' => 'required',
        'last_name'  => 'required',
        'username'  => 'required',
        'password'  => 'required',
        'email'  => 'required|email|unique:users',   
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

        //Social Handles
        'twitter_handle',
        'facebook_handle',
        'instagram_handle',
        'snapchat_handle',
        'tiktok_handle',
        'twitch_handle',
        'youtube_handle',
        'paetron_handle',

        //Invirtu Data
        'invirtu_user_id',
        'invirtu_user_jwt_token',
        'invirtu_user_jwt_issued',
        'invirtu_user_jwt_expiration'
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
       return $this->hasManyThrough(User::class, EventUser::class, 'user_id', 'id','id', 'event_id');
    }
}
