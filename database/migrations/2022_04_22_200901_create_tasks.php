<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTasks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('name',500);
            $table->text('description');
            $table->date('start_at');
            $table->date('end_at')->nullable();
            $table->integer('created_by');
            $table->text('assigned_to');
            $table->string('status')->comment('"In Progress","Not Started","Testing","Awaiting Feedback","Complete"');
            $table->string('priority')->comment('High,Medium,Urgent');
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
        Schema::dropIfExists('tasks');
    }
}
