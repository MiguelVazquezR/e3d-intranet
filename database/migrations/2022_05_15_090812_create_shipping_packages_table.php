<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShippingPackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipping_packages', function (Blueprint $table) {
            $table->id();

            $table->unsignedFloat('width');
            $table->unsignedFloat('large');
            $table->unsignedFloat('height');
            $table->unsignedFloat('weight');
            $table->unsignedFloat('quantity');
            $table->string('status')->default('Preparando para envío');

            $table->string('inside_image');
            $table->string('outside_image');

            $table->foreignId('sell_ordered_product_id')
            ->constrained()
            ->onDelete('cascade');
            
            $table->foreignId('user_id')
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
        Schema::dropIfExists('shipping_packages');
    }
}
