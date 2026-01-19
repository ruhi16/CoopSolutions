<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ec06LoanScheme extends Model
{
    use HasFactory;
    protected $table = 'ec06_loan_schemes';
    
    // Define fillable fields to prevent mass assignment vulnerabilities
    protected $fillable = [
        'name',
        'name_short', 
        'description',
        'start_date',
        'end_date',
        'status',
        'is_emi_enabled',
        'remarks',
        'is_active'
    ];
    
    // Define castable fields for proper type conversion
    protected $casts = [
        'is_emi_enabled' => 'boolean',
        'is_active' => 'boolean',
        'start_date' => 'date',
        'end_date' => 'date',
    ];
    
    // Define dates to be treated as Carbon instances
    protected $dates = ['start_date', 'end_date'];

    public function loanSchemeDetails(){
        return $this->hasMany(Ec07LoanSchemeDetail::class, 'loan_scheme_id', 'id');
        // 'loan_scheme_id' is the Foreign Key
        // 'id' is the Local Key
    }
}
