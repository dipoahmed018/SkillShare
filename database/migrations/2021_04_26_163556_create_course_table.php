<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateCourseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('description');
            $table->decimal('price', 8, 2);
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
        Schema::dropIfExists('course');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
