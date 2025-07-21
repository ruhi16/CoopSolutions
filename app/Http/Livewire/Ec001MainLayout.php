<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Ec001MainLayout extends Component{

    public $activeMenu = 'dashboard';

    public $organisationMenus = [
        'dashboard' => [
            'label' => 'Dashboard',
            'icon' => 'fas fa-tachometer-alt',
            'name' => 'dashboard'
        ],
        'organisation' => [
            'label' => 'Organisation',
            'icon' => 'fas fa-users',
            'name' => 'organisation',
            'component' =>'ec01-organisation'
        ],
        'officials' => [
            'label' => 'Officials',
            'icon' => 'fas fa-cog',
            'name' => 'officials',
            'component' =>'ec02-financial-year'
        ],
        'loanscheme' => [
            'label' => 'Loan Scheme',
            'icon' => 'fas fa-cog',
            'name' => 'loanscheme',
            'component' =>'ec06-loan-scheme-comp'
        ],
    ];

    public function setActiveMenu($menu)
    {
        $this->activeMenu = $menu;
        $this->dispatchBrowserEvent('menu-state-updated', ['activeMenu' => $menu]);
    }


    public function render()
    {
        return view('livewire.ec001-main-layout');
    }
}
