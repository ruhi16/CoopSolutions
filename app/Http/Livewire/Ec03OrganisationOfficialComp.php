<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Ec03OrganisationOfficial;
use App\Models\Ec01Organisation;
use App\Models\Ec04Member;
use Livewire\WithPagination;

class Ec03OrganisationOfficialComp extends Component
{
    use WithPagination;
    
    public $isOpen = false;
    public $search = '';
    public $sortField = 'id';
    public $sortDirection = 'asc';
    public $perPage = 10;
    
    // Form fields
    public $officialId;
    public $organisation_id;
    public $member_id;
    public $designation;
    public $description;
    public $is_active = true;
    public $remarks;
    
    protected $listeners = ['delete'];
    
    protected $rules = [
        'organisation_id' => 'required|exists:ec01_organisations,id',
        'member_id' => 'nullable|exists:ec04_members,id',
        'designation' => 'required|string|max:255',
        'description' => 'nullable|string',
        'is_active' => 'boolean',
        'remarks' => 'nullable|string',
    ];
    
    public function render()
    {
        return view('livewire.ec03-organisation-official-comp', [
            'officials' => $this->getOfficials(),
            'organisations' => Ec01Organisation::where('is_active', true)->get(),
            'members' => Ec04Member::where('is_active', true)->get(),
        ]);
    }
    
    public function getOfficials()
    {
        return Ec03OrganisationOfficial::query()
            ->when($this->search, function($query) {
                $query->where(function($q) {
                    $q->where('designation', 'like', '%' . $this->search . '%')
                      ->orWhere('description', 'like', '%' . $this->search . '%')
                      ->orWhereHas('organisation', function($q) {
                          $q->where('name', 'like', '%' . $this->search . '%');
                      })
                      ->orWhereHas('member', function($q) {
                          $q->where('name', 'like', '%' . $this->search . '%');
                      });
                });
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);
    }
    
    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }
        
        $this->sortField = $field;
    }
    
    public function openModal()
    {
        $this->resetInputFields();
        $this->isOpen = true;
    }
    
    public function closeModal()
    {
        $this->isOpen = false;
        $this->resetInputFields();
    }
    
    public function resetInputFields()
    {
        $this->officialId = null;
        $this->organisation_id = '';
        $this->member_id = '';
        $this->designation = '';
        $this->description = '';
        $this->is_active = true;
        $this->remarks = '';
        $this->resetErrorBag();
    }
    
    public function store()
    {
        $this->validate();
        
        Ec03OrganisationOfficial::updateOrCreate(['id' => $this->officialId], [
            'organisation_id' => $this->organisation_id,
            'member_id' => $this->member_id,
            'designation' => $this->designation,
            'description' => $this->description,
            'is_active' => $this->is_active,
            'remarks' => $this->remarks ?? '',
        ]);
        
        session()->flash('message', $this->officialId ? 'Official updated successfully.' : 'Official created successfully.');
        
        $this->closeModal();
    }
    
    public function edit($id)
    {
        $official = Ec03OrganisationOfficial::findOrFail($id);
        $this->officialId = $id;
        $this->organisation_id = $official->organisation_id;
        $this->member_id = $official->member_id;
        $this->designation = $official->designation;
        $this->description = $official->description;
        $this->is_active = $official->is_active;
        $this->remarks = $official->remarks;
        
        $this->openModal();
    }
    
    public function delete($id)
    {
        Ec03OrganisationOfficial::find($id)->delete();
        session()->flash('message', 'Official deleted successfully.');
    }
}
