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
        Schema::create('event_ticket_types', function (Blueprint $table) {
            $table->uuid('id');
            $table->primary('id');

            $table->uuid('event_id');

            $table->string('name');
            $table->text('description')->default('')->nullable();

            $table->integer('max_available')->default(0)->nullable();
            $table->integer('min_purchasable')->default(0)->nullable();
            $table->integer('max_purchasable')->default(0)->nullable();

            $table->double('price')->default(0);

            $table->tinyInteger('ticket_type')->default(1); //Paid, Donation, Free
            $table->tinyInteger('visibility')->default(1);

            $table->boolean('disabled')->default(false)->nullable();
            $table->boolean('requires_account')->default(0);

            $table->timestamp('sales_start_date')->nullable();
            $table->timestamp('sales_end_date')->nullable();
            $table->timestamp('visibility_start_date')->nullable(); //For when visibility is scheduled
            $table->timestamp('visibility_end_date')->nullable();
            $table->timestamp('ticket_usage_date')->nullable(); //For Single Day Tickets

            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');

            $table->timestamps();
        });

        DB::statement('ALTER TABLE event_ticket_types ALTER COLUMN id SET DEFAULT uuid_generate_v4();');

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('event_ticket_types');
    }
};
