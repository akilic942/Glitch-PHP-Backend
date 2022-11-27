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
        Schema::create('event_overlays', function (Blueprint $table) {
            $table->uuid('id');
            $table->primary('id');
            $table->uuid('event_id');

            $table->boolean('is_active')->nullable()->default(0);
            $table->string('image_url')->default('');
            $table->string('label')->default('')->nullable();

            $table->foreign('event_id')->references('id')->on('events');

            $table->timestamps();
        });

        DB::statement('ALTER TABLE event_overlays ALTER COLUMN id SET DEFAULT uuid_generate_v4();');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('event_overlays');
    }
};
