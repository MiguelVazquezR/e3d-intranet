<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePayRollsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pay_rolls', function (Blueprint $table) {
            $table->id();
            
            $table->date('start_period');
            $table->date('end_period');
            $table->boolean('closed')->default(0);

            $table->unsignedInteger('week');

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
        Schema::dropIfExists('pay_rolls');
    }
}
