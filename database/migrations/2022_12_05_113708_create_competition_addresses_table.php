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
        Schema::create('competition_addresses', function (Blueprint $table) {
            $table->uuid('id');
            $table->primary('id');

            $table->uuid('competition_id');
            
            //Location
            $table->string('venue_name')->nullable()->default('');
            $table->text('venue_description')->nullable();
            $table->string('address_line_1')->nullable()->default('');
            $table->string('address_line_2')->nullable()->default('');
            $table->string('postal_code')->nullable()->default('');
            $table->string('locality')->nullable()->default(''); //city
            $table->string('province')->nullable()->default(''); //state
            $table->string('country')->nullable()->default('');

            $table->string('venue_image')->nullable()->default('');

            $table->tinyInteger('is_virtual_hybrid_remote')->nullable()->default(0);

            $table->foreign('competition_id')->references('id')->on('competitions');

            $table->timestamps();
        });

        DB::statement('ALTER TABLE competition_addresses ALTER COLUMN id SET DEFAULT uuid_generate_v4();');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('competition_addresses');
    }
};
