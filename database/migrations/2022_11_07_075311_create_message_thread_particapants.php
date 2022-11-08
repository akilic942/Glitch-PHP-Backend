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
        Schema::create('message_thread_particapants', function (Blueprint $table) {

            $table->uuid('thread_id');
            $table->uuid('user_id');

            $table->primary(array('user_id', 'thread_id'));

            $table->foreign('thread_id')->references('id')->on('message_threads');
            $table->foreign('user_id')->references('id')->on('users');

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
        Schema::dropIfExists('message_thread_particapants');
    }
};
