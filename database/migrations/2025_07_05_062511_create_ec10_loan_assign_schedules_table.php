<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEc10LoanAssignSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
        Schema::create('ec10_loan_assign_schedules', function (Blueprint $table) {
            $table->id();
            $table->integer('loan_assign_id');


            $table->integer('payment_schedule_no')->nullable();
            $table->date('payment_schedule_date')->nullable();
            $table->enum('payment_schedule_status', ['pending', 'completed', 'suspended'])->nullable();

            $table->double('payment_schedule_balance_amount_copy', 10, 2)->nullable();

            $table->double('payment_schedule_total_amount', 10, 2)->nullable();

            $table->double('payment_schedule_principal', 10, 2)->nullable();
            $table->double('payment_schedule_interest', 10, 2)->nullable();
            $table->double('payment_schedule_others', 10, 2)->nullable();



            $table->boolean('is_paid')->default(false);


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
        Schema::dropIfExists('ec10_loan_assign_schedules');
    }
}
