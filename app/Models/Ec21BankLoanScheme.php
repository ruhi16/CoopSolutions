<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ec21BankLoanScheme extends Model
{
    use HasFactory;

    protected $table = 'ec21_bank_loan_schemes';

    protected $fillable = [
        'name',
        'description',
        'bank_id',
        'effected_on',
        'status',
        'task_execution_id',
        'user_id',
        'organisation_id',
        'financial_year_id',
        'is_finalized',
        'finalized_by',
        'finalized_at',
        'is_active',
        'remarks'
    ];

    protected $casts = [
        'effected_on' => 'date',
        'finalized_at' => 'date',
        'is_finalized' => 'boolean',
        'is_active' => 'boolean',
    ];

    /**
     * Get all specifications for this loan scheme
     */
    public function specifications(): HasMany
    {
        return $this->hasMany(Ec21BankLoanSchemeSpecification::class, 'bank_loan_scheme_id');
    }

    /**
     * Get the bank that owns the loan scheme
     */
    public function bank(): BelongsTo
    {
        return $this->belongsTo(Ec20Bank::class, 'bank_id');
    }

    /**
     * Get the user who created the loan scheme
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the organization that owns the loan scheme
     */
    public function organisation(): BelongsTo
    {
        return $this->belongsTo(Ec01Organisation::class, 'organisation_id');
    }

    /**
     * Get the financial year for the loan scheme
     */
    public function financialYear(): BelongsTo
    {
        return $this->belongsTo(Ec02FinancialYear::class, 'financial_year_id');
    }
}
