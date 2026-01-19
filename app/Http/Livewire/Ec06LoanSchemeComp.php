<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Ec06LoanScheme;

class Ec06LoanSchemeComp extends Component
{
    public $loanSchemes = null;
    public $isOpen = false;
    
    // Model properties
    public $loan_scheme_id = null;
    public $name = '';
    public $name_short = '';
    public $description = '';
    public $start_date = '';
    public $end_date = '';
    public $is_emi_enabled = false;
    public $status = 'suspended';
    public $is_active = true;
    public $remarks = '';

    public function mount()
    {
        $this->loanSchemes = Ec06LoanScheme::all();
    }

    // Open modal for creating a new loan scheme
    public function openModal()
    {
        $this->resetInputFields();
        $this->isOpen = true;
    }

    // Open modal for editing an existing loan scheme
    public function edit($id)
    {
        $loanScheme = Ec06LoanScheme::findOrFail($id);
        $this->loan_scheme_id = $id;
        $this->name = $loanScheme->name;
        $this->name_short = $loanScheme->name_short;
        $this->description = $loanScheme->description;
        $this->start_date = $loanScheme->start_date ? $loanScheme->start_date->format('Y-m-d') : '';
        $this->end_date = $loanScheme->end_date ? $loanScheme->end_date->format('Y-m-d') : '';
        $this->is_emi_enabled = $loanScheme->is_emi_enabled;
        $this->status = $loanScheme->status;
        $this->is_active = $loanScheme->is_active;
        $this->remarks = $loanScheme->remarks;
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
        $this->loan_scheme_id = null;
        $this->name = '';
        $this->name_short = '';
        $this->description = '';
        $this->start_date = '';
        $this->end_date = '';
        $this->is_emi_enabled = false;
        $this->status = 'suspended';
        $this->is_active = true;
        $this->remarks = '';
    }

    // Store or update loan scheme
    public function store()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'name_short' => 'required|string|max:50',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $loanSchemeData = [
            'name' => $this->name,
            'name_short' => $this->name_short,
            'description' => $this->description,
            'start_date' => $this->start_date ?: null,
            'end_date' => $this->end_date ?: null,
            'is_emi_enabled' => $this->is_emi_enabled,
            'status' => $this->status,
            'is_active' => $this->is_active,
            'remarks' => $this->remarks,
        ];

        Ec06LoanScheme::updateOrCreate(
            ['id' => $this->loan_scheme_id],
            $loanSchemeData
        );

        session()->flash(
            $this->loan_scheme_id ? 'success' : 'success',
            $this->loan_scheme_id ? 'Loan Scheme Updated Successfully.' : 'Loan Scheme Created Successfully.'
        );

        $this->closeModal();
        $this->loanSchemes = Ec06LoanScheme::all();
    }

    // Delete loan scheme
    public function delete($id)
    {
        Ec06LoanScheme::find($id)->delete();
        $this->loanSchemes = Ec06LoanScheme::all();
        session()->flash('success', 'Loan Scheme Deleted Successfully.');
    }

    public function getStatusClass($status)
    {
        $statusClasses = [
            'running' => 'bg-green-100 text-green-800',
            'completed' => 'bg-blue-100 text-blue-800',
            'upcoming' => 'bg-yellow-100 text-yellow-800',
            'suspended' => 'bg-red-100 text-red-800',
            'cancelled' => 'bg-gray-100 text-gray-800',
        ];
        
        return $statusClasses[$status] ?? 'bg-gray-100 text-gray-800';
    }

    public function render()
    {
        return view('livewire.ec06-loan-scheme-comp');
    }
}