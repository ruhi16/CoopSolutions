<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wf02TaskEventParticular extends Model
{
    use HasFactory;
    protected $table = 'wf02_task_event_particulars';
    protected $guarded = ['id'];

    public function taskEvent(){
        return $this->belongsTo(Wf02TaskEvent::class,'task_event_id','id');
        
        // 'task_event_id' is the foreign key in the wf02_task_events table
        // 'id' is the primary key in the wf02_task_events table
    }


    


}
