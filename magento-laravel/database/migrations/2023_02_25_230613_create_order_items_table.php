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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('magento_item_id')->nullable();
            $table->unsignedBigInteger('order_id');
            $table->unsignedInteger('magento_order_id')->nullable();
            $table->unsignedInteger('magento_parent_item_id')->nullable();
            $table->unsignedInteger('magento_quote_item_id')->nullable();
            $table->unsignedSmallInteger('store_id')->nullable();
            $table->unsignedBigInteger('product_id');
            $table->foreign('order_id')->references('id')->on('orders');
            $table->foreign('product_id')->references('id')->on('products');
            $table->string('sku', 64);
            $table->string('name', 255);
            $table->text('description')->nullable();
            $table->text('applied_rule_ids')->nullable();
            $table->text('additional_data')->nullable();
            $table->unsignedTinyInteger('is_qty_decimal')->nullable();
            $table->unsignedTinyInteger('no_discount')->default(0);
            $table->decimal('qty_backordered', 12, 4)->nullable()->default(0);
            $table->decimal('qty_canceled', 12, 4)->nullable()->default(0);
            $table->decimal('qty_invoiced', 12, 4)->nullable()->default(0);
            $table->decimal('qty_ordered', 12, 4)->nullable()->default(0);
            $table->decimal('qty_shipped', 12, 4)->nullable()->default(0);
            $table->decimal('base_cost', 12, 4)->nullable()->default(0);
            $table->decimal('price', 12, 4)->default(0);
            $table->decimal('base_price', 12, 4)->default(0);
            $table->decimal('original_price', 12, 4)->default(0);
            $table->decimal('base_original_price', 12, 4)->default(0);
            $table->decimal('tax_percent', 12, 4)->default(0);
            $table->decimal('tax_amount', 20, 4)->nullable()->default(0);
            $table->decimal('base_tax_amount', 20, 4)->nullable()->default(0);
            $table->decimal('tax_invoiced', 20, 4)->nullable()->default(0);
            $table->decimal('base_tax_invoiced', 20, 4)->nullable()->default(0);
            $table->decimal('discount_percent', 12, 4)->nullable()->default(0);
            $table->decimal('discount_amount', 20, 4)->nullable()->default(0);
            $table->decimal('base_discount_amount', 20, 4)->nullable()->default(0);
            $table->decimal('discount_invoiced', 20, 4)->nullable()->default(0);
            $table->decimal('base_discount_invoiced', 20, 4)->nullable()->default(0);
            $table->decimal('amount_refunded', 20, 4)->nullable()->default(0);
            $table->decimal('base_amount_refunded', 20, 4)->nullable()->default(0);
            $table->decimal('row_total', 20, 4)->default(0);
            $table->decimal('base_row_total', 20, 4)->default(0);
            $table->decimal('row_invoiced', 20, 4)->default(0);
            $table->decimal('base_row_invoiced', 20, 4)->default(0);
            $table->decimal('price_incl_tax', 20, 4)->nullable();
            $table->decimal('base_price_incl_tax', 20, 4)->nullable();
            $table->decimal('row_total_incl_tax', 20, 4)->nullable();
            $table->decimal('base_row_total_incl_tax', 20, 4)->nullable();
            $table->decimal('row_weight', 12, 4)->nullable()->default(0);
            $table->text('other_magento_data')->nullable();
            $table->unsignedSmallInteger('locked_do_invoice')->nullable();
            $table->unsignedSmallInteger('locked_do_ship')->nullable();
            $table->unsignedSmallInteger('free_shipping')->default(0);
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
        Schema::dropIfExists('order_items');
    }
};
