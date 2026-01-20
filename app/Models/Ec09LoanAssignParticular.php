<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ec09LoanAssignParticular extends Model
{
    use HasFactory;
    protected $table = 'ec09_loan_assign_particulars';
    
    protected $fillable = [
        'loan_assign_id',
        'loan_scheme_id',
        'loan_scheme_detail_id',
        'loan_scheme_feature_id',
        'loan_scheme_feature_standard_id',
        'particular_name',
        'particular_description',
        'amount',
        'is_active',
        'remarks',
    ];
    
    protected $casts = [
        'amount' => 'decimal:2',
        'is_active' => 'boolean',
    ];
    
    /**
     * Get the loan assignment that owns the particular
     */
    public function loanAssign(): BelongsTo
    {
        return $this->belongsTo(Ec08LoanAssign::class, 'loan_assign_id');
    }
    
    /**
     * Get the loan scheme associated with this particular
     */
    public function loanScheme(): BelongsTo
    {
        return $this->belongsTo(Ec06LoanScheme::class, 'loan_scheme_id');
    }
    
    /**
     * Get the loan scheme detail associated with this particular
     */
    public function loanSchemeDetail(): BelongsTo
    {
        return $this->belongsTo(Ec07LoanSchemeDetail::class, 'loan_scheme_detail_id');
    }
    
    /**
     * Get the loan scheme feature associated with this particular
     */
    public function loanSchemeFeature(): BelongsTo
    {
        return $this->belongsTo(Ec07LoanSchemeFeature::class, 'loan_scheme_feature_id');
    }
    
    /**
     * Get the loan scheme feature standard associated with this particular
     */
    public function loanSchemeFeatureStandard(): BelongsTo
    {
        return $this->belongsTo(Ec07LoanSchemeFeatureStandard::class, 'loan_scheme_feature_standard_id');
    }
    
    /**
     * Get the loan payment details associated with this particular
     */
    public function loanPaymentDetails(): HasMany
    {
        return $this->hasMany(Ec12LoanPaymentDetail::class, 'loan_assign_particular_id');
    }
}