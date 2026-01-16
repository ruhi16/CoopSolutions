<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Ec16ShfundMemberMasterDb;
use App\Models\Ec04Member;
use App\Models\Ec08LoanAssign;

class Ec16ShfundMemberMasterDbComp extends Component
{
    public $masterDbs;
    public $members;
    public $loanAssigns;
    
    public $masterDbId = null;
    public $name = null;
    public $description = null;
    public $memberId = null;
    public $loanAssignId = null;
    public $operationalAmount = null;
    public $operationalType = null;
    public $operationalDate = null;
    public $currentBalance = null;
    public $status = 'draft';
    public $isFinalized = true;
    public $finalizedBy = null;
    public $finalizedAt = null;
    public $isActive = true;
    public $remarks = null;
    
    public $showMasterDbModal = false;
    public $showDeleteConfirmModal = false;
    public $deleteConfirmId = null;

    public function mount(){
        $this->refresh();
    }

    public function refresh(){
        $this->masterDbs = Ec16ShfundMemberMasterDb::with(['member', 'loanAssign'])->get();
        $this->members = Ec04Member::where('is_active', true)->get();
        $this->loanAssigns = Ec08LoanAssign::all();
        
        $this->resetForm();
    }

    public function resetForm(){
        $this->masterDbId = null;
        $this->name = null;
        $this->description = null;
        $this->memberId = null;
        $this->loanAssignId = null;
        $this->operationalAmount = null;
        $this->operationalType = null;
        $this->operationalDate = null;
        $this->currentBalance = null;
        $this->status = 'draft';
        $this->isFinalized = true;
        $this->finalizedBy = null;
        $this->finalizedAt = null;
        $this->isActive = true;
        $this->remarks = null;
    }

    public function openModal($masterDbId = null){
        if($masterDbId){
            $masterDb = Ec16ShfundMemberMasterDb::find($masterDbId);
            $this->masterDbId = $masterDb->id;
            $this->name = $masterDb->name;
            $this->description = $masterDb->description;
            $this->memberId = $masterDb->member_id;
            $this->loanAssignId = $masterDb->loan_assign_id;
            $this->operationalAmount = $masterDb->share_operational_amount;
            $this->operationalType = $masterDb->share_operational_type;
            $this->operationalDate = $masterDb->share_operational_date;
            $this->currentBalance = $masterDb->share_current_balnce;
            $this->status = $masterDb->status;
            $this->isFinalized = $masterDb->is_finalized;
            $this->finalizedBy = $masterDb->finalized_by;
            $this->finalizedAt = $masterDb->finalized_at;
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
            'loanAssignId' => 'required|exists:ec08_loan_assigns,id',
            'operationalAmount' => 'nullable|numeric|min:0',
            'operationalType' => 'nullable|in:deposit,withdrawal',
            'operationalDate' => 'nullable|date',
            'currentBalance' => 'nullable|numeric|min:0',
            'status' => 'required|in:draft,published,archived',
        ]);

        try{
            Ec16ShfundMemberMasterDb::updateOrCreate([
                'id' => $this->masterDbId,
            ], [
                'name' => $this->name,
                'description' => $this->description,
                'member_id' => $this->memberId,
                'loan_assign_id' => $this->loanAssignId,
                'share_operational_amount' => $this->operationalAmount,
                'share_operational_type' => $this->operationalType,
                'share_operational_date' => $this->operationalDate,
                'share_current_balnce' => $this->currentBalance,
                'status' => $this->status,
                'organisation_id' => session('organisation_id') ?? 1,
                'user_id' => auth()->id() ?? 1,
                'financial_year_id' => session('financial_year_id') ?? 1,
                'is_finalized' => $this->isFinalized,
                'finalized_by' => $this->finalizedBy,
                'finalized_at' => $this->finalizedAt,
                'is_active' => $this->isActive,
                'remarks' => $this->remarks,
            ]);

            $this->closeModal();
            $this->refresh();
            session()->flash('success', 'Share Fund Member Master DB ' . ($this->masterDbId ? 'Updated' : 'Created') . ' Successfully');
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
                $masterDb = Ec16ShfundMemberMasterDb::find($this->deleteConfirmId);
                $masterDb->delete();

                $this->refresh();
                $this->cancelDelete();
                session()->flash('success', 'Share Fund Member Master DB Deleted Successfully');
            }catch(\Exception $e){
                session()->flash('error', $e->getMessage());
                $this->cancelDelete();
            }
        }
    }

    public function render()
    {
        return view('livewire.ec16-shfund-member-master-db-comp');
    }
}
