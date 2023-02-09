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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('magento_order_id')->nullable();
            $table->string('state', 32)->nullable();
            $table->string('status', 32)->nullable();
            $table->string('coupon_code')->nullable();
            $table->string('shipping_description')->nullable();
            $table->unsignedInteger('customer_id')->nullable();
            $table->decimal('base_discount_amount', 20, 4)->nullable();
            $table->decimal('base_discount_canceled', 20, 4)->nullable();
            $table->decimal('base_discount_invoiced', 20, 4)->nullable();
            $table->decimal('base_discount_refunded', 20, 4)->nullable();
            $table->decimal('base_grand_total', 20, 4)->nullable();
            $table->decimal('base_shipping_amount', 20, 4)->nullable();
            $table->decimal('base_shipping_canceled', 20, 4)->nullable();
            $table->decimal('base_shipping_invoiced', 20, 4)->nullable();
            $table->decimal('base_shipping_refunded', 20, 4)->nullable();
            $table->decimal('base_shipping_tax_amount', 20, 4)->nullable();
            $table->decimal('base_shipping_tax_refunded', 20, 4)->nullable();
            $table->decimal('base_subtotal', 20, 4)->nullable();
            $table->decimal('base_subtotal_canceled', 20, 4)->nullable();
            $table->decimal('base_subtotal_invoiced', 20, 4)->nullable();
            $table->decimal('base_subtotal_refunded', 20, 4)->nullable();
            $table->decimal('base_tax_amount', 20, 4)->nullable();
            $table->decimal('base_tax_canceled', 20, 4)->nullable();
            $table->decimal('base_tax_invoiced', 20, 4)->nullable();
            $table->decimal('base_tax_refunded', 20, 4)->nullable();
            $table->decimal('base_to_global_rate', 20, 4)->nullable();
            $table->decimal('base_to_order_rate', 20, 4)->nullable();
            $table->decimal('base_total_canceled', 20, 4)->nullable();
            $table->decimal('base_total_invoiced', 20, 4)->nullable();
            $table->decimal('base_total_invoiced_cost', 20, 4)->nullable();
            $table->decimal('base_total_offline_refunded', 20, 4)->nullable();
            $table->decimal('base_total_online_refunded', 20, 4)->nullable();
            $table->decimal('base_total_paid', 20, 4)->nullable();
            $table->decimal('base_total_qty_ordered', 20, 4)->nullable();
            $table->decimal('base_total_refunded', 20, 4)->nullable();
            $table->decimal('discount_amount', 20, 4)->nullable();
            $table->decimal('discount_canceled', 20, 4)->nullable();
            $table->decimal('discount_invoiced', 20, 4)->nullable();
            $table->decimal('discount_refunded', 20, 4)->nullable();
            $table->decimal('grand_total', 20, 4)->nullable();
            $table->decimal('shipping_amount', 20, 4)->nullable();
            $table->decimal('shipping_canceled', 20, 4)->nullable();
            $table->decimal('shipping_invoiced', 20, 4)->nullable();
            $table->decimal('shipping_refunded', 20, 4)->nullable();
            $table->decimal('shipping_tax_amount', 20, 4)->nullable();
            $table->decimal('shipping_tax_refunded', 20, 4)->nullable();
            $table->decimal('store_to_base_rate', 20, 4)->nullable();
            $table->decimal('store_to_order_rate', 20, 4)->nullable();
            $table->decimal('subtotal', 20, 4)->nullable();
            $table->decimal('subtotal_canceled', 20, 4)->nullable();
            $table->decimal('subtotal_invoiced', 20, 4)->nullable();
            $table->decimal('subtotal_refunded', 20, 4)->nullable();
            $table->decimal('tax_amount', 20, 4)->nullable();
            $table->decimal('tax_canceled', 20, 4)->nullable();
            $table->decimal('tax_invoiced', 20, 4)->nullable();
            $table->decimal('tax_refunded', 20, 4)->nullable();
            $table->decimal('total_canceled', 20, 4)->nullable();
            $table->decimal('total_invoiced', 20, 4)->nullable();
            $table->decimal('total_offline_refunded', 20, 4)->nullable();
            $table->decimal('total_online_refunded', 20, 4)->nullable();
            $table->decimal('total_paid', 20, 4)->nullable();
            $table->decimal('total_qty_ordered', 12, 4)->nullable();
            $table->decimal('total_refunded', 20, 4)->nullable();
            $table->unsignedSmallInteger('can_ship_partially')->nullable();
            $table->unsignedSmallInteger('can_ship_partially_item')->nullable();
            $table->tinyInteger('customer_is_guest')->nullable();
            $table->tinyInteger('customer_note_notify')->nullable();
            $table->integer('billing_address_id')->nullable();
            $table->integer('customer_group_id')->nullable();
            $table->integer('edit_increment')->nullable();
            // Index this
            $table->unsignedSmallInteger('email_sent')->nullable()->index();
            // Index this
            $table->unsignedSmallInteger('send_email')->nullable()->index();
            $table->unsignedSmallInteger('force_shipment_with_invoice')->nullable();
            // Index this
            $table->integer('quote_id')->nullable()->index();
            $table->decimal('adjustment_negative', 20, 4)->nullable();
            $table->decimal('adjustment_positive', 20, 4)->nullable();
            $table->decimal('base_adjustment_negative', 20, 4)->nullable();
            $table->decimal('base_adjustment_positive', 20, 4)->nullable();
            $table->decimal('base_shipping_discount_amount', 20, 4)->nullable();
            $table->decimal('base_subtotal_incl_tax', 20, 4)->nullable();
            $table->decimal('base_total_due', 20, 4)->nullable();
            $table->decimal('payment_authorization_amount', 20, 4)->nullable();
            $table->decimal('shipping_discount_amount', 20, 4)->nullable();
            $table->decimal('subtotal_incl_tax', 20, 4)->nullable();
            $table->decimal('total_due', 20, 4)->nullable();
            $table->decimal('weight', 12, 4)->nullable();
            $table->string('increment_id', 50)->nullable();
            $table->string('applied_rule_ids', 128)->nullable();
            $table->string('base_currency_code', 3)->nullable();
            $table->string('customer_email', 128)->nullable();
            $table->string('customer_firstname', 128)->nullable();
            $table->string('customer_lastname', 128)->nullable();
            $table->string('customer_middlename', 128)->nullable();
            $table->string('customer_prefix', 32)->nullable();
            $table->string('customer_suffix', 32)->nullable();
            $table->string('customer_taxvat', 32)->nullable();
            $table->string('discount_description', 255)->nullable();
            $table->string('ext_customer_id', 32)->nullable();
            $table->string('ext_order_id', 32)->nullable();
            $table->string('global_currency_code', 3)->nullable();
            $table->string('hold_before_state', 32)->nullable();
            $table->string('hold_before_status', 32)->nullable();
            $table->string('order_currency_code', 3)->nullable();
            $table->string('original_increment_id', 50)->nullable();
            $table->string('relation_child_id', 32)->nullable();
            $table->string('relation_child_real_id', 32)->nullable();
            $table->string('relation_parent_id', 32)->nullable();
            $table->string('relation_parent_real_id', 32)->nullable();
            $table->string('remote_ip', 45)->nullable();
            $table->string('shipping_method')->nullable();
            $table->string('store_currency_code', 3)->nullable();
            $table->string('store_name', 255)->nullable();
            $table->string('x_forwarded_for', 255)->nullable();
            $table->integer('payment_auth_expiration')->nullable();
            $table->integer('quote_address_id')->nullable();
            $table->integer('shipping_address_id')->nullable();
            $table->dateTime('customer_dob')->nullable();
            $table->text('customer_note')->nullable();
            $table->unsignedSmallInteger('total_item_count')->default(0);
            $table->tinyInteger('customer_gender')->nullable();
            $table->decimal('discount_tax_compensation_amount', 20, 4)->nullable();
            $table->decimal('base_discount_tax_compensation_amount', 20, 4)->nullable();
            // Index this
            $table->decimal('shipping_discount_tax_compensation_amount', 20, 4)->nullable()->index();
            $table->decimal('base_shipping_discount_tax_compensation_amnt', 20, 4)->nullable();
            $table->decimal('discount_tax_compensation_invoiced', 20, 4)->nullable();
            $table->decimal('base_discount_tax_compensation_invoiced', 20, 4)->nullable();
            $table->decimal('discount_tax_compensation_refunded', 20, 4)->nullable();
            $table->decimal('base_discount_tax_compensation_refunded', 20, 4)->nullable();
            $table->decimal('shipping_incl_tax', 20, 4)->nullable();
            $table->decimal('base_shipping_incl_tax', 20, 4)->nullable();
            $table->string('coupon_rule_name')->nullable();
            $table->integer('gift_message_id')->nullable();
            $table->integer('paypal_ipn_customer_notified')->default(0)->nullable();
            /*
             */
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
        Schema::dropIfExists('orders');
    }
};
