<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserStockDistributionOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_stock_distribution_orders', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_no');
            $table->string('user_name');
            $table->string('address');
            $table->string('phone');
            $table->string('email');
            $table->double('total_cost');
            $table->double('total_tax');
            $table->boolean('is_cancelled')->nullable();
            $table->integer('created_by');
            $table->integer('updated_by')->nullable();
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
        Schema::dropIfExists('user_stock_distribution_orders');
    }
}
