<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ec01Organisation extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $table = 'ec01_organisations';

    public function financialYears(){
        return $this->hasMany('\App\Models\Ec02FinancialYear', 'organisation_id', 'id');
        // 'organisation_id' is the foreign key in the ec02_financial_years table
        // 'id' is the primary key in the ec01_organisations table

    }

    
}
