<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ec06LoanScheme extends Model
{
    use HasFactory;
    protected $table = 'ec06_loan_schemes';
    protected $guarded = ['id'];

    public function loanSchemeDetails(){
        return $this->hasMany(Ec07LoanSchemeDetail::class, 'loan_scheme_id', 'id');
        // 'loan_scheme_id' is the Foreign Key
        // 'id' is the Local Key
    }
}
