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
    
    public $isEmiEnabled = false, $emiAmount = null, $emiPaymentDate = null;

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
        $this->isEmiEnabled = false;
        $this->emiAmount = null;
        $this->emiPaymentDate = null;
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

    public function updatedIsEmiEnabled($value){
        if ($value && $this->selectedLoanSchemeId && $this->expectedAmount && $this->selectedTimePeriod) {
            $this->calculateEMI();
        }
    }

    public function openModal($loanRequestId = null){
        if($loanRequestId != null){
            // when 'edit' button is pressed
            $loanRequest = \App\Models\Ec08LoanRequest::find($loanRequestId);
            
            // Check if the loan request is assigned
            if ($loanRequest && $loanRequest->isAssigned()) {
                session()->flash('error', 'Cannot edit loan request that is already assigned.');
                return;
            }
            
            $this->selectedLoanRequestId = $loanRequestId;
            $this->selectedMemberId = $loanRequest->member_id;
            $this->selectedLoanSchemeId = $loanRequest->req_loan_scheme_id;
            $this->selectedTimePeriod = (int) $loanRequest->time_period_months / 12;
            $this->expectedAmount = $loanRequest->req_loan_amount;
            $this->loanDate = $loanRequest->req_date;
            $this->isEmiEnabled = $loanRequest->is_emi_enabled;
            $this->emiAmount = $loanRequest->emi_amount;
            // Convert day-of-month integer back to a date format for the form
            if ($loanRequest->emi_payment_date) {
                // Use current year and month with the stored day
                $currentDate = \Carbon\Carbon::now();
                $this->emiPaymentDate = $currentDate->year . '-' . str_pad($currentDate->month, 2, '0', STR_PAD_LEFT) . '-' . str_pad($loanRequest->emi_payment_date, 2, '0', STR_PAD_LEFT);
            } else {
                $this->emiPaymentDate = null;
            }

        }else{
            // when 'add new' button is pressed
            $this->selectedLoanRequestId = null;

            $this->selectedMemberId = null;
            $this->selectedLoanSchemeId = null;
            $this->loanAmount = null;
            $this->loanDate = null;
            $this->isEmiEnabled = false;
            $this->emiAmount = null;
            $this->emiPaymentDate = null;
        }

        $this->showLoanRequestModal = true;
    }

    public function closeModal(){
        $this->refresh();
        $this->showLoanRequestModal = false;
    }

    public function confirmDelete($loanRequestId){ // This method is not used in the current blade file for this component.
        $loanRequest = \App\Models\Ec08LoanRequest::find($loanRequestId);
        
        // Check if the loan request is assigned
        if ($loanRequest && $loanRequest->isAssigned()) {
            session()->flash('error', 'Cannot delete loan request that is already assigned.');
            return;
        }
        
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
                
                // Double check if the loan request is assigned
                if ($data && $data->isAssigned()) {
                    session()->flash('error', 'Cannot delete loan request that is already assigned.');
                    $this->cancelDelete();
                    return;
                }
                
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
        
        // Check if loan request exists before showing modal
        if ($this->selectedLoanRequest) {
            $this->showEmiDetailsModal = true;
        } else {
            session()->flash('error', 'Loan request not found.');
        }
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
        
        if ($loanRequest) {
            // Use the model's built-in method to get amortization schedule
            $loanRequest->emiDetails = $loanRequest->getAmortizationSchedule();
            
            $this->selectedLoanRequest = $loanRequest;
            $this->showEmiDetailsModal = true;
        } else {
            session()->flash('error', 'Loan request not found.');
        }
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
        
        // Set the emi_amount field when EMI is enabled
        if ($this->isEmiEnabled) {
            $this->emiAmount = $this->emi_amount;
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
            'isEmiEnabled' => 'boolean',
            'emiAmount' => 'nullable|numeric|min:0',
            'emiPaymentDate' => 'nullable|date_format:Y-m-d',
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

            // Process emiPaymentDate to only keep the day part if it's provided
            $processedEmiPaymentDate = null;
            if ($this->emiPaymentDate) {
                $date = \Carbon\Carbon::parse($this->emiPaymentDate);
                // Just store the day number (1-31) for recurring monthly payments
                $processedEmiPaymentDate = $date->day;
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
                'is_emi_enabled' => $this->isEmiEnabled,
                'emi_amount' => $this->emiAmount,
                'emi_payment_date' => $processedEmiPaymentDate, // Store only the day number
            ];

            // Use updateOrCreate with proper key-value pairs
            if ($this->selectedLoanRequestId) {
                // Update existing record
                $loanRequest = \App\Models\Ec08LoanRequest::find($this->selectedLoanRequestId);
                
                // Check if the loan request is assigned before updating
                if ($loanRequest && $loanRequest->isAssigned()) {
                    session()->flash('error', 'Cannot update loan request that is already assigned.');
                    $this->closeModal();
                    return;
                }
                
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
