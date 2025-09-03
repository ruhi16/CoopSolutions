<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;
    protected $table = 'roles';

    protected $fillable = [
        'name',
        'description',
        'is_active',
        'remarks'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];
}
