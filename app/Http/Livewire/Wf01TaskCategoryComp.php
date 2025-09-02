<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Wf01TaskCagegory as Wf01TaskCategory;

class Wf01TaskCategoryComp extends Component
{
    public $name, $description, $is_active = true, $remarks;
    public $categories = [];
    public $category_id;
    public $showModal = false;
    public $isEditMode = false;
    public $search = '';

    protected $rules = [
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'remarks' => 'required|string',
        'is_active' => 'boolean',
    ];

    public function mount()
    {
        $this->loadCategories();
    }

    public function loadCategories()
    {
        $this->categories = Wf01TaskCategory::where('is_deleted', false)
            ->where('name', 'like', '%' . $this->search . '%')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function openModal($isEdit = false, $categoryId = null)
    {
        $this->isEditMode = $isEdit;
        $this->resetInputFields();

        if ($isEdit && $categoryId) {
            $category = Wf01TaskCategory::findOrFail($categoryId);
            $this->category_id = $category->id;
            $this->name = $category->name;
            $this->description = $category->description;
            $this->remarks = $category->remarks;
            $this->is_active = $category->is_active;
        }

        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetInputFields();
    }

    public function resetInputFields()
    {
        $this->name = '';
        $this->description = '';
        $this->remarks = '';
        $this->is_active = true;
        $this->category_id = null;
        $this->resetErrorBag();
    }

    public function save()
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'description' => $this->description,
            'remarks' => $this->remarks,
            'is_active' => $this->is_active,
            'organisation_id' => Auth::user()->organisation_id ?? null,
        ];

        if ($this->isEditMode) {
            $category = Wf01TaskCategory::findOrFail($this->category_id);
            $category->update($data);
            session()->flash('message', 'Category updated successfully.');
        } else {
            Wf01TaskCategory::create($data);
            session()->flash('message', 'Category created successfully.');
        }

        $this->closeModal();
        $this->loadCategories();
    }

    public function delete($categoryId)
    {
        $category = Wf01TaskCategory::findOrFail($categoryId);
        $category->update([
            'is_deleted' => true,
            'deleted_by' => Auth::id(),
            'deleted_at' => now(),
        ]);
        
        session()->flash('message', 'Category deleted successfully.');
        $this->loadCategories();
    }

    public function updatedSearch()
    {
        $this->loadCategories();
    }


    public function render()
    {
        return view('livewire.wf01-task-category-comp');
    }
}
