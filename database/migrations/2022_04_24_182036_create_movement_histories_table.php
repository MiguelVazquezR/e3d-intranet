<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMovementHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('movement_histories', function (Blueprint $table) {
            $table->id();

            $table->text('description');

            $table->foreignId('user_id')
            ->constrained()
            ->onDelete('cascade');

            $table->unsignedTinyInteger('movement_type');
            
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
        Schema::dropIfExists('movement_histories');
    }
}
