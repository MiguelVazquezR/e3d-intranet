<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseOrderedProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_ordered_products', function (Blueprint $table) {
            $table->id();

            $table->unsignedFloat('quantity');
            
            $table->unsignedFloat('price');
            $table->string('code');

            $table->text('notes')->nullable();

            $table->foreignId('product_id')
            ->constrained()
            ->onDelete('cascade');
            
            $table->foreignId('purchase_order_id')
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
        Schema::dropIfExists('purchase_ordered_products');
    }
}
