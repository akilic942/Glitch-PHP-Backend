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
        Schema::create('competition_ticket_type_sections', function (Blueprint $table) {
            $table->uuid('id');
            $table->primary('id');

            $table->uuid('ticket_type_id');

            $table->string('title');
            $table->text('instructions')->default('');
            $table->integer('section_order')->default(0);

            $table->foreign('ticket_type_id')->references('id')->on('competition_ticket_types')->onDelete('cascade');

            $table->timestamps();
        });

        DB::statement('ALTER TABLE competition_ticket_type_sections ALTER COLUMN id SET DEFAULT uuid_generate_v4();');

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('competition_ticket_type_sections');
    }
};
