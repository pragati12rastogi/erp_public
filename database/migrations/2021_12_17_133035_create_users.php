<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('profile')->length(500)->nullable();
            $table->integer('role_id');
            $table->string('firm_name')->nullable();
            $table->text('address')->nullable();
            $table->string('gst_no')->length(100)->nullable();
            $table->string('mobile')->length(50)->unique();
            $table->integer('state_id');
            $table->string('district');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->boolean('status');
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
