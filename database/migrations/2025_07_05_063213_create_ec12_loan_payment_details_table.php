<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEc12LoanPaymentDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ec12_loan_payment_details', function (Blueprint $table) {
            $table->id();
            $table->integer('loan_assign_id')->nullable();
            $table->integer('loan_payment_id')->nullable();

            $table->integer('loan_schedule_id')->nullable();
            $table->integer('loan_assign_particular_id')->nullable();
            $table->double('loan_assign_current_balance_copy', 10, 2)->nullable()->default(0);
            $table->integer('loan_assign_particular_amount')->nullable();

            $table->boolean('is_scheduled')->default(false);  // scheduled = true or regular = null or false
            $table->boolean('is_fixed_amount')->default(false);  
            


            $table->boolean('is_active')->default(true);
            $table->string('remarks')->nullable();
            $table->timestamps()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ec12_loan_payment_details');
    }
}
