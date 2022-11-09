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
        Schema::create('events', function (Blueprint $table) {
            $table->uuid('id');
            $table->primary('id');

            //Event Info
            $table->string('title');
            $table->text('description');
            $table->date('start_date')->nullable();

            $table->boolean('is_live')->nullable()->default(false);

            $table->string('image_main')->nullable();;
            $table->string('image_banner')->nullable();;

            //Invirtu Streaming Information
            $table->string('invirtu_id')->uuid('id')->nullable();
            $table->string('invirtu_webrtc_url')->nullable()->default('');
            $table->string('invirtu_livestream_url')->nullable()->default('');
            $table->string('invirtu_broadcast_url')->nullable()->default('');
            $table->string('invirtu_rtmp_livestream_endpoint')->nullable()->default('');
            $table->string('invirtu_rtmp_livestream_key')->nullable()->default('');
            $table->string('invirtu_rtmp_broadcast_endpoint')->nullable()->default('');
            $table->string('invirtu_rtmp_broadcast_key')->nullable()->default('');

            $table->timestamps();


        });

        DB::statement('ALTER TABLE events ALTER COLUMN id SET DEFAULT uuid_generate_v4();');

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('events');
    }
};
