<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Ec001MainLayout extends Component{

    public $activeMenu = 'dashboard';

    public $organisationMenus = [
        
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
        'memberstype' => [
            'label' => 'Members Type',
            'icon' => 'fas fa-cog',
            'name' => 'memberstype',
            'component' =>'ec05-member-type-comp'
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
        // 'loanscheme' => [
        //     'label' => 'Loan Scheme',
        //     'icon' => 'fas fa-cog',
        //     'name' => 'loanscheme',
        //     'component' =>'ec06-loan-scheme-comp'
        // ],
        // 'loanschemefeature' => [
        //     'label' => 'Loan Sc Feature',
        //     'icon' => 'fas fa-cog',
        //     'name' => 'loanschemefeature',
        //     'component' =>'ec07-loan-scheme-feature-comp'
        // ],
        // 'loanschemedetail' => [
        //     'label' => 'Loan Sc Details',
        //     'icon' => 'fas fa-cog',
        //     'name' => 'loanschemedetail',
        //     'component' =>'ec06-loan-scheme-comp'
        // ],
        // 'loanrequest' => [
        //     'label' => 'Loan Request',
        //     'icon' => 'fas fa-cog',
        //     'name' => 'loanrequest',
        //     'component' =>'ec08-loan-request-comp'
        // ],
        // 'loanassign' => [
        //     'label' => 'Loan Assign',
        //     'icon' => 'fas fa-cog',
        //     'name' => 'loanassign',
        //     'component' =>'ec08-loan-assign-comp'
        // ],
    ];

    public $workflowMenuItems = [
        'taskCategory' => [
            'label' => 'Task Category',
            'icon' => 'fas fa-tachometer-alt',
            'name' => 'taskCategory',
            'component' =>'wf01-task-category-comp'
        ],
        'task' => [
            'label' => 'Task',
            'icon' => 'fas fa-tachometer-alt',
            'name' => 'task',
            'component' =>'wf01-task-comp'
        ],
        'taskAssign' => [
            'label' => 'Task Assign',
            'icon' => 'fas fa-tachometer-alt',
            'name' => 'taskAssign',
            'component' =>'wf01-task-assign-comp'
        ],
        'taskHistory' => [
            'label' => 'Task History',
            'icon' => 'fas fa-tachometer-alt',
            'name' => 'taskHistory',
            'component' =>'wf01-task-history-comp'
        ],
        'taskReport' => [
            'label' => 'Task Report',
            'icon' => 'fas fa-tachometer-alt',
            'name' => 'taskReport',
            'component' =>'wf01-task-report-comp'
        ],
    ];
    // public $organisationMenus = [
    //     'main-dashboard' => [
    //         'label' => 'My Dashboard',
    //         'icon' => 'fas fa-tachometer-alt',
    //         'submenus' => [                        
    //             'dashboard' => [
    //                 'label' => 'Dashboard',
    //                 'icon' => 'fas fa-tachometer-alt',
    //                 'name' => 'dashboard',
    //                 'component' =>'ec01-organisation'
    //             ],
    //             'organisation' => [
    //                 'label' => 'Organisation',
    //                 'icon' => 'fas fa-cog',
    //                 'name' => 'organisation',
    //                 'component' =>'ec01-organisation'
    //             ],
    //             'finyear' => [
    //                 'label' => 'Financial Year',
    //                 'icon' => 'fas fa-cog',
    //                 'name' => 'finyear',
    //                 'component' =>'ec02-financial-year'
    //             ],
    //             'officials' => [
    //                 'label' => 'Officials',
    //                 'icon' => 'fas fa-cog',
    //                 'name' => 'officials',
    //                 'component' =>'ec03-officials'
    //             ],
    //             'loanscheme' => [
    //                 'label' => 'Loan Scheme',
    //                 'icon' => 'fas fa-cog',
    //                 'name' => 'loanscheme',
    //                 'component' =>'ec06-loan-scheme-comp'
    //             ],
    //             'loanschemefeature' => [
    //                 'label' => 'Loan Sc Feature',
    //                 'icon' => 'fas fa-cog',
    //                 'name' => 'loanschemefeature',
    //                 'component' =>'ec07-loan-scheme-feature-comp'
    //             ],
    //             'loanschemedetail' => [
    //                 'label' => 'Loan Sc Details',
    //                 'icon' => 'fas fa-cog',
    //                 'name' => 'loanschemedetail',
    //                 'component' =>'ec06-loan-scheme-comp'
    //             ],
    //             'loanrequest' => [
    //                 'label' => 'Loan Request',
    //                 'icon' => 'fas fa-cog',
    //                 'name' => 'loanrequest',
    //                 'component' =>'ec08-loan-request-comp'
    //             ],
    //             'loanassign' => [
    //                 'label' => 'Loan Assign',
    //                 'icon' => 'fas fa-cog',
    //                 'name' => 'loanassign',
    //                 'component' =>'ec08-loan-assign-comp'
    //             ],
    //         ],
    //     ],

    //     'second-dashboard' => [
    //         'label' => 'Second Dashboard',
    //         'icon' => 'fas fa-tachometer-alt',
    //         'submenus' => [
    //             'dashboard' => [
    //                 'label' => 'Dashboard',
    //                 'icon' => 'fas fa-tachometer-alt',
    //                 'name' => 'dashboard',
    //                 'component' =>'ec01-organisation'
    //             ],

    //         ],
    //     ],

    // ];

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
