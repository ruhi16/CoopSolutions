<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ec10LoanAssignSchedule extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ec10_loan_assign_schedules';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
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

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'payment_schedule_date' => 'date',
        'payment_schedule_balance_amount_copy' => 'double',
        'payment_schedule_total_amount' => 'double',
        'payment_schedule_principal' => 'double',
        'payment_schedule_interest' => 'double',
        'payment_schedule_others' => 'double',
        'is_paid' => 'boolean',
        'is_active' => 'boolean',
    ];

    /**
     * Get the loan assignment that owns the schedule.
     */
    public function loanAssign()
    {
        return $this->belongsTo(Ec10LoanAssign::class, 'loan_assign_id');
    }

    /**
     * Scope a query to only include active schedules.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include paid schedules.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePaid($query)
    {
        return $query->where('is_paid', true);
    }

    /**
     * Scope a query to only include unpaid schedules.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUnpaid($query)
    {
        return $query->where('is_paid', false);
    }

    /**
     * Scope a query to filter by payment status.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $status
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('payment_schedule_status', $status);
    }
}