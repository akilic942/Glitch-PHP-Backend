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
        Schema::create('images', function (Blueprint $table) {
            $table->uuid('id');
            $table->primary('id');
            
            $table->string('original_image_url')->default('');
            $table->string('large_image_url')->default('');
            $table->string('large_square_image_url')->default('');
            $table->string('medium_image_url')->default('');
            $table->string('medium_square_image_url')->default('');
            $table->string('small_image_url')->default('');
            $table->string('small_square_image_url')->default('');
            $table->string('thumbnail_url')->default('');


            $table->timestamps();
        });

        DB::statement('ALTER TABLE images ALTER COLUMN id SET DEFAULT uuid_generate_v4();');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('images');
    }
};
