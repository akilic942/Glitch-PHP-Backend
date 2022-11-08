<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class MessageThread extends BaseModel
{
    use HasFactory;

    protected $primaryKey = "id";

    protected $keyType =  'string';

    protected $casts = [
        'id' => 'string',
    ];

    public function users() {
        return $this->hasMany(MessageThreadParticapant::class, 'thread_id', 'id');
    }

    public function messages() {
        return $this->hasMany(Message::class, 'thread_id', 'id');
    }


    
}
