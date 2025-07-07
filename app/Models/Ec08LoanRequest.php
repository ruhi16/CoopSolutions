<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ec08LoanRequest extends Model
{
    use HasFactory;
    protected $table = 'ec08_loan_requests';
    protected $guarded = ['id'];
}
