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
        Schema::create('event_ticket_purchases', function (Blueprint $table) {
            $table->uuid('id');
            $table->primary('id');

            $table->uuid('ticket_type_id');
            $table->uuid('user_id')->nullable();

            $table->json('fields')->nullable(); //For the fields that are stored

            $table->integer('quantity')->default(1);
            
            $table->double('subtotal')->default(0);
            $table->double('fees')->default(0);
            $table->double('taxes')->default(0);
            $table->double('total_price')->default(0);

            $table->string('currency')->default('');
            $table->string('access_token')->default('')->nullable();
            $table->string('admin_token')->default('')->nullable();

            $table->double('platform_take')->default(0);
            $table->double('payment_processing_take')->default(0);
            $table->double('host_take')->default(0);

            $table->boolean('has_access')->default(0);
            $table->boolean('fully_paid')->default(0);
            $table->boolean('show_entry')->default(0);
            $table->boolean('is_voided')->default(0);

            $table->foreign('ticket_type_id')->references('id')->on('event_ticket_types')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->timestamps();
        });

        DB::statement('ALTER TABLE event_ticket_purchases ALTER COLUMN id SET DEFAULT uuid_generate_v4();');

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('event_ticket_purchases');
    }
};
