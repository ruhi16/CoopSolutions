<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wf02TaskEventParticularStatus extends Model {
    
    use HasFactory;
    protected $table = 'wf02_task_event_particular_statuses';
    protected $guarded = ['id'];

    protected $fillable = [
        'name',
        'description',
        'task_event_id',
        'task_event_particular_id',
        'is_active',
        'remarks'
    ];

    public function taskEventParticular(){
        return $this->belongsTo(Wf02TaskEventParticular::class,'task_event_particular_id','id');        
        // 'task_event_particular_id' is the foreign key in the wf02_task_event_particulars table
        // 'id' is the primary key in the wf02_task_event_particulars table
    }



}
