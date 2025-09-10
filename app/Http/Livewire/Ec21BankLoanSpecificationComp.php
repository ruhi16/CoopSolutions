<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Ec21BankLoanScheme;
use App\Models\Ec21BankLoanSchemaParticular;
use App\Models\Ec21BankLoanSchemeSpecification;

class Ec21BankLoanSpecificationComp extends Component
{
    use WithPagination;

    public $schemes;
    public $particulars;
    public $specifications = [];
    
    public $selectedSchemeId;
    public $selectedParticulars = [];
    public $search = '';
    public $isEditing = false;
    public $editingId = null;
    
    protected $rules = [
        'selectedSchemeId' => 'required|exists:ec21_bank_loan_schemes,id',
        'selectedParticulars.*.particular_id' => 'required|exists:ec21_bank_loan_schema_particulars,id',
        'selectedParticulars.*.value' => 'required|numeric|min:0',
        'selectedParticulars.*.is_percent_on_current_balance' => 'boolean',
        'selectedParticulars.*.is_regular' => 'boolean',
        'selectedParticulars.*.effected_on' => 'required|date',
        'selectedParticulars.*.status' => 'required|in:running,completed,upcoming,suspended,cancelled',
    ];

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        $this->schemes = Ec21BankLoanScheme::where('is_active', true)
            ->where('status', '!=', 'cancelled')
            ->get();
            
        $this->particulars = Ec21BankLoanSchemaParticular::where('is_active', true)
            ->where('status', 'published')
            ->get();
            
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->selectedSchemeId = null;
        $this->selectedParticulars = [];
        $this->isEditing = false;
        $this->editingId = null;
    }

    public function addParticularRow()
    {
        $this->selectedParticulars[] = [
            'particular_id' => null,
            'value' => 0,
            'is_percent_on_current_balance' => true,
            'is_regular' => true,
            'effected_on' => now()->format('Y-m-d'),
            'status' => 'suspended'
        ];
    }

    public function removeParticularRow($index)
    {
        unset($this->selectedParticulars[$index]);
        $this->selectedParticulars = array_values($this->selectedParticulars);
    }

    public function edit($id)
    {
        $specification = Ec21BankLoanSchemeSpecification::findOrFail($id);
        
        $this->editingId = $id;
        $this->isEditing = true;
        $this->selectedSchemeId = $specification->bank_loan_scheme_id;
        
        // Load all specifications for this scheme
        $this->selectedParticulars = Ec21BankLoanSchemeSpecification::where('bank_loan_scheme_id', $this->selectedSchemeId)
            ->get()
            ->map(function($item) {
                return [
                    'id' => $item->id,
                    'particular_id' => $item->bank_loan_schema_particular_id,
                    'value' => $item->bank_loan_schema_particular_value,
                    'is_percent_on_current_balance' => $item->is_percent_on_current_balance,
                    'is_regular' => $item->is_regular,
                    'effected_on' => $item->effected_on,
                    'status' => $item->status
                ];
            })->toArray();
    }

    public function save()
    {
        $this->validate();

        try {
            // Delete existing specifications for this scheme
            Ec21BankLoanSchemeSpecification::where('bank_loan_scheme_id', $this->selectedSchemeId)->delete();
            
            // Create new specifications
            foreach ($this->selectedParticulars as $particular) {
                Ec21BankLoanSchemeSpecification::create([
                    'bank_loan_scheme_id' => $this->selectedSchemeId,
                    'bank_loan_schema_particular_id' => $particular['particular_id'],
                    'bank_loan_schema_particular_value' => $particular['value'],
                    'is_percent_on_current_balance' => $particular['is_percent_on_current_balance'] ?? true,
                    'is_regular' => $particular['is_regular'] ?? true,
                    'effected_on' => $particular['effected_on'],
                    'status' => $particular['status'],
                    'user_id' => auth()->id(),
                    'organisation_id' => auth()->user()->organisation_id,
                    'financial_year_id' => auth()->user()->financial_year_id,
                ]);
            }
            
            session()->flash('message', 'Loan scheme specifications saved successfully.');
            $this->resetForm();
        } catch (\Exception $e) {
            session()->flash('error', 'Error saving specifications: ' . $e->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            Ec21BankLoanSchemeSpecification::where('id', $id)->delete();
            session()->flash('message', 'Specification deleted successfully.');
        } catch (\Exception $e) {
            session()->flash('error', 'Error deleting specification: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $query = Ec21BankLoanSchemeSpecification::with(['scheme', 'particular'])
            ->where('is_active', true);
            
        if ($this->search) {
            $query->whereHas('scheme', function($q) {
                $q->where('name', 'like', '%' . $this->search . '%');
            });
        }
        
        $specs = $query->paginate(10);
        
        return view('livewire.ec21-bank-loan-specification-comp', [
            'specs' => $specs
        ]);
    }

    // public function render()
    // {
    //     return view('livewire.ec21-bank-loan-specification-comp');
    // }
}
