<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ec05MemberType extends Model
{
    use HasFactory;
    protected $table = 'ec05_member_types';
    protected $guarded = ['id'];
}
