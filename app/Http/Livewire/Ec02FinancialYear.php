<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class Ec02FinancialYear extends Component{

    // Modal state
    public $showModal = false;
    public $editMode = false;
    
    // Form fields
    public $financialYearId;
    public $organisation_id = '';
    public $name = '';
    public $description = '';
    public $start_date = '';
    public $end_date = '';
    public $status = 'suspended';
    public $is_active = false;
    public $remarks = '';
    
    // Table data
    public $financialYears;
    public $organisations = null;
    
    // Sorting
    public $sortField = 'id';
    public $sortDirection = 'asc';

    // Status options
    public $statusOptions = [
        'running' => 'Running',
        'completed' => 'Completed',
        'upcoming' => 'Upcoming',
        'suspended' => 'Suspended',
        'cancelled' => 'Cancelled'
    ];

    protected function rules()
    {
        return [
            'organisation_id' => 'nullable|integer',
            'name' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:255',
            'start_date' => 'nullable|string|max:255',
            'end_date' => 'nullable|string|max:255',
            'status' => 'required|in:running,completed,upcoming,suspended,cancelled',
            'is_active' => 'required|boolean',
            'remarks' => 'required|string|max:255',
        ];
    }

    protected $messages = [
        'status.required' => 'Status is required.',
        'status.in' => 'Invalid status selected.',
        'is_active.required' => 'Active status is required.',
        'remarks.required' => 'Remarks field is required.',
    ];

    public function mount()
    {
        $this->loadFinancialYears();
        $this->loadOrganisations();
    }

    public function loadFinancialYears()
    {
        $this->financialYears = \App\Models\Ec02FinancialYear::all();
        
            // DB::table('ec02_financial_years')
            // ->leftJoin('ec01_organisations', 'ec02_financial_years.organisation_id', '=', 'ec01_organisations.id')
            // ->select('ec02_financial_years.*', 'ec01_organisations.name as organisation_name')
            // ->orderBy('ec02_financial_years.' . $this->sortField, $this->sortDirection)
            // ->get();
    }

    public function loadOrganisations()
    {
        $this->organisations = \App\Models\Ec01Organisation::all();
        
        // DB::table('ec01_organisations')
        //     ->select('id', 'name')
        //     ->where('is_active', true)
        //     ->orderBy('name')
        //     ->get();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }
        
        $this->sortField = $field;
        $this->loadFinancialYears();
    }

    public function openModal()
    {
        $this->resetForm();
        $this->editMode = false;
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
        $this->resetValidation();
    }

    public function resetForm()
    {
        $this->financialYearId = null;
        $this->organisation_id = '';
        $this->name = '';
        $this->description = '';
        $this->start_date = '';
        $this->end_date = '';
        $this->status = 'suspended';
        $this->is_active = false;
        $this->remarks = '';
    }

    public function save()
    {
        $this->validate();

        try {
            $data = [
                'organisation_id' => $this->organisation_id ?: null,
                'name' => $this->name ?: null,
                'description' => $this->description ?: null,
                'start_date' => $this->start_date ?: null,
                'end_date' => $this->end_date ?: null,
                'status' => $this->status,
                'is_active' => $this->is_active,
                'remarks' => $this->remarks,
                'updated_at' => now(),
            ];

            if ($this->editMode) {
                DB::table('ec02_financial_years')
                    ->where('id', $this->financialYearId)
                    ->update($data);
                
                session()->flash('message', 'Financial Year updated successfully!');
            } else {
                $data['created_at'] = now();
                DB::table('ec02_financial_years')->insert($data);
                
                session()->flash('message', 'Financial Year created successfully!');
            }

            $this->closeModal();
            $this->loadFinancialYears();
            
        } catch (\Exception $e) {
            session()->flash('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $financialYear = DB::table('ec02_financial_years')->where('id', $id)->first();
        
        if ($financialYear) {
            $this->financialYearId = $financialYear->id;
            $this->organisation_id = $financialYear->organisation_id ?: '';
            $this->name = $financialYear->name ?: '';
            $this->description = $financialYear->description ?: '';
            $this->start_date = $financialYear->start_date ?: '';
            $this->end_date = $financialYear->end_date ?: '';
            $this->status = $financialYear->status;
            $this->is_active = (bool) $financialYear->is_active;
            $this->remarks = $financialYear->remarks ?: '';
            
            $this->editMode = true;
            $this->showModal = true;
        }
    }

    public function delete($id)
    {
        try {
            DB::table('ec02_financial_years')->where('id', $id)->delete();
            
            session()->flash('message', 'Financial Year deleted successfully!');
            $this->loadFinancialYears();
            
        } catch (\Exception $e) {
            session()->flash('error', 'An error occurred while deleting: ' . $e->getMessage());
        }
    }

    public function confirmDelete($id)
    {
        $this->dispatchBrowserEvent('confirm-delete', [
            'id' => $id,
            'message' => 'Are you sure you want to delete this financial year?'
        ]);
    }

    public function getStatusColor($status)
    {
        $colors = [
            'running' => 'bg-green-100 text-green-800',
            'completed' => 'bg-blue-100 text-blue-800',
            'upcoming' => 'bg-yellow-100 text-yellow-800',
            'suspended' => 'bg-gray-100 text-gray-800',
            'cancelled' => 'bg-red-100 text-red-800'
        ];

        return $colors[$status] ?? 'bg-gray-100 text-gray-800';
    }

    // public $financialYears;

    // public function mount(){
    //     $this->financialYears = \App\Models\Ec02FinancialYear::all();
    //     // dd($this->financialYears);
    // }


    public function render()
    {
        return view('livewire.ec02-financial-year');
    }
}
