<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWf09TaskEventRolePermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wf09_task_event_role_permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();

            $table->integer('role_id')->nullable();
            $table->integer('task_event_id')->nullable();
            
            // $table->integer('task_event_particular_id')->nullable();
            // $table->integer('task_event_particular_status_id')->nullable();
            // $table->integer('task_event_step_id')->nullable();
            // $table->integer('task_execution_id')->nullable();
            // $table->integer('task_execution_phase_id')->nullable();
            // $table->integer('task_category_id');

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
        Schema::dropIfExists('wf09_task_event_role_permissions');
    }
}
