<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Wf08TaskEventSchedule;
use App\Models\Wf01TaskCategory;
use App\Models\Wf03TaskEvent;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;


class Wf08TaskEventScheduleComp extends Component{

    use WithPagination;

    public $name, $description, $task_category_id, $task_event_id;
    public $scheduled_date, $scheduled_time;
    public $is_active = true, $is_finalized = false, $remarks;
    public $recordId;
    public $isModalOpen = false;
    public $isDeleteModalOpen = false;
    public $search = '';
    public $taskCategories = [];
    public $taskEvents = [];
    public $filterStatus = 'all'; // all, upcoming, completed

    protected $rules = [
        'name' => 'required|min:3',
        'description' => 'nullable|string',
        'task_category_id' => 'required|integer',
        'task_event_id' => 'required|integer',
        'scheduled_date' => 'required|date',
        'scheduled_time' => 'required',
        'is_active' => 'boolean',
        'is_finalized' => 'boolean',
        'remarks' => 'nullable|string'
    ];

    public function mount()
    {
        $this->taskCategories = Wf01TaskCategory::where('is_active', true)->get();
        $this->loadTaskEvents();
    }

    public function loadTaskEvents()
    {
        if ($this->task_category_id) {
            $this->taskEvents = Wf03TaskEvent::where('task_category_id', $this->task_category_id)
                ->where('is_active', true)
                ->get();
        } else {
            $this->taskEvents = [];
        }
    }

    public function updatedTaskCategoryId()
    {
        $this->loadTaskEvents();
        $this->task_event_id = null;
    }

    public function render()
    {
        $query = Wf08TaskEventSchedule::with(['taskCategory', 'taskEvent'])
            ->where('name', 'like', '%'.$this->search.'%')
            ->orWhere('description', 'like', '%'.$this->search.'%');

        // Apply status filter
        if ($this->filterStatus === 'upcoming') {
            $query->where('scheduled_at', '>', now());
        } elseif ($this->filterStatus === 'completed') {
            $query->where('scheduled_at', '<=', now());
        }

        $schedules = $query->orderBy('scheduled_at', 'asc')
            ->paginate(10);

        return view('livewire.wf08-task-event-schedule-comp', [
            'schedules' => $schedules
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
        $this->task_category_id = '';
        $this->task_event_id = '';
        $this->scheduled_date = '';
        $this->scheduled_time = '';
        $this->is_active = true;
        $this->is_finalized = false;
        $this->remarks = '';
        $this->recordId = '';
    }

    public function store()
    {
        $this->validate();

        // Combine date and time
        $scheduledAt = Carbon::parse($this->scheduled_date . ' ' . $this->scheduled_time);

        Wf08TaskEventSchedule::updateOrCreate(['id' => $this->recordId], [
            'name' => $this->name,
            'description' => $this->description,
            'task_category_id' => $this->task_category_id,
            'task_event_id' => $this->task_event_id,
            'scheduled_at' => $scheduledAt,
            'is_active' => $this->is_active,
            'is_finalized' => $this->is_finalized,
            'remarks' => $this->remarks,
        ]);

        session()->flash('message', 
            $this->recordId ? 'Task Event Schedule Updated Successfully.' : 'Task Event Schedule Created Successfully.');

        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $schedule = Wf08TaskEventSchedule::findOrFail($id);
        $this->recordId = $id;
        $this->name = $schedule->name;
        $this->description = $schedule->description;
        $this->task_category_id = $schedule->task_category_id;
        
        // Load events for the selected category
        $this->loadTaskEvents();
        
        $this->task_event_id = $schedule->task_event_id;
        
        // Split scheduled_at into date and time
        $this->scheduled_date = $schedule->scheduled_at->format('Y-m-d');
        $this->scheduled_time = $schedule->scheduled_at->format('H:i');
        
        $this->is_active = $schedule->is_active;
        $this->is_finalized = $schedule->is_finalized;
        $this->remarks = $schedule->remarks;

        $this->openModal();
    }

    public function delete()
    {
        Wf08TaskEventSchedule::find($this->recordId)->delete();
        session()->flash('message', 'Task Event Schedule Deleted Successfully.');
        $this->closeDeleteModal();
    }

    public function toggleFinalized($id)
    {
        $schedule = Wf08TaskEventSchedule::findOrFail($id);
        $schedule->is_finalized = !$schedule->is_finalized;
        $schedule->finalized_at = $schedule->is_finalized ? now() : null;
        $schedule->finalized_by = $schedule->is_finalized ? auth()->id() : null;
        $schedule->save();

        session()->flash('message', 'Task Event Schedule ' . ($schedule->is_finalized ? 'Finalized' : 'Unfinalized') . ' Successfully.');
    }

    public function getStatusBadgeClass($scheduledAt)
    {
        $now = now();
        $scheduled = Carbon::parse($scheduledAt);
        
        if ($scheduled->isPast()) {
            return 'bg-red-100 text-red-800';
        } elseif ($scheduled->isToday()) {
            return 'bg-yellow-100 text-yellow-800';
        } else {
            return 'bg-green-100 text-green-800';
        }
    }

    public function getStatusText($scheduledAt)
    {
        $now = now();
        $scheduled = Carbon::parse($scheduledAt);
        
        if ($scheduled->isPast()) {
            return 'Completed';
        } elseif ($scheduled->isToday()) {
            return 'Today';
        } else {
            return 'Upcoming';
        }
    }


    // public function render(){

    //     return view('livewire.wf08-task-event-schedule-comp');
    // }
}
