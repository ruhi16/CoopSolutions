<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;

use App\Models\Wf03TaskEvent;
use App\Models\Wf02TaskEventParticular;
use App\Models\Wf02TaskEventParticularStatus;

use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class Wf02TaskEventParticularStatusComp extends Component {

    use WithPagination;

    public $name, $description, $task_event_id, $task_event_particular_id;
    public $is_active = true, $remarks;
    public $recordId;
    public $isModalOpen = false;
    public $isDeleteModalOpen = false;
    public $search = '';
    public $taskEvents = [];
    public $taskEventParticulars = [];

    protected $rules = [
        'name' => 'required|min:3',
        'description' => 'nullable|string',
        'task_event_id' => 'nullable|integer',
        'task_event_particular_id' => 'nullable|integer',
        'is_active' => 'boolean',
        'remarks' => 'nullable|string'
    ];

    public function mount()
    {
        $this->taskEvents = Wf03TaskEvent::where('is_active', true)->get();
        $this->taskEventParticulars = Wf02TaskEventParticular::where('is_active', true)->get();
    }

    public function render()
    {
        $statuses = Wf02TaskEventParticularStatus::where('name', 'like', '%'.$this->search.'%')
            ->orWhere('description', 'like', '%'.$this->search.'%')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.wf02-task-event-particular-status-comp', [
            'statuses' => $statuses
        ]);
    }

    public function create()
    {
        $this->resetInputFields();
        $this->openModal();
    }

    public function openModal()
    {
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->resetInputFields();
    }

    public function openDeleteModal($id)
    {
        $this->recordId = $id;
        $this->isDeleteModalOpen = true;
    }

    public function closeDeleteModal()
    {
        $this->isDeleteModalOpen = false;
        $this->recordId = null;
    }

    private function resetInputFields()
    {
        $this->name = '';
        $this->description = '';
        $this->task_event_id = '';
        $this->task_event_particular_id = '';
        $this->is_active = true;
        $this->remarks = '';
        $this->recordId = '';
    }

    public function store()
    {
        $this->validate();

        Wf02TaskEventParticularStatus::updateOrCreate(['id' => $this->recordId], [
            'name' => $this->name,
            'description' => $this->description,
            'task_event_id' => $this->task_event_id,
            'task_event_particular_id' => $this->task_event_particular_id,
            'is_active' => $this->is_active,
            'remarks' => $this->remarks,
        ]);

        session()->flash('message', 
            $this->recordId ? 'Task Event Particular Status Updated Successfully.' : 'Task Event Particular Status Created Successfully.');

        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $status = Wf02TaskEventParticularStatus::findOrFail($id);
        $this->recordId = $id;
        $this->name = $status->name;
        $this->description = $status->description;
        $this->task_event_id = $status->task_event_id;
        $this->task_event_particular_id = $status->task_event_particular_id;
        $this->is_active = $status->is_active;
        $this->remarks = $status->remarks;

        $this->openModal();
    }

    public function delete()
    {
        Wf02TaskEventParticularStatus::find($this->recordId)->delete();
        session()->flash('message', 'Task Event Particular Status Deleted Successfully.');
        $this->closeDeleteModal();
    }
    // public function render()
    // {
    //     return view('livewire.wf02-task-event-particular-status-comp');
    // }
}
