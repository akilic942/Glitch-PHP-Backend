<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Event extends BaseModel
{
    use HasFactory;

    protected $primaryKey = "id";

    protected $keyType =  'string';

    protected $rules = array(
        'title' => 'required',
        'description'  => 'required',
        'start_date' => 'date_format:"d-m-Y"|nullable'  
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

        'invirtu_id',
        'invirtu_webrtc_url',
        'invirtu_broadcast_url',
        'invirtu_rtmp_broadcast_endpoint',
        'invirtu_rtmp_broadcast_key'
    ];

    protected $casts = [
        'id' => 'string',
    ];

    public function users()
    {
        return $this->hasManyThrough(User::class, EventUser::class, 'user_id', 'id','id', 'event_id');
    }
}
