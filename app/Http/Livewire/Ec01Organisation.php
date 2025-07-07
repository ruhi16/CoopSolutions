<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Ec01Organisation extends Component{
    

    public $organisations;

    public function mount(){
        $this->organisations = \App\Models\Ec01Organisation::all();
        // dd($this->organisations);
        
    }

    
    public function render(){
        return view('livewire.ec01-organisation');
    }
}
