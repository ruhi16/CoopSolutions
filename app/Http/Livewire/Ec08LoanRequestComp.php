<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Ec08LoanRequestComp extends Component
{

    public $showLoanRequestModal = false;

    public $members = null, $loanSchemes = null;

    public $selectedMemberId = null, $selectedLoanSchemeId = null, $loanAmount = null, $loanDate = null;
    
    public $selectedLoanScheme = null, $selectedTimePeriod = null, $expectedAmount = null;
    // public $selectedLoanSchemeFeatureType = null, $selectedLoanSchemeFeatureName = null, $selectedLoanSchemeFeatureValue = null;

    public $loanRequests = null;

    public function mount(){
        $this->members = \App\Models\Ec04Member::all();
        $this->loanSchemes = \App\Models\Ec06LoanScheme::all();
        $this->loanRequests = \App\Models\Ec08LoanRequest::all();
    }


    public function updatedSelectedLoanSchemeId($selectedLoanSchemeId){
        // dd($selectedLoanSchemeFeatureId);
        $this->selectedLoanScheme = \App\Models\Ec06LoanScheme::find($selectedLoanSchemeId);
        // dd($this->selectedLoanScheme->loanSchemeDetails );
        // $this->selectedLoanSchemeFeatureName = $this->selectedLoanScheme->loan_scheme_feature_name;
        // $this->selectedLoanSchemeFeatureType = $this->selectedLoanScheme->loan_scheme_feature_type;
        // dd($this->selectedLoanSchemeFeatureName, $this->selectedLoanSchemeFeatureType);
    }



    public function openModal(){
        $this->showLoanRequestModal = true;
    }

    public function closeModal(){
        $this->showLoanRequestModal = false;
    }



    public function saveLoanRequest(){
        $this->validate([
            'selectedMemberId' => 'required',
            'selectedLoanSchemeId' => 'required',
            'selectedTimePeriod' => 'required',
            'expectedAmount' => 'required',
            // 'loanDate' => 'required',
        ]);

        try{

            $loanRequest = new \App\Models\Ec08LoanRequest();
            $loanRequest->member_id = $this->selectedMemberId;
            $loanRequest->organisation_id = 1;
            $loanRequest->req_loan_scheme_id = $this->selectedLoanSchemeId;
            $loanRequest->req_loan_amount = $this->expectedAmount;
            $loanRequest->req_date = now();

            $loanRequest->save();


            $this->closeModal();
            session()->flash('success', 'Loan Request Saved Successfully');
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
