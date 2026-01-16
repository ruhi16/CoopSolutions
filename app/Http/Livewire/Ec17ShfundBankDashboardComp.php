<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Ec17ShfundBankDashboardComp extends Component
{
    public $activeTab = 'transactions';
    
    public function switchTab($tab)
    {
        $this->activeTab = $tab;
    }
    
    public function render()
    {
        return view('livewire.ec17-shfund-bank-dashboard-comp');
    }
}
