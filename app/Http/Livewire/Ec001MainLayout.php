<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Ec001MainLayout extends Component{

    public $activeMenu = 'dashboard';
    
    public $testMenus = [

        'mainMenu' =>[
            'label' => 'Main Menu',
            'icon' => 'fas fa-tachometer-alt',
            'submenus' => [
                'dashboard' => [
                    'label' => 'Dashboard',
                    'icon' => 'fas fa-tachometer-alt',
                    'name' => 'dashboard',
                    'component' =>'ec01-organisation'
                ],
                'organisation' => [
                    'label' => 'Organisation',
                    'icon' => 'fas fa-cog',
                    'name' => 'organisation',
                    'component' =>'ec01-organisation'
                ],
                'finyear' => [
                    'label' => 'Financial Year',
                    'icon' => 'fas fa-cog',
                    'name' => 'finyear',
                    'component' =>'ec02-financial-year'
                ],
                'bankLoanSchema' => [
                    'label' => 'Bank Schema',
                    'icon' => 'fas fa-cog',
                    'name' => 'bankLoanSchema',
                    'component' =>'ec21-bank-loan-specification'
                ],
                'loanRequest' => [
                    'label' => 'Loan Request',
                    'icon' => 'fas fa-cog',
                    'name' => 'loanRequest',
                    'component' =>'ec08-member-loan-request-comp'
                
                ],
            ],
        ],

        'secondMenu' => [
            'label' => 'Second Menu',
            'icon' => 'fas fa-cog',
            'submenus' => [                
                'taskCategory' => [
                    'label' => 'Task Category',
                    'icon' => 'fas fa-tachometer-alt',
                    'name' => 'taskCategory',
                    'component' =>'wf01-task-category-comp'
                ],
                'taskParticular' => [
                    'label' => 'Task Particular',
                    'icon' => 'fas fa-tachometer-alt',
                    'name' => 'taskParticular',
                    'component' =>'wf02-task-event-particular-comp'
                ],
                'taskParticularStatus' => [
                    'label' => 'Task Particular Status',
                    'icon' => 'fas fa-tachometer-alt',
                    'name' => 'taskParticularStatus',
                    'component' =>'wf01-task-event-particular-status-comp'
                ],
            ],
        ],

        'thirdMenu' => [
            'label' => 'Third Menu',
            'icon' => 'fas fa-cog',
            'submenus' => [
                'bankDetails' => [
                    'label' => 'Bank Detail',
                    'icon' => 'fas fa-cog',
                    'name' => 'bankDetails',
                    'component' =>'ec20-bank-detail-comp'
                ],
                'members' => [
                    'label' => 'Members',
                    'icon' => 'fas fa-cog',
                    'name' => 'members',
                    'component' =>'ec05-member-comp'
                ],
                'officials' => [
                    'label' => 'Officials',
                    'icon' => 'fas fa-cog',
                    'name' => 'officials',
                    'component' =>'ec03-officials'
                ],
            ],
        ],

    ];

    

    public function setActiveMenu($menu)
    {
        // dd($menu);
        $this->activeMenu = $menu;
        // $this->dispatchBrowserEvent('menu-state-updated', ['activeMenu' => $menu]);
    }


    public function render()
    {
        return view('livewire.ec001-main-layout');
    }
}
