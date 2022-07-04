<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompositProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('composit_products', function (Blueprint $table) {
            $table->id();

            $table->string('image');
            $table->string('alias');

            $table->foreignId('product_status_id')
            ->constrained()
            ->onDelete('cascade');

            $table->foreignId('product_family_id')
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
        Schema::dropIfExists('composit_products');
    }
}
