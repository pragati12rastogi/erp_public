<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFbPageSubscriptions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fb_page_subscriptions', function (Blueprint $table) {
            $table->increment('fb_id');
            $table->string('subscribed_page_name');
            $table->text('sub_access_token');
            $table->string('subscribed_page_id');
            $table->string('category');
            $table->longtext('category_list')->nullable();
            $table->longtext('tasks')->nullable();
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
        Schema::dropIfExists('fb_page_subscriptions');
    }
}
