<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEc09LoanAssignParticularsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ec09_loan_assign_particulars', function (Blueprint $table) {
            $table->id();
            $table->integer('loan_assign_id')->nullable();

            $table->integer('loan_scheme_id')->nullable();
            $table->integer('loan_scheme_detail_id')->nullable();
            
            $table->boolean('is_regular')->default(false);  // scheduled = true or regular = null or false
            $table->integer('loan_schedule_id')->nullable();    // when is_regular = false, fine or others, specific to 


            // $table->integer('loan_schedule_id')->nullable();

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
        Schema::dropIfExists('ec09_loan_assign_particulars');
    }
}
