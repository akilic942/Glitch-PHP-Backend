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
            $table->string('invirtu_user_id')->uuid('id')->nullable();
            $table->text('invirtu_user_jwt_token')->nullable()->default('');
            $table->integer('invirtu_user_jwt_issued')->nullable();
            $table->integer('invirtu_user_jwt_expiration')->nullable();

            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
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
