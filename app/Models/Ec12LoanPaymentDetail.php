<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ec12LoanPaymentDetail extends Model
{
    use HasFactory;
    protected $table = 'ec12_loan_payment_details';
    
    protected $fillable = [
        'loan_assign_id',
        'loan_payment_id',
        'loan_schedule_id',
        'loan_assign_particular_id',
        'loan_assign_current_balance_copy',
        'loan_assign_particular_amount',
        'is_scheduled',
        'is_fixed_amount',
        'is_active',
        'remarks',
    ];
    
    protected $casts = [
        'loan_assign_current_balance_copy' => 'decimal:2',
        'is_scheduled' => 'boolean',
        'is_fixed_amount' => 'boolean',
        'is_active' => 'boolean',
    ];
    
    public function loanPayment(): BelongsTo
    {
        return $this->belongsTo(Ec11LoanPayment::class, 'loan_payment_id');
    }
    
    public function loanAssign(): BelongsTo
    {
        return $this->belongsTo(Ec08LoanAssign::class, 'loan_assign_id');
    }
    
    public function loanAssignSchedule(): BelongsTo
    {
        return $this->belongsTo(Ec08LoanAssignSchedule::class, 'loan_schedule_id');
    }
    
    public function loanAssignParticular(): BelongsTo
    {
        return $this->belongsTo(Ec09LoanAssignParticular::class, 'loan_assign_particular_id');
    }
}
