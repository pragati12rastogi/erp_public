<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseOrderItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_order_items', function (Blueprint $table) {
            $table->id();
            $table->integer('po_id');
            $table->integer('item_id');
            $table->double('product_price');
            $table->double('gst_percent');
            $table->double('igst')->nullable();
            $table->double('scgst')->nullable();
            $table->integer('distributed_quantity');
            $table->double('product_total_price');
            $table->boolean('is_cancelled')->default('0');
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
        Schema::dropIfExists('purchase_order_items');
    }
}
