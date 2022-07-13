<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDistributions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('distributions', function (Blueprint $table) {
            $table->id();
            $table->integer('order_id');
            $table->integer('user_id');
            $table->integer('item_id');
            $table->double('product_price');
            $table->double('igst')->nullable();
            $table->double('cgst')->nullable();
            $table->double('sgst')->nullable();
            $table->integer('distributed_quantity');
            $table->double('product_total_price');
            $table->double('discount')->nullable()->default(0);
            $table->double('charge')->nullable()->default(0);
            $table->double('gst_percent')->nullable()->default(0);
            $table->boolean('is_cancelled')->default('0');
            $table->integer('updated_by')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('distributions');
    }
}
