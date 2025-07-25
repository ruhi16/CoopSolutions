<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ec07LoanSchemeFeature extends Model
{
    use HasFactory;
    protected $guarded = ['id', 'created_at', 'updated_at'];
    protected $tables = 'ec07_loan_scheme_features';


    public function loanSchemeDetails(){
        return $this->hasMany(Ec07LoanSchemeDetail::class, 'loan_scheme_feature_id', 'id');
        // 'loan_scheme_feature_id' is the Foreign Key
        // 'id' is the Local
    }
    

}
