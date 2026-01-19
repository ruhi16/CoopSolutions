<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Ec21BankLoanScheme;
use App\Models\Ec20Bank;
use App\Models\Ec01Organisation;
use App\Models\Ec02FinancialYear;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class Ec06LoanSchemeComp extends Component{

    public $loanSchemes = null, $loanSchemeId;
    public $showLoanSchemeModal = false;
    
    // Form fields
    public $name, $description, $bank_id, $effected_on, $status;
    public $organisation_id, $financial_year_id, $is_finalized, $remarks;
    public $is_emi_enabled = false;
    
    // Dropdown data
    public $banks = [], $organisations = [], $financialYears = [];
    
    // Search and pagination
    public $search = '';
    public $perPage = 10;
    
    // Loan schemes data (protected to avoid Livewire serialization issues)
    protected $loanSchemesPaginator;
    public $loanSchemesData = [];


    public function mount(){
        $this->loadDropdowns();
        $this->loadLoanSchemes();
        
        // Ensure paginator is initialized
        if (!isset($this->loanSchemesPaginator)) {
            $this->loadLoanSchemes();
        }
    }

    
    public function loadDropdowns()
    {
        $this->banks = Ec20Bank::where('is_active', true)->get();
        $this->organisations = Ec01Organisation::where('is_active', true)->get();
        $this->financialYears = Ec02FinancialYear::where('is_active', true)->get();
    }
    
    public function loadLoanSchemes()
    {
        $query = Ec21BankLoanScheme::with(['bank', 'organisation', 'financialYear']);
        
        if ($this->search) {
            $query->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%');
            });
        }
        
        $this->loanSchemesPaginator = $query->orderBy('id', 'desc')->paginate($this->perPage);
        $this->loanSchemesData = $this->loanSchemesPaginator->items();
    }
    
    public function openModal($loanSchemeId = null){
        $this->loanSchemeId = $loanSchemeId;
        // Reset form fields
        $this->resetForm();
        
        if($loanSchemeId){
            // Find the loan scheme with proper error handling
            $loanScheme = Ec21BankLoanScheme::find($loanSchemeId);
            
            // Only set properties if the loan scheme exists
            if($loanScheme) {
                $this->name = $loanScheme->name;
                $this->description = $loanScheme->description;
                $this->bank_id = $loanScheme->bank_id;
                $this->effected_on = $loanScheme->effected_on;
                $this->status = $loanScheme->status;
                $this->organisation_id = $loanScheme->organisation_id;
                $this->financial_year_id = $loanScheme->financial_year_id;
                $this->is_finalized = $loanScheme->is_finalized;
                $this->remarks = $loanScheme->remarks;
                $this->is_emi_enabled = $loanScheme->is_emi_enabled ?? false;
            }
        } else {
            // Set default values for new loan scheme
            $this->organisation_id = session('organisation_id') ?? 1;
            $this->financial_year_id = session('financial_year_id') ?? 1;
            $this->status = 'suspended';
            $this->is_finalized = false;
        }
    
        $this->showLoanSchemeModal = true;
    }

    public function resetForm()
    {
        $this->name = '';
        $this->description = '';
        $this->bank_id = null;
        $this->effected_on = null;
        $this->status = 'suspended';
        $this->organisation_id = null;
        $this->financial_year_id = null;
        $this->is_finalized = false;
        $this->remarks = '';
        $this->is_emi_enabled = false;
        $this->resetErrorBag();
    }

    public function closeModal(){
        $this->showLoanSchemeModal = false;
        $this->resetForm();
    }

    public function saveLoanScheme(){
        
        $this->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'bank_id' => 'nullable|exists:ec20_banks,id',
            'effected_on' => 'nullable|date',
            'status' => 'required|in:running,completed,upcoming,suspended,cancelled',
            'organisation_id' => 'required|exists:ec01_organisations,id',
            'financial_year_id' => 'required|exists:ec02_financial_years,id',
            'remarks' => 'nullable|string|max:500',
            'is_emi_enabled' => 'boolean'
        ]);
        
        
        try{
            
            $data = Ec21BankLoanScheme::updateOrCreate([
                'id' => $this->loanSchemeId,
            ],[
                'name' => $this->name,
                'description' => $this->description,
                'bank_id' => $this->bank_id,
                'effected_on' => $this->effected_on,
                'status' => $this->status,
                'organisation_id' => $this->organisation_id,
                'financial_year_id' => $this->financial_year_id,
                'is_finalized' => $this->is_finalized,
                'finalized_by' => $this->is_finalized ? Auth::id() : null,
                'finalized_at' => $this->is_finalized ? now() : null,
                'user_id' => Auth::id(),
                'remarks' => $this->remarks,
                'is_emi_enabled' => $this->is_emi_enabled,
                'is_active' => true
            ]);
            
            
            session()->flash('success', 'Bank Loan Scheme saved successfully');
            
        }catch(\Exception $e){
            session()->flash('error', 'Failed to save bank loan scheme: ' . $e->getMessage());
            
        }
        
        
        $this->closeModal();
        $this->loadLoanSchemes();
    }
    
    public function deleteLoanScheme($id)
    {
        try {
            $loanScheme = Ec21BankLoanScheme::findOrFail($id);
            $loanScheme->update(['is_active' => false]);
            session()->flash('success', 'Bank Loan Scheme deactivated successfully');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to deactivate bank loan scheme: ' . $e->getMessage());
        }
        
        $this->loadLoanSchemes();
    }
    
    public function updatedSearch()
    {
        $this->loadLoanSchemes();
    }



    public function render()
    {
        return view('livewire.ec06-loan-scheme-comp', [
            'loanSchemesPaginator' => $this->loanSchemesPaginator
        ]);
    }
}
