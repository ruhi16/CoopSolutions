<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ec04Member extends Model
{
    use HasFactory;
    protected $table = 'ec04_members';
    protected $guarded = ['id'];


    // public function loanRequests(){
    //     return $this->hasMany(Ec08LoanRequest::class, 'member_id', 'id');
    //     // 'member_id' is the foreign key 
    //     // 'id' is the local key
    // }
    
}
