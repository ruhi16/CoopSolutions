<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wf03TaskEvent extends Model
{
    use HasFactory;
    protected $table = 'wf03_task_events';
    protected $guarded = ['id'];
    
    protected $fillable = [
        'name',
        'description',
        'task_category_id',
        'role_id',
        'is_active',
        'remarks'
    ];

    public function taskCategory(){
        return $this->belongsTo(Wf01TaskCategory::class,'task_category_id','id');
        // 'task_category_id' is the foreign key in the wf01_task_categories table
        // 'id' is the primary key in the wf01_task_categories table
        
    }


    public function taskParticulars(){
        return $this->hasMany(Wf02TaskEventParticular::class, 'task_event_id', 'id');
        // 'task_event_id' is the foreign key in the wf02_task_events table
        // 'id' is the primary key in the wf02_task_events table
    }










    public function role(){
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }
}
