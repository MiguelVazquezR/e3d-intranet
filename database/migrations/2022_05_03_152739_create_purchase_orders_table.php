<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();

            $table->string('status', 30)->default('Esperando autorización');
            $table->string('shipping_company');
            $table->string('tracking_guide')->nullable();
            
            $table->boolean('iva_included')->default(0);

            $table->text('notes')->nullable();

            $table->unsignedBigInteger('authorized_user_id')->nullable();
            $table->foreign('authorized_user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->foreignId('user_id')
                ->constrained()
                ->onDelete('cascade');

            $table->foreignId('supplier_id')
                ->constrained()
                ->onDelete('cascade');

            $table->foreignId('contact_id')
                ->constrained()
                ->onDelete('cascade');

            $table->date('expected_delivery_at')->nullable();
            $table->date('emitted_at')->nullable();
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
        Schema::dropIfExists('purchase_orders');
    }
}
