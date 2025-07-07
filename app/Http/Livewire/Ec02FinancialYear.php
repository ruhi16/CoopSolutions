<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Ec02FinancialYear extends Component{

    public $financialYears;

    public function mount(){
        $this->financialYears = \App\Models\Ec02FinancialYear::all();
        // dd($this->financialYears);
    }


    public function render()
    {
        return view('livewire.ec02-financial-year');
    }
}
