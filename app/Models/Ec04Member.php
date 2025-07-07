<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ec04Member extends Model
{
    use HasFactory;
    protected $table = 'ec04_members';
    protected $guarded = ['id'];

    
}
