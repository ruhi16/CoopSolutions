<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Ec21BankLoanScheme;
use App\Models\Ec21BankLoanSchemeSpecification;
use App\Models\Ec21BankLoanSchemaParticular;
use Illuminate\Support\Facades\Auth;

class Ec21BankLoanSpecification extends Component{

    use WithPagination;

    public $activeTab = 'schemes'; // 'schemes' or 'particulars'
    
    // Properties for schemes
    public $scheme_id;
    public $name;
    public $description;
    public $bank_id;
    public $effected_on;
    public $status = 'suspended';
    public $is_finalized = true;
    public $remarks;
    
    // Properties for specifications
    public $specifications = [];
    public $allParticulars = [];
    
    // Properties for particulars
    public $particular_id;
    public $particular_name;
    public $particular_description;
    public $particular_is_optional = false;
    public $particular_status = 'suspended';
    
    // UI control properties
    public $showSchemeModal = false;
    public $showParticularModal = false;
    public $editMode = false;
    
    protected $rules = [
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'bank_id' => 'nullable|integer',
        'effected_on' => 'nullable|date',
        'status' => 'required|in:running,completed,upcoming,suspended,cancelled',
        'remarks' => 'nullable|string',
    ];
    
    protected $particularRules = [
        'particular_name' => 'required|string|max:255',
        'particular_description' => 'nullable|string',
        'particular_is_optional' => 'boolean',
        'particular_status' => 'required|in:draft,published,archived',
    ];

    public function mount()
    {
        $this->allParticulars = Ec21BankLoanSchemaParticular::where('is_active', true)->get();
        
        // Initialize at least one specification row
        $this->specifications[] = [
            'bank_loan_schema_particular_id' => null,
            'bank_loan_schema_particular_value' => null,
            'is_percent_on_current_balance' => true,
            'is_regular' => true,
            'effected_on' => null,
            'status' => 'suspended'
        ];
    }

    public function render()
    {
        $schemes = Ec21BankLoanScheme::with('specifications.particular')
            ->orderBy('created_at', 'desc')
            ->paginate(10, ['*'], 'schemesPage');
            
        $particulars = Ec21BankLoanSchemaParticular::orderBy('created_at', 'desc')
            ->paginate(10, ['*'], 'particularsPage');

        return view('livewire.ec21-bank-loan-specification', compact('schemes', 'particulars'));
    }

    public function changeTab($tab)
    {
        $this->activeTab = $tab;
        $this->resetPage();
    }

    // Scheme CRUD methods
    public function showSchemeModal()
    {
        $this->resetSchemeForm();
        $this->showSchemeModal = true;
        $this->editMode = false;
    }

    public function editScheme($id)
    {
        $scheme = Ec21BankLoanScheme::with('specifications')->findOrFail($id);
        
        $this->scheme_id = $scheme->id;
        $this->name = $scheme->name;
        $this->description = $scheme->description;
        $this->bank_id = $scheme->bank_id;
        $this->effected_on = $scheme->effected_on;
        $this->status = $scheme->status;
        $this->is_finalized = $scheme->is_finalized;
        $this->remarks = $scheme->remarks;
        
        // Load specifications
        $this->specifications = [];
        foreach ($scheme->specifications as $spec) {
            $this->specifications[] = [
                'id' => $spec->id,
                'bank_loan_schema_particular_id' => $spec->bank_loan_schema_particular_id,
                'bank_loan_schema_particular_value' => $spec->bank_loan_schema_particular_value,
                'is_percent_on_current_balance' => $spec->is_percent_on_current_balance,
                'is_regular' => $spec->is_regular,
                'effected_on' => $spec->effected_on,
                'status' => $spec->status
            ];
        }
        
        $this->showSchemeModal = true;
        $this->editMode = true;
    }

    public function saveScheme()
    {
        $this->validate();
        
        $data = [
            'name' => $this->name,
            'description' => $this->description,
            'bank_id' => $this->bank_id,
            'effected_on' => $this->effected_on,
            'status' => $this->status,
            'is_finalized' => $this->is_finalized,
            'remarks' => $this->remarks,
            'user_id' => Auth::id(),
            'organisation_id' => Auth::user()->organisation_id ?? null,
        ];
        
        if ($this->editMode) {
            $scheme = Ec21BankLoanScheme::find($this->scheme_id);
            $scheme->update($data);
            
            // Update specifications
            $this->saveSpecifications($scheme->id);
            
            session()->flash('message', 'Scheme updated successfully.');
        } else {
            $scheme = Ec21BankLoanScheme::create($data);
            
            // Save specifications
            $this->saveSpecifications($scheme->id);
            
            session()->flash('message', 'Scheme created successfully.');
        }
        
        $this->showSchemeModal = false;
        $this->resetSchemeForm();
    }

