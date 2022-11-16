<?php

namespace App\Models;

use App\Enums\Roles;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Event extends BaseModel
{
    use HasFactory;

    protected $primaryKey = "id";

    protected $keyType =  'string';

    protected $rules = array(
        'title' => 'required|string|min:0|max:255',
        'description'  => 'required',
        //'start_date' => 'nullable|sometimes|date_format:"d-m-Y"',
        'is_public' => 'boolean|nullable',
        'is_live'  => 'boolean|nullable',
    );

     /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'description',
        'start_date',
        'is_public',
        'is_live',
    ];

    protected $casts = [
        'id' => 'string',
    ];

    public function users()
    {
        return $this->hasManyThrough(User::class, EventUser::class, 'event_id', 'id','id', 'user_id');
    }

    public function admins()
    {
        return $this->hasManyThrough(User::class, EventUser::class, 'event_id', 'id','id', 'user_id')->where('user_role', Roles::Administrator)->orWhere('user_role', Roles::SuperAdministrator);
    }

    public function moderators()
    {
        return $this->hasManyThrough(User::class, EventUser::class, 'event_id', 'id','id', 'user_id')->where('user_role', Roles::Moderator);
    }

    public function cohosts()
    {
        return $this->hasManyThrough(User::class, EventUser::class, 'event_id', 'id','id', 'user_id')->where('user_role', Roles::Speaker);
    }
    
}
