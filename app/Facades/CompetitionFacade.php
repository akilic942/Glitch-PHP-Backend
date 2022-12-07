<?php
namespace App\Facades;

use App\Models\Competition;
use App\Models\CompetitionRound;
use App\Models\User;

class CompetitionFacade {


    //Make a number of rounds based on the number of competitors and 
    public static function makeRounds(Competition $competition, int $number_of_competitors, $competitors_per_backet = 2) {

        $rounds = 0;

        for($i = $number_of_competitors; $i>=0; $i++) {

            $rounds++;
            $remainder = fmod($i , $competitors_per_backet);
            $i = $i /$competitors_per_backet;
            $i += $remainder;
        }


        return $rounds;

    }

    public static function autoAssignCompetitorsUsers(Competition $competition, int $round) {

        // Try to get previous round
        $round = CompetitionRound::where('competition_id', $competition->id)
        ->where('round', $round -1)
        ->first();

        $competitors = null;

        if(!$round) {
            //If no previous round, get current round
            $round = CompetitionRound::where('competition_id', $competition->id)
            ->where('round', $round)
            ->first();

            if(!$round) {
                $round = CompetitionRound::create(['competition_id' => $competition->id, 'round' => $round]);
            }

        } else {
            $competitors = User::where('competition_id', $competition->id)
            ->where('round', $round -1)
            ->where('is_winner', true)
            ->join('competition_round_brackets')
            ->get();
        }





    }
}