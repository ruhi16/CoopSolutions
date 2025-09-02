<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wf01TaskCagegory extends Model
{
    use HasFactory;
    protected $table = 'wf01_task_cagegories';

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
}
