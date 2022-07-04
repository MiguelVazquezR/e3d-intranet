<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSellOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sell_orders', function (Blueprint $table) {
            $table->id();

            $table->string('shipping_company');
            $table->string('freight_cost');
            $table->string('status', 30)->default('Esperando autorización');
            $table->string('priority', 30);
            $table->string('oce')->nullable();
            $table->string('oce_name')->nullable();
            $table->string('order_via');
            $table->string('invoice')->nullable();
            $table->string('tracking_guide')->nullable();

            $table->text('notes')->nullable();

            $table->unsignedBigInteger('authorized_user_id')->nullable();
            $table->foreign('authorized_user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->foreignId('user_id')
                ->constrained()
                ->onDelete('cascade');

            $table->foreignId('customer_id')
                ->constrained()
                ->onDelete('cascade');

            $table->foreignId('contact_id')
                ->constrained()
                ->onDelete('cascade');

            $table->date('received_at')->nullable();
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
        Schema::dropIfExists('sell_orders');
    }
}
