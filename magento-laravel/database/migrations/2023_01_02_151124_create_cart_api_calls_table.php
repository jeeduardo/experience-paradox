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
        Schema::create('cart_api_calls', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cart_id')->nullable();
            $table->unsignedBigInteger('cart_item_id')->nullable();
            $table->string('magento_api_url', 2000);
            $table->string('method', 20)->default('GET');
            $table->text('payload')->nullable();
            $table->string('response_status', 4)->nullable();
            $table->text('response')->nullable();
            // pending - running - success - error
            $table->string('status', 32)->default('pending');
            $table->text('error')->nullable();
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
        Schema::dropIfExists('cart_api_calls');
    }
};
