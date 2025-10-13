<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ec03OrganisationOfficial extends Model
{
    use HasFactory;
    protected $table = 'ec03_organisation_officials';
    protected $guarded = ['id'];
    
    public function organisation()
    {
        return $this->belongsTo(Ec01Organisation::class, 'organisation_id');
    }
    
    public function member()
    {
        return $this->belongsTo(Ec04Member::class, 'member_id');
    }
}
