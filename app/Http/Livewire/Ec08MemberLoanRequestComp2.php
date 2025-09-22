<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Ec08LoanRequest;
use App\Models\Ec04Member as Member;
use App\Models\Ec06LoanScheme as LoanScheme;
use Illuminate\Support\Facades\Auth;

class Ec08MemberLoanRequestComp2 extends Component
{
    use WithPagination;

    public $organisation_id;
    public $member_id;
    public $req_loan_scheme_id;
    public $req_loan_amount;
    public $time_period_months;
    public $req_date;
    public $status = 'pending';
    public $status_instructions;
    public $approved_loan_amount;
    public $approved_loan_amount_date;
    public $member_concent = false;
    public $member_concent_note;
    public $member_concent_date;
    public $remarks;
    
    public $isModalOpen = false;
    public $isDeleteModalOpen = false;
    public $isViewModalOpen = false;
    public $editingId = null;
    public $search = '';
    public $selectedStatus = '';

    protected $rules = [
        'member_id' => 'required|exists:members,id',
        'req_loan_scheme_id' => 'required|exists:loan_schemes,id',
        'req_loan_amount' => 'required|numeric|min:1',
        'time_period_months' => 'required|integer|min:1',
        'req_date' => 'required|date',
        'status' => 'required|in:pending,approved,rejected,cancelled,closed,expired,overdue,completed',
        'status_instructions' => 'nullable|string|max:500',
        'approved_loan_amount' => 'nullable|numeric|min:0',
        'approved_loan_amount_date' => 'nullable|date',
        'member_concent_note' => 'nullable|string|max:500',
        'member_concent_date' => 'nullable|date',
        'remarks' => 'nullable|string|max:500',
    ];

    public function mount()
    {
        $this->organisation_id = session('organisation_id') ?? 1; // Default to 1 if not set
        $this->req_date = now()->format('Y-m-d');
    }

