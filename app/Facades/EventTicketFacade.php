<?php
namespace App\Facades;

use App\Enums\TicketTypes;
use App\Models\EventTicketPurchase;
use App\Models\EventTicketType;
use App\Models\EventTicketTypeField;
use App\Models\User;

class EventTicketFacade {

    public static function isUnderMaxAvailable(EventTicketType $type, int $quantity) {

        if($type->max_available && $type->max_available >0) {

            $purchases = EventTicketPurchase::where('ticket_type_id', '=', $type->id)
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

    public static function isUnderMaxPurchasable(EventTicketType $type, int $quantity) {

        if($type->max_purchasable && $type->max_purchasable>0) {

            return $quantity <= $type->max_purchasable;
        }

        return true;
    }

    public static function isOverMinPurchasable(EventTicketType $type, int $quantity) {

        if($type->min_purchasable && $type->min_purchasable>0) {

            return $quantity >= $type->min_purchasable;
        }

        return true;

    }

    public static function validateFields(EventTicketType $type, array $input = []) {

        $result = [
            'status' => true,
            'errors' => []
        ];

        $required_fields = EventTicketTypeField:: where('ticket_type_id', '=', $type->id)
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

    public static function saveInput(EventTicketType $type, EventTicketPurchase $purchase, array $input = []) {

        $fields = EventTicketTypeField:: where('ticket_type_id', '=', $type->id)
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

    public static function processPayment(EventTicketType $type, EventTicketPurchase $purchase, int $quantity, $processor = null, array $payment = []) {

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