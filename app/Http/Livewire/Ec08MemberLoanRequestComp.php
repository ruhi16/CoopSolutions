<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Ec08LoanRequest as LoanRequest;
use App\Models\Ec06LoanScheme as LoanSchema;
use Livewire\WithPagination;
use LivewireUI\Modal\ModalComponent;

class Ec08MemberLoanRequestComp extends Component
{
    use WithPagination;
    
    // Properties for the form fields
    public $loanRequestId, $req_loan_scheme_id, $req_loan_amount, $time_period_months, $req_date;
    public $member_id, $organisation_id;
    
    // Property to hold the loan schemas for the select dropdown
    public $loanSchemas = [];
    
    // Properties for modal and state management
    public $isOpen = 0;
    public $search = '';
    public $sortField = 'created_at';
    public $sortAsc = false;
    public $confirmingDeletion = false;
    public $loanRequestToDelete;

    // Validation rules
    protected $rules = [
        'req_loan_scheme_id' => 'required',
        'req_loan_amount' => 'required|numeric|min:0',
        'time_period_months' => 'required|numeric|min:1',
    ];
    
    // Mount method to set initial values and fetch data
    public function mount()
    {
        // For demonstration, you might want to get member_id and organisation_id from auth or another source
        $this->member_id = 1; // Example
        $this->organisation_id = 1; // Example
        $this->loanSchemas = LoanSchema::all();
    }

    // Render the view
    public function render()
    {
        $loanRequests = LoanRequest::where('member_id', $this->member_id)
            ->where(function($query) {
                $query->where('req_loan_amount', 'like', '%' . $this->search . '%')
                      ->orWhere('status', 'like', '%' . $this->search . '%');
            })
            ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
            ->paginate(10);
            
        return view('livewire.ec08-member-loan-request-comp', [
            'loanRequests' => $loanRequests,
        ]);
    }

    // Reset all form fields
    private function resetInputFields(){
        $this->loanRequestId = null;
        $this->req_loan_scheme_id = null;
        $this->req_loan_amount = null;
        $this->time_period_months = null;
        $this->req_date = null;
    }

    // Open the creation/edit modal
    public function create(){
        $this->resetInputFields();
        $this->isOpen = true;
    }

    // Store or update a loan request
    public function store()
    {
        $this->validate();

        LoanRequest::updateOrCreate(['id' => $this->loanRequestId], [
            'organisation_id' => $this->organisation_id,
            'member_id' => $this->member_id,
            'req_loan_scheme_id' => $this->req_loan_scheme_id,
            'req_loan_amount' => $this->req_loan_amount,
            'time_period_months' => $this->time_period_months,
            'req_date' => now(),
        ]);
  
        session()->flash('message', 
            $this->loanRequestId ? 'Loan Request Updated Successfully.' : 'Loan Request Created Successfully.');
  
        $this->closeModal();
        $this->resetInputFields();
    }

    // Edit an existing loan request
    public function edit($id)
    {
        $loanRequest = LoanRequest::findOrFail($id);
        $this->loanRequestId = $id;
        $this->req_loan_scheme_id = $loanRequest->req_loan_scheme_id;
        $this->req_loan_amount = $loanRequest->req_loan_amount;
        $this->time_period_months = $loanRequest->time_period_months;
        $this->req_date = $loanRequest->req_date;
        
        $this->isOpen = true;
    }

    // Open the deletion confirmation modal
    public function delete($id)
    {
        $this->confirmingDeletion = true;
        $this->loanRequestToDelete = LoanRequest::findOrFail($id);
    }
    
    // Confirm and delete the loan request
    public function destroy()
    {
        if ($this->loanRequestToDelete) {
            $this->loanRequestToDelete->delete();
            session()->flash('message', 'Loan Request Deleted Successfully.');
        }
        
        $this->confirmingDeletion = false;
        $this->loanRequestToDelete = null;
    }

    // Close the modal and reset state
    public function closeModal()
    {
        $this->isOpen = false;
        $this->confirmingDeletion = false;
        $this->resetInputFields();
    }
    // public function render()
    // {
    //     return view('livewire.ec08-member-loan-request-comp');
    // }
}
