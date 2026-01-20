<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ec08LoanRequest extends Model
{
    use HasFactory;
    protected $table = 'ec08_loan_requests';
    protected $guarded = [];
    
    protected $fillable = [
        'member_id',
        'organisation_id', 
        'req_loan_scheme_id',
        'req_loan_schema_roi_copy',
        'req_loan_amount',
        'time_period_months',
        'req_date',
        'status',
        'is_emi_enabled',
        'emi_amount',
        'emi_payment_date'
    ];
    
    // cast the 'req_date' in datetime
    protected $casts = [
        'req_date' => 'datetime', // or 'date'
        'emi_payment_date' => 'date',
        'is_emi_enabled' => 'boolean',
        'emi_amount' => 'double',
        // Add other date fields if needed
    ];

    // Then the accessor will work
    public function getReqDateAttribute($value){
        if (!$value) return null;

        try {
            return \Carbon\Carbon::parse($value)->format('Y-m-d');
        } catch (\Exception $e) {
            return $value; // return original value if parsing fails
        }       
        
        // return $value ? $value->format('Y-m-d') : null;
        // Or any other format you prefer
        // return $value ? $value->format('d/m/Y') : null;
    }


    // This mutator will capitalize the name before saving
    // public function setStatusAttribute($value){
    //     $this->attributes['status'] = strtoupper($value);
    // }

    public function member(){
        return $this->belongsTo(Ec04Member::class,'member_id','id');

    }

    public function loanScheme(){
        return $this->belongsTo(Ec06LoanScheme::class,'req_loan_scheme_id','id');
    }

    public function loanAssign(){
        return $this->hasOne(Ec08LoanAssign::class,'loan_request_id','id');
    }
    
    public function isAssigned(){
        return $this->loanAssign()->exists();
    }

    public function getEmiAmountAttribute(){
        $loanAssign = $this->loanAssign;
        return $loanAssign ? $loanAssign->emi_amount : 'N/A';
    }

    public function calculateEMI()
    {
        // Use the stored ROI from the loan request
        $annualInterestRate = $this->req_loan_schema_roi_copy ?? 0;
        $monthlyInterestRate = $annualInterestRate / 12 / 100;
        $loanAmount = $this->req_loan_amount;
        $months = $this->time_period_months;
        
        if ($monthlyInterestRate > 0 && $months > 0) {
            // Calculate EMI using the formula: EMI = P * r * (1+r)^n / ((1+r)^n - 1)
            $emi = $loanAmount * $monthlyInterestRate * pow(1 + $monthlyInterestRate, $months) / (pow(1 + $monthlyInterestRate, $months) - 1);
            $calculatedEmi = round($emi, 2);
            
            // Save the calculated EMI to the database if it differs from current value
            if ($this->emi_amount !== $calculatedEmi) {
                $this->emi_amount = $calculatedEmi;
                $this->save();
            }
            
            return $calculatedEmi;
        } else {
            // If no interest, divide principal equally
            $calculatedEmi = $months > 0 ? round($loanAmount / $months, 2) : 0;
            
            // Save the calculated EMI to the database if it differs from current value
            if ($this->emi_amount !== $calculatedEmi) {
                $this->emi_amount = $calculatedEmi;
                $this->save();
            }
            
            return $calculatedEmi;
        }
    }

    public function getAmortizationSchedule()
    {
        // Ensure all values are numeric before calculations
        $annualInterestRate = is_numeric($this->req_loan_schema_roi_copy) ? floatval($this->req_loan_schema_roi_copy) : 0;
        $monthlyInterestRate = $annualInterestRate / 12 / 100;
        $loanAmount = is_numeric($this->req_loan_amount) ? floatval($this->req_loan_amount) : 0;
        $months = is_numeric($this->time_period_months) ? intval($this->time_period_months) : 0;
        
        $emi = $this->calculateEMI();
        $schedule = [];
        $remainingBalance = $loanAmount;
        
        // Only calculate if we have valid positive values
        if ($months <= 0) {
            return $schedule; // Return empty schedule if no months
        }
        
        for ($month = 1; $month <= $months; $month++) {
            if ($monthlyInterestRate > 0) {
                // Calculate interest for current month based on remaining balance
                $monthlyInterest = $remainingBalance * $monthlyInterestRate;
                $monthlyPrincipal = $emi - $monthlyInterest;
                
                // Adjust principal if it would exceed remaining balance (for the last month)
                if ($monthlyPrincipal > $remainingBalance) {
                    $monthlyPrincipal = $remainingBalance;
                    $monthlyInterest = $emi - $monthlyPrincipal;
                }
                
                $remainingBalance -= $monthlyPrincipal;
                
                // Round to prevent floating point errors
                $monthlyInterest = round($monthlyInterest, 2);
                $monthlyPrincipal = round($monthlyPrincipal, 2);
                $remainingBalance = round($remainingBalance, 2);
            } else {
                // No interest scenario: divide principal equally
                $monthlyPrincipal = round($loanAmount / $months, 2);
                $monthlyInterest = 0;
                
                // Adjust for the last month to account for rounding differences
                if ($month == $months) {
                    $monthlyPrincipal = $remainingBalance;
                } else {
                    $remainingBalance -= $monthlyPrincipal;
                }
            }
            
            $schedule[] = [
                'month' => $month,
                'emi' => round($emi, 2),
                'principal' => $monthlyPrincipal,
                'interest' => $monthlyInterest,
                'balance' => max(0, $remainingBalance) // Ensure balance doesn't go negative
            ];
        }
        
        return $schedule;
    }


}
