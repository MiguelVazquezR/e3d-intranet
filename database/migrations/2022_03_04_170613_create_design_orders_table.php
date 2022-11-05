<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDesignOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('design_orders', function (Blueprint $table) {
            $table->id();

            $table->string('design_name');
            $table->string('plans_image')->nullable();
            $table->string('logo_image')->nullable();
            $table->string('customer_name')->nullable();
            $table->string('contact_name')->nullable();
            $table->string('dimentions')->nullable();
            $table->string('status', 30)->default('Esperando autorización');

            $table->text('design_data');
            $table->text('especifications');
            $table->text('pantones')->nullable();

            $table->unsignedBigInteger('designer_id');
            $table->foreign('designer_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->foreignId('customer_id')
                ->nullable()
                ->constrained()
                ->onDelete('cascade');

            $table->foreignId('contact_id')
                ->nullable()
                ->constrained()
                ->onDelete('cascade');

            $table->foreignId('user_id')
                ->constrained()
                ->onDelete('cascade');

            $table->foreignId('design_type_id')
                ->constrained()
                ->onDelete('cascade');

            $table->foreignId('measurement_unit_id')
                ->constrained()
                ->onDelete('cascade');

            $table->unsignedBigInteger('authorized_user_id')->nullable();
            $table->foreign('authorized_user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->unsignedBigInteger('original_id')->nullable();
            $table->unsignedBigInteger('modified_id')->nullable();

            $table->boolean('is_complex')->nullable();
            
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
        Schema::dropIfExists('design_orders');
    }
}
