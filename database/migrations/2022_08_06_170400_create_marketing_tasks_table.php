<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMarketingTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('marketing_tasks', function (Blueprint $table) {
            $table->id();

            $table->string('description', 255);
            $table->timestamp('finished_at')->nullable();
            $table->timestamp('estimated_finish');
            $table->foreignId('marketing_project_id')->constrained()->onDelete('cascade');

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
        Schema::dropIfExists('marketing_tasks');
    }
}
