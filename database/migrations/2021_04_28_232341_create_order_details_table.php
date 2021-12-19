<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_details', function (Blueprint $table) {
            $table->id()->primary();
            $table->string('payment_intent');
            $table->string('client_sc');
            $table->unsignedBigInteger('productable_id');
            $table->string('productable_type');
            $table->unsignedBigInteger('owner');
            $table->boolean('status');
            $table->timestamps();

            $table->foreign('owner')->references('id')->on('users')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_details');
    }
}
