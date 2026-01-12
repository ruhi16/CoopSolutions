<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Ec08LoanRequestComp extends Component
{

    public $showLoanRequestModal = false;

    public $members = null, $loanSchemes = null;

    public $selectedMemberId = null, $selectedLoanSchemeId = null, $loanAmount = null, $loanDate = null;
    
    public $selectedLoanScheme = null, $selectedTimePeriod = null, $expectedAmount = null,  $deleteConfirmId = null;
    // public $selectedLoanSchemeFeatureType = null, $selectedLoanSchemeFeatureName = null, $selectedLoanSchemeFeatureValue = null;

    public $showDeleteConfirmModal = false;
    public $showEmiDetailsModal = false;
    public $loanRequests = null, $selectedLoanRequestId = null;
    public $selectedLoanRequest = null;

    public $emi_amount = null;
    public $principal_amount = null;
    public $interest_amount = null;
    public $loanSchemeDetail = null;
    public $monthlyBreakdown = [];

    
    public function refresh(){
        $this->members = \App\Models\Ec04Member::all();
        $this->loanSchemes = \App\Models\Ec06LoanScheme::all();
        $this->loanRequests = \App\Models\Ec08LoanRequest::with('member', 'loanScheme', 'loanAssign')->get();

        $this->selectedLoanRequestId = null;
            
        $this->selectedMemberId = null;
        $this->selectedLoanSchemeId = null;
        $this->expectedAmount = null;
        $this->loanDate = null;
    }
    
    public function mount(){
        // Initialize deleteConfirmId to null on mount
        $this->refresh();
        
    }


    public function updatedSelectedLoanSchemeId($selectedLoanSchemeId){
        $this->selectedLoanScheme = \App\Models\Ec06LoanScheme::find($selectedLoanSchemeId);
        // dd($this->selectedLoanScheme->loanSchemeDetails );
        
        // Calculate EMI when loan scheme changes
        $this->calculateEMI();
    }

    public function updatedSelectedTimePeriod($selectedTimePeriod){
        // Calculate EMI when time period changes
        $this->calculateEMI();
    }

    public function updatedExpectedAmount($expectedAmount){
        // Calculate EMI when expected amount changes
        $this->calculateEMI();
    }

    public function updatedSelectedMemberId($selectedMemberId){
        // Recalculate EMI when member changes, just in case
        if ($this->selectedLoanSchemeId && $this->expectedAmount && $this->selectedTimePeriod) {
            $this->calculateEMI();
        }
    }

    public function openModal($loanRequestId = null){
        if($loanRequestId != null){
            // when 'edit' button is pressed
            $this->selectedLoanRequestId = $loanRequestId;

            $loanRequest = \App\Models\Ec08LoanRequest::find($loanRequestId);
            $this->selectedMemberId = $loanRequest->member_id;
            $this->selectedLoanSchemeId = $loanRequest->req_loan_scheme_id;
            $this->selectedTimePeriod = (int) $loanRequest->time_period_months / 12;
            $this->expectedAmount = $loanRequest->req_loan_amount;
            $this->loanDate = $loanRequest->req_date;

        }else{
            // when 'add new' button is pressed
            $this->selectedLoanRequestId = null;

            $this->selectedMemberId = null;
            $this->selectedLoanSchemeId = null;
            $this->loanAmount = null;
            $this->loanDate = null;
        }

        $this->showLoanRequestModal = true;
    }

    public function closeModal(){
        $this->refresh();
        $this->showLoanRequestModal = false;
    }

    public function confirmDelete($loanRequestId){ // This method is not used in the current blade file for this component.
        $this->deleteConfirmId = $loanRequestId;
        $this->showDeleteConfirmModal = true;
    }

    public function cancelDelete(){
        $this->deleteConfirmId = null;
        $this->showDeleteConfirmModal = false;
    }

    public function deleteLoanRequest(){
        if($this->deleteConfirmId){
            try{
                $data = \App\Models\Ec08LoanRequest::find($this->deleteConfirmId);
                $data->delete();

                $this->refresh();
                $this->cancelDelete();
                session()->flash('success', 'Loan Scheme Detail Deleted Successfully');
            }catch(\Exception $e){
                session()->flash('error', $e->getMessage());
                $this->cancelDelete();
            }
        }
    }

    public function showEmiDetails($loanRequestId){
        $this->selectedLoanRequest = \App\Models\Ec08LoanRequest::with([
            'member',
            'loanScheme',
            'loanAssign' => function($query) {
                $query->with(['loanAssignSchedules' => function($scheduleQuery) {
                    $scheduleQuery->orderBy('payment_schedule_date');
                }]);
            }
        ])->find($loanRequestId);
        
        $this->showEmiDetailsModal = true;
    }

    public function closeEmiDetailsModal(){
        $this->showEmiDetailsModal = false;
        $this->selectedLoanRequest = null;
    }

    public function getEmiDetails($loanRequestId){
        $loanRequest = \App\Models\Ec08LoanRequest::with([
            'member',
            'loanScheme',
            'loanAssign' => function($query) {
                $query->with(['loanAssignSchedules' => function($scheduleQuery) {
                    $scheduleQuery->orderBy('payment_schedule_date');
                }]);
            }
        ])->find($loanRequestId);
        
        // Calculate EMI breakdown if loan is assigned
        if($loanRequest->loanAssign) {
            $loanAssign = $loanRequest->loanAssign;
            
            // Get loan scheme details to calculate EMI breakdown - specifically for interest rate
            $interestRateFeature = \App\Models\Ec07LoanSchemeFeature::where('loan_scheme_feature_name', 'like', '%interest%')
                ->orWhere('loan_scheme_feature_name', 'like', '%roi%')
                ->orWhere('loan_scheme_feature_name', 'like', '%rate%')
                ->first();
            
            if ($interestRateFeature) {
                $loanSchemeDetail = \App\Models\Ec07LoanSchemeDetail::where('loan_scheme_id', $loanRequest->req_loan_scheme_id)
                    ->where('loan_scheme_feature_id', $interestRateFeature->id)
                    ->where('is_active', true)
                    ->orderBy('updated_at', 'desc')
                    ->first();
            } else {
                // Fallback: try to find any feature that might represent interest rate
                $loanSchemeDetail = \App\Models\Ec07LoanSchemeDetail::where('loan_scheme_id', $loanRequest->req_loan_scheme_id)
                    ->whereHas('loanSchemeFeature', function($query) {
                        $query->where('loan_scheme_feature_name', 'like', '%interest%')
                              ->orWhere('loan_scheme_feature_name', 'like', '%roi%')
                              ->orWhere('loan_scheme_feature_name', 'like', '%rate%');
                    })
                    ->where('is_active', true)
                    ->orderBy('updated_at', 'desc')
                    ->first();
            }
            
            if($loanSchemeDetail && $loanAssign->emi_amount) {
                $annualInterestRate = $loanSchemeDetail->loan_scheme_feature_value ?? 0;
                $monthlyInterestRate = $annualInterestRate / 12 / 100;
                
                // Calculate the breakdown for each EMI schedule
                $emiDetails = [];
                $remainingPrincipal = $loanAssign->loan_amount;
                
                if($monthlyInterestRate > 0) {
                    foreach($loanAssign->loanAssignSchedules as $index => $schedule) {
                        $interestForThisMonth = $remainingPrincipal * $monthlyInterestRate;
                        $principalForThisMonth = $loanAssign->emi_amount - $interestForThisMonth;
                        
                        // Adjust for the last payment if needed
                        if($principalForThisMonth > $remainingPrincipal) {
                            $principalForThisMonth = $remainingPrincipal;
                            $interestForThisMonth = $loanAssign->emi_amount - $principalForThisMonth;
                        }
                        
                        $emiDetails[] = [
                            'emi_sl' => $index + 1,
                            'principal' => round($principalForThisMonth, 2),
                            'interest' => round($interestForThisMonth, 2),
                            'total_remaining_principal' => round($remainingPrincipal - $principalForThisMonth, 2)
                        ];
                        
                        $remainingPrincipal -= $principalForThisMonth;
                        $remainingPrincipal = max(0, $remainingPrincipal); // Ensure it doesn't go negative
                    }
                } else {
                    // If no interest, divide principal equally
                    $totalSchedules = count($loanAssign->loanAssignSchedules);
                    $principalPerInstallment = $loanAssign->loan_amount / $totalSchedules;
                    
                    foreach($loanAssign->loanAssignSchedules as $index => $schedule) {
                        $emiDetails[] = [
                            'emi_sl' => $index + 1,
                            'principal' => round($principalPerInstallment, 2),
                            'interest' => 0,
                            'total_remaining_principal' => round($loanAssign->loan_amount - ($principalPerInstallment * ($index + 1)), 2)
                        ];
                    }
                }
                
                $loanRequest->emiDetails = $emiDetails;
            } else {
                // If no loan assign or scheme details, set empty details
                $loanRequest->emiDetails = [];
            }
        } else {
            // If loan is not assigned yet, calculate based on request
            // Use the stored ROI from the loan request
            $annualInterestRate = $loanRequest->req_loan_schema_roi_copy ?? 0;
            $monthlyInterestRate = $annualInterestRate / 12 / 100;
            $loanAmount = $loanRequest->req_loan_amount;
            $months = $loanRequest->time_period_months;
            
            if($monthlyInterestRate > 0 && $months > 0) {
                // Calculate EMI using the formula: EMI = P * r * (1+r)^n / ((1+r)^n - 1)
                $emi = $loanAmount * $monthlyInterestRate * pow(1 + $monthlyInterestRate, $months) / (pow(1 + $monthlyInterestRate, $months) - 1);
                
                // Calculate breakdown for each month
                $emiDetails = [];
                $remainingPrincipal = $loanAmount;
                
                for($i = 1; $i <= $months; $i++) {
                    $interestForThisMonth = $remainingPrincipal * $monthlyInterestRate;
                    $principalForThisMonth = $emi - $interestForThisMonth;
                    
                    // Adjust for the last payment if needed
                    if($principalForThisMonth > $remainingPrincipal) {
                        $principalForThisMonth = $remainingPrincipal;
                        $interestForThisMonth = $emi - $principalForThisMonth;
                    }
                    
                    $emiDetails[] = [
                        'emi_sl' => $i,
                        'principal' => round($principalForThisMonth, 2),
                        'interest' => round($interestForThisMonth, 2),
                        'total_remaining_principal' => round($remainingPrincipal - $principalForThisMonth, 2)
                    ];
                    
                    $remainingPrincipal -= $principalForThisMonth;
                    $remainingPrincipal = max(0, $remainingPrincipal); // Ensure it doesn't go negative
                }
                
                $loanRequest->emiDetails = $emiDetails;
            } else {
                // If no interest, divide principal equally
                $principalPerInstallment = $loanAmount / $months;
                $emiDetails = [];
                
                for($i = 1; $i <= $months; $i++) {
                    $emiDetails[] = [
                        'emi_sl' => $i,
                        'principal' => round($principalPerInstallment, 2),
                        'interest' => 0,
                        'total_remaining_principal' => round($loanAmount - ($principalPerInstallment * $i), 2)
                    ];
                }
                
                $loanRequest->emiDetails = $emiDetails;
            }
        }
        
        $this->selectedLoanRequest = $loanRequest;
        $this->showEmiDetailsModal = true;
    }


    public function calculateEMI()
    {
        if (!$this->expectedAmount || !$this->selectedLoanSchemeId || !$this->selectedTimePeriod) {
            $this->emi_amount = null;
            $this->principal_amount = null;
            $this->interest_amount = null;
            $this->monthlyBreakdown = [];
            return;
        }

        // Get the most recent loan scheme detail with ROI for the selected loan scheme
        // Need to find the loan scheme detail that corresponds to the interest rate feature
        $interestRateFeature = \App\Models\Ec07LoanSchemeFeature::where('loan_scheme_feature_name', 'like', '%interest%')
            ->orWhere('loan_scheme_feature_name', 'like', '%roi%')
            ->orWhere('loan_scheme_feature_name', 'like', '%rate%')
            ->first();
        
        if ($interestRateFeature) {
            $this->loanSchemeDetail = \App\Models\Ec07LoanSchemeDetail::where('loan_scheme_id', $this->selectedLoanSchemeId)
                ->where('loan_scheme_feature_id', $interestRateFeature->id)
                ->where('is_active', true)
                ->orderBy('updated_at', 'desc')
                ->first();
        } else {
            // Fallback: try to find any feature that might represent interest rate
            $this->loanSchemeDetail = \App\Models\Ec07LoanSchemeDetail::where('loan_scheme_id', $this->selectedLoanSchemeId)
                ->whereHas('loanSchemeFeature', function($query) {
                    $query->where('loan_scheme_feature_name', 'like', '%interest%')
                          ->orWhere('loan_scheme_feature_name', 'like', '%roi%')
                          ->orWhere('loan_scheme_feature_name', 'like', '%rate%');
                })
                ->where('is_active', true)
                ->orderBy('updated_at', 'desc')
                ->first();
        }
        
        if (!$this->loanSchemeDetail) {
            $this->emi_amount = null;
            $this->principal_amount = null;
            $this->interest_amount = null;
            $this->monthlyBreakdown = [];
            return;
        }
        
        // Use the ROI from the loan scheme detail to calculate EMI
        $annualInterestRate = (float) ($this->loanSchemeDetail->loan_scheme_feature_value ?? 0);
        
        $principal = (float) $this->expectedAmount;
        $months = (int) $this->selectedTimePeriod * 12;
        
        if ($annualInterestRate > 0 && $months > 0) {
            // Convert annual interest rate to monthly rate
            $monthlyInterestRate = $annualInterestRate / 12 / 100;
            
            // Calculate EMI using the formula: EMI = P * r * (1+r)^n / ((1+r)^n - 1)
            if ($monthlyInterestRate > 0) {
                $emi = $principal * $monthlyInterestRate * pow(1 + $monthlyInterestRate, $months) / (pow(1 + $monthlyInterestRate, $months) - 1);
                $this->emi_amount = round($emi, 2);
                
                // Calculate total interest
                $totalPayment = $this->emi_amount * $months;
                $this->interest_amount = round($totalPayment - $principal, 2);
                $this->principal_amount = $principal;
                
                // Calculate monthly breakdown
                $this->calculateMonthlyBreakdown($principal, $monthlyInterestRate, $months, $this->emi_amount);
            } else {
                // If interest rate is 0, simple division
                $this->emi_amount = round($principal / $months, 2);
                $this->interest_amount = 0;
                $this->principal_amount = $principal;
                
                // Simple equal distribution when no interest
                $this->calculateMonthlyBreakdown($principal, 0, $months, $this->emi_amount);
            }
        } else {
            // If no interest rate or terms, set EMI to simple division
            $this->emi_amount = $months > 0 ? round($principal / $months, 2) : 0;
            $this->interest_amount = 0;
            $this->principal_amount = $principal;
            
            // Simple equal distribution when no interest
            $this->calculateMonthlyBreakdown($principal, 0, $months, $this->emi_amount);
        }
    }

    private function calculateMonthlyBreakdown($principal, $monthlyInterestRate, $months, $emi_amount)
    {
        $this->monthlyBreakdown = [];
        $remainingBalance = $principal;
        
        for ($month = 1; $month <= $months; $month++) {
            if ($monthlyInterestRate > 0) {
                // Calculate interest for current month based on remaining balance
                $monthlyInterest = $remainingBalance * $monthlyInterestRate;
                $monthlyPrincipal = $emi_amount - $monthlyInterest;
                
                // Adjust principal if it would exceed remaining balance (for the last month)
                if ($monthlyPrincipal > $remainingBalance) {
                    $monthlyPrincipal = $remainingBalance;
                    $monthlyInterest = $emi_amount - $monthlyPrincipal;
                }
                
                $remainingBalance -= $monthlyPrincipal;
                
                // Round to prevent floating point errors
                $monthlyInterest = round($monthlyInterest, 2);
                $monthlyPrincipal = round($monthlyPrincipal, 2);
                $remainingBalance = round($remainingBalance, 2);
            } else {
                // No interest scenario: divide principal equally
                $monthlyPrincipal = round($principal / $months, 2);
                $monthlyInterest = 0;
                
                // Adjust for the last month to account for rounding differences
                if ($month == $months) {
                    $monthlyPrincipal = $remainingBalance;
                } else {
                    $remainingBalance -= $monthlyPrincipal;
                }
            }
            
            $this->monthlyBreakdown[] = [
                'month' => $month,
                'emi' => round($emi_amount, 2),
                'principal' => $monthlyPrincipal,
                'interest' => $monthlyInterest,
                'balance' => max(0, $remainingBalance) // Ensure balance doesn't go negative
            ];
        }
    }

    public function saveLoanRequest(){
        
        $this->validate([
            'selectedMemberId' => 'required',
            'selectedLoanSchemeId' => 'required',
            'selectedTimePeriod' => 'required',
            'expectedAmount' => 'required',
            // 'req_ate' => 'required',
            // 'status' => 'required',
        ]);

        try{
            // Get the latest ROI from loan scheme details to save as copy
            // Need to find the loan scheme detail that corresponds to the interest rate feature
            $interestRateFeature = \App\Models\Ec07LoanSchemeFeature::where('loan_scheme_feature_name', 'like', '%interest%')
                ->orWhere('loan_scheme_feature_name', 'like', '%roi%')
                ->orWhere('loan_scheme_feature_name', 'like', '%rate%')
                ->first();
            
            $roi = null;
            if ($interestRateFeature) {
                $loanSchemeDetail = \App\Models\Ec07LoanSchemeDetail::where('loan_scheme_id', $this->selectedLoanSchemeId)
                    ->where('loan_scheme_feature_id', $interestRateFeature->id)
                    ->where('is_active', true)
                    ->orderBy('updated_at', 'desc')
                    ->first();
                
                $roi = $loanSchemeDetail ? $loanSchemeDetail->loan_scheme_feature_value : null;
            } else {
                // Fallback: try to find any feature that might represent interest rate
                $loanSchemeDetail = \App\Models\Ec07LoanSchemeDetail::where('loan_scheme_id', $this->selectedLoanSchemeId)
                    ->whereHas('loanSchemeFeature', function($query) {
                        $query->where('loan_scheme_feature_name', 'like', '%interest%')
                              ->orWhere('loan_scheme_feature_name', 'like', '%roi%')
                              ->orWhere('loan_scheme_feature_name', 'like', '%rate%');
                    })
                    ->where('is_active', true)
                    ->orderBy('updated_at', 'desc')
                    ->first();
                
                $roi = $loanSchemeDetail ? $loanSchemeDetail->loan_scheme_feature_value : null;
            }

            // Prepare the attributes to save
            $attributes = [
                'member_id' => $this->selectedMemberId,
                'organisation_id' => 1,
                'req_loan_scheme_id' => $this->selectedLoanSchemeId,
                'req_loan_schema_roi_copy' => $roi,
                'req_loan_amount' => $this->expectedAmount,
                'time_period_months' => (int) $this->selectedTimePeriod * 12,
                'req_date' => now(), // $this->loanDate,
                'status' => 'pending',
            ];

            // Use updateOrCreate with proper key-value pairs
            if ($this->selectedLoanRequestId) {
                // Update existing record
                $loanRequest = \App\Models\Ec08LoanRequest::find($this->selectedLoanRequestId);
                if ($loanRequest) {
                    $loanRequest->update($attributes);
                }
            } else {
                // Create new record
                \App\Models\Ec08LoanRequest::create($attributes);
            }

            $this->closeModal();
            $this->refresh(); // Refresh the data to show the updated list
            session()->flash('success', 'Loan Request Saved or Updated Successfully');
        }catch(\Exception $e){
            session()->flash('error', $e->getMessage());
            error_log('Loan Request Save Error: ' . $e->getMessage());
        }
        // $this->dispatchBrowserEvent('alert', ['type' => 'success', 'message' => 'Loan Request Saved Successfully']);
    }


    public function render()
    {
        return view('livewire.ec08-loan-request-comp');
    }

    public function updated($property, $value) {
        // This will be called when any property is updated
        // We can use this to trigger recalculations if needed
    }
}
