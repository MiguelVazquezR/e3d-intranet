<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockCompositProductMovementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_composit_product_movements', function (Blueprint $table) {
            $table->id();

            $table->decimal('quantity');
            
            $table->text('notes')->nullable();

            $table->foreignId('user_id')
            ->constrained()
            ->onDelete('cascade');
            
            $table->foreignId('stock_composit_product_id')
            ->constrained()
            ->onDelete('cascade');

            $table->foreignId('stock_action_type_id')
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
        Schema::dropIfExists('stock_composit_product_movements');
    }
}
