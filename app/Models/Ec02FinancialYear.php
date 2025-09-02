<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Ec02FinancialYear;
use App\Models\Ec01Organisation;



class Ec02FinancialYear extends Model{

    use HasFactory;
    protected $guarded = ['id'];
    protected $table = 'ec02_financial_years';


    public function scopeCurrentlyActive($query){
        return $query->where('is_active', '=', '1');

    }


    public function organisation(){
        
        return $this->belongsTo('\App\Models\Ec01Organisation', 'organisation_id', 'id');
        // 'organisation_id' is the foreign key in the ec02_financial_years table, 
        // that references primary key in the ec01_organisations table
        // 'id' is the primary key in the ec01_organisations table

    }
}
