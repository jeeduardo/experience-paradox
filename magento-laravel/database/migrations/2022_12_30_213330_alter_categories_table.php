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
        //
        Schema::table('categories', function (Blueprint $table) {
            $table->smallInteger('level')->nullable()->change();
            $table->string('path', 20)->nullable()->change();
            $table->smallInteger('include_in_menu')->nullable()->change();
            $table->string('custom_attributes', 4000)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->smallInteger('level')->change();
            $table->string('path', 20)->change();
            $table->smallInteger('include_in_menu')->change();
            $table->string('custom_attributes', 4000)->change();
        });
    }
};
