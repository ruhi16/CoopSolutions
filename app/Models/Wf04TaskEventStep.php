<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wf04TaskEventStep extends Model
{
    use HasFactory;
    protected $table = 'wf04_task_event_steps';
    protected $primaryKey = 'id';
    protected $guarded = ['id'];

    protected $fillable = [
        'name',
        'description',
        'task_event_id',
        'task_event_order_index',
        'task_event_particular_id',
        'task_event_particular_status_id',
        'role_id',
        'is_active',
        'remarks'
    ];

    public function taskEvent(){
        return $this->belongsTo(Wf03TaskEvent::class, 'task_event_id');
    }

    public function taskEventParticular(){
        return $this->belongsTo(Wf02TaskEventParticular::class, 'task_event_particular_id');
    }

    public function taskEventParticularStatus(){
        return $this->belongsTo(Wf02TaskEventParticularStatus::class, 'task_event_particular_status_id');
    }

    public function role(){
        return $this->belongsTo(Role::class, 'role_id');
    }
}
