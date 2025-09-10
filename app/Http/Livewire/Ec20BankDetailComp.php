<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Ec20Bank;
use App\Models\Ec20BankDetail;

class Ec20BankDetailComp extends Component{

    use WithPagination;

    public $banks = null, $bankDetails = null;
    public $bankId, $name, $description, $status, $is_active, $remarks;
    public $detailId, $bank_id, $detail_name, $detail_description, $detail_status;
    public $isOpen = false;
    public $isDetailOpen = false;
    public $search = '';
    public $detailSearch = '';
    public $selectedBankId = null;

    protected $rules = [
        'name' => 'required|min:3',
        'description' => 'nullable|string',
        'status' => 'required|in:draft,published,archived',
        'is_active' => 'boolean'
    ];

    protected $detailRules = [
        'bank_id' => 'required|exists:ec20_banks,id',
        'detail_name' => 'required|min:3',
        'detail_description' => 'nullable|string',
        'detail_status' => 'required|in:running,completed,upcoming,suspended,cancelled,archived'
    ];

    

    public function render()
    {
        if (!empty($this->detailSearch)){

        }
        $this->banks = Ec20Bank::all();
        // where('name', 'like', '%'.$this->search.'%')
        //             ->orWhere('description', 'like', '%'.$this->search.'%')
        //             ->orderBy('created_at', 'desc')
        //             ->paginate(10);
        // dd($banks);
        $bankDetails = [];
        if ($this->selectedBankId) {
            $bankDetails = Ec20BankDetail::all();
                // where('bank_id', $this->selectedBankId)
                //             ->where(function($query) {
                //                 $query->where('name', 'like', '%'.$this->detailSearch.'%')
                //                       ->orWhere('description', 'like', '%'.$this->detailSearch.'%');
                //             })
                //             ->orderBy('created_at', 'desc')
                //             ->paginate(10);
        }

        return view('livewire.ec20-bank-detail-comp', [
            // 'banks' => $banks,
            // 'bankDetails' => $bankDetails
        ]);
    }

    public function create()
    {
        $this->resetInputFields();
        $this->openModal();
    }

    public function openModal()
    {
        // dd('open modal');
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
    }

    public function openDetailModal()
    {
        $this->isDetailOpen = true;
    }

    public function closeDetailModal()
    {
        $this->isDetailOpen = false;
    }

    private function resetInputFields()
    {
        $this->bankId = '';
        $this->name = '';
        $this->description = '';
        $this->status = 'draft';
        $this->is_active = true;
        $this->remarks = '';
    }

    private function resetDetailInputFields()
    {
        $this->detailId = '';
        $this->bank_id = $this->selectedBankId;
        $this->detail_name = '';
        $this->detail_description = '';
        $this->detail_status = 'suspended';
    }

    public function store()
    {
        $this->validate();

        Ec20Bank::updateOrCreate(['id' => $this->bankId], [
            'name' => $this->name,
            'description' => $this->description,
            'status' => $this->status,
            'is_active' => $this->is_active,
            'remarks' => $this->remarks,
            'user_id' => auth()->id(),
            'organisation_id' => auth()->user()->organisation_id ?? null,
            'financial_year_id' => 1, // You might want to get this from session
        ]);

        session()->flash('message', 
            $this->bankId ? 'Bank Updated Successfully.' : 'Bank Created Successfully.');

        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $bank = Ec20Bank::findOrFail($id);
        // dd($bank);
        $this->bankId = $id;
        $this->name = $bank->name;
        $this->description = $bank->description;
        $this->status = $bank->status;
        $this->is_active = $bank->is_active;
        $this->remarks = $bank->remarks;
        
        $this->openModal();
    }

    public function delete($id)
    {
        Ec20Bank::find($id)->delete();
        session()->flash('message', 'Bank Deleted Successfully.');
    }

    public function selectBank($bankId)
    {
        $this->selectedBankId = $bankId;
        $this->resetDetailInputFields();
    }

    public function createDetail()
    {
        $this->resetDetailInputFields();
        $this->openDetailModal();
    }

    public function storeDetail()
    {
        $this->validate($this->detailRules);

        Ec20BankDetail::updateOrCreate(['id' => $this->detailId], [
            'bank_id' => $this->bank_id,
            'name' => $this->detail_name,
            'description' => $this->detail_description,
            'status' => $this->detail_status,
            'user_id' => auth()->id(),
            'organisation_id' => auth()->user()->organisation_id ?? null,
            'financial_year_id' => 1, // You might want to get this from session
        ]);

        session()->flash('message', 
            $this->detailId ? 'Bank Detail Updated Successfully.' : 'Bank Detail Created Successfully.');

        $this->closeDetailModal();
        $this->resetDetailInputFields();
    }

    public function editDetail($id)
    {
        $detail = Ec20BankDetail::findOrFail($id);
        $this->detailId = $id;
        $this->bank_id = $detail->bank_id;
        $this->detail_name = $detail->name;
        $this->detail_description = $detail->description;
        $this->detail_status = $detail->status;
        
        $this->openDetailModal();
    }

    public function deleteDetail($id)
    {
        Ec20BankDetail::find($id)->delete();
        session()->flash('message', 'Bank Detail Deleted Successfully.');
    }


    // public function render()
    // {
    //     return view('livewire.ec20-bank-detail-comp');
    // }
}
