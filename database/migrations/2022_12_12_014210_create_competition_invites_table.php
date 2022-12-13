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
        Schema::create('competition_invites', function (Blueprint $table) {
            $table->uuid('id');
            $table->primary('id');
            $table->uuid('competition_id');
            $table->uuid('user_id')->nullable();

            $table->string('email');
            $table->string('name')->nullable()->default('');

            $table->string('token')->nullable()->default('');

            $table->integer('role')->nullable();

            $table->boolean('invited_as_participant')->nullable()->default(0);
            $table->boolean('invited_as_team_member')->nullable()->default(0);
            $table->boolean('accepted_invite')->nullable()->default(0);

            $table->foreign('competition_id')->references('id')->on('competitions')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->timestamps();
        });

        DB::statement('ALTER TABLE competition_invites ALTER COLUMN id SET DEFAULT uuid_generate_v4();');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('competition_invites');
    }
};
