<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEc20BankDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ec20_bank_details', function (Blueprint $table) {
            $table->id();
            $table->integer('bank_id')->nullable();
            $table->string('name');
            $table->string('description')->nullable();

            

            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            
            $table->integer('user_id')->nullable();
            $table->integer('organisation_id')->nullable();
            $table->integer('financial_year_id')->nullable();

            $table->boolean('is_finalized')->nullable()->default(true);
            $table->integer('finalized_by')->nullable();
            $table->date('finalized_at')->nullable();

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
        Schema::dropIfExists('ec20_bank_details');
    }
}
