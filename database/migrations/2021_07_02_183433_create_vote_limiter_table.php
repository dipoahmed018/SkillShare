<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVoteLimiterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vote_limiter', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('voter_id');
            $table->unsignedBigInteger('voteable_id');
            $table->enum('vote_type', ['increment', 'decrement']);
            $table->string('voteable_type');
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
        Schema::dropIfExists('vote_limiter');
    }
}
