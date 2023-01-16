<?php

use Illuminate\Database\Migrations\Migration;
//use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
//use Illuminate\Support\Facades\Schema;

use Tpetry\PostgresqlEnhanced\Schema\Blueprint;
use Tpetry\PostgresqlEnhanced\Schema\Concerns\ZeroDowntimeMigration;
use Tpetry\PostgresqlEnhanced\Support\Facades\Schema;


return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::createExtensionIfNotExists('hstore');

        Schema::create('event_ticket_type_fields', function (Blueprint $table) {
            $table->uuid('id');
            $table->primary('id');

            $table->uuid('ticket_type_id');
            $table->uuid('section_id')->nullable();

            $table->tinyInteger('field_type');

            $table->string('label');
            $table->string('name');
            $table->integer('field_order')->default(0);
            $table->json('field_items')->nullable(); //For select fields

            $table->boolean('is_required')->default(false)->nullable();
            $table->boolean('is_disabled')->default(false)->nullable();

            $table->foreign('ticket_type_id')->references('id')->on('event_ticket_types')->onDelete('cascade');
            $table->foreign('section_id')->references('id')->on('event_ticket_type_sections')->onDelete('cascade');
            
            $table->timestamps();
        });

        DB::statement('ALTER TABLE event_ticket_type_fields ALTER COLUMN id SET DEFAULT uuid_generate_v4();');

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('event_ticket_type_fields');
    }
};
