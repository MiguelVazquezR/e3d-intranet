<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockCompositProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_composit_products', function (Blueprint $table) {
            $table->id();

            $table->string('location')->nullable();
            $table->unsignedMediumInteger('quantity');
            $table->string('image');

            $table->unsignedInteger('composit_product_id');

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
        Schema::dropIfExists('stock_composit_products');
    }
}
