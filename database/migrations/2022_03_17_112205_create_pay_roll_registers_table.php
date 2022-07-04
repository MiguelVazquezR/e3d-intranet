<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePayRollRegistersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pay_roll_registers', function (Blueprint $table) {
            $table->id();

            $table->date('day');

            $table->string('check_in', 15)->nullable();
            $table->string('start_break', 15)->nullable();
            $table->string('end_break', 15)->nullable();
            $table->string('check_out', 15)->nullable();
            
            $table->unsignedInteger('extra_hours')->nullable();
            $table->unsignedInteger('extra_minutes')->nullable();
            
            $table->boolean('extras_enabled')->default(0);

            $table->unsignedTinyInteger('late')->default(0);

            $table->foreignId('pay_roll_id')
                ->constrained()
                ->onDelete('cascade');

            $table->foreignId('justification_event_id')
                ->nullable()
                ->constrained()
                ->onDelete('cascade');

            $table->foreignId('user_id')
                ->constrained()
                ->onDelete('cascade');

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
        Schema::dropIfExists('pay_roll_registers');
    }
}
