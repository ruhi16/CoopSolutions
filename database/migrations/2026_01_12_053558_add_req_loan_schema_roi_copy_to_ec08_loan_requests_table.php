<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddReqLoanSchemaRoiCopyToEc08LoanRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ec08_loan_requests', function (Blueprint $table) {
            $table->double('req_loan_schema_roi_copy', 10, 2)->nullable()->after('req_loan_scheme_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ec08_loan_requests', function (Blueprint $table) {
            $table->dropColumn('req_loan_schema_roi_copy');
        });
    }
}
