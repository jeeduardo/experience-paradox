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
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->integer('magento_category_id')->unsigned();
            $table->integer('magento_parent_category_id')->unsigned();
            $table->string('name', 255);
            $table->smallInteger('is_active')->default(1);
            $table->smallInteger('position')->nullable();
            $table->smallInteger('level')->default(0);
            $table->string('path', 20);
            $table->smallInteger('include_in_menu')->default(1);
            $table->string('custom_attributes', 4000);
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
        Schema::dropIfExists('categories');
    }
};
