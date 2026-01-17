<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Ec21BankLoanBorrowed;
use App\Models\Ec21BankLoanScheme;
use App\Models\Ec21BankLoanSchemeSpecification;
use App\Models\Ec21BankLoanSchemaParticular;
use App\Models\Ec20Bank;

class Ec21BankLoanBorrowedComp extends Component
{
    public $borrowedLoans;
    public $loanSchemes;
    public $loanSchemeSpecifications;
    
    public $borrowedLoanId = null;
    public $selectedSchemeId = null;
    public $loanAssignRefId = null;
    public $selectedSpecifications = [];
    public $name = null;
    public $description = null;
    public $loanBorrowedAmount = null;
    public $loanBorrowedDate = null;
    public $bankLoanBorrowedPreviousBalance = null;
    public $installmentAmount = null;
    public $noOfInstallments = null;
    public $status = 'suspended';
    public $isActive = true;
    public $remarks = null;
    
    public $showBorrowedLoanModal = false;
    public $showDeleteConfirmModal = false;
    public $deleteConfirmId = null;
    public $expandedLoanId = null;
    
    public $schemeSpecifications = [];
    
    protected $listeners = ['refreshBorrowedLoans' => 'refresh'];

    public function mount(){
        $this->refresh();
    }

    public function refresh(){
        $this->borrowedLoans = Ec21BankLoanBorrowed::with(['loanScheme', 'specifications.particular'])->get();
        $this->loanSchemes = Ec21BankLoanScheme::with(['bank', 'specifications.particular'])->get();
        
        $this->resetForm();
    }

    public function resetForm(){
        $this->borrowedLoanId = null;
        $this->selectedSchemeId = null;
        $this->loanAssignRefId = null;
        $this->selectedSpecifications = [];
        $this->name = null;
        $this->description = null;
        $this->loanBorrowedAmount = null;
        $this->loanBorrowedDate = null;
        $this->bankLoanBorrowedPreviousBalance = null;
        $this->installmentAmount = null;
        $this->noOfInstallments = null;
        $this->status = 'suspended';
        $this->isActive = true;
        $this->remarks = null;
        $this->schemeSpecifications = [];
    }

    public function openModal($borrowedLoanId = null){
        if($borrowedLoanId){
            $borrowedLoan = Ec21BankLoanBorrowed::with(['loanScheme.specifications', 'specifications'])->find($borrowedLoanId);
            $this->borrowedLoanId = $borrowedLoan->id;
            $this->name = $borrowedLoan->name;
            $this->description = $borrowedLoan->description;
            $this->selectedSchemeId = $borrowedLoan->bank_loan_scheme_particular_id;  // Set the selected scheme ID
            $this->loanAssignRefId = $borrowedLoan->loan_assign_ref_id;
            $this->loanBorrowedAmount = $borrowedLoan->loan_borrowed_amount;
            $this->loanBorrowedDate = $borrowedLoan->loan_borrowed_date;
            $this->bankLoanBorrowedPreviousBalance = $borrowedLoan->bank_loan_borrowed_previous_balance;
            $this->installmentAmount = $borrowedLoan->installment_amount;
            $this->noOfInstallments = $borrowedLoan->no_of_installments;
            $this->status = $borrowedLoan->status;
            $this->isActive = $borrowedLoan->is_active;
            $this->remarks = $borrowedLoan->remarks;
            
            // Load loan scheme specifications if loan scheme is selected
            if($this->selectedSchemeId) {
                $this->loadSchemeSpecifications($this->selectedSchemeId);
            }
            
            // Load existing specifications for this borrowed loan
            $this->selectedSpecifications = $borrowedLoan->specifications->pluck('bank_loan_scheme_specification_id')->toArray();
        } else {
            $this->resetForm();
        }

        $this->showBorrowedLoanModal = true;
    }

    public function closeModal(){
        $this->showBorrowedLoanModal = false;
        $this->resetForm();
    }

    public function loadSchemeSpecifications($schemeId = null){
        if($schemeId){
            $loanScheme = Ec21BankLoanScheme::with(['specifications.particular'])->find($schemeId);
            if($loanScheme) {
                $this->selectedSchemeId = $schemeId;
                $this->schemeSpecifications = $loanScheme->specifications;
                
                // Reset selected specifications array
                $this->selectedSpecifications = [];
                
                // Pre-select mandatory specifications (non-regular = mandatory)
                foreach($this->schemeSpecifications as $spec) {
                    if(!$spec->is_regular) { // Mandatory specifications
                        $this->selectedSpecifications[] = $spec->id;
                    }
                }
            }
        }
    }
    
