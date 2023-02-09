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
        Schema::create('directory_country_regions', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('region_id');
            $table->string('country_id', 4);
            $table->string('code', 32)->nullable();
            $table->string('default_name')->nullable();
            $table->index('country_id');
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
        Schema::dropIfExists('directory_country_regions');
    }
};
