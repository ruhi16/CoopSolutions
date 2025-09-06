<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEc21BankLoanBorrowedsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ec21_bank_loan_borroweds', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();

            $table->integer('bank_loan_scheme_id')->nullable();

            $table->double('loan_borrowed_amount', 10, 2)->nullable();
            $table->date('loan_borrowed_date')->nullable();
            $table->integer('loan_assign_ref_id')->nullable();


            $table->double('installment_amount', 10, 2)->nullable();
            $table->integer('no_of_installments')->nullable();

            $table->enum('status', ['running', 'completed', 'upcoming', 'suspended', 'cancelled'])->default('suspended')->nullable();



            $table->integer('task_execution_id')->nullable();   //****/  
            
            
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
        Schema::dropIfExists('ec21_bank_loan_borroweds');
    }
}
