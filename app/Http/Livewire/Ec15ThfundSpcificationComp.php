<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Ec15ThfundSpecification;

class Ec15ThfundSpcificationComp extends Component
{
    public $specifications;
    
    public $specificationId = null;
    public $name = null;
    public $description = null;
    public $particular = null;
    public $particularValue = null;
    public $effectedOn = null;
    public $status = 'draft';
    public $isActive = true;
    public $remarks = null;
    
    public $showSpecificationModal = false;
    public $showDeleteConfirmModal = false;
    public $deleteConfirmId = null;

    public function mount(){
        $this->refresh();
    }

    public function refresh(){
        $this->specifications = Ec15ThfundSpecification::all();
        $this->resetForm();
    }

    public function resetForm(){
        $this->specificationId = null;
        $this->name = null;
        $this->description = null;
        $this->particular = null;
        $this->particularValue = null;
        $this->effectedOn = null;
        $this->status = 'draft';
        $this->isActive = true;
        $this->remarks = null;
    }

    public function openModal($specificationId = null){
        if($specificationId){
            $specification = Ec15ThfundSpecification::find($specificationId);
            $this->specificationId = $specification->id;
            $this->name = $specification->name;
            $this->description = $specification->description;
            $this->particular = $specification->particular;
            $this->particularValue = $specification->particular_value;
            $this->effectedOn = $specification->effected_on;
            $this->status = $specification->status;
            $this->isActive = $specification->is_active;
            $this->remarks = $specification->remarks;
        } else {
            $this->resetForm();
        }

        $this->showSpecificationModal = true;
    }

    public function closeModal(){
        $this->showSpecificationModal = false;
        $this->resetForm();
    }

    public function saveSpecification(){
        $this->validate([
            'name' => 'required|string|max:255',
            'particular' => 'nullable|string|max:255',
            'particularValue' => 'nullable|numeric',
            'effectedOn' => 'nullable|date',
            'status' => 'required|in:draft,published,archived',
        ]);

        try{
            Ec15ThfundSpecification::updateOrCreate([
                'id' => $this->specificationId,
            ], [
                'name' => $this->name,
                'description' => $this->description,
                'particular' => $this->particular,
                'particular_value' => $this->particularValue,
                'effected_on' => $this->effectedOn,
                'status' => $this->status,
                'organisation_id' => session('organisation_id') ?? 1,
                'user_id' => auth()->id() ?? 1,
                'financial_year_id' => session('financial_year_id') ?? 1,
                'is_active' => $this->isActive,
                'remarks' => $this->remarks,
            ]);

            $this->closeModal();
            $this->refresh();
            session()->flash('success', 'Thrift Fund Specification ' . ($this->specificationId ? 'Updated' : 'Created') . ' Successfully');
        }catch(\Exception $e){
            session()->flash('error', $e->getMessage());
        }
    }

    public function confirmDelete($specificationId){
        $this->deleteConfirmId = $specificationId;
        $this->showDeleteConfirmModal = true;
    }

    public function cancelDelete(){
        $this->deleteConfirmId = null;
        $this->showDeleteConfirmModal = false;
    }

    public function deleteSpecification(){
        if($this->deleteConfirmId){
            try{
                $specification = Ec15ThfundSpecification::find($this->deleteConfirmId);
                $specification->delete();

                $this->refresh();
                $this->cancelDelete();
                session()->flash('success', 'Thrift Fund Specification Deleted Successfully');
            }catch(\Exception $e){
                session()->flash('error', $e->getMessage());
                $this->cancelDelete();
            }
        }
    }

    public function render()
    {
        return view('livewire.ec15-thfund-spcification-comp');
    }
}