<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReferrelTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('referrel', function (Blueprint $table) {
            $table->id();
            $table->string('referrel_token');
            $table->integer('cut_of')->unsigned();
            $table->unsignedBigInteger('item_id');
            $table->enum('item_type',['tuition','course']);
            $table->integer('quantity');
            $table->timestamp('expires_at');
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
        Schema::dropIfExists('referrel');
    }
}
