<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ec11LoanPayment extends Model
{
    use HasFactory;
    protected $table = 'ec11_loan_payments';
    protected $guarded = ['id'];
    
    protected $fillable = [
        'loan_assign_id',
        'member_id',
        'payment_schedule_id',
        'payment_total_amount',
        'payment_principal_amount',
        'regular_amount_total',
        'scheduled_amount_total',
        'payment_date',
        'is_paid',
        'principal_balance_amount',
        'is_active',
        'remarks',
    ];
    
    protected $casts = [
        'payment_date' => 'date',
        'is_paid' => 'boolean',
        'is_active' => 'boolean',
    ];
    
    public function loanAssign()
    {
        return $this->belongsTo(Ec08LoanAssign::class, 'loan_assign_id');
    }
}
