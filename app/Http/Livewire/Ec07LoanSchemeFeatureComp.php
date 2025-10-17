<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Ec07LoanSchemeFeature;

class Ec07LoanSchemeFeatureComp extends Component{

    public $loanSchemeFeatures = null;
    public $isOpen = false;
    
    // Model properties
    public $loan_scheme_feature_id = null;
    public $name = '';
    public $description = '';
    public $loan_scheme_feature_name = '';
    public $loan_scheme_feature_type = '';
    public $loan_scheme_feature_unit = '';
    public $is_required = true;
    public $is_active = true;
    public $remarks = '';

    public function mount(){
        $this->loanSchemeFeatures = Ec07LoanSchemeFeature::all();
    }

    // Open modal for creating a new loan scheme feature
    public function openModal()
    {
        $this->resetInputFields();
        $this->isOpen = true;
    }

    // Open modal for editing an existing loan scheme feature
    public function edit($id)
    {
        $loanSchemeFeature = Ec07LoanSchemeFeature::findOrFail($id);
        $this->loan_scheme_feature_id = $id;
        $this->name = $loanSchemeFeature->name;
        $this->description = $loanSchemeFeature->description;
        $this->loan_scheme_feature_name = $loanSchemeFeature->loan_scheme_feature_name;
        $this->loan_scheme_feature_type = $loanSchemeFeature->loan_scheme_feature_type;
        $this->loan_scheme_feature_unit = $loanSchemeFeature->loan_scheme_feature_unit;
        $this->is_required = $loanSchemeFeature->is_required;
        $this->is_active = $loanSchemeFeature->is_active;
        $this->remarks = $loanSchemeFeature->remarks;
        $this->isOpen = true;
    }

    // Close modal and reset input fields
    public function closeModal()
    {
        $this->isOpen = false;
        $this->resetInputFields();
    }

    // Reset input fields
    private function resetInputFields()
    {
        $this->loan_scheme_feature_id = null;
        $this->name = '';
        $this->description = '';
        $this->loan_scheme_feature_name = '';
        $this->loan_scheme_feature_type = '';
        $this->loan_scheme_feature_unit = '';
        $this->is_required = true;
        $this->is_active = true;
        $this->remarks = '';
    }

    // Store or update loan scheme feature
    public function store()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'loan_scheme_feature_name' => 'required|string|max:255',
            'loan_scheme_feature_type' => 'required|string|max:255',
        ]);

        Ec07LoanSchemeFeature::updateOrCreate(
            ['id' => $this->loan_scheme_feature_id],
            [
                'name' => $this->name,
                'description' => $this->description,
                'loan_scheme_feature_name' => $this->loan_scheme_feature_name,
                'loan_scheme_feature_type' => $this->loan_scheme_feature_type,
                'loan_scheme_feature_unit' => $this->loan_scheme_feature_unit,
                'is_required' => $this->is_required,
                'is_active' => $this->is_active,
                'remarks' => $this->remarks,
            ]
        );

        session()->flash(
            $this->loan_scheme_feature_id ? 'success' : 'success',
            $this->loan_scheme_feature_id ? 'Loan Scheme Feature Updated Successfully.' : 'Loan Scheme Feature Created Successfully.'
        );

        $this->closeModal();
        $this->loanSchemeFeatures = Ec07LoanSchemeFeature::all();
    }

    // Delete loan scheme feature
    public function delete($id)
    {
        Ec07LoanSchemeFeature::find($id)->delete();
        $this->loanSchemeFeatures = Ec07LoanSchemeFeature::all();
        session()->flash('success', 'Loan Scheme Feature Deleted Successfully.');
    }

    public function render(){
        return view('livewire.ec07-loan-scheme-feature-comp');
    }
}