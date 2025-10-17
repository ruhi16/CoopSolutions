<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEc07LoanSchemeDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ec07_loan_scheme_details', function (Blueprint $table) {
            $table->id();
            $table->integer('loan_scheme_id');

            $table->integer('loan_scheme_feature_id')->nullable();
            $table->integer('loan_scheme_feature_standard_id')->nullable();
            $table->string('loan_scheme_feature_value')->nullable();

            $table->boolean('is_featured')->default(false);
            $table->boolean('is_open')->default(false);         // open to member
            $table->boolean('is_calculated')->default(false);   
            $table->boolean('is_regular')->default(false);      // for all emi


            // $table->double('min_amount',10,2)->nullable();
            // $table->double('max_amount',10,2)->nullable();

            // $table->integer('terms_in_month')->nullable();

            // $table->double('main_interest_rate', 10, 2)->nullable();
            // $table->double('service_interest_rate', 10, 2)->nullable();
            
            $table->string('schedule_type')->nullable(); // regular(for all schedules), scheduled            

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
        Schema::dropIfExists('ec07_loan_scheme_details');
    }
}
