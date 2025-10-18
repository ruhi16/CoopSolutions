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

    // New properties for the checkboxes
    public $isFeatured = false;
    public $isOpen = false;
    public $isCalculated = false;

    public $loanSchemeId = null;

    public $loanSchemeDetails = null, $selectedLoanSchemeDetailId = null;
    public $deleteConfirmId = null;

    protected $rules = [
        'selectedLoanSchemeId' => 'required',
        'selectedLoanSchemeFeatureId' => 'required',
        'selectedLoanSchemeFeatureValue' => 'required',
    ];

    public function mount(){
        $this->refresh();
    }

    public function refresh(){
        $this->loanSchemes = $this->loanSchemeId ?
            \App\Models\Ec06LoanScheme::find($this->loanSchemeId) :
            \App\Models\Ec06LoanScheme::all();

        $this->loanSchemeFeatures = \App\Models\Ec07LoanSchemeFeature::all();
        $this->loanSchemeDetails = \App\Models\Ec07LoanSchemeDetail::all();
        
        // Reset form fields to avoid state issues
        $this->resetForm();
    }

    public function resetForm() {
        $this->selectedLoanSchemeId = null;
        $this->selectedLoanSchemeFeatureId = null;
        $this->selectedLoanSchemeFeature = null;
        $this->selectedLoanSchemeFeatureName = null;
        $this->selectedLoanSchemeFeatureType = null;
        $this->selectedLoanSchemeFeatureValue = null;
        $this->isFeatured = false;
        $this->isOpen = false;
        $this->isCalculated = false;
    }

    public function updatedSelectedLoanSchemeFeatureId($selectedLoanSchemeFeatureId){
        if ($selectedLoanSchemeFeatureId) {
            $this->selectedLoanSchemeFeature = \App\Models\Ec07LoanSchemeFeature::find($selectedLoanSchemeFeatureId);
            
            if ($this->selectedLoanSchemeFeature) {
                $this->selectedLoanSchemeFeatureName = $this->selectedLoanSchemeFeature->loan_scheme_feature_name;
                $this->selectedLoanSchemeFeatureType = $this->selectedLoanSchemeFeature->loan_scheme_feature_type;
            }
        } else {
            $this->selectedLoanSchemeFeature = null;
            $this->selectedLoanSchemeFeatureName = null;
            $this->selectedLoanSchemeFeatureType = null;
        }
    }

    public function updatedSelectedLoanSchemeId($value) {
        // Reset dependent fields when loan scheme changes
        $this->selectedLoanSchemeFeatureId = null;
        $this->selectedLoanSchemeFeature = null;
        $this->selectedLoanSchemeFeatureName = null;
        $this->selectedLoanSchemeFeatureType = null;
    }

    public function openModal($loanSchemeDetailId = null){
        if($loanSchemeDetailId == null){
            $this->selectedLoanSchemeDetailId = null;
            $this->resetForm();
        } else {
            $this->selectedLoanSchemeDetailId = $loanSchemeDetailId;
            $loanSchemeDetail = \App\Models\Ec07LoanSchemeDetail::find($loanSchemeDetailId);

            if ($loanSchemeDetail) {
                $this->selectedLoanSchemeId = $loanSchemeDetail->loan_scheme_id;
                $this->selectedLoanSchemeFeatureId = $loanSchemeDetail->loan_scheme_feature_id;
                $this->selectedLoanSchemeFeatureValue = $loanSchemeDetail->loan_scheme_feature_value;

                // Set checkbox values
                $this->isFeatured = (bool) $loanSchemeDetail->is_featured;
                $this->isOpen = (bool) $loanSchemeDetail->is_open;
                $this->isCalculated = (bool) $loanSchemeDetail->is_calculated;

                $this->selectedLoanSchemeFeature = \App\Models\Ec07LoanSchemeFeature::find($this->selectedLoanSchemeFeatureId);

                if ($this->selectedLoanSchemeFeature) {
                    $this->selectedLoanSchemeFeatureName = $this->selectedLoanSchemeFeature->loan_scheme_feature_name;
                    $this->selectedLoanSchemeFeatureType = $this->selectedLoanSchemeFeature->loan_scheme_feature_type;
                }
            }
        }

        $this->showLoanSchemeDetailModal = true;
    }

    public function closeModal(){
        $this->showLoanSchemeDetailModal = false;
        $this->resetForm();
    }

    public function editLoanSchemeDetail($loanSchemeDetailId){
        $this->openModal($loanSchemeDetailId);
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
                if ($data) {
                    $data->delete();
                }

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
        $this->validate();

        try{
            $data = \App\Models\Ec07LoanSchemeDetail::updateOrCreate(
                ['id' => $this->selectedLoanSchemeDetailId],
                [
                    'loan_scheme_id' => $this->selectedLoanSchemeId,
                    'loan_scheme_feature_id' => $this->selectedLoanSchemeFeatureId,
                    'loan_scheme_feature_value' => $this->selectedLoanSchemeFeatureValue,
                    'is_featured' => $this->isFeatured,
                    'is_open' => $this->isOpen,
                    'is_calculated' => $this->isCalculated,
                ]
            );

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