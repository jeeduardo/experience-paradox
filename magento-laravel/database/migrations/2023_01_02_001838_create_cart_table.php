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
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('magento_quote_id');
            $table->string('checkout_method')->nullable();
            $table->unsignedInteger('customer_id')->nullable();
            $table->string('customer_email')->nullable();
            $table->string('customer_firstname')->nullable();
            $table->string('customer_middlename', 40)->nullable();
            $table->string('customer_lastname')->nullable();
            $table->string('customer_suffix', 40)->nullable();
            $table->unsignedSmallInteger('customer_is_guest')->default(0)->nullable();
            $table->string('remote_ip', 45)->nullable();
            $table->decimal('grand_total', 20, 4)->default(0)->nullable();
            $table->decimal('base_grand_total', 20, 4)->default(0)->nullable();
            $table->decimal('subtotal', 20, 4)->default(0)->nullable();
            $table->decimal('base_subtotal', 20, 4)->default(0)->nullable();
            $table->unsignedInteger('items_count')->nullable();
            $table->decimal('items_qty', 12, 4)->nullable();
            $table->unsignedSmallInteger('is_active')->default(1)->nullable();
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
        Schema::dropIfExists('carts');
    }
};
