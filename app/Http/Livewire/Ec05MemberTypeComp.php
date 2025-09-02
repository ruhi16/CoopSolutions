<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Ec05MemberTypeComp extends Component
{

    public $showMemberTypeModal = false;
    public $memberTypes;
    public $name, $description;


    public function refreshMemberTypes(){
        $this->memberTypes = \App\Models\Ec04MemberType::all();
    }

    public function mount(){
        // $this->memberTypes = \App\Models\Ec04MemberType::all();
        
        $this->refreshMemberTypes();

    }

    public function openModal($memberType_id = null){
        if($memberType_id){
            $memberType_data = \App\Models\Ec04MemberType::find($memberType_id);
            $this->name = $memberType_data->name;
            $this->description = $memberType_data->description;
            $this->showMemberTypeModal = true;
            return;
        }
        
        $this->name = '';
        $this->description = '';
        $this->showMemberTypeModal = true;
    }

    public function closeModal()
    {
        $this->showMemberTypeModal = false;
    }


    public function saveMemberType($memberType_id = null)
    {
        
        $this->validate([
            'name' => 'required|string|max:255',
        ]);
    
        try {
            $memberType_data = \App\Models\Ec04MemberType::updateOrCreate([
                'id' => $memberType_id,
            ],[
                'name' => $this->name,
                'description'=>$this->description,
                'is_active' => 1,
            ]);
        

            session()->flash('success', 'Member type saved successfully');
            
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to save member type: ' . $e->getMessage());
        }

        $this->refreshMemberTypes();
        $this->closeModal();

    }


    public function render()
    {
        return view('livewire.ec05-member-type-comp');
    }
}
