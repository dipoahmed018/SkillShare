<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateTuitionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tuition', function (Blueprint $table) {
            $table->id();
            $table->string('stripe_id');
            $table->string('title');
            $table->string('description');
            $table->unsignedBigInteger('owner');
            $table->unsignedBigInteger('forum_id')->nullable();
            $table->timestamps();

            $table->foreign('owner')->references('id')->on('users');
            $table->foreign('forum_id')->references('id')->on('forum');
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
        Schema::dropIfExists('tuition');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
