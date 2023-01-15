<?php
namespace App\Facades;

use App\Models\CompetitionTicketType;
use App\Models\CompetitionTicketTypeField;
use App\Models\User;

class CompetitionTicketFacade {

    public function canPurchaseTicket(CompetitionTicketType $type, User $user = null) {

        if($type->max_available && $type->max_available >0) {

            //Need To Get max amount
        }
    }

    public static function validateFields(CompetitionTicketType $type, array $input = []) {

        $result = [
            'status' => true,
            'errors' => []
        ];

        $required_fields = CompetitionTicketTypeField:: where('ticket_type_id', '=', $type->id)
        ->where('is_required', '=', 1)
        ->where('is_disabled', '=', 0)
        ->get();

        foreach($required_fields as $field) {

            if(!isset($input[$field->name]) || (isset($input[$field->name]) && !$input[$field->name])) {
                $result['status'] = false;

                if(!isset($result['errors'][$field->name])) {
                    $result['errors'][$field->name] = [];
                }

                $result['errors'][$field->name][] = $field->label . ' is required';
            }
        }


        return $result;
    }

    public static function saveInput(CompetitionTicketType $type, array $input = []) {

        
    }


}