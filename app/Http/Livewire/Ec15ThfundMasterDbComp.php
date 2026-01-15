<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Ec15ThfundMasterDb;
use App\Models\Ec04Member;

class Ec15ThfundMasterDbComp extends Component
{
    public $masterDbs;
    public $members;
    
    public $masterDbId = null;
    public $name = null;
    public $description = null;
    public $memberId = null;
    public $operationalAmount = null;
    public $operationalType = null;
    public $operationalDate = null;
    public $currentBalance = null;
    public $startDate = null;
    public $endDate = null;
    public $numberOfMonths = null;
    public $status = 'draft';
    public $isActive = true;
    public $remarks = null;
    
    public $showMasterDbModal = false;
    public $showDeleteConfirmModal = false;
    public $deleteConfirmId = null;

    public function mount(){
        $this->refresh();
    }

    public function refresh(){
        $this->masterDbs = Ec15ThfundMasterDb::with('member')->get();
        $this->members = Ec04Member::where('is_active', true)->get();
        
        $this->resetForm();
    }

    public function resetForm(){
        $this->masterDbId = null;
        $this->name = null;
        $this->description = null;
        $this->memberId = null;
        $this->operationalAmount = null;
        $this->operationalType = null;
        $this->operationalDate = null;
        $this->currentBalance = null;
        $this->startDate = null;
        $this->endDate = null;
        $this->numberOfMonths = null;
        $this->status = 'draft';
        $this->isActive = true;
        $this->remarks = null;
    }

    public function openModal($masterDbId = null){
        if($masterDbId){
            $masterDb = Ec15ThfundMasterDb::find($masterDbId);
            $this->masterDbId = $masterDb->id;
            $this->name = $masterDb->name;
            $this->description = $masterDb->description;
            $this->memberId = $masterDb->member_id;
            $this->operationalAmount = $masterDb->thfund_operational_amount;
            $this->operationalType = $masterDb->thfund_operational_type;
            $this->operationalDate = $masterDb->thfund_operational_date;
            $this->currentBalance = $masterDb->thfund_current_balnce;
            $this->startDate = $masterDb->start_at;
            $this->endDate = $masterDb->end_at;
            $this->numberOfMonths = $masterDb->no_of_months;
            $this->status = $masterDb->status;
            $this->isActive = $masterDb->is_active;
            $this->remarks = $masterDb->remarks;
        } else {
            $this->resetForm();
        }

        $this->showMasterDbModal = true;
    }

    public function closeModal(){
        $this->showMasterDbModal = false;
        $this->resetForm();
    }

    public function saveMasterDb(){
        $this->validate([
            'name' => 'required|string|max:255',
            'memberId' => 'required|exists:ec04_members,id',
            'operationalAmount' => 'nullable|numeric|min:0',
            'operationalType' => 'nullable|in:deposit,withdrawal',
            'operationalDate' => 'nullable|date',
            'currentBalance' => 'nullable|numeric|min:0',
            'startDate' => 'nullable|date',
            'endDate' => 'nullable|date|after:startDate',
            'status' => 'required|in:draft,published,archived',
        ]);

        try{
            Ec15ThfundMasterDb::updateOrCreate([
                'id' => $this->masterDbId,
            ], [
                'name' => $this->name,
                'description' => $this->description,
                'member_id' => $this->memberId,
                'thfund_operational_amount' => $this->operationalAmount,
                'thfund_operational_type' => $this->operationalType,
                'thfund_operational_date' => $this->operationalDate,
                'thfund_current_balnce' => $this->currentBalance,
                'start_at' => $this->startDate,
                'end_at' => $this->endDate,
                'no_of_months' => $this->numberOfMonths,
                'status' => $this->status,
                'organisation_id' => session('organisation_id') ?? 1,
                'user_id' => auth()->id() ?? 1,
                'financial_year_id' => session('financial_year_id') ?? 1,
                'is_active' => $this->isActive,
                'remarks' => $this->remarks,
            ]);

            $this->closeModal();
            $this->refresh();
            session()->flash('success', 'Thrift Fund Master DB ' . ($this->masterDbId ? 'Updated' : 'Created') . ' Successfully');
        }catch(\Exception $e){
            session()->flash('error', $e->getMessage());
        }
    }

    public function confirmDelete($masterDbId){
        $this->deleteConfirmId = $masterDbId;
        $this->showDeleteConfirmModal = true;
    }

    public function cancelDelete(){
        $this->deleteConfirmId = null;
        $this->showDeleteConfirmModal = false;
    }

    public function deleteMasterDb(){
        if($this->deleteConfirmId){
            try{
                $masterDb = Ec15ThfundMasterDb::find($this->deleteConfirmId);
                $masterDb->delete();

                $this->refresh();
                $this->cancelDelete();
                session()->flash('success', 'Thrift Fund Master DB Deleted Successfully');
            }catch(\Exception $e){
                session()->flash('error', $e->getMessage());
                $this->cancelDelete();
            }
        }
    }

    public function render()
    {
        return view('livewire.ec15-thfund-master-db-comp');
    }
}