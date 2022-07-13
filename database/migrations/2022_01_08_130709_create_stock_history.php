<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockHistory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_history', function (Blueprint $table) {
            $table->id();
            $table->integer('stock_id');
            $table->integer('prod_quantity');
            $table->integer('total_qty');
            $table->double('gst');
            $table->double('prod_price');
            $table->double('total_price');
            $table->double('per_freight_price');
            $table->double('user_percent');
            $table->double('final_price');
            $table->double('price_for_user');
            $table->date('date_of_purchase');
            $table->integer('vendor_id');
            $table->integer('created_by');
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
        Schema::dropIfExists('stock_history');
    }
}
