<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStocks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stocks', function (Blueprint $table) {
            $table->id();
            $table->integer('item_id');
            $table->integer('prod_quantity');
            $table->double('prod_price');
            $table->double('total_price');
            $table->double('per_freight_price');
            $table->double('user_percent');
            $table->double('final_price');
            $table->double('price_for_user');
            $table->date('date_of_purchase');
            $table->integer('vendor_id');
            $table->integer('created_by');
            $table->integer('updated_by')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stocks');
    }
}
