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
        Schema::create('webhooks', function (Blueprint $table) {
            $table->uuid('id');
            $table->primary('id');

            $table->integer('incoming_outgoing'); //incoming is 1, outgoing is 2

            $table->string('action')->nullable()->default('');
            $table->text('data')->nullable();

            
            $table->string('url')->nullable()->default('');
            $table->string('request_method')->nullable()->default('');
            $table->string('signature_key')->nullable()->default('');
            $table->string('signature_value')->nullable()->default('');

            $table->boolean('processed')->nullable()->default(false);


            $table->timestamps();
        });

        DB::statement('ALTER TABLE webhooks ALTER COLUMN id SET DEFAULT uuid_generate_v4();');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('webhooks');
    }
};
