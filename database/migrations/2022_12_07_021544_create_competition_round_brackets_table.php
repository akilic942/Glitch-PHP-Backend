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
        Schema::create('competition_round_brackets', function (Blueprint $table) {
            $table->uuid('id');
            $table->primary('id');

            $table->uuid('competition_id');
            $table->integer('round');
            $table->integer('bracket');

            $table->uuid('user_id')->nullable();
            $table->uuid('team_id')->nullable();
            $table->uuid('address_id')->nullable();
            $table->uuid('event_id')->nullable();
            
            $table->boolean('checked_in')->nullable()->default(false);
            $table->timestamp('checked_in_time')->nullable();

            $table->boolean('is_winner')->nullable();
            $table->boolean('is_finished')->nullable();

            $table->float('points_awarded')->nullable()->default(0);
            $table->float('cash_awarded')->nullable()->default(0);

            $table->timestamp('bracket_start_date')->nullable();
            $table->timestamp('bracket_end_date')->nullable();

            $table->foreign('competition_id')->references('id')->on('competitions')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('team_id')->references('id')->on('teams')->onDelete('cascade');
            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
            $table->foreign('address_id')->references('id')->on('competition_addresses')->onDelete('cascade');


            $table->timestamps();
        });

        DB::statement('ALTER TABLE competition_round_brackets ALTER COLUMN id SET DEFAULT uuid_generate_v4();');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('competition_round_brackets');
    }
};
