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
        Schema::create('event_invites', function (Blueprint $table) {
            $table->uuid('id');
            $table->primary('id');
            $table->uuid('event_id');
            $table->uuid('user_id')->nullable();

            $table->string('email')->unique();
            $table->string('name')->nullable()->default('');

            $table->string('token')->nullable()->default('');

            $table->boolean('invited_as_cohost')->nullable()->default(0);
            $table->boolean('accepted_invite')->nullable()->default(0);

            $table->foreign('event_id')->references('id')->on('events');
            $table->foreign('user_id')->references('id')->on('users');

            $table->timestamps();
        });

        DB::statement('ALTER TABLE event_invites ALTER COLUMN id SET DEFAULT uuid_generate_v4();');

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('event_invites');
    }
};
