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
        Schema::create('waitlists', function (Blueprint $table) {
            $table->uuid('id');
            $table->primary('id');

            $table->string('email');

            $table->string('first_name')->nullable()->default('');
            $table->string('last_name')->nullable()->default('');
            $table->string('full_name')->nullable()->default('');
            $table->string('display_name')->nullable()->default('');
            $table->string('username')->nullable()->default('');
            $table->string('phone_number')->nullable()->default('');
            $table->string('website')->nullable()->default('');
            $table->string('title')->nullable()->default('');
            $table->string('company')->nullable()->default('');

            $table->json('meta')->nullable(); //For extra fields

            $table->timestamps();
        });

        DB::statement('ALTER TABLE waitlists ALTER COLUMN id SET DEFAULT uuid_generate_v4();');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('waitlists');
    }
};
