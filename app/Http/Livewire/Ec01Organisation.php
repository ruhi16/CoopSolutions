<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Validation\Rule;
use App\Models\Ec01Organisation as Organisation;


class Ec01Organisation extends Component{
    // Modal state
    public $showModal = false;
    public $editMode = false;
    
    // Form fields
    public $organizationId;
    public $name = '';
    public $address = '';
    public $phone = '';
    public $email = '';
    public $website = '';
    public $description = '';
    public $is_active = true;
    
    // Table data
    public $organisations;
    
    // Sorting
    public $sortField = 'id';
    public $sortDirection = 'asc';

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255|' . Rule::unique('ec01_organisations')->ignore($this->organizationId),
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean'
        ];
    }

    protected $messages = [
        'name.required' => 'Organization name is required.',
        'name.unique' => 'Organization name already exists.',
        'email.email' => 'Please enter a valid email address.',
        'website.url' => 'Please enter a valid website URL.',
    ];

    public function mount()
    {
        $this->loadOrganisations();
    }

    public function loadOrganisations()
    {
        $this->organisations = Organisation::with('financialYears')
            ->orderBy($this->sortField, $this->sortDirection)
            ->get();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }
        
        $this->sortField = $field;
        $this->loadOrganisations();
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
        $this->organizationId = null;
        $this->name = '';
        $this->address = '';
        $this->phone = '';
        $this->email = '';
        $this->website = '';
        $this->description = '';
        $this->is_active = true;
    }

    public function save()
    {
        $this->validate();

        try {
            if ($this->editMode) {
                $organisation = Organisation::findOrFail($this->organizationId);
                $organisation->update([
                    'name' => $this->name,
                    'address' => $this->address,
                    'phone' => $this->phone,
                    'email' => $this->email,
                    'website' => $this->website,
                    'description' => $this->description,
                    'is_active' => $this->is_active,
                ]);
                
                session()->flash('message', 'Organization updated successfully!');
            } else {
                Organisation::create([
                    'name' => $this->name,
                    'address' => $this->address,
                    'phone' => $this->phone,
                    'email' => $this->email,
                    'website' => $this->website,
                    'description' => $this->description,
                    'is_active' => $this->is_active,
                ]);
                
                session()->flash('message', 'Organization created successfully!');
            }

            $this->closeModal();
            $this->loadOrganisations();
            
        } catch (\Exception $e) {
            session()->flash('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $organisation = Organisation::findOrFail($id);
        
        $this->organizationId = $organisation->id;
        $this->name = $organisation->name;
        $this->address = $organisation->address;
        $this->phone = $organisation->phone;
        $this->email = $organisation->email;
        $this->website = $organisation->website;
        $this->description = $organisation->description;
        $this->is_active = $organisation->is_active;
        
        $this->editMode = true;
        $this->showModal = true;
    }

    public function delete($id)
    {
        try {
            $organisation = Organisation::findOrFail($id);
            $organisation->delete();
            
            session()->flash('message', 'Organization deleted successfully!');
            $this->loadOrganisations();
            
        } catch (\Exception $e) {
            session()->flash('error', 'An error occurred while deleting: ' . $e->getMessage());
        }
    }

    public function confirmDelete($id)
    {
        $this->dispatchBrowserEvent('confirm-delete', [
            'id' => $id,
            'message' => 'Are you sure you want to delete this organization?'
        ]);
    }

    // public $organisations;

    // public function mount(){
    //     $this->organisations = \App\Models\Ec01Organisation::all();
    //     // dd($this->organisations);
        
    // }

    
    public function render(){
        return view('livewire.ec01-organisation');
    }
}
