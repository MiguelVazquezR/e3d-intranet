<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            
            $table->float('salary');
            $table->float('discounts');
            $table->float('hours_per_week');
            $table->string('days_off');
            $table->string('check_in_times');
            $table->string('bonuses')->nullable();
            $table->string('job_position');

            $table->float('vacations', 8, 3)->default(0);
            
            $table->foreignId('department_id')
            ->constrained()
            ->onDelete('cascade');
            
            $table->foreignId('user_id')
            ->constrained()
            ->onDelete('cascade');

            $table->date('birth_date')->nullable();
            $table->date('join_date')->nullable();
            $table->date('vacations_updated_at')->nullable();
           
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
        Schema::dropIfExists('employees');
    }
}
