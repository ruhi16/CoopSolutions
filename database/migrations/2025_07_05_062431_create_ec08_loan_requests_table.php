<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEc08LoanRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ec08_loan_requests', function (Blueprint $table) {
            $table->id();
            $table->integer('organisation_id')->nullable();
            $table->integer('member_id')->nullable();
            
            $table->integer('req_loan_scheme_id')->nullable();
            $table->double('req_loan_amount', 10, 2)->nullable();
            $table->integer('time_period_months')->nullable();
            $table->date('req_date')->nullable();


            $table->enum('status', ['pending', 'approved', 'rejected', 'cancelled', 'closed', 'expired', 'overdue', 'completed'])->default('pending')->nullable();
            $table->date('status_assigning_date')->nullable();                      
            $table->date('status_closed_date')->nullable();     // after member concent
            $table->string('status_instructions')->nullable();  // approved amount
            
            $table->double('approved_loan_amount', 10, 2)->nullable();
            $table->date('approved_loan_amount_date')->nullable();

            $table->boolean('member_concent')->default(false);
            $table->string('member_concent_note')->nullable();
            $table->date('member_concent_date')->nullable();

            
            $table->integer('done_by')->nullable(); // user_id (auto generated)
            
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
        Schema::dropIfExists('ec08_loan_requests');
    }
}
