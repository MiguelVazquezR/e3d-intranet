<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompositProductDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('composit_product_details', function (Blueprint $table) {
            $table->id();

            $table->unsignedFloat('quantity');
            $table->text('notes')->nullable();
            
            $table->foreignId('product_id')
            ->constrained()
            ->onDelete('cascade');
            
            $table->foreignId('composit_product_id')
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
        Schema::dropIfExists('composit_product_details');
    }
}
