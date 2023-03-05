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
        Schema::create('sales_today', function (Blueprint $table) {
            $table->id();
            // Sales for today table...
            $table->string('year', 4);
            $table->string('month', 2);
            $table->string('day', 2);
            $table->date('date_today');
            $table->decimal('order_amount_total', 20, 4)->default(0);
            $table->integer('orders_total')->default(0);
            $table->integer('orders_cancelled_total')->default(0);
            $table->decimal('order_amount_refund_total', 12, 4)->default(0);
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
        Schema::dropIfExists('sales_today');
    }
};