    private function saveSpecifications($schemeId)
    {
        // First, delete existing specifications if in edit mode
        if ($this->editMode) {
            Ec21BankLoanSchemeSpecification::where('bank_loan_scheme_id', $schemeId)->delete();
        }
        
        // Save new specifications
        foreach ($this->specifications as $spec) {
            if (!empty($spec['bank_loan_schema_particular_id']) && !empty($spec['bank_loan_schema_particular_value'])) {
                Ec21BankLoanSchemeSpecification::create([
                    'name' => 'Specification for scheme ' . $schemeId,
                    'description' => 'Auto-generated specification',
                    'bank_loan_scheme_id' => $schemeId,
                    'bank_loan_schema_particular_id' => $spec['bank_loan_schema_particular_id'],
                    'bank_loan_schema_particular_value' => $spec['bank_loan_schema_particular_value'],
                    'is_percent_on_current_balance' => $spec['is_percent_on_current_balance'] ?? true,
                    'is_regular' => $spec['is_regular'] ?? true,
                    'effected_on' => $spec['effected_on'],
                    'status' => $spec['status'] ?? 'suspended',
                    'user_id' => Auth::id(),
                    'organisation_id' => Auth::user()->organisation_id ?? null,
                ]);
            }
        }
    }

    public function deleteScheme($id)
    {
        Ec21BankLoanScheme::find($id)->delete();
        Ec21BankLoanSchemeSpecification::where('bank_loan_scheme_id', $id)->delete();
        
        session()->flash('message', 'Scheme deleted successfully.');
    }

    public function addSpecificationRow()
    {
        $this->specifications[] = [
            'bank_loan_schema_particular_id' => null,
            'bank_loan_schema_particular_value' => null,
            'is_percent_on_current_balance' => true,
            'is_regular' => true,
            'effected_on' => null,
            'status' => 'suspended'
        ];
    }

    public function removeSpecificationRow($index)
    {
        unset($this->specifications[$index]);
        $this->specifications = array_values($this->specifications);
    }

    public function resetSchemeForm()
    {
        $this->reset([
            'scheme_id', 'name', 'description', 'bank_id', 
            'effected_on', 'status', 'is_finalized', 'remarks'
        ]);
        
        $this->specifications = [[
            'bank_loan_schema_particular_id' => null,
            'bank_loan_schema_particular_value' => null,
            'is_percent_on_current_balance' => true,
            'is_regular' => true,
            'effected_on' => null,
            'status' => 'suspended'
        ]];
    }

    // Particular CRUD methods
    public function showParticularModal()
    {
        $this->resetParticularForm();
        $this->showParticularModal = true;
        $this->editMode = false;
    }

    public function editParticular($id)
    {
        $particular = Ec21BankLoanSchemaParticular::findOrFail($id);
        
        $this->particular_id = $particular->id;
        $this->particular_name = $particular->name;
        $this->particular_description = $particular->description;
        $this->particular_is_optional = $particular->is_optional;
        $this->particular_status = $particular->status;
        
        $this->showParticularModal = true;
        $this->editMode = true;
    }

    public function saveParticular()
    {
        $this->validate($this->particularRules);
        
        $data = [
            'name' => $this->particular_name,
            'description' => $this->particular_description,
            'is_optional' => $this->particular_is_optional,
            'status' => $this->particular_status,
            'user_id' => Auth::id(),
            'organisation_id' => Auth::user()->organisation_id ?? null,
        ];
        
        if ($this->editMode) {
            Ec21BankLoanSchemaParticular::find($this->particular_id)->update($data);
            session()->flash('message', 'Particular updated successfully.');
        } else {
            Ec21BankLoanSchemaParticular::create($data);
            session()->flash('message', 'Particular created successfully.');
        }
        
        $this->showParticularModal = false;
        $this->resetParticularForm();
        $this->allParticulars = Ec21BankLoanSchemaParticular::where('is_active', true)->get();
    }

    public function deleteParticular($id)
    {
        Ec21BankLoanSchemaParticular::find($id)->delete();
        session()->flash('message', 'Particular deleted successfully.');
        $this->allParticulars = Ec21BankLoanSchemaParticular::where('is_active', true)->get();
    }

    public function resetParticularForm()
    {
        $this->reset([
            'particular_id', 'particular_name', 'particular_description',
            'particular_is_optional', 'particular_status'
        ]);
    }






    // public function render(){
    //     return view('livewire.ec21-bank-loan-specification');
    // }
}
