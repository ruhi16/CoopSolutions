<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Wf02TaskEventParticular;
use App\Models\Wf02TaskEvent;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\WithPagination;



class Wf02TaskEventParticularComp extends Component{
    
    use WithPagination;

    public $name, $description, $task_event_id, $is_active = true, $remarks;
    public $recordId;
    public $isModalOpen = false;
    public $isDeleteModalOpen = false;
    public $search = '';

    protected $rules = [
        'name' => 'required|min:3',
        'description' => 'nullable|string',
        'task_event_id' => 'nullable|integer',
        'is_active' => 'boolean',
        'remarks' => 'nullable|string'
    ];

    

    public function create(){
        $this->resetInputFields();
        $this->openModal();
    }

    public function openModal(){
        $this->isModalOpen = true;
    }

    public function closeModal(){
        $this->isModalOpen = false;
        $this->resetInputFields();
    }

    public function openDeleteModal($id){
        $this->recordId = $id;
        $this->isDeleteModalOpen = true;
    }

    public function closeDeleteModal(){
        $this->isDeleteModalOpen = false;
        $this->recordId = null;
    }

    private function resetInputFields(){
        $this->name = '';
        $this->description = '';
        $this->task_event_id = '';
        $this->is_active = true;
        $this->remarks = '';
        $this->recordId = '';
    }

    public function store(){
        $this->validate();

        Wf02TaskEventParticular::updateOrCreate(['id' => $this->recordId], [
            'name' => $this->name,
            'description' => $this->description,
            'task_event_id' => $this->task_event_id ?: null,
            'is_active' => $this->is_active,
            'remarks' => $this->remarks,
        ]);

        session()->flash('message', 
            $this->recordId ? 'Task Event Particular Updated Successfully.' : 'Task Event Particular Created Successfully.');

        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id){
        $particular = Wf02TaskEventParticular::findOrFail($id);
        $this->recordId = $id;
        $this->name = $particular->name;
        $this->description = $particular->description;
        $this->task_event_id = $particular->task_event_id;
        $this->is_active = $particular->is_active;
        $this->remarks = $particular->remarks;

        $this->openModal();
    }

    public function delete(){
        Wf02TaskEventParticular::find($this->recordId)->delete();
        session()->flash('message', 'Task Event Particular Deleted Successfully.');
        $this->closeDeleteModal();
    }

    public function render(){
        $particulars = Wf02TaskEventParticular::where('name', 'like', '%'.$this->search.'%')
            ->orWhere('description', 'like', '%'.$this->search.'%')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.wf02-task-event-particular-comp', [
            'particulars' => $particulars
        ]);
    }
    // public function render()
    // {
    //     return view('livewire.wf02-task-event-particular-comp');
    // }
}
