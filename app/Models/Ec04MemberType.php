<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ec04MemberType extends Model
{
    use HasFactory;
    protected $table = 'ec04_member_types';
    protected $guarded = ['id'];
}
