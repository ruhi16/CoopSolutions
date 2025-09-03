<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Wf03TaskEvent;
use App\Models\Wf01TaskCategory;
use App\Models\Role;
use App\Models\Wf04TaskEventStep;
use App\Models\Wf02TaskEventParticular;
use App\Models\Wf02TaskEventParticularStatus;

class Wf03TaskEventComp extends Component
{
    use WithPagination;

    public $name, $description, $task_category_id, $role_id;
    public $is_active = true, $remarks;
    public $recordId;
    public $isModalOpen = false;
    public $isDeleteModalOpen = false;
    public $isStepModalOpen = false;
    public $search = '';
    public $taskCategories = [];
    public $roles = [];
    
    // Step properties
    public $step_name, $step_description, $task_event_order_index;
    public $task_event_particular_id, $task_event_particular_status_id, $step_role_id;
    public $step_remarks, $step_is_active = true;
    public $currentTaskEventId;
    public $taskEventParticulars = [];
    public $taskEventParticularStatuses = [];
    public $steps = [];

    protected $rules = [
        'name' => 'required|min:3',
        'description' => 'nullable|string',
        'task_category_id' => 'nullable|integer',
        'role_id' => 'nullable|integer',
        'is_active' => 'boolean',
        'remarks' => 'nullable|string'
    ];

    protected $stepRules = [
        'step_name' => 'required|min:3',
        'step_description' => 'nullable|string',
        'task_event_order_index' => 'required|integer|min:1',
        'task_event_particular_id' => 'nullable|integer',
        'task_event_particular_status_id' => 'nullable|integer',
        'step_role_id' => 'nullable|integer',
        'step_is_active' => 'boolean',
        'step_remarks' => 'nullable|string'
    ];

    public function mount()
    {
        $this->taskCategories = Wf01TaskCategory::where('is_active', true)->get();
        $this->roles = Role::where('is_active', true)->get();
        $this->taskEventParticulars = Wf02TaskEventParticular::where('is_active', true)->get();
        $this->taskEventParticularStatuses = Wf02TaskEventParticularStatus::where('is_active', true)->get();
    }

    public function render()
    {
        $taskEvents = Wf03TaskEvent::where('name', 'like', '%'.$this->search.'%')
            ->orWhere('description', 'like', '%'.$this->search.'%')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.wf03-task-event-comp', [
            'taskEvents' => $taskEvents
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

    public function openStepModal($taskEventId)
    {
        $this->currentTaskEventId = $taskEventId;
        $this->resetStepInputFields();
        $this->isStepModalOpen = true;
        
        // Load steps for this task event
        $this->steps = Wf04TaskEventStep::where('task_event_id', $taskEventId)
            ->orderBy('task_event_order_index', 'asc')
            ->get();
    }

    public function closeStepModal()
    {
        $this->isStepModalOpen = false;
        $this->resetStepInputFields();
        $this->currentTaskEventId = null;
        $this->steps = [];
    }

    private function resetInputFields()
    {
        $this->name = '';
        $this->description = '';
        $this->task_category_id = '';
        $this->role_id = '';
        $this->is_active = true;
        $this->remarks = '';
        $this->recordId = '';
    }

    private function resetStepInputFields()
    {
        $this->step_name = '';
        $this->step_description = '';
        $this->task_event_order_index = '';
        $this->task_event_particular_id = '';
        $this->task_event_particular_status_id = '';
        $this->step_role_id = '';
        $this->step_is_active = true;
        $this->step_remarks = '';
    }

    public function store()
    {
        $this->validate();

        Wf03TaskEvent::updateOrCreate(['id' => $this->recordId], [
            'name' => $this->name,
            'description' => $this->description,
            'task_category_id' => $this->task_category_id,
            'role_id' => $this->role_id,
            'is_active' => $this->is_active,
            'remarks' => $this->remarks,
        ]);

        session()->flash('message', 
            $this->recordId ? 'Task Event Updated Successfully.' : 'Task Event Created Successfully.');

        $this->closeModal();
        $this->resetInputFields();
    }

    public function storeStep()
    {
        $this->validate($this->stepRules);

        Wf04TaskEventStep::create([
            'name' => $this->step_name,
            'description' => $this->step_description,
            'task_event_id' => $this->currentTaskEventId,
            'task_event_order_index' => $this->task_event_order_index,
            'task_event_particular_id' => $this->task_event_particular_id,
            'task_event_particular_status_id' => $this->task_event_particular_status_id,
            'role_id' => $this->step_role_id,
            'is_active' => $this->step_is_active,
            'remarks' => $this->step_remarks,
        ]);

        session()->flash('step_message', 'Task Event Step Created Successfully.');

        // Refresh the steps list
        $this->steps = Wf04TaskEventStep::where('task_event_id', $this->currentTaskEventId)
            ->orderBy('task_event_order_index', 'asc')
            ->get();
            
        $this->resetStepInputFields();
    }

    public function edit($id)
    {
        $taskEvent = Wf03TaskEvent::findOrFail($id);
        $this->recordId = $id;
        $this->name = $taskEvent->name;
        $this->description = $taskEvent->description;
        $this->task_category_id = $taskEvent->task_category_id;
        $this->role_id = $taskEvent->role_id;
        $this->is_active = $taskEvent->is_active;
        $this->remarks = $taskEvent->remarks;

        $this->openModal();
    }

    public function deleteStep($stepId)
    {
        Wf04TaskEventStep::find($stepId)->delete();
        session()->flash('step_message', 'Task Event Step Deleted Successfully.');
        
        // Refresh the steps list
        $this->steps = Wf04TaskEventStep::where('task_event_id', $this->currentTaskEventId)
            ->orderBy('task_event_order_index', 'asc')
            ->get();
    }

    public function delete()
    {
        Wf03TaskEvent::find($this->recordId)->delete();
        session()->flash('message', 'Task Event Deleted Successfully.');
        $this->closeDeleteModal();
    }
}