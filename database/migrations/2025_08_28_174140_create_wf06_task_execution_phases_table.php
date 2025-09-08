<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWf06TaskExecutionPhasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wf06_task_execution_phases', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            
            $table->integer('task_execution_id')->nullable();

            // $table->integer('task_category_id')->nullable();
            // $table->integer('task_event_id')->nullable();
            $table->integer('task_event_step_id')->nullable();
            
            
            $table->boolean('is_finalized')->nullable()->default(true);
            $table->integer('finalized_by')->nullable();
            $table->date('finalized_at')->nullable();
            
            $table->boolean('is_deleted')->nullable()->default(false); 
            $table->integer('deleted_by')->nullable();
            $table->date('deleted_at')->nullable();            
            
            $table->integer('organisation_id')->nullable();


            $table->boolean('is_active')->default(true);
            $table->string('remarks')->nullable();

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
        Schema::dropIfExists('wf06_task_execution_phases');
    }
}
