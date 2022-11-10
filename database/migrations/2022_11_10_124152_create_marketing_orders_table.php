<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMarketingOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('marketing_orders', function (Blueprint $table) {
            $table->id();

            $table->string('order_name');
            $table->string('customer_name')->nullable();
            $table->string('status', 30)->default('Esperando autorización');

            $table->text('especifications');

            $table->foreignId('customer_id')
                ->nullable()
                ->constrained()
                ->onDelete('cascade');

            $table->foreignId('user_id')
                ->constrained()
                ->onDelete('cascade');

            $table->unsignedBigInteger('authorized_user_id')->nullable();
            $table->foreign('authorized_user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->timestamp('tentative_end')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('authorized_at')->nullable();

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
        Schema::dropIfExists('marketing_orders');
    }
}
