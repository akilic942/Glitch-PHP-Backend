<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompetitionRoundBracket extends BaseModel
{
    use HasFactory;

    protected $primaryKey = "id";

    protected $keyType =  'string';

    protected $casts = [
        'id' => 'string',
    ];

    protected $rules = array(
        'competition_id' => 'required|uuid',
        'round' => 'required|integer',
        'bracket' => 'required|integer',
        'user_id' => 'uuid|nullable|required_without:team_id',
        'team_id' => 'uuid|nullable|required_without:user_id',
        'event_id' => 'uuid|nullable',
        'venue_id' => 'uuid|nullable',
        'is_winner' => 'boolean|nullable',
        'is_finished' => 'boolean|nullable',
        'checked_in' => 'boolean|nullable',
        'cash_awarded' => 'nullable|numeric',
        'points_awarded' => 'nullable|numeric',
        'bracket_start_date'    => 'nullable|date',
        'bracket_end_date'      => 'nullable|date|after_or_equal:bracket_start_date',
    );

    protected $fillable = [
        'competition_id',
        'round',
        'bracket',
        'user_id',
        'team_id',
        'event_id',
        'is_winner',
        'is_finished',
        'bracket_start_date',
        'bracket_end_date',
        'checked_in',
        'cash_awarded',
        'points_awarded',
        'venue_id'
    ];

    public function competition()
    {
        return $this->belongsTo(Competition::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function venue()
    {
        return $this->belongsTo(Venue::class);
    }


}
