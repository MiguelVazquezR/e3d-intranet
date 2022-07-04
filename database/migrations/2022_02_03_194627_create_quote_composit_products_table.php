<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuoteCompositProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quote_composit_products', function (Blueprint $table) {
            $table->id();

            $table->unsignedDecimal('quantity');
            
            $table->unsignedDecimal('price', 8, 2);
            
            $table->boolean('show_image')->default(1);
            
            $table->text('notes')->nullable();

            $table->foreignId('quote_id')
            ->nullable()
            ->constrained()
            ->onDelete('cascade');

            $table->foreignId('composit_product_id')
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
        Schema::dropIfExists('quote_composit_products');
    }
}
