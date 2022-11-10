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
        Schema::create('recordings', function (Blueprint $table) {
            $table->uuid('id');
            $table->primary('id');

            $table->uuid('event_id');

            $table->string('name');
            $table->text('description');

            $table->string('main_image')->nullable();

            $table->boolean('is_private')->nullable()->default(0);

            $table->boolean('published_to_facebook')->nullable()->default(0);
            $table->boolean('published_to_youtube')->nullable()->default(0);
            $table->boolean('published_to_twtich')->nullable()->default(0);

            $table->boolean('enable_auto_publish_to_facebook')->nullable()->default(0);
            $table->boolean('enable_auto_publish_to_youtube')->nullable()->default(0);
            $table->boolean('enable_auto_publish_to_twtich')->nullable()->default(0);

            $table->string('invirtu_video_id')->uuid('id')->nullable();

            $table->foreign('event_id')->references('id')->on('events');

            $table->timestamps();
        });

        DB::statement('ALTER TABLE recordings ALTER COLUMN id SET DEFAULT uuid_generate_v4();');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('recordings');
    }
};
