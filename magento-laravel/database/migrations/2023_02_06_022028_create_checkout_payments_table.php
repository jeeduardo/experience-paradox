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
        Schema::create('checkout_payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('magento_payment_id')->nullable();
            $table->unsignedBigInteger('cart_id');
            $table->unsignedInteger('magento_quote_id')->nullable();
            // method in quote_payment is varchar(255) - why?
            $table->string('method', 64)->nullable();
            $table->text('additional_data')->nullable();
            $table->foreign('cart_id')->references('id')->on('carts');
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
        Schema::dropIfExists('checkout_payments');
    }
};
