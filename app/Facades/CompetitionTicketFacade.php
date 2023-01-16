<?php
namespace App\Facades;

use App\Enums\TicketTypes;
use App\Models\CompetitionTicketPurchase;
use App\Models\CompetitionTicketType;
use App\Models\CompetitionTicketTypeField;
use App\Models\User;

class CompetitionTicketFacade {

    public static function isUnderMaxAvailable(CompetitionTicketType $type, int $quantity) {

        if($type->max_available && $type->max_available >0) {

            $purchases = CompetitionTicketPurchase::where('ticket_type_id', '=', $type->id)
            ->where('fully_paid', '=', 1)
            ->where('is_voided', '=', 0)
            ->count();

            if(!$purchases) {
                return true;
            }

            return ($purchases + $quantity) < $type->max_available;
        }

        return true;
    }

    public static function isUnderMaxPurchasable(CompetitionTicketType $type, int $quantity) {

        if($type->max_purchasable && $type->max_purchasable>0) {

            return $quantity <= $type->max_purchasable;
        }

        return true;
    }

    public static function isOverMinPurchasable(CompetitionTicketType $type, int $quantity) {

        if($type->min_purchasable && $type->min_purchasable>0) {

            return $quantity >= $type->min_purchasable;
        }

        return true;

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

    public static function saveInput(CompetitionTicketType $type, CompetitionTicketPurchase $purchase, array $input = []) {

        $fields = CompetitionTicketTypeField:: where('ticket_type_id', '=', $type->id)
        ->get();

        $filtered = [];

        foreach($fields as $field) {

            if(isset($input[$field->name])) {
                $filtered[$field->name] = $input[$field->name];
            }
        }


        $purchase->forceFill([
            'fields' => $filtered
        ]);

        return $purchase;
    }

    public static function processPayment(CompetitionTicketType $type, CompetitionTicketPurchase $purchase, int $quantity, $processor = null, array $payment = []) {

        if($type->ticket_type == TicketTypes::FREE) {
            $purchase->forceFill([
                'has_access' => 1,
                'fully_paid' => 1,
                'show_entry' => 1,
                'quantity' => $quantity
            ]);

            $purchase->save();

            return $purchase;
        } else if($type->ticket_type == TicketTypes::PAID ) {

        }

    }


}