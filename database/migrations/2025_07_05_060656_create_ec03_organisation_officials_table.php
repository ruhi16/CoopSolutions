<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEc03OrganisationOfficialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ec03_organisation_officials', function (Blueprint $table) {
            $table->id();
            $table->integer('organisation_id');
            $table->integer('member_id')->nullable();

            $table->string('designation')->nullable();
            $table->string('description')->nullable();
            

            $table->boolean('is_active');            
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
        Schema::dropIfExists('ec03_organisation_officials');
    }
}
