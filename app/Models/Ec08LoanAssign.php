<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ec08LoanAssign extends Model
{
    use HasFactory;
    protected $table = 'ec08_loan_assigns';
    
    protected $fillable = [
        'member_id',
        'organisation_id',
        'loan_request_id',
        'loan_scheme_id',
        'loan_amount',
        'loan_start_date',
        'loan_end_date',
        'loan_status',
        'is_active',
        'remarks',
    ];
    
    protected $casts = [
        'loan_start_date' => 'date',
        'loan_end_date' => 'date',
        'is_active' => 'boolean',
    ];
    
    /**
     * Get the organization that owns the loan assignment
     */
    public function organisation(): BelongsTo
    {
        return $this->belongsTo(Ec01Organisation::class, 'organisation_id');
    }
    
    /**
     * Get the member associated with the loan assignment
     */
    public function member(): BelongsTo
    {
        return $this->belongsTo(Ec04Member::class, 'member_id');
    }
    
    /**
     * Get the loan request associated with the loan assignment
     */
    public function loanRequest(): BelongsTo
    {
        return $this->belongsTo(Ec08LoanRequest::class, 'loan_request_id');
    }
    
    /**
     * Get the loan scheme associated with the loan assignment
     */
    public function loanScheme(): BelongsTo
    {
        return $this->belongsTo(Ec06LoanScheme::class, 'loan_scheme_id');
    }
    
    /**
     * Get the loan assign particulars for this assignment
     */
    public function loanAssignParticulars(): HasMany
    {
        return $this->hasMany(Ec09LoanAssignParticular::class, 'loan_assign_id');
    }
    
    /**
     * Get the loan assign schedules for this assignment
     */
    public function loanAssignSchedules(): HasMany
    {
        return $this->hasMany(Ec08LoanAssignSchedule::class, 'loan_assign_id');
    }
    
    /**
     * Get the loan payments for this assignment
     */
    public function loanPayments(): HasMany
    {
        return $this->hasMany(Ec11LoanPayment::class, 'loan_assign_id');
    }
    
    /**
     * Get the loan payment details for this assignment
     */
    public function loanPaymentDetails()
    {
        return $this->hasManyThrough(Ec12LoanPaymentDetail::class, Ec11LoanPayment::class, 'loan_assign_id', 'loan_payment_id', 'id', 'id');
    }
}