    public function toggleSpecification($specId){
        if(in_array($specId, $this->selectedSpecifications)){
            // Remove from array if already selected
            $this->selectedSpecifications = array_filter($this->selectedSpecifications, function($item) use ($specId) {
                return $item != $specId;
            });
            $this->selectedSpecifications = array_values($this->selectedSpecifications); // Reindex
        } else {
            // Add to array if not selected
            $this->selectedSpecifications[] = $specId;
        }
    }
    
    public function toggleSpecifications($loanId){
        if($this->expandedLoanId == $loanId){
            // Close if already expanded
            $this->expandedLoanId = null;
        } else {
            // Expand the clicked loan
            $this->expandedLoanId = $loanId;
        }
    }

    public function saveBorrowedLoan(){
        $this->validate([
            'name' => 'required|string|max:255',
            'loanBorrowedAmount' => 'required|numeric|min:0',
            'loanBorrowedDate' => 'nullable|date',
            'status' => 'required|in:running,completed,upcoming,suspended,cancelled',
            'selectedSchemeId' => 'required|exists:ec21_bank_loan_schemes,id',
        ]);

        try{
            $borrowedLoan = Ec21BankLoanBorrowed::updateOrCreate([
                'id' => $this->borrowedLoanId,
            ], [
                'name' => $this->name,
                'description' => $this->description,
                'bank_loan_scheme_particular_id' => $this->selectedSchemeId,  // Using selectedSchemeId which corresponds to loan scheme
                'loan_assign_ref_id' => $this->loanAssignRefId,
                'loan_borrowed_amount' => $this->loanBorrowedAmount,
                'loan_borrowed_date' => $this->loanBorrowedDate,
                'bank_loan_borrowed_previous_balance' => $this->bankLoanBorrowedPreviousBalance,
                'installment_amount' => $this->installmentAmount,
                'no_of_installments' => $this->noOfInstallments,
                'status' => $this->status,
                'organisation_id' => session('organisation_id') ?? 1,
                'user_id' => auth()->id() ?? 1,
                'financial_year_id' => session('financial_year_id') ?? 1,
                'is_active' => $this->isActive,
                'remarks' => $this->remarks,
            ]);

            // Now assign the selected specifications to the borrowed loan
            if ($this->selectedSpecifications) {
                // First, delete existing specifications for this borrowed loan
                $borrowedLoan->specifications()->delete();

                // Then add the selected specifications with their corresponding values
                foreach ($this->selectedSpecifications as $specId) {
                    $specification = Ec21BankLoanSchemeSpecification::with('particular')->find($specId);
                    if ($specification) {
                        $borrowedLoan->specifications()->create([
                            'bank_loan_scheme_specification_id' => $specification->id,
                            'bank_loan_schema_particular_id' => $specification->bank_loan_schema_particular_id,
                            'bank_loan_schema_particular_value' => $specification->bank_loan_schema_particular_value,
                            'is_percent_on_current_balance' => $specification->is_percent_on_current_balance,
                            'is_regular' => $specification->is_regular,
                            'effected_on' => $specification->effected_on,
                            'status' => $specification->status,
                            'organisation_id' => $specification->organisation_id,
                            'user_id' => $specification->user_id,
                            'financial_year_id' => $specification->financial_year_id,
                            'is_active' => $specification->is_active,
                            'remarks' => $specification->remarks,
                        ]);
                    }
                }
            }

            $this->closeModal();
            $this->refresh();
            session()->flash('success', 'Bank Loan Borrowed ' . ($this->borrowedLoanId ? 'Updated' : 'Created') . ' Successfully');
        }catch(\Exception $e){
            session()->flash('error', $e->getMessage());
        }
    }

    public function confirmDelete($borrowedLoanId){
        $this->deleteConfirmId = $borrowedLoanId;
        $this->showDeleteConfirmModal = true;
    }

    public function cancelDelete(){
        $this->deleteConfirmId = null;
        $this->showDeleteConfirmModal = false;
    }

    public function deleteBorrowedLoan(){
        if($this->deleteConfirmId){
            try{
                $borrowedLoan = Ec21BankLoanBorrowed::with('specifications')->find($this->deleteConfirmId);
                
                // Delete related specifications first
                if($borrowedLoan && $borrowedLoan->specifications) {
                    $borrowedLoan->specifications()->delete();
                }
                
                $borrowedLoan->delete();

                $this->refresh();
                $this->cancelDelete();
                session()->flash('success', 'Bank Loan Borrowed Deleted Successfully');
            }catch(\Exception $e){
                session()->flash('error', $e->getMessage());
                $this->cancelDelete();
            }
        }
    }

    public function render()
    {
        return view('livewire.ec21-bank-loan-borrowed-comp');
    }
}