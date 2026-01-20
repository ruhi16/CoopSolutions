<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ec08LoanAssignSchedule extends Model
{
    use HasFactory;
    protected $table = 'ec10_loan_assign_schedules';
    
    protected $fillable = [
        'loan_assign_id',
        'payment_schedule_no',
        'payment_schedule_date',
        'payment_schedule_status',
        'payment_schedule_balance_amount_copy',
        'payment_schedule_total_amount',
        'payment_schedule_principal',
        'payment_schedule_interest',
        'payment_schedule_others',
        'is_paid',
        'is_active',
        'remarks',
    ];
    
    protected $casts = [
        'payment_schedule_date' => 'date',
        'is_paid' => 'boolean',
        'is_active' => 'boolean',
    ];
    
    public function loanAssign(): BelongsTo
    {
        return $this->belongsTo(Ec08LoanAssign::class, 'loan_assign_id');
    }
    
    public function loanPaymentDetails(): HasMany
    {
        return $this->hasMany(Ec12LoanPaymentDetail::class, 'loan_schedule_id');
    }
    
    public function loanPayments(): HasMany
    {
        return $this->hasMany(Ec11LoanPayment::class, 'payment_schedule_id');
    }
}
