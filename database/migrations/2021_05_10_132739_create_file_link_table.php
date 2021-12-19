<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFileLinkTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('file_link', function (Blueprint $table) {
            $table->id()->primary();
            $table->string('file_name');
            $table->string('file_link')->nullable();
            $table->string('file_type');
            $table->enum('security',['public','private']);
            $table->unsignedBigInteger('fileable_id');
            $table->string('fileable_type');
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
        Schema::dropIfExists('file_link');
    }
}
