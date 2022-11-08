<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id');
            $table->primary('id');

            //User Information
            $table->string('first_name');
            $table->string('last_name');
            $table->string('display_name')->nullable()->default('');
            $table->string('username')->nullable()->default('');
            $table->string('email')->unique();
            $table->date('date_of_birth')->nullable();
            $table->integer('phone_number')->nullable();
            $table->integer('phone_number_country_code')->nullable();
            $table->text('bio')->nullable();
            
            //Social Pages
            $table->string('twitter_page')->nullable()->default('');
            $table->string('facebook_page')->nullable()->default('');
            $table->string('instagram_page')->nullable()->default('');
            $table->string('snapchat_page')->nullable()->default('');
            $table->string('tiktok_page')->nullable()->default('');
            $table->string('twitch_page')->nullable()->default('');
            $table->string('youtube_page')->nullable()->default('');
            $table->string('paetron_page')->nullable()->default('');

            //Social Handles
            $table->string('twitter_handle')->nullable()->default('');
            $table->string('facebook_handle')->nullable()->default('');
            $table->string('instagram_handle')->nullable()->default('');
            $table->string('snapchat_handle')->nullable()->default('');
            $table->string('tiktok_handle')->nullable()->default('');
            $table->string('twitch_handle')->nullable()->default('');
            $table->string('youtube_handle')->nullable()->default('');
            $table->string('paetron_handle')->nullable()->default('');

            //Invirtu Data
            $table->uuid('invirtu_user_id')->nullable();
            $table->text('invirtu_user_jwt_token')->nullable()->default('');
            $table->integer('invirtu_user_jwt_issued')->nullable();
            $table->integer('invirtu_user_jwt_expiration')->nullable();

            //Facebook OAuth
            $table->text('facebook_auth_token')->nullable()->default('');
            $table->text('facebook_refresh_token')->nullable()->default('');
            $table->integer('facebook_token_issued')->nullable();
            $table->integer('facebook_token_expiration')->nullable();
            $table->string('facebook_id')->nullable();
            $table->string('facebook_name')->nullable();
            $table->string('facebook_email')->nullable();
            $table->string('facebook_avatar')->nullable();

            //Youtube OAuth
            $table->text('youtube_auth_token')->nullable()->default('');
            $table->text('youtube_refresh_token')->nullable()->default('');
            $table->integer('youtube_token_issued')->nullable();
            $table->integer('youtube_token_expiration')->nullable();
            $table->string('youtube_id')->nullable();
            $table->string('youtube_username')->nullable();
            $table->string('youtube_avatar')->nullable();

            //Twitch OAuth
            $table->text('twitch_auth_token')->nullable()->default('');
            $table->text('twitch_refresh_token')->nullable()->default('');
            $table->integer('twitch_token_issued')->nullable();
            $table->integer('twitch_token_expiration')->nullable();
            $table->string('twitch_id')->nullable();
            $table->string('twitch_username')->nullable();
            $table->string('twitch_email')->nullable();
            $table->string('twitch_avatar')->nullable();

            //Billing Info
            $table->boolean('restream_active_subscription')->nullable()->default(0);
            $table->string('stripe_customer_id')->nullable();
            $table->string('stripe_subscription_id')->nullable();
            $table->date('stripe_trial_end')->nullable();

            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('one_time_login_token')->nullable()->default('');
            $table->date('one_time_login_token_date')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        DB::statement('ALTER TABLE users ALTER COLUMN id SET DEFAULT uuid_generate_v4();');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
