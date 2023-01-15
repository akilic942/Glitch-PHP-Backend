<?php
namespace App\Facades;

use App\Models\CompetitionTicketType;
use App\Models\User;

class CompetitionTicketFacade {

    public function canPurchaseTicket(CompetitionTicketType $type, User $user = null) {

        if($type->max_available && $type->max_available >0) {

            //Need To Get max amount
        }
    }


}