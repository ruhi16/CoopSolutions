<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Ec06LoanSchemeComp extends Component{

    public $showLoanSchemeModal = false;
    public $name, $description;

    
    public function openModal(){
        $this->showLoanSchemeModal = true;
    }

    public function closeModal(){
        $this->showLoanSchemeModal = false;
    }

    public function saveLoanScheme($loanSchemeId = null){
        $this->closeModal();
        try{
            
            $this->validate([
                'name' => 'required|string|max:255',
                'description' => 'required|string|max:255'
            ]);

            $data = \App\Models\Ec06LoanScheme::updateOrCreate([
                'id' => $loanSchemeId,
            ],[
                'name' => $this->name,
                'description' => $this->description,
            ]);


            session()->flash('success', 'Loan Scheme saved successfully');

        }catch(\Exception $e){
            session()->flash('error', 'Failed to save loan scheme: ' . $e->getMessage());

        }
    }



    public function render()
    {
        return view('livewire.ec06-loan-scheme-comp');
    }
}
