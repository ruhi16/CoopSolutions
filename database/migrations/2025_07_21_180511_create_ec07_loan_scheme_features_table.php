<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEc07LoanSchemeFeaturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ec07_loan_scheme_features', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description');
            $table->string('loan_scheme_feature_name');
            $table->string('loan_scheme_feature_type');
            $table->boolean('is_required')->default(1);



            $table->boolean('is_active')->default(1);
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
        Schema::dropIfExists('ec07_loan_scheme_features');
    }
}
