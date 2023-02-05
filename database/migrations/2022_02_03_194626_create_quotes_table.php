<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quotes', function (Blueprint $table) {
            $table->id();

            $table->string('receiver', 60);
            $table->string('department', 60);
            $table->string('tooling_cost', 120);
            $table->string('customer_name')->nullable();
            $table->string('freight_cost', 60);
            $table->string('first_production_days', 60)->default('3 a 4 semanas');

            $table->boolean('spanish_template')->nullable();            

            $table->text('notes')->nullable();

            $table->boolean('strikethrough_tooling_cost')->default(0);

            $table->foreignId('user_id')
                ->constrained()
                ->onDelete('cascade');

            $table->foreignId('sell_order_id')
                ->nullable()
                ->constrained()
                ->onDelete('cascade');

            $table->foreignId('currency_id')
                ->nullable()
                ->constrained()
                ->onDelete('cascade');

            $table->unsignedBigInteger('authorized_user_id')->nullable();
            $table->foreign('authorized_user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->foreignId('customer_id')
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
        Schema::dropIfExists('quotes');
    }
}
