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
        Schema::create('competition_rounds', function (Blueprint $table) {
            $table->uuid('competition_id');
            $table->integer('round');

            $table->primary(['competition_id', 'round']);

            $table->string('title')->nullable();
            $table->text('overview')->nullable();
            $table->timestamp('round_start_date')->nullable();
            $table->timestamp('round_end_date')->nullable();

            $table->boolean('checkin_enabled')->nullable()->default(false);
            $table->integer('checkin_mintues_prior')->nullable();
            $table->integer('elimination_type')->nullable();
            $table->string('timezone', 5)->nullable();

            $table->uuid('venue_id')->nullable();

            $table->foreign('competition_id')->references('id')->on('competitions')->onDelete('cascade');
            $table->foreign('venue_id')->references('id')->on('competition_venues')->onDelete('cascade');
            
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('competition_rounds');
    }
};
