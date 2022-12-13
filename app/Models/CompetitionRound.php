<?php

namespace App\Models;

use App\Traits\HasCompositePrimaryKeyTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompetitionRound extends BaseModel
{
    use HasFactory, HasCompositePrimaryKeyTrait;

    protected $primaryKey = ['competition_id','round'];
    
    public $incrementing = false;

    protected $keyType =  'string';

    protected $casts = [
        'competition_id' => 'string',
        'round' => 'integer',
    ];

    protected $rules = array(
        'competition_id' => 'required|uuid',
        'round'  => 'required|integer',
        'title' => 'nullable|string|min:0|max:255',

        'checkin_enabled' => 'boolean|nullable',
        'checkin_mintues_prior' => 'integer|nullable',

        'venue_id' => 'uuid|nullable',

        'elimination_type' => 'nullable|numeric|min:1|max:9',

        'round_start_date'    => 'nullable|date',
        'round_end_date'      => 'nullable|date|after_or_equal:round_start_date',
    );

    protected $fillable = [
        'competition_id',
        'round',
        'title',
        'overview',
        'round_start_date',
        'round_end_date',
        'checkin_enabled',
        'checkin_mintues_prior',
        'elimination_type',
        'venue_id'
    ];

    public function competition()
    {
        return $this->belongsTo(Competition::class);
    }

    public function brackets() {
        return $this->hasMany(CompetitionRoundBracket::class, 'round', 'round')->where('competition_id', '=', $this->competition_id)->orderBy('bracket', 'asc');;
    }

}
