<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Ec15ThfundDashboardComp extends Component
{
    public $activeTab = 'transactions';
    
    protected $listeners = ['tabChanged' => 'setActiveTab'];
    
    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
    }
    
    public function switchTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function render()
    {
        return view('livewire.ec15-thfund-dashboard-comp');
    }
}
