<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Ec07LoanSchemeDetailComp extends Component
{


    public $showLoanSchemeDetailModal = false;
    public $showDeleteConfirmModal = false;
    public $loanSchemes = null, $loanSchemeDetail = null, $loanSchemeFeatures = null;

    public $selectedLoanSchemeId = null, $selectedLoanSchemeFeature = null, $selectedLoanSchemeFeatureId = null;

    public $selectedLoanSchemeFeatureName = null, $selectedLoanSchemeFeatureType = null, $selectedLoanSchemeFeatureValue = null;


    public $loanSchemeId = null;

    public $loanSchemeDetails = null, $selectedLoanSchemeDetailId = null;
    public $deleteConfirmId = null;



    public function mount(){
        $this->refresh();

        // $this->loanSchemeDetails = \App\Models\Ec07LoanSchemeDetail::all();

    }


    public function refresh(){
        $this->loanSchemes = $this->loanSchemeId ?
            \App\Models\Ec06LoanScheme::find($this->loanSchemeId) :
            \App\Models\Ec06LoanScheme::all();

            $this->loanSchemeFeatures = \App\Models\Ec07LoanSchemeFeature::all();
            $this->loanSchemeDetails = \App\Models\Ec07LoanSchemeDetail::all();
    }



    public function updatedSelectedLoanSchemeFeatureId($selectedLoanSchemeFeatureId){
        // dd($selectedLoanSchemeFeatureId);
        $this->selectedLoanSchemeFeature = \App\Models\Ec07LoanSchemeFeature::find($selectedLoanSchemeFeatureId);
        
        $this->selectedLoanSchemeFeatureName = $this->selectedLoanSchemeFeature->loan_scheme_feature_name;
        $this->selectedLoanSchemeFeatureType = $this->selectedLoanSchemeFeature->loan_scheme_feature_type;
        // dd($this->selectedLoanSchemeFeatureName, $this->selectedLoanSchemeFeatureType);
    }


    

    public function openModal($loanSchemeDetailId = null){

        if($loanSchemeDetailId == null){
            $this->selectedLoanSchemeDetailId = null;

            $this->selectedLoanSchemeId = null;
            $this->selectedLoanSchemeFeatureId = null;
            $this->selectedLoanSchemeFeatureType = null;
            $this->selectedLoanSchemeFeatureName = null;
            $this->selectedLoanSchemeFeatureValue = null;    
        }else{
            $this->selectedLoanSchemeDetailId = $loanSchemeDetailId;

            $loanSchemeDetail = \App\Models\Ec07LoanSchemeDetail::find($loanSchemeDetailId);

            $this->selectedLoanSchemeId = $loanSchemeDetail->loan_scheme_id;
            $this->selectedLoanSchemeFeatureId = $loanSchemeDetail->loan_scheme_feature_id;
            $this->selectedLoanSchemeFeatureValue = $loanSchemeDetail->loan_scheme_feature_value;

            $this->selectedLoanSchemeFeature = \App\Models\Ec07LoanSchemeFeature::find($this->selectedLoanSchemeFeatureId);

            $this->selectedLoanSchemeFeatureName = $this->selectedLoanSchemeFeature->loan_scheme_feature_name;
            $this->selectedLoanSchemeFeatureType = $this->selectedLoanSchemeFeature->loan_scheme_feature_type;



        }

        // $this->selectedLoanSchemeDetailId = $loanSchemeDetailId;

        // $this->selectedLoanSchemeId = $this->loanSchemeId;
        // $this->selectedLoanSchemeFeatureId = null;
        // $this->selectedLoanSchemeFeatureType = null;
        // $this->selectedLoanSchemeFeatureName = null;
        // $this->selectedLoanSchemeFeatureValue = null;

        // $this->refresh();

        $this->showLoanSchemeDetailModal = true;
    }

    public function closeModal(){
        $this->showLoanSchemeDetailModal = false;
    }




    public function editLoanSchemeDetail($loanSchemeDetailId){
        $this->openModal($loanSchemeDetailId);

        // $loanSchemeDetail = \App\Models\Ec07LoanSchemeDetail::find($loanSchemeDetailId);

        // $this->selectedLoanSchemeId = $loanSchemeDetail->loan_scheme_id;
        // $this->selectedLoanSchemeFeatureId = $loanSchemeDetail->loan_scheme_feature_id;
        // $this->selectedLoanSchemeFeatureValue = $loanSchemeDetail->loan_scheme_feature_value;

        // $this->selectedLoanSchemeFeature = \App\Models\Ec07LoanSchemeFeature::find($this->selectedLoanSchemeFeatureId);

        // $this->selectedLoanSchemeFeatureName = $this->selectedLoanSchemeFeature->loan_scheme_feature_name;
        // $this->selectedLoanSchemeFeatureType = $this->selectedLoanSchemeFeature->loan_scheme_feature_type;

    }

    public function confirmDelete($loanSchemeDetailId){
        $this->deleteConfirmId = $loanSchemeDetailId;
        $this->showDeleteConfirmModal = true;
    }

    public function cancelDelete(){
        $this->deleteConfirmId = null;
        $this->showDeleteConfirmModal = false;
    }

    public function deleteLoanSchemeDetail(){
        if($this->deleteConfirmId){
            try{
                $data = \App\Models\Ec07LoanSchemeDetail::find($this->deleteConfirmId);
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

    public function saveLoanSchemeDetail(){

        $this->validate([
            'selectedLoanSchemeId' => 'required',
            'selectedLoanSchemeFeatureId' => 'required',
            'selectedLoanSchemeFeatureValue' => 'required',
        ]);

        try{

            $data = \App\Models\Ec07LoanSchemeDetail::updateOrCreate([
                'id' => $this->selectedLoanSchemeDetailId,
                'loan_scheme_id' => $this->selectedLoanSchemeId,
            ], [
                'loan_scheme_feature_id' => $this->selectedLoanSchemeFeatureId,
                'loan_scheme_feature_value' => $this->selectedLoanSchemeFeatureValue,
            ]);


            $this->closeModal();
            $this->refresh();
            
            session()->flash('success', 'Loan Scheme Detail Saved Successfully');
        }catch(\Exception $e){
            session()->flash('error', $e->getMessage());
        }



    }


    public function render()
    {
        return view('livewire.ec07-loan-scheme-detail-comp');
    }
}
