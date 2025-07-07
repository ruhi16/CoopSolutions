<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ec12LoanPaymentDetail extends Model
{
    use HasFactory;
    protected $table = 'ec12_loan_payment_details';
    protected $guarded = ['id'];
}
