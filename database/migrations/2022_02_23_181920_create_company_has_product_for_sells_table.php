<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanyHasProductForSellsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_has_product_for_sells', function (Blueprint $table) {
            $table->id();

            $table->unsignedMediumInteger('model_id');
            $table->string('model_name');
            
            $table->timestamp('old_date')->nullable();
            $table->timestamp('new_date')->nullable();
            
            $table->unsignedFloat('old_price')->nullable();
            $table->unsignedFloat('new_price');
            
            $table->string('old_price_currency', 12)->nullable();
            $table->string('new_price_currency', 12);

            $table->foreignId('company_id')
            ->nullable()
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
        Schema::dropIfExists('company_has_product_for_sells');
    }
}
