<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ec08LoanAssignSchedule extends Model
{
    use HasFactory;
    protected $table = 'ec08_loan_assigns_schedules';
    protected $guarded = ['id'];
}
