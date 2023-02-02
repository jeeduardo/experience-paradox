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
        Schema::create('checkout_address', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cart_id');
            $table->unsignedInteger('magento_quote_address_id')->nullable();
            $table->unsignedInteger('customer_id')->nullable();
            $table->string('address_type', 10);
            $table->string('email');
            $table->string('prefix', 40);
            $table->string('firstname');
            $table->string('middlename', 128);
            $table->string('lastname');

            $table->string('suffix', 40)->nullable();
            $table->string('company')->nullable();
            $table->string('street')->nullable();
            $table->string('city')->nullable();
            $table->string('region')->nullable();
            $table->string('postcode', 20);
            $table->string('country_id', 30);
            $table->string('telephone', 64);
            $table->tinyInteger('same_as_billing')->nullable()->default(1);
            $table->unsignedSmallInteger('collect_shipping_rates')->default(0);
            $table->string('shipping_method', 120)->nullable();
            $table->string('shipping_description')->nullable();
            $table->decimal('weight', 12, 4)->default(0);
            $table->decimal('subtotal', 20, 4)->default(0);
            $table->decimal('base_subtotal', 20, 4)->default(0);
            $table->decimal('subtotal_with_discount', 20, 4)->default(0);
            $table->decimal('base_subtotal_with_discount', 20, 4)->default(0);
            $table->decimal('tax_amount', 20, 4)->default(0);
            $table->decimal('base_tax_amount', 20, 4)->default(0);
            $table->decimal('shipping_amount', 20, 4)->default(0);
            $table->decimal('base_shipping_amount', 20, 4)->default(0);
            $table->decimal('shipping_tax_amount', 20, 4)->nullable();
            $table->decimal('base_shipping_tax_amount', 20, 4)->nullable();
            $table->decimal('discount_amount', 20, 4)->default(0);
            $table->decimal('base_discount_amount', 20, 4)->default(0);
            $table->decimal('grand_total', 20, 4)->default(0);
            $table->decimal('base_grand_total', 20, 4)->default(0);
            $table->text('customer_notes')->nullable();
            $table->text('applied_taxes')->nullable();
            $table->string('discount_description')->nullable();
            $table->decimal('shipping_discount_amount', 20, 4)->nullable();
            $table->decimal('base_shipping_discount_amount', 20, 4)->nullable();
            $table->decimal('subtotal_incl_tax', 20, 4)->nullable();
            $table->decimal('base_subtotal_incl_tax', 20, 4)->nullable();
            $table->decimal('discount_tax_compensation_amount', 20, 4)->nullable();
            $table->decimal('base_discount_tax_compensation_amount', 20, 4)->nullable();
            $table->decimal('shipping_discount_tax_compensation_amount', 20, 4)->nullable();
            $table->decimal('base_shipping_discount_tax_compensation_amnt', 20, 4)->nullable();
            $table->decimal('shipping_incl_tax', 20, 4)->nullable();
            $table->decimal('base_shipping_incl_tax', 20, 4)->nullable();
            $table->text('vat_id')->nullable();
            $table->smallInteger('vat_is_valid')->nullable();
            $table->text('vat_request_id')->nullable();
            $table->text('vat_request_date')->nullable();
            $table->smallInteger('vat_request_success')->nullable();
            $table->text('validated_country_code')->nullable();
            $table->text('validated_vat_number')->nullable();
            $table->integer('gift_message_id')->nullable();
            $table->unsignedSmallInteger('free_shipping')->default(0);
            $table->foreign('cart_id')
                ->references('id')
                ->on('carts')
                ->onDelete('cascade');
            // onDelete('cascade') is easier to read for me,
            // but you can use cascadeOnDelete also.. might be confusing though =p
            // Following were not included...
            /*
| region_id                                    | int(10) unsigned     | YES  |     | NULL                |                               |
| fax                                          | varchar(255)         | YES  |     | NULL                |                               |
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
        Schema::dropIfExists('checkout_address');
    }
};
