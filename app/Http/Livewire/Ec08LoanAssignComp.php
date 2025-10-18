<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Ec08LoanAssign;
use App\Models\Ec09LoanAssignParticular;
use App\Models\Ec08LoanRequest;
use App\Models\Ec04Member;
use App\Models\Ec06LoanScheme;
use App\Models\Ec07LoanSchemeDetail;
use App\Models\Ec01Organisation;
use Illuminate\Support\Facades\Auth;

class Ec08LoanAssignComp extends Component
{
    use WithPagination;

    // Form fields
    public $organisation_id;
    public $member_id;
    public $loan_request_id;
    public $loan_scheme_id;
    public $loan_amount;
    public $start_date;
    public $end_date;
    public $emi_payment_date;
    public $emi_amount;
    public $is_active = true;
    public $remarks;

    // Additional fields for loan scheme details
    public $selectedLoanSchemeDetails = []; // For storing selected loan scheme details
    public $loanSchemeDetails = []; // For storing available loan scheme details

    // UI state
    public $isModalOpen = false;
    public $isDeleteModalOpen = false;
    public $editingId = null;
    public $search = '';
    public $sortField = 'id';
    public $sortDirection = 'desc';
    public $perPage = 10;

    // Data lists
    public $members = [];
    public $allLoanRequests = []; // All loan requests for selection
    public $loanSchemes = [];

    protected $rules = [
        'organisation_id' => 'required|exists:ec01_organisations,id',
        'member_id' => 'required|exists:ec04_members,id',
        'loan_request_id' => 'required|exists:ec08_loan_requests,id',
        'loan_scheme_id' => 'required|exists:ec06_loan_schemes,id',
        'loan_amount' => 'required|numeric|min:1',
        'start_date' => 'required|date',
        'end_date' => 'required|date|after:start_date',
        'emi_payment_date' => 'required|integer|min:1|max:31',
        'emi_amount' => 'required|numeric|min:1',
        'is_active' => 'boolean',
        'remarks' => 'nullable|string|max:500',
        'selectedLoanSchemeDetails' => 'array',
        'selectedLoanSchemeDetails.*' => 'exists:ec07_loan_scheme_details,id',
    ];

    public function mount()
    {
        $this->organisation_id = session('organisation_id') ?? 1; // Default to 1 if not set
        $this->start_date = now()->format('Y-m-d');
        $this->end_date = now()->addMonths(12)->format('Y-m-d');
        $this->emi_payment_date = now()->day; // Default to current day of month
        $this->loadDropdowns();
    }

    public function loadDropdowns()
    {
        // Load active members for the organization
        $this->members = Ec04Member::where('organisation_id', $this->organisation_id)
            ->where('is_active', true)
            ->get();
            
        // Load ALL active loan requests for the organization
        $this->allLoanRequests = Ec08LoanRequest::with('member', 'loanScheme')
            ->where('organisation_id', $this->organisation_id)
            ->where('is_active', true)
            ->get();
            
        // Load active loan schemes
        $this->loanSchemes = Ec06LoanScheme::where('is_active', true)
            ->get();
    }

