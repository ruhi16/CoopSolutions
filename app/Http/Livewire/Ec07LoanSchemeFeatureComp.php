<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Ec07LoanSchemeFeatureComp extends Component{

    public $loanSchemeFeatures = null;



    public function mount(){
        $loanSchemeFeatures = \App\Models\EC07LoanSchemeFeature::all();
        // dd($loanSchemeFeatures);
    }







    public function render(){
        return view('livewire.ec07-loan-scheme-feature-comp');
    }
}
