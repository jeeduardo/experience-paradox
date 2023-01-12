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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('magento_product_id');
            $table->unsignedSmallInteger('attribute_set_id');
            $table->string('type_id', 32);
            $table->string('sku', 64);
            $table->decimal('price', 20, 6);
            $table->string('name', 255);
            $table->integer('status')->default(0);
            $table->smallInteger('visibility')->nullable();
            $table->string('media_gallery_entries', 4000)->nullable();
            $table->string('description', 4000)->nullable();
            $table->string('short_description', 1000)->nullable();
            $table->index('name');
            $table->index('sku');
            $table->index('type_id');
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
        Schema::dropIfExists('products');
    }
};
