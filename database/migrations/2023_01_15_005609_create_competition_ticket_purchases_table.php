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
        Schema::create('competition_ticket_purchases', function (Blueprint $table) {
            $table->uuid('id');
            $table->primary('id');

            $table->uuid('ticket_type_id');

            $table->timestamps();
        });

        DB::statement('ALTER TABLE competition_ticket_purchases ALTER COLUMN id SET DEFAULT uuid_generate_v4();');

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('competition_ticket_purchases');
    }
};
