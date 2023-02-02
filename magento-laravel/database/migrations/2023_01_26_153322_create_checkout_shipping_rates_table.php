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
        Schema::create('checkout_shipping_rates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('address_id')->nullable();
            $table->unsignedInteger('magento_address_id')->nullable();
            $table->string('carrier', 64)->nullable();
            $table->string('carrier_title')->nullable();
            $table->string('code', 64)->nullable();
            $table->string('method', 128)->nullable();
            $table->text('method_description')->nullable();
            $table->decimal('price', 20, 4)->default(0);
            $table->text('error_message')->nullable();
            $table->text('method_title')->nullable();
            $table->foreign('address_id')
                ->references('id')
                ->on('checkout_address')
                ->onDelete('cascade');
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
        Schema::dropIfExists('checkout_shipping_rates');
    }
};
