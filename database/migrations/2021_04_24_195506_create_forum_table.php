<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateForumTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    
    public function up()
    {
        Schema::create('forum', function (Blueprint $table) {
            $table->id()->primary();
            $table->string('name');
            $table->string('description');
            $table->unsignedBigInteger('owner');
            $table->unsignedBigInteger('forumable_id');
            $table->string('forumable_type');
            $table->timestamps();

            $table->foreign('owner')->references('id')->on('users')->onDelete('CASCADE');
        });

    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('forum');
    }
}
