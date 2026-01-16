<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Ec16ShfundMemberDashboardComp extends Component
{
    public $activeTab = 'transactions';
    
    public function switchTab($tab)
    {
        $this->activeTab = $tab;
    }
    
    public function render()
    {
        return view('livewire.ec16-shfund-member-dashboard-comp');
    }
}
