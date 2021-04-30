<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateTuitionPricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tuition_prices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tuition_id');
            $table->unsignedBigInteger('price_id');
            $table->timestamps();

            $table->foreign('tuition_id')->references('id')->on('tuition');
            $table->foreign('price_id')->references('id')->on('price');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::dropIfExists('tuition_prices');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
