<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wf01TaskCategory extends Model
{
    use HasFactory;
    protected $table = 'wf01_task_categories';

    protected $fillable = [
        'name',
        'description',
        'is_active',
        'remarks',
        'organisation_id',
        'is_finalized',
        'finalized_by',
        'finalized_at',
        'is_deleted',
        'deleted_by',
        'deleted_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_finalized' => 'boolean',
        'is_deleted' => 'boolean',
        'finalized_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function taskEvents(){
        return $this->hasMany('\App\Models\Wf03TaskEvent', 'task_category_id', 'id');
        // 'task_cagegory_id' is the foreign key in the wf03_task_events table
        // 'id' is the primary key in the wf01_task_cagegories table
    }




}
