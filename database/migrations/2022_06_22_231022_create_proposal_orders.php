<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProposalOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('proposal_orders', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_no');
            $table->integer('role_id');
            $table->integer('user_id');
            $table->double('total_cost');
            $table->double('total_discount')->nullable()->default(0);
            $table->double('total_tax');
            $table->boolean('is_converted')->default(0);
            $table->boolean('is_cancelled')->default('0');
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
        Schema::dropIfExists('proposal_orders');
    }
}
