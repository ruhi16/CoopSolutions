<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Ec16ShfundMemberTransaction;
use App\Models\Ec04Member;
use App\Models\Ec08LoanAssign;

class Ec16ShfundMemberTransactionComp extends Component
{
    public $transactions;
    public $members;
    public $loanAssigns;
    
    public $transactionId = null;
    public $name = null;
    public $description = null;
    public $memberId = null;
    public $loanAssignId = null;
    public $transactionIdField = null;
    public $transactionType = null;
    public $transactionAmount = null;
    public $transactionDate = null;
    public $transactionReasons = null;
    public $status = 'draft';
    public $isFinalized = true;
    public $finalizedBy = null;
    public $finalizedAt = null;
    public $isActive = true;
    public $remarks = null;
    
    public $showTransactionModal = false;
    public $showDeleteConfirmModal = false;
    public $deleteConfirmId = null;

    public function mount(){
        $this->refresh();
    }

    public function refresh(){
        $this->transactions = Ec16ShfundMemberTransaction::with(['member', 'loanAssign'])->get();
        $this->members = Ec04Member::where('is_active', true)->get();
        $this->loanAssigns = Ec08LoanAssign::all();
        
        $this->resetForm();
    }

    public function resetForm(){
        $this->transactionId = null;
        $this->name = null;
        $this->description = null;
        $this->memberId = null;
        $this->loanAssignId = null;
        $this->transactionIdField = null;
        $this->transactionType = null;
        $this->transactionAmount = null;
        $this->transactionDate = null;
        $this->transactionReasons = null;
        $this->status = 'draft';
        $this->isFinalized = true;
        $this->finalizedBy = null;
        $this->finalizedAt = null;
        $this->isActive = true;
        $this->remarks = null;
    }

    public function openModal($transactionId = null){
        if($transactionId){
            $transaction = Ec16ShfundMemberTransaction::find($transactionId);
            $this->transactionId = $transaction->id;
            $this->name = $transaction->name;
            $this->description = $transaction->description;
            $this->memberId = $transaction->member_id;
            $this->loanAssignId = $transaction->loan_assign_id;
            $this->transactionIdField = $transaction->transaction_id;
            $this->transactionType = $transaction->transaction_type;
            $this->transactionAmount = $transaction->transaction_amount;
            $this->transactionDate = $transaction->transaction_date;
            $this->transactionReasons = $transaction->transaction_reasons;
            $this->status = $transaction->status;
            $this->isFinalized = $transaction->is_finalized;
            $this->finalizedBy = $transaction->finalized_by;
            $this->finalizedAt = $transaction->finalized_at;
            $this->isActive = $transaction->is_active;
            $this->remarks = $transaction->remarks;
        } else {
            $this->resetForm();
        }

        $this->showTransactionModal = true;
    }

    public function closeModal(){
        $this->showTransactionModal = false;
        $this->resetForm();
    }

    public function saveTransaction(){
        $this->validate([
            'name' => 'required|string|max:255',
            'memberId' => 'required|exists:ec04_members,id',
            'transactionIdField' => 'required|string|unique:ec16_shfund_member_transactions,transaction_id,' . ($this->transactionId ?: 'NULL') . ',id',
            'transactionType' => 'nullable|in:deposit,withdrawal',
            'transactionAmount' => 'required|numeric|min:0',
            'transactionDate' => 'nullable|date',
            'status' => 'required|in:draft,published,archived',
        ]);

        try{
            Ec16ShfundMemberTransaction::updateOrCreate([
                'id' => $this->transactionId,
            ], [
                'name' => $this->name,
                'description' => $this->description,
                'member_id' => $this->memberId,
                'loan_assign_id' => $this->loanAssignId,
                'transaction_id' => $this->transactionIdField,
                'transaction_type' => $this->transactionType,
                'transaction_amount' => $this->transactionAmount,
                'transaction_date' => $this->transactionDate,
                'transaction_reasons' => $this->transactionReasons,
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
            session()->flash('success', 'Share Fund Member Transaction ' . ($this->transactionId ? 'Updated' : 'Created') . ' Successfully');
        }catch(\Exception $e){
            session()->flash('error', $e->getMessage());
        }
    }

    public function confirmDelete($transactionId){
        $this->deleteConfirmId = $transactionId;
        $this->showDeleteConfirmModal = true;
    }

    public function cancelDelete(){
        $this->deleteConfirmId = null;
        $this->showDeleteConfirmModal = false;
    }

    public function deleteTransaction(){
        if($this->deleteConfirmId){
            try{
                $transaction = Ec16ShfundMemberTransaction::find($this->deleteConfirmId);
                $transaction->delete();

                $this->refresh();
                $this->cancelDelete();
                session()->flash('success', 'Share Fund Member Transaction Deleted Successfully');
            }catch(\Exception $e){
                session()->flash('error', $e->getMessage());
                $this->cancelDelete();
            }
        }
    }

    public function render()
    {
        return view('livewire.ec16-shfund-member-transaction-comp');
    }
}
