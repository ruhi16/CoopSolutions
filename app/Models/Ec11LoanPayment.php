<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ec11LoanPayment extends Model
{
    use HasFactory;
    protected $table = 'ec11_loan_payments';
    protected $guarded = ['id'];
}
