<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ec08LoanRequest extends Model
{
    use HasFactory;
    protected $table = 'ec08_loan_requests';
    protected $guarded = ['id'];
    
    // cast the 'req_date' in datetime
    protected $casts = [
        'req_date' => 'datetime', // or 'date'
        // Add other date fields if needed
    ];

    // Then the accessor will work
    public function getReqDateAttribute($value){
        if (!$value) return null;

        try {
            return \Carbon\Carbon::parse($value)->format('Y-m-d');
        } catch (\Exception $e) {
            return $value; // return original value if parsing fails
        }       
        
        // return $value ? $value->format('Y-m-d') : null;
        // Or any other format you prefer
        // return $value ? $value->format('d/m/Y') : null;
    }


    // This mutator will capitalize the name before saving
    // public function setStatusAttribute($value){
    //     $this->attributes['status'] = strtoupper($value);
    // }

    public function member(){
        return $this->belongsTo(Ec04Member::class,'member_id','id');

    }

    public function loanScheme(){
        return $this->belongsTo(Ec06LoanScheme::class,'req_loan_scheme_id','id');
    }

    public function loanAssign(){
        return $this->hasOne(Ec08LoanAssign::class,'loan_request_id','id');
    }

    public function getEmiAmountAttribute(){
        $loanAssign = $this->loanAssign;
        return $loanAssign ? $loanAssign->emi_amount : 'N/A';
    }



}
