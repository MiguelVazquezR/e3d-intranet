<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateE3dMediaTable extends Migration
{
    public function up()
    {
        Schema::create('e3d_media', function (Blueprint $table) {
            $table->id();

            $table->unsignedTinyInteger('num_files');
            $table->string('path', 255);
            $table->foreignId('user_id')->constrained();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('e3d_media');
    }
}
