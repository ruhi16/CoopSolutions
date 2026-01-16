<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Ec17ShfundBankMasterDb;
use App\Models\Ec20Bank;
use App\Models\Ec08LoanAssign;

class Ec17ShfundBankMasterDbComp extends Component
{
    public $masterDbs;
    public $banks;
    public $loanAssigns;
    
    public $masterDbId = null;
    public $name = null;
    public $description = null;
    public $bankId = null;
    public $loanAssignId = null;
    public $previousLoanBalance = null;
    public $previousShareBalance = null;
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
        $this->masterDbs = Ec17ShfundBankMasterDb::with(['bank', 'loanAssign'])->get();
        $this->banks = Ec20Bank::where('is_active', true)->get();
        $this->loanAssigns = Ec08LoanAssign::all();
        
        $this->resetForm();
    }

    public function resetForm(){
        $this->masterDbId = null;
        $this->name = null;
        $this->description = null;
        $this->bankId = null;
        $this->loanAssignId = null;
        $this->previousLoanBalance = null;
        $this->previousShareBalance = null;
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
            $masterDb = Ec17ShfundBankMasterDb::find($masterDbId);
            $this->masterDbId = $masterDb->id;
            $this->name = $masterDb->name;
            $this->description = $masterDb->description;
            $this->bankId = $masterDb->bank_id;
            $this->loanAssignId = $masterDb->loan_assign_id;
            $this->previousLoanBalance = $masterDb->bank_loan_previous_balnce;
            $this->previousShareBalance = $masterDb->bank_share_previous_balnce;
            $this->operationalAmount = $masterDb->bank_share_operational_amount;
            $this->operationalType = $masterDb->bank_share_operational_type;
            $this->operationalDate = $masterDb->bank_share_operational_date;
            $this->currentBalance = $masterDb->bank_share_current_balnce;
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
            'bankId' => 'required|exists:ec20_banks,id',
            'loanAssignId' => 'required|exists:ec08_loan_assigns,id',
            'previousLoanBalance' => 'nullable|numeric|min:0',
            'previousShareBalance' => 'nullable|numeric|min:0',
            'operationalAmount' => 'nullable|numeric|min:0',
            'operationalType' => 'nullable|in:deposit,withdrawal',
            'operationalDate' => 'nullable|date',
            'currentBalance' => 'nullable|numeric|min:0',
            'status' => 'required|in:draft,published,archived',
        ]);

        try{
            Ec17ShfundBankMasterDb::updateOrCreate([
                'id' => $this->masterDbId,
            ], [
                'name' => $this->name,
                'description' => $this->description,
                'bank_id' => $this->bankId,
                'loan_assign_id' => $this->loanAssignId,
                'bank_loan_previous_balnce' => $this->previousLoanBalance,
                'bank_share_previous_balnce' => $this->previousShareBalance,
                'bank_share_operational_amount' => $this->operationalAmount,
                'bank_share_operational_type' => $this->operationalType,
                'bank_share_operational_date' => $this->operationalDate,
                'bank_share_current_balnce' => $this->currentBalance,
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
            session()->flash('success', 'Bank Share Fund Master DB ' . ($this->masterDbId ? 'Updated' : 'Created') . ' Successfully');
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
                $masterDb = Ec17ShfundBankMasterDb::find($this->deleteConfirmId);
                $masterDb->delete();

                $this->refresh();
                $this->cancelDelete();
                session()->flash('success', 'Bank Share Fund Master DB Deleted Successfully');
            }catch(\Exception $e){
                session()->flash('error', $e->getMessage());
                $this->cancelDelete();
            }
        }
    }

    public function render()
    {
        return view('livewire.ec17-shfund-bank-master-db-comp');
    }
}
