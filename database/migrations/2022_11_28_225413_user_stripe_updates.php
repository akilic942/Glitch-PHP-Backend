<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('users', function($table) {
            $table->string('stripe_express_account_id')->nullable();
            $table->string('stripe_express_email')->nullable();
            $table->string('stripe_express_currency')->nullable();
            $table->string('stripe_express_country')->nullable();
            $table->string('stripe_express_token')->nullable();
            $table->string('stripe_express_refresh_token')->nullable();
            $table->string('stripe_donation_product_id')->nullable();
            $table->string('stripe_donation_price_id')->nullable();
            $table->string('stripe_donation_purhcase_link_id')->nullable();
            $table->string('stripe_donation_purhcase_link_url')->nullable();
        });
       
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function($table) {
            $table->dropColumn('stripe_express_account_id');
            $table->dropColumn('stripe_express_email');
            $table->dropColumn('stripe_express_currency');
            $table->dropColumn('stripe_express_country');
            $table->dropColumn('stripe_express_token');
            $table->dropColumn('stripe_express_refresh_token');
            $table->dropColumn('stripe_donation_product_id');
            $table->dropColumn('stripe_donation_price_id');
            $table->dropColumn('stripe_donation_purhcase_link_id');
            $table->dropColumn('stripe_donation_purhcase_link_url');
        });
    }
};
