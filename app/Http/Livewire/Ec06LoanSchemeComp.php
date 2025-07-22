<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Ec06LoanSchemeComp extends Component{

    public $loanSchemes = null, $loanSchemeId;
    public $showLoanSchemeModal = false;
    public $name, $description;


    public function mount(){
        $this->loanSchemes = \App\Models\Ec06LoanScheme::all();
    }

    
    public function openModal($loanSchemeId = null){
        $this->loanSchemeId = $loanSchemeId;
        // Reset form fields
        $this->name = '';
        $this->description = '';
        
        if($loanSchemeId){
            // Find the loan scheme with proper error handling
            $loanScheme = \App\Models\Ec06LoanScheme::find($loanSchemeId);
            
            // Only set properties if the loan scheme exists
            if($loanScheme) {
                $this->name = $loanScheme->name ?? 'NA';
                $this->description = $loanScheme->description ?? 'NA';
            }
        }
    
        $this->showLoanSchemeModal = true;
    }

    public function closeModal(){
        $this->showLoanSchemeModal = false;
    }

    public function saveLoanScheme(){
        
        $this->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255'
        ]);
        
        
        try{
            
            
            $data = \App\Models\Ec06LoanScheme::updateOrCreate([
                'id' => $this->loanSchemeId,
            ],[
                'name' => $this->name,
                'description' => $this->description,
            ]);
            
            
            session()->flash('success', 'Loan Scheme saved successfully');
            
        }catch(\Exception $e){
            session()->flash('error', 'Failed to save loan scheme: ' . $e->getMessage());
            
        }
        
        
        $this->closeModal();
        $this->mount();
    }



    public function render()
    {
        return view('livewire.ec06-loan-scheme-comp');
    }
}
