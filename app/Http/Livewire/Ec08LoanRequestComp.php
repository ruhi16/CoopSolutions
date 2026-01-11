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

            \App\Models\Ec08LoanRequest::updateOrCreate([
                'id' => $this->selectedLoanRequestId,
            ],[
                'member_id' => $this->selectedMemberId,
                'organisation_id' => 1,
                'req_loan_scheme_id' => $this->selectedLoanSchemeId,
                'req_loan_amount' => $this->expectedAmount,
                'time_period_months' => (int) $this->selectedTimePeriod * 12,
                'req_date' => now(), // $this->loanDate,
                'status' => 'pending',
            ]);


            $this->closeModal();
            session()->flash('success', 'Loan Request Saved or Updated Successfully');
        }catch(\Exception $e){
            session()->flash('error', $e->getMessage());
        }
        // $this->dispatchBrowserEvent('alert', ['type' => 'success', 'message' => 'Loan Request Saved Successfully']);
    }


    public function render()
    {
        return view('livewire.ec08-loan-request-comp');
    }
}
