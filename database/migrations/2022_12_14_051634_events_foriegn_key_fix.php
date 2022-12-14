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
        Schema::table('event_users', function (Blueprint $table) {
          
            $table->dropForeign('event_users_event_id_foreign');
    
        });

        Schema::table('event_overlays', function (Blueprint $table) {
          
            $table->dropForeign('event_overlays_event_id_foreign');
    
        });

        Schema::table('event_users', function (Blueprint $table) {
          
            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');;
    
        });

        Schema::table('event_overlays', function (Blueprint $table) {
          
            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');;
    
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
