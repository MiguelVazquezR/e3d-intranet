<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMarketingProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('marketing_projects', function (Blueprint $table) {
            $table->id();

            $table->string('project_name');
            $table->text('objective');
            $table->text('feedback')->nullable();
            $table->double('project_cost');
            $table->timestamp('authorized_at')->nullable();
            $table->unsignedInteger('project_owner_id');
            $table->foreign('project_owner_id')->references('id')->on('users');
            $table->unsignedInteger('authorized_by_id')->nullable();
            $table->foreign('authorized_by_id')->references('id')->on('users');
            
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
        Schema::dropIfExists('marketing_projects');
    }
}
