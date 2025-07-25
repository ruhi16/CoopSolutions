<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ec07LoanSchemeDetail extends Model
{
    use HasFactory;
    protected $table = 'ec07_loan_scheme_details';
    protected $guarded = ['id'];


    public function loanScheme(){
        return $this->belongsTo(Ec06LoanScheme::class,'loan_scheme_id', 'id');
        
    }

    public function loanSchemeFeature(){
        return $this->belongsTo(Ec07LoanSchemeFeature::class, 'loan_scheme_feature_id', 'id');

    }
}
