<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class EventOverlay extends BaseModel
{
    use HasFactory;

    protected $primaryKey = "id";

    protected $keyType =  'string';

    protected $casts = [
        'id' => 'string',
    ];

    protected $rules = array(
        'label' => 'required|string|min:0|max:255',
        'event_id' => 'required|uuid',
    );

    protected $fillable = [
        'label',
        'event_id',
        'image_url',
    ];
    
    public function getImageUrl() {

        return Storage::disk('s3')->url($this->image_url);
    }
}
