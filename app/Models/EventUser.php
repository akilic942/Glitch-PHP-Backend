<?php

namespace App\Models;

use App\Traits\HasCompositePrimaryKeyTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventUser extends BaseModel
{
    use HasFactory, HasCompositePrimaryKeyTrait;

    protected $primaryKey = ['event_id','user_id'];

    public $incrementing = false;

    protected $keyType =  'string';

    protected $casts = [
        'user_id' => 'string',
        'event_id' => 'string',
    ];

    protected $rules = array(
        'event_id' => 'required',
        'user_id'  => 'required',
    );

    protected $fillable = [
        'event_id',
        'user_id',
        'user_role',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
