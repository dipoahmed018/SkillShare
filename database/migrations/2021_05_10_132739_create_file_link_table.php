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
            $table->id();
            $table->string('file_name');
            $table->string('file_link')->nullable();
            $table->enum('file_type',['tutorial','records','thumbnail','introduction','post','comment','message','profile_photo','profile_video']);
            $table->enum('security',['public','private']);
            $table->unsignedBigInteger('fileable_id');
            $table->enum('fileable_type',['course','tuition','post','comment','message','user']);
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
