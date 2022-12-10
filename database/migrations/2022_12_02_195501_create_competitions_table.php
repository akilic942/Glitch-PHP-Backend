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
        Schema::create('competitions', function (Blueprint $table) {
            $table->uuid('id');
            $table->primary('id');

            $table->string('name')->nullable()->default('');
            $table->text('description')->nullable()->default('');
            $table->integer('type')->nullable();
            $table->text('rules')->nullable()->default('');
            $table->text('agreement')->nullable()->default('');
            $table->text('schedule')->nullable()->default('');
            $table->text('disqualifiers')->nullable()->default('');
            $table->text('refund_policy')->nullable()->default('');
            $table->text('harassment_policy')->nullable()->default('');
            $table->text('saftey_policy')->nullable()->default('');
            $table->string('timezone', 5)->nullable();

            $table->string('main_image')->nullable()->default('');
            $table->string('banner_image')->nullable()->default('');
            $table->string('logo')->nullable()->default('');

            //Social Pages
            $table->string('twitter_page')->nullable()->default('');
            $table->string('facebook_page')->nullable()->default('');
            $table->string('instagram_page')->nullable()->default('');
            $table->string('snapchat_page')->nullable()->default('');
            $table->string('tiktok_page')->nullable()->default('');
            $table->string('twitch_page')->nullable()->default('');
            $table->string('youtube_page')->nullable()->default('');
            $table->string('paetron_page')->nullable()->default('');
            $table->string('github_page')->nullable()->default('');

            //Social Handles
            $table->string('twitter_handle')->nullable()->default('');
            $table->string('facebook_handle')->nullable()->default('');
            $table->string('instagram_handle')->nullable()->default('');
            $table->string('snapchat_handle')->nullable()->default('');
            $table->string('tiktok_handle')->nullable()->default('');
            $table->string('twitch_handle')->nullable()->default('');
            $table->string('youtube_handle')->nullable()->default('');
            $table->string('paetron_handle')->nullable()->default('');
            $table->string('github_handle')->nullable()->default('');

            //Contact Info
            $table->string('contact_name')->nullable()->default('');
            $table->string('contact_email')->nullable()->default('');
            $table->string('contact_phone_number')->nullable()->default('');
            $table->string('website')->nullable()->default('');

            //Dates
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();

            $table->timestamp('registration_start_date')->nullable();
            $table->timestamp('registration_end_date')->nullable();

            $table->boolean('allow_team_signup')->nullable()->default(false);
            $table->boolean('allow_individual_signup')->nullable()->default(false);
            $table->boolean('is_private')->nullable()->default(false);

            $table->boolean('checkin_enabled')->nullable()->default(false);
            $table->integer('checkin_mintues_prior')->nullable()->default(false);

            $table->boolean('team_signup_requires_approval')->nullable()->default(false);
            $table->boolean('individual_signup_requires_approval')->nullable()->default(false);

            $table->integer('competitors_per_match')->default(2);
            $table->integer('winners_per_match')->default(1);
            
            $table->integer('max_registration_for_teams')->nullable();
            $table->integer('max_registration_for_users')->nullable();
            $table->integer('minimum_team_size')->nullable();

            $table->double('team_registration_price')->nullable()->default(0);
            $table->double('individual_registration_price')->nullable()->default(0);
            

            $table->timestamps();
        });

        DB::statement('ALTER TABLE competitions ALTER COLUMN id SET DEFAULT uuid_generate_v4();');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('competitions');
    }
};
