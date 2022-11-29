<?php
namespace App\Facades;

use App\Models\User;

class StripeFacade
{


    public static function createPaymentLink(array $paymentData, array $accountData = [])
    {

        $stripe = new \Stripe\StripeClient(env('STRIPE_CLIENT_SECRET'));

        return $stripe->paymentLinks->create($paymentData,  $accountData);
    }

    public static function createProduct(string $product_name, array $data = array())
    {

        $defaults = array(
            'name' => $product_name,
        );

        $data += $defaults;

        $stripe = new \Stripe\StripeClient(env('STRIPE_CLIENT_SECRET'));

        return $stripe->products->create($data);
    }

    public static function createPrice(string $product_id, array $data = array()) {

        $stripe = new \Stripe\StripeClient(env('STRIPE_CLIENT_SECRET'));

        $defaults = [
            'currency' => 'usd',
            'custom_unit_amount' => ['enabled' => true],
            'product' => $product_id,
        ];

        $data += $defaults;

        return $stripe->prices->create($data);
    }
}
