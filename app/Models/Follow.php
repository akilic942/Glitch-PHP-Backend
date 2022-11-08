<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Follow extends BaseModel
{
    use HasFactory;

    protected $primaryKey = ['following_id','follower_id'];

    public $incrementing = false;

    protected $casts = [
        'following_id' => 'string',
        'follower_id' => 'string',
    ];

    protected $fillable = [
        'following_id',
        'follower_id',
    ];

    protected $rules = [
        'following_id' => 'required',
        'follower_id' => 'required',
    ];

    public function following() {
        return $this->belongsTo(User::class);
    }

    public function follower() {
        return $this->belongsTo(User::class);
    }
}
