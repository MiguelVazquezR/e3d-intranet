<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePayRollMoreTimesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pay_roll_more_times', function (Blueprint $table) {
            $table->id();

            $table->foreignId('pay_roll_id')->constrained();
            $table->foreignId('user_id')->constrained();
            $table->tetx('report')->nullable();
            $table->unsignedTinyInteger('authorized_by')->nullable();
            $table->timestamp('authorized_at')->nullable();
            $table->time('additional_time');
            $table->string('comments')->nullable();

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
        Schema::dropIfExists('pay_roll_more_times');
    }
}
