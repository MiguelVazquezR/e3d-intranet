<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMarketingOrderResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('marketing_order_results', function (Blueprint $table) {
            $table->id();

            $table->string('external_link')->nullable();
            $table->text('notes')->nullable();

            $table->foreignId('user_id')->constrained();
            $table->foreignId('marketing_order_id')->constrained();
            $table->foreignId('media_id')->nullable()->constrained()->onDelete('cascade');

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
        Schema::dropIfExists('marketing_order_results');
    }
}
