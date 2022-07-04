<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSellOrderedProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sell_ordered_products', function (Blueprint $table) {
            $table->id();
            
            $table->unsignedFloat('quantity');
            
            $table->boolean('for_sell')->default(1);
            $table->boolean('new_design')->default(0);

            $table->text('notes')->nullable();
            $table->string('status')->default('Sin iniciar');

            $table->foreignId('company_has_product_for_sell_id')
            ->constrained()
            ->onDelete('cascade');
            
            $table->foreignId('sell_order_id')
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
        Schema::dropIfExists('sell_ordered_products');
    }
}
