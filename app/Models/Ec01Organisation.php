<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ec01Organisation extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $table = 'ec01_organisations';

    protected $fillable = [
        'name',
        'address',
        'phone',
        'email',
        'website',
        'description',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    // public function financialYears()
    // {
    //     return $this->hasMany(FinancialYear::class);
    // }

    public function financialYears(){
        return $this->hasMany('\App\Models\Ec02FinancialYear', 'organisation_id', 'id');
        // 'organisation_id' is the foreign key in the ec02_financial_years table
        // 'id' is the primary key in the ec01_organisations table

    }
    

    
}
