<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ec11LoanPayment extends Model
{
    use HasFactory;
    protected $table = 'ec11_loan_payments';
    
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
    
    public function loanAssign(): BelongsTo
    {
        return $this->belongsTo(Ec08LoanAssign::class, 'loan_assign_id');
    }
    
    public function loanPaymentDetails(): HasMany
    {
        return $this->hasMany(Ec12LoanPaymentDetail::class, 'loan_payment_id');
    }
    
    public function member(): BelongsTo
    {
        return $this->belongsTo(Ec04Member::class, 'member_id');
    }
    
    public function paymentSchedule(): BelongsTo
    {
        return $this->belongsTo(Ec08LoanAssignSchedule::class, 'payment_schedule_id');
    }
}
