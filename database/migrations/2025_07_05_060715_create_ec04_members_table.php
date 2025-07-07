<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEc04MembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ec04_members', function (Blueprint $table) {
            $table->id();
            $table->morphs('organisation_id')->nullable();
            $table->integer('member_type_id')->nullable();

            $table->string('name');
            $table->string('name_short')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('mobile')->nullable();
            $table->string('address')->nullable();
            $table->string('dob')->nullable();
            $table->string('gender')->nullable();
            $table->string('nationality')->nullable();
            $table->string('religion')->nullable();
            $table->string('marital_status')->nullable();
            $table->string('blood_group')->nullable();
            $table->string('pan_no')->nullable();
            $table->string('aadhar_no')->nullable();

            $table->string('account_bank')->nullable();
            $table->string('account_branch')->nullable();
            $table->string('account_no')->nullable();
            $table->string('account_ifsc')->nullable();
            $table->string('account_micr')->nullable();
            $table->string('account_customer_id')->nullable();
            $table->string('account_holder_name')->nullable();

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
        Schema::dropIfExists('ec04_members');
    }
}
