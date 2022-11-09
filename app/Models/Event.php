<?php

namespace App\Models;

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
        'is_public' => 'boolean|nullable' 
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
    ];

    protected $casts = [
        'id' => 'string',
    ];

    public function users()
    {
        return $this->hasManyThrough(User::class, EventUser::class, 'user_id', 'id','id', 'event_id');
    }
}
