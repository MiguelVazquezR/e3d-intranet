<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserHasSellOrderedProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_has_sell_ordered_products', function (Blueprint $table) {
            $table->id();

            $table->unsignedSmallInteger('estimated_time');
            $table->unsignedSmallInteger('time_paused')->default(0);
            
            $table->text('indications');

            $table->foreignId('sell_ordered_product_id')
            ->constrained()
            ->onDelete('cascade');
            
            $table->foreignId('user_id')
            ->constrained()
            ->onDelete('cascade');

            $table->timestamp('start')->nullable();
            $table->timestamp('pause')->nullable();
            $table->timestamp('finish')->nullable();

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
        Schema::dropIfExists('user_has_sell_ordered_products');
    }
}
