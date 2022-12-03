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
        Schema::table('events', function (Blueprint $table) {
            $table->text('google_auth_token')->nullable()->default('');
            $table->text('google_refresh_token')->nullable()->default('');
            $table->integer('google_token_issued')->nullable();
            $table->integer('google_token_expiration')->nullable();
            $table->string('google_id')->nullable();
            $table->string('google_name')->nullable();
            $table->string('google_email')->nullable();
            $table->string('google_avatar')->nullable(); 

            $table->text('microsoft_auth_token')->nullable()->default('');
            $table->text('microsoft_refresh_token')->nullable()->default('');
            $table->integer('microsoft_token_issued')->nullable();
            $table->integer('microsoft_token_expiration')->nullable();
            $table->string('microsoft_id')->nullable();
            $table->string('microsoft_name')->nullable();
            $table->string('microsoft_email')->nullable();
            $table->string('microsoft_avatar')->nullable(); 

            $table->text('microsoft_teams_auth_token')->nullable()->default('');
            $table->text('microsoft_teams_refresh_token')->nullable()->default('');
            $table->integer('microsoft_teams_token_issued')->nullable();
            $table->integer('microsoft_teams_token_expiration')->nullable();
            $table->string('microsoft_teams_id')->nullable();
            $table->string('microsoft_teams_name')->nullable();
            $table->string('microsoft_teams_email')->nullable();
            $table->string('microsoft_teams_avatar')->nullable(); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('events', function($table) {
            $table->dropColumn('google_auth_token');
            $table->dropColumn('google_refresh_token');
            $table->dropColumn('google_token_issued');
            $table->dropColumn('google_token_expiration');
            $table->dropColumn('google_id');
            $table->dropColumn('google_name');
            $table->dropColumn('google_email');
            $table->dropColumn('google_avatar');

            $table->dropColumn('microsoft_auth_token');
            $table->dropColumn('microsoft_refresh_token');
            $table->dropColumn('microsoft_token_issued');
            $table->dropColumn('microsoft_token_expiration');
            $table->dropColumn('microsoft_id');
            $table->dropColumn('microsoft_name');
            $table->dropColumn('microsoft_email');
            $table->dropColumn('microsoft_avatar');

            $table->dropColumn('microsoft_teams_auth_token');
            $table->dropColumn('microsoft_teams_refresh_token');
            $table->dropColumn('microsoft_teams_token_issued');
            $table->dropColumn('microsoft_teams_token_expiration');
            $table->dropColumn('microsoft_teams_id');
            $table->dropColumn('microsoft_teams_name');
            $table->dropColumn('microsoft_teams_email');
            $table->dropColumn('microsoft_teams_avatar');
        });
    }
};
