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
        Schema::create('messages', function (Blueprint $table) {
            $table->uuid('id');
            $table->primary('id');

            $table->text('message');

            $table->uuid('thread_id');
            $table->uuid('user_id');

            $table->foreign('thread_id')->references('id')->on('message_threads');
            $table->foreign('user_id')->references('id')->on('users');

            $table->timestamps();
        });

        DB::statement('ALTER TABLE messages ALTER COLUMN id SET DEFAULT uuid_generate_v4();');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('messages');
    }
};