    public function render()
    {
        $query = Ec08LoanAssign::with(['member', 'loanRequest', 'loanScheme'])
            ->where('organisation_id', $this->organisation_id);

        if ($this->search) {
            $query->whereHas('member', function($q) {
                $q->where('name', 'like', '%' . $this->search . '%');
            })
            ->orWhereHas('loanScheme', function($q) {
                $q->where('name', 'like', '%' . $this->search . '%');
            });
        }

        $loanAssigns = $query->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.ec08-loan-assign-comp', [
            'loanAssigns' => $loanAssigns
        ]);
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function openModal()
    {
        $this->resetForm();
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->resetValidation();
    }

    public function resetForm()
    {
        $this->editingId = null;
        $this->member_id = null;
        $this->loan_request_id = null;
        $this->loan_scheme_id = null;
        $this->loan_amount = null;
        $this->start_date = now()->format('Y-m-d');
        $this->end_date = now()->addMonths(12)->format('Y-m-d');
        $this->emi_payment_date = now()->day;
        $this->emi_amount = null;
        $this->is_active = true;
        $this->remarks = null;
        $this->selectedLoanSchemeDetails = [];
        $this->loanSchemeDetails = [];
        $this->resetValidation();
    }

    public function save()
    {
        $this->validate();

        // Create or update the loan assignment
        if ($this->editingId) {
            $loanAssign = Ec08LoanAssign::findOrFail($this->editingId);
            $loanAssign->update([
                'organisation_id' => $this->organisation_id,
                'member_id' => $this->member_id,
                'loan_request_id' => $this->loan_request_id,
                'loan_scheme_id' => $this->loan_scheme_id,
                'loan_amount' => $this->loan_amount,
                'start_date' => $this->start_date,
                'end_date' => $this->end_date,
                'emi_payment_date' => $this->emi_payment_date,
                'emi_amount' => $this->emi_amount,
                'is_active' => $this->is_active,
                'remarks' => $this->remarks,
            ]);
            
            session()->flash('message', 'Loan assignment updated successfully.');
        } else {
            // Create the loan assignment
            $loanAssign = Ec08LoanAssign::create([
                'organisation_id' => $this->organisation_id,
                'member_id' => $this->member_id,
                'loan_request_id' => $this->loan_request_id,
                'loan_scheme_id' => $this->loan_scheme_id,
                'loan_amount' => $this->loan_amount,
                'start_date' => $this->start_date,
                'end_date' => $this->end_date,
                'emi_payment_date' => $this->emi_payment_date,
                'emi_amount' => $this->emi_amount,
                'is_active' => $this->is_active,
                'remarks' => $this->remarks,
            ]);
            
            // Create loan assign particulars for each selected loan scheme detail
            foreach ($this->selectedLoanSchemeDetails as $detailId) {
                Ec09LoanAssignParticular::create([
                    'loan_assign_id' => $loanAssign->id,
                    'loan_scheme_id' => $this->loan_scheme_id,
                    'loan_scheme_detail_id' => $detailId,
                    'is_regular' => true, // Assuming regular by default
                    'is_active' => true,
                ]);
            }
            
            // Update the loan request to set is_active to false and done_by to user_id
            $loanRequest = Ec08LoanRequest::find($this->loan_request_id);
            if ($loanRequest) {
                $loanRequest->update([
                    'is_active' => false,
                    'done_by' => Auth::id() ?? 1, // Use current user ID or default to 1
                ]);
            }
            
            session()->flash('message', 'Loan assignment created successfully.');
        }

        $this->closeModal();
        $this->resetForm();
        $this->loadDropdowns(); // Refresh dropdowns
    }

    public function edit($id)
    {
        $loanAssign = Ec08LoanAssign::findOrFail($id);
        
        $this->editingId = $id;
        $this->organisation_id = $loanAssign->organisation_id;
        $this->member_id = $loanAssign->member_id;
        $this->loan_request_id = $loanAssign->loan_request_id;
        $this->loan_scheme_id = $loanAssign->loan_scheme_id;
        $this->loan_amount = $loanAssign->loan_amount;
        $this->start_date = $loanAssign->start_date;
        $this->end_date = $loanAssign->end_date;
        $this->emi_payment_date = $loanAssign->emi_payment_date;
        $this->emi_amount = $loanAssign->emi_amount;
        $this->is_active = $loanAssign->is_active;
        $this->remarks = $loanAssign->remarks;
        
        // Load existing loan assign particulars for editing
        $particulars = Ec09LoanAssignParticular::where('loan_assign_id', $id)->get();
        $this->selectedLoanSchemeDetails = $particulars->pluck('loan_scheme_detail_id')->toArray();
        
        // Load loan scheme details for the selected loan scheme
        $this->loanSchemeDetails = Ec07LoanSchemeDetail::where('loan_scheme_id', $this->loan_scheme_id)
            ->where('is_active', true)
            ->get();
        
        $this->isModalOpen = true;
    }

    public function openDeleteModal($id)
    {
        $this->editingId = $id;
        $this->isDeleteModalOpen = true;
    }

    public function closeDeleteModal()
    {
        $this->isDeleteModalOpen = false;
    }

    public function delete()
    {
        $loanAssign = Ec08LoanAssign::findOrFail($this->editingId);
        $loanAssign->delete();
        
        // Also delete related loan assign particulars
        Ec09LoanAssignParticular::where('loan_assign_id', $this->editingId)->delete();
        
        session()->flash('message', 'Loan assignment deleted successfully.');
        $this->closeDeleteModal();
    }

    // When loan request is selected, auto-populate member, loan scheme, and amount
    // Also load loan scheme details and calculate EMI
    public function updatedLoanRequestId()
    {
        if ($this->loan_request_id) {
            $loanRequest = Ec08LoanRequest::with('member', 'loanScheme')->find($this->loan_request_id);
            if ($loanRequest) {
                $this->member_id = $loanRequest->member_id;
                $this->loan_scheme_id = $loanRequest->req_loan_scheme_id;
                // Use approved amount if available, otherwise use requested amount
                $this->loan_amount = $loanRequest->approved_loan_amount ?: $loanRequest->req_loan_amount;
                
                // Load loan scheme details for the selected loan scheme
                $this->loanSchemeDetails = Ec07LoanSchemeDetail::where('loan_scheme_id', $this->loan_scheme_id)
                    ->where('is_active', true)
                    ->get();
                
                // Auto-calculate EMI when loan request is selected
                $this->calculateEMI();
            }
        } else {
            // Clear loan scheme details if no loan request is selected
            $this->loanSchemeDetails = [];
            $this->emi_amount = null;
        }
    }

    // Calculate EMI based on principal, interest rate, and time period
    public function calculateEMI()
    {
        // Validate required fields
        if (!$this->loan_amount || !$this->loan_scheme_id) {
            $this->emi_amount = null;
            return;
        }

        // Get loan scheme details to calculate EMI
        $loanSchemeDetail = Ec07LoanSchemeDetail::where('loan_scheme_id', $this->loan_scheme_id)
            ->where('is_active', true)
            ->first();
            
        if (!$loanSchemeDetail) {
            $this->emi_amount = null;
            return;
        }

        $principal = (float) $this->loan_amount;
        $annualInterestRate = (float) ($loanSchemeDetail->main_interest_rate ?? 0);
        
        // Calculate loan term in months based on start and end dates
        $months = 12; // Default to 12 months
        if ($this->start_date && $this->end_date) {
            try {
                $startDate = \Carbon\Carbon::parse($this->start_date);
                $endDate = \Carbon\Carbon::parse($this->end_date);
                $months = $startDate->diffInMonths($endDate);
                // Ensure minimum of 1 month
                $months = max(1, $months);
            } catch (\Exception $e) {
                // If date parsing fails, use default 12 months
                $months = 12;
            }
        }

        // If we have a loan request, use its time period as fallback
        if ($months == 12 && $this->loan_request_id) {
            $loanRequest = Ec08LoanRequest::find($this->loan_request_id);
            if ($loanRequest && $loanRequest->time_period_months) {
                $months = $loanRequest->time_period_months;
            }
        }
        
        if ($annualInterestRate > 0 && $months > 0) {
            // Convert annual interest rate to monthly rate
            $monthlyInterestRate = $annualInterestRate / 12 / 100;
            
            // Calculate EMI using the formula: EMI = P * r * (1+r)^n / ((1+r)^n - 1)
            if ($monthlyInterestRate > 0) {
                $emi = $principal * $monthlyInterestRate * pow(1 + $monthlyInterestRate, $months) / (pow(1 + $monthlyInterestRate, $months) - 1);
                $this->emi_amount = round($emi, 2);
            } else {
                // If interest rate is 0, simple division
                $this->emi_amount = round($principal / $months, 2);
            }
        } else {
            // If no interest rate or terms, set EMI to simple division
            $this->emi_amount = $months > 0 ? round($principal / $months, 2) : 0;
        }
    }

    // Auto-calculate EMI when loan amount changes
    public function updatedLoanAmount()
    {
        $this->calculateEMI();
    }

    // Auto-calculate EMI when loan scheme changes
    public function updatedLoanSchemeId()
    {
        $this->calculateEMI();
    }
    
    // Auto-calculate EMI when member changes (in case of manual member selection)
    public function updatedMemberId()
    {
        $this->calculateEMI();
    }
    
    // Auto-calculate EMI when start date changes
    public function updatedStartDate()
    {
        $this->calculateEMI();
    }
    
    // Auto-calculate EMI when end date changes
    public function updatedEndDate()
    {
        $this->calculateEMI();
    }
}