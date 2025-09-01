<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWf04TaskEventStepsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wf04_task_event_steps', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            
            $table->integer('task_event_id')->nullable();

            $table->integer('task_event_order_index')->nullable();
            
            $table->integer('task_event_particular_id')->nullable();
            $table->integer('task_event_particular_status_id')->nullable();
            
            $table->integer('role_id')->nullable();

            $table->boolean('is_active')->default(true);
            $table->string('remarks');
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
        Schema::dropIfExists('wf04_task_event_steps');
    }
}
