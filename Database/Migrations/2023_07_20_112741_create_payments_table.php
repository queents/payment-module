<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();

            // maybe user, admin, driver, vendor etc..
            $table->unsignedBigInteger('model_id');
            $table->string('model_table');

            // maybe order, service, subscription etc..
            $table->unsignedBigInteger('order_id');
            $table->string('order_table');

            $table->foreignId('payment_method_id')
                ->nullable()
                ->constrained()
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->unsignedBigInteger('payment_status_id');

            $table->foreign('payment_status_id')
                ->on('payment_status')
                ->references('id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            //Amounts
            $table->string('transaction_code')->nullable()->unique();
            $table->double('amount')->unsigned()->default(0);
            $table->string('notes')->nullable();



            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payments');
    }
};
