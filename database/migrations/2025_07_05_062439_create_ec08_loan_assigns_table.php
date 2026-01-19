<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEc08LoanAssignsTable extends Migration{
    
    public function up(){

        Schema::create('ec08_loan_assigns', function (Blueprint $table) {
            $table->id();
            $table->integer('organisation_id');
            $table->integer('member_id');


            $table->integer('loan_request_id');
            $table->integer('loan_scheme_id');

            $table->double('loan_amount', 10, 2)->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();

            $table->boolean('is_emi_enabled')->nullable()->default(false);
            $table->date('emi_payment_date')->nullable();
            $table->double('emi_amount', 10, 2)->nullable();

            
            

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
        Schema::dropIfExists('ec08_loan_assigns');
    }
}