    public function render()
    {
        $query = Ec08LoanRequest::with(['member', 'loanScheme'])
            ->where('organisation_id', $this->organisation_id);

        if ($this->search) {
            $query->whereHas('member', function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('member_code', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->selectedStatus) {
            $query->where('status', $this->selectedStatus);
        }

        $loanRequests = $query->orderBy('created_at', 'desc')->paginate(10);
        $members = Member::where('organisation_id', $this->organisation_id)->where('is_active', true)->get();
        $loanSchemes = LoanScheme::where('organisation_id', $this->organisation_id)->where('is_active', true)->get();

        return view('livewire.ec08-member-loan-request-comp2', [
            'loanRequests' => $loanRequests,
            'members' => $members,
            'loanSchemes' => $loanSchemes,
            'statusOptions' => [
                'pending' => 'Pending',
                'approved' => 'Approved',
                'rejected' => 'Rejected',
                'cancelled' => 'Cancelled',
                'closed' => 'Closed',
                'expired' => 'Expired',
                'overdue' => 'Overdue',
                'completed' => 'Completed'
            ]
        ]);
    }

    public function create()
    {
        $this->resetForm();
        $this->isModalOpen = true;
    }

    public function edit($id)
    {
        $loanRequest = Ec08LoanRequest::findOrFail($id);
        
        $this->editingId = $id;
        $this->member_id = $loanRequest->member_id;
        $this->req_loan_scheme_id = $loanRequest->req_loan_scheme_id;
        $this->req_loan_amount = $loanRequest->req_loan_amount;
        $this->time_period_months = $loanRequest->time_period_months;
        $this->req_date = $loanRequest->req_date;
        $this->status = $loanRequest->status;
        $this->status_instructions = $loanRequest->status_instructions;
        $this->approved_loan_amount = $loanRequest->approved_loan_amount;
        $this->approved_loan_amount_date = $loanRequest->approved_loan_amount_date;
        $this->member_concent = $loanRequest->member_concent;
        $this->member_concent_note = $loanRequest->member_concent_note;
        $this->member_concent_date = $loanRequest->member_concent_date;
        $this->remarks = $loanRequest->remarks;
        
        $this->isModalOpen = true;
    }

    public function view($id)
    {
        $this->editingId = $id;
        $loanRequest = Ec08LoanRequest::with(['member', 'loanScheme'])->findOrFail($id);
        
        $this->member_id = $loanRequest->member_id;
        $this->req_loan_scheme_id = $loanRequest->req_loan_scheme_id;
        $this->req_loan_amount = $loanRequest->req_loan_amount;
        $this->time_period_months = $loanRequest->time_period_months;
        $this->req_date = $loanRequest->req_date;
        $this->status = $loanRequest->status;
        $this->status_instructions = $loanRequest->status_instructions;
        $this->approved_loan_amount = $loanRequest->approved_loan_amount;
        $this->approved_loan_amount_date = $loanRequest->approved_loan_amount_date;
        $this->member_concent = $loanRequest->member_concent;
        $this->member_concent_note = $loanRequest->member_concent_note;
        $this->member_concent_date = $loanRequest->member_concent_date;
        $this->remarks = $loanRequest->remarks;
        
        $this->isViewModalOpen = true;
    }

    public function save()
    {
        $this->validate();

        $data = [
            'organisation_id' => $this->organisation_id,
            'member_id' => $this->member_id,
            'req_loan_scheme_id' => $this->req_loan_scheme_id,
            'req_loan_amount' => $this->req_loan_amount,
            'time_period_months' => $this->time_period_months,
            'req_date' => $this->req_date,
            'status' => $this->status,
            'status_instructions' => $this->status_instructions,
            'approved_loan_amount' => $this->approved_loan_amount,
            'approved_loan_amount_date' => $this->approved_loan_amount_date,
            'member_concent' => $this->member_concent,
            'member_concent_note' => $this->member_concent_note,
            'member_concent_date' => $this->member_concent_date,
            'remarks' => $this->remarks,
        ];

        // Set status dates based on status changes
        if ($this->status === 'approved' || $this->status === 'rejected') {
            $data['status_assigning_date'] = now();
        }

        if ($this->status === 'closed' || $this->status === 'completed') {
            $data['status_closed_date'] = now();
        }

        if ($this->editingId) {
            $loanRequest = Ec08LoanRequest::findOrFail($this->editingId);
            $loanRequest->update($data);
            session()->flash('message', 'Loan request updated successfully.');
        } else {
            $data['done_by'] = Auth::id();
            Ec08LoanRequest::create($data);
            session()->flash('message', 'Loan request created successfully.');
        }

        $this->closeModal();
    }

    public function delete()
    {
        Ec08LoanRequest::findOrFail($this->editingId)->delete();
        $this->isDeleteModalOpen = false;
        session()->flash('message', 'Loan request deleted successfully.');
    }

    public function confirmDelete($id)
    {
        $this->editingId = $id;
        $this->isDeleteModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->isViewModalOpen = false;
        $this->isDeleteModalOpen = false;
        $this->resetForm();
        $this->editingId = null;
    }

    private function resetForm()
    {
        $this->reset([
            'member_id', 'req_loan_scheme_id', 'req_loan_amount', 
            'time_period_months', 'req_date', 'status', 'status_instructions',
            'approved_loan_amount', 'approved_loan_amount_date', 'member_concent',
            'member_concent_note', 'member_concent_date', 'remarks'
        ]);
        $this->req_date = now()->format('Y-m-d');
        $this->status = 'pending';
        $this->member_concent = false;
    }

    public function approveLoan($id)
    {
        $loanRequest = Ec08LoanRequest::findOrFail($id);
        $loanRequest->update([
            'status' => 'approved',
            'status_assigning_date' => now(),
            'approved_loan_amount' => $loanRequest->req_loan_amount,
            'approved_loan_amount_date' => now()
        ]);
        
        session()->flash('message', 'Loan request approved successfully.');
    }

    public function rejectLoan($id)
    {
        $loanRequest = Ec08LoanRequest::findOrFail($id);
        $loanRequest->update([
            'status' => 'rejected',
            'status_assigning_date' => now(),
            'status_instructions' => 'Loan application rejected by admin.'
        ]);
        
        session()->flash('message', 'Loan request rejected successfully.');
    }

    public function updateStatus($id, $status)
    {
        $loanRequest = Ec08LoanRequest::findOrFail($id);
        
        $updateData = ['status' => $status];
        
        if ($status === 'approved') {
            $updateData['status_assigning_date'] = now();
            $updateData['approved_loan_amount'] = $loanRequest->req_loan_amount;
            $updateData['approved_loan_amount_date'] = now();
        } elseif ($status === 'rejected') {
            $updateData['status_assigning_date'] = now();
        } elseif ($status === 'closed' || $status === 'completed') {
            $updateData['status_closed_date'] = now();
        }
        
        $loanRequest->update($updateData);
        
        session()->flash('message', 'Loan request status updated successfully.');
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingSelectedStatus()
    {
        $this->resetPage();
    }
    // public function render()
    // {
    //     return view('livewire.ec08-member-loan-request-comp2');
    // }
}
