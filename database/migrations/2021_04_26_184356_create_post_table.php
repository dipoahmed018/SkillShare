<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreatePostTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('post', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->string('content')->nullable();
            $table->integer('vote');
            $table->unsignedBigInteger('owner');
            $table->unsignedBigInteger('postable_id');
            $table->enum('post_type',['question','post']);
            $table->timestamps();

            $table->foreign('owner')->references('id')->on('users');
            $table->foreign('postable_id')->references('id')->on('forum');
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
        Schema::dropIfExists('post');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
