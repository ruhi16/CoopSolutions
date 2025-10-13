<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ec04Member extends Model
{
    use HasFactory;
    protected $table = 'ec04_members';
    protected $guarded = ['id'];

    /**
     * Get the member type for this member
     */
    public function memberType(): BelongsTo
    {
        return $this->belongsTo(Ec04MemberType::class, 'member_type_id');
    }

    /**
     * Get the loan requests for this member
     */
    public function loanRequests()
    {
        return $this->hasMany(Ec08LoanRequest::class, 'member_id', 'id');
    }
}