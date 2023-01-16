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
        Schema::create('competition_ticket_purchase_transactions', function (Blueprint $table) {
            $table->uuid('id');
            $table->primary('id');

            $table->uuid('purchase_id');

            $table->tinyInteger('payment_processor');

            $table->tinyInteger('payment_or_refund'); //1 for payment, 2 for refund
            $table->tinyInteger('transaction_state')->default(0); 

            $table->string('transaction_id')->nullable();
            $table->string('transaction_to_currency')->default('')->nullable();
            $table->string('transaction_from_currency')->default('')->nullable();
            $table->double('transaction_conversion_rate')->default(0);
            $table->double('transaction_amount')->default(0);
            $table->double('transaction_processing_fee')->default(0);

            $table->boolean('transaction_successful')->default(0);
            $table->boolean('transaction_voided')->default(0);

            $table->json('meta')->nullable();

            $table->foreign('purchase_id')->references('id')->on('competition_ticket_purchases')->onDelete('cascade');

            $table->timestamps();
        });

        DB::statement('ALTER TABLE competition_ticket_purchase_transactions ALTER COLUMN id SET DEFAULT uuid_generate_v4();');

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('competition_ticket_purchase_transactions');
    }
};
