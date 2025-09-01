<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTb02RoleOperationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb02_role_operations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            
            $table->string('table_name')->nullable();
            $table->integer('operation_value')->nullable();
            $table->integer('role_id')->nullable();
            

            $table->boolean('is_finalized')->nullable()->default(true);
            $table->integer('finalized_by')->nullable();
            $table->date('finalized_at')->nullable();
            
            $table->boolean('is_deleted')->nullable()->default(false); 
            $table->integer('deleted_by')->nullable();
            $table->date('deleted_at')->nullable();            
            
            $table->integer('organisation_id')->nullable();


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
        Schema::dropIfExists('tb02_role_operations');
    }
}
