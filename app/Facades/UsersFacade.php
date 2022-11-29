<?php
namespace App\Facades;

use App\Enums\Roles;
use App\Enums\Widgets;
use App\Models\Event;
use App\Models\PasswordReset;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class UsersFacade {

    public static function retrieveOrCreate(string $email, string $first_name, string $last_name, string $username, string $avatar = ''){

        $user = User::where('email',$email)->first();

        if(!$user) {

            $user = User::create([
                'email' => $email,
                'first_name' => $first_name,
                'last_name' => $last_name,
                'username' => $username,
                'password' => Str::random(30),
                'avatar' => $avatar
            ]);

            if($avatar) {

                $user->forceFill(['avatar' => $avatar]);

                $user->save();
            }
        }

        return $user;
    }

    public static function sendPasswordReset(string $email) {

        $token = Str::random(64);

        $password = PasswordReset::create([
            'email' => $email,
            'token' => $token, 
            'created_at' => Carbon::now()

        ]);

        $user = User::where('email', $password->email)->first();

        $password_reset_url = env('PASSWORD_RESET_REDIRECT');

        $password_reset_url = $password_reset_url . '?token=' . $password->token . '&email=' . $user->email; 

        Mail::send('email.forgetPassword', ['token' => $token, 'password' => $password, 'user' => $user, 'password_reset_url' => $password_reset_url], function($message) use($email){

            $message->to($email);
            $message->subject('Reset Password');
            $message->from(env('MAIL_FROM_ADDRESS', 'noreply@glitch.fun'),env('MAIL_FROM_NAME', 'Glitch Gaming'));
        });

        return $password;
    }

    public static function resetPassword(PasswordReset $password, string $new_password) {

        $result = User::where('email', $password->email)
                    ->update(['password' => Hash::make($new_password)]);

        $password->delete();

        return $result;
    }
    


    public static function createUserDonationPaymentLink(User $user)
    {

        $data = [
            'line_items' => [
                [
                    'price' => $user->stripe_donation_price_id,
                    'quantity' => 1,
                ],
            ],
            'application_fee_amount' => 2,
            'on_behalf_of' => $user->stripe_express_account_id,
            'transfer_data' => [
                'destination' => $user->stripe_express_account_id,
            ]
        ];

        
        //$account_data = ['stripe_account' => trim($user->stripe_express_account_id)];
        $account_data = [];
        

        $link = StripeFacade::createPaymentLink($data, $account_data);

        $user->forceFill([
            'stripe_donation_purhcase_link_id' => $link->id,
            'stripe_donation_purhcase_link_url' => $link->url
        ]);

        $user->save();

        return $link;
    }

    public static function createUserDonationProduct(User $user)
    {
        $data = array(
            'name' => $user->username . ' Donation',
            'active' => true,
            'description' => 'Make a donation to ' . $user->username . ' and support them in their content.'
        );

        $product = StripeFacade::createProduct($user->username . ' Donation', $data);

        $user->forceFill(['stripe_donation_product_id' => $product->id]);
        $user->save();

        return $product;

    }

    public static function createUserDonationtPrice(User $user) {


        $data = [
            'currency' => 'usd',
            'custom_unit_amount' => ['enabled' => true],
            'product' => $user->stripe_donation_product_id,
        ];

        $price = StripeFacade::createPrice($user->stripe_donation_product_id, $data);

        $user->forceFill(['stripe_donation_price_id' => $price->id]);
        $user->save();

        return $price;
    }

    public static function runAllDonationLinkCreation(User $user) {

        UsersFacade::createUserDonationProduct($user);
        $user->refresh();


        UsersFacade::createUserDonationtPrice($user);
        $user->refresh();


        UsersFacade::createUserDonationPaymentLink($user);
        $user->refresh();

        return $user;
    }

    public static function activateDonationForEvent(Event $event, User $user) {

        if(!$user->stripe_donation_purhcase_link_url) {
            return false;
        }

        EventsFacade::setWidgetEnvOptions($event, Widgets::GLITCH_DONATION_BUTTON, ['key' => 'stripe_payment_link', 'value' => $user->stripe_donation_purhcase_link_url]);

        //Activate Widget For Everyone
        EventsFacade::enableWidget($event, Widgets::GLITCH_DONATION_BUTTON, [Roles::Subscriber]);


        return $event;
    }

    public static function deactivateDonationForEvent(Event $event) {

        EventsFacade::disableWidget($event, Widgets::GLITCH_DONATION_BUTTON);

        return $event;
    }


}