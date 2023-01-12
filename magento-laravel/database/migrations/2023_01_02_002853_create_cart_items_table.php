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
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('magento_quote_item_id');
            $table->unsignedInteger('magento_quote_id');
            $table->unsignedBigInteger('cart_id');
            $table->string('sku');
            $table->string('name');
            $table->unsignedBigInteger('product_id');
            $table->decimal('qty', 12, 4);
            $table->decimal('price', 12, 4);
            $table->decimal('base_price', 12, 4);
            $table->decimal('discount_percent', 12, 4)->default(0)->nullable();
            $table->decimal('discount_amount', 20, 4)->default(0)->nullable();
            $table->decimal('base_discount_amount', 20, 4)->default(0)->nullable();
            $table->decimal('tax_percent', 12, 4)->default(0)->nullable();
            $table->decimal('tax_amount', 20, 4)->default(0)->nullable();
            $table->decimal('base_tax_amount', 20, 4)->default(0)->nullable();
            $table->decimal('row_total', 20, 4)->default(0);
            $table->decimal('base_row_total', 20, 4)->default(0);
            $table->decimal('row_total_with_discount', 20, 4)->default(0)->nullable();
            $table->string('product_type')->nullable();
            $table->decimal('base_tax_before_discount', 20, 4)->nullable();
            $table->decimal('tax_before_discount', 20, 4)->nullable();
            $table->decimal('original_custom_price', 12, 4)->nullable();
            $table->string('redirect_url')->nullable();
            $table->decimal('price_incl_tax', 20, 4)->nullable();
            $table->decimal('base_price_incl_tax', 20, 4)->nullable();
            $table->decimal('row_total_incl_tax', 20, 4)->nullable();
            $table->decimal('base_row_total_incl_tax', 20, 4)->nullable();
            $table->unsignedSmallInteger('free_shipping')->default(0);
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
        Schema::dropIfExists('cart_items');
    }
};
