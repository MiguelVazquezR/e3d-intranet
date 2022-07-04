<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();

            $table->string('name', 60);
            $table->text('address');
            $table->string('post_code',10);

            $table->foreignId('sat_method_id')
            ->constrained()
            ->onDelete('cascade');

            $table->foreignId('sat_type_id')
            ->constrained()
            ->onDelete('cascade');

            $table->foreignId('sat_way_id')
            ->constrained()
            ->onDelete('cascade');

            $table->foreignId('company_id')
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
        Schema::dropIfExists('customers');
    }
}
