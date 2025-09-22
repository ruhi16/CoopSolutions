<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Ec001MainLayout extends Component{

    public $activeMenu = 'dashboard';
    
    public $testMenus = [
        'orgSetup' => [
            'label' => 'Organization Setup',
            'icon' => 'fas fa-building',
            'role' => 'admin,manager',
            'submenus' => [
                'dashboard' => [
                    'label' => 'Dashboard',
                    'icon' => 'fas fa-tachometer-alt',
                    'name' => 'dashboard',
                    'component' => 'dashboard'
                ],
                'organisation' => [
                    'label' => 'Organization Info',
                    'icon' => 'fas fa-building',
                    'name' => 'organisation',
                    'component' => 'ec01-organisation'
                ],
                'finyear' => [
                    'label' => 'Financial Years',
                    'icon' => 'fas fa-calendar-alt',
                    'name' => 'finyear',
                    'component' => 'ec02-financial-year'
                ],
                'memberstype' => [
                    'label' => 'Member Types',
                    'icon' => 'fas fa-users-cog',
                    'name' => 'memberstype',
                    'component' => 'ec05-member-type-comp'
                ],
                'officials' => [
                    'label' => 'Organization Officials',
                    'icon' => 'fas fa-user-tie',
                    'name' => 'officials',
                    'component' => 'ec03-officials-comp'
                ],
            ],
        ],

        'memberMgmt' => [
            'label' => 'Member Management',
            'icon' => 'fas fa-users',
            'role' => 'admin,manager,officer',
            'submenus' => [
                'members' => [
                    'label' => 'Member Registration',
                    'icon' => 'fas fa-user-plus',
                    'name' => 'members',
                    'component' => 'ec04-member-comp'
                ],
                'memberImport' => [
                    'label' => 'Bulk Import',
                    'icon' => 'fas fa-file-import',
                    'name' => 'memberImport',
                    'component' => 'ec04-member-import-comp'
                ],
                'memberProfile' => [
                    'label' => 'Member Profiles',
                    'icon' => 'fas fa-id-card',
                    'name' => 'memberProfile',
                    'component' => 'ec04-member-profile-comp'
                ],
            ],
        ],

        'loanMgmt' => [
            'label' => 'Loan Management',
            'icon' => 'fas fa-hand-holding-usd',
            'role' => 'admin,manager,officer',
            'submenus' => [
                'loanscheme' => [
                    'label' => 'Loan Schemes',
                    'icon' => 'fas fa-list-alt',
                    'name' => 'loanscheme',
                    'component' => 'ec06-loan-scheme-comp'
                ],
                'loanschemedetail' => [
                    'label' => 'Scheme Details',
                    'icon' => 'fas fa-info-circle',
                    'name' => 'loanschemedetail',
                    'component' => 'ec07-loan-scheme-detail-comp'
                ],
                'loanrequest' => [
                    'label' => 'Loan Applications',
                    'icon' => 'fas fa-file-alt',
                    'name' => 'loanrequest',
                    'component' => 'ec08-loan-request-comp'
                ],
                'loanassign' => [
                    'label' => 'Loan Assignment',
                    'icon' => 'fas fa-handshake',
                    'name' => 'loanassign',
                    'component' => 'ec08-loan-assign-comp'
                ],
            ],
        ],

        'fundMgmt' => [
            'label' => 'Fund Management',
            'icon' => 'fas fa-piggy-bank',
            'role' => 'admin,manager,officer',
            'submenus' => [
                'thriftFund' => [
                    'label' => 'Thrift Fund',
                    'icon' => 'fas fa-coins',
                    'name' => 'thriftFund',
                    'component' => 'ec15-thfund-comp'
                ],
                'shareFundMember' => [
                    'label' => 'Member Share Fund',
                    'icon' => 'fas fa-share-alt',
                    'name' => 'shareFundMember',
                    'component' => 'ec16-shfund-member-comp'
                ],
                'shareFundBank' => [
                    'label' => 'Bank Share Fund',
                    'icon' => 'fas fa-university',
                    'name' => 'shareFundBank',
                    'component' => 'ec17-shfund-bank-comp'
                ],
            ],
        ],

        'bankingOps' => [
            'label' => 'Banking Operations',
            'icon' => 'fas fa-university',
            'role' => 'admin,manager',
            'submenus' => [
                'bankDetails' => [
                    'label' => 'Bank Management',
                    'icon' => 'fas fa-building-columns',
                    'name' => 'bankDetails',
                    'component' => 'ec20-bank-detail-comp'
                ],
                'bankLoanSchema' => [
                    'label' => 'Bank Loan Schemes',
                    'icon' => 'fas fa-file-contract',
                    'name' => 'bankLoanSchema',
                    'component' => 'ec21-bank-loan-specification'
                ],
                'bankLoanBorrowed' => [
                    'label' => 'Bank Borrowings',
                    'icon' => 'fas fa-money-bill-wave',
                    'name' => 'bankLoanBorrowed',
                    'component' => 'ec21-bank-loan-borrowed-comp'
                ],
            ],
        ],

        'workflow' => [
            'label' => 'Workflow Management',
            'icon' => 'fas fa-tasks',
            'role' => 'admin,manager',
            'submenus' => [
                'taskCategory' => [
                    'label' => 'Task Categories',
                    'icon' => 'fas fa-folder',
                    'name' => 'taskCategory',
                    'component' => 'wf01-task-category-comp'
                ],
                'taskParticular' => [
                    'label' => 'Task Particulars',
                    'icon' => 'fas fa-list',
                    'name' => 'taskParticular',
                    'component' => 'wf02-task-event-particular-comp'
                ],
                'taskParticularStatus' => [
                    'label' => 'Task Status',
                    'icon' => 'fas fa-check-circle',
                    'name' => 'taskParticularStatus',
                    'component' => 'wf02-task-event-particular-status-comp'
                ],
                'taskEvent' => [
                    'label' => 'Task Events',
                    'icon' => 'fas fa-calendar-check',
                    'name' => 'taskEvent',
                    'component' => 'wf03-task-event-comp'
                ],
                'taskSchedule' => [
                    'label' => 'Task Scheduling',
                    'icon' => 'fas fa-clock',
                    'name' => 'taskSchedule',
                    'component' => 'wf08-task-event-schedule-comp'
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
