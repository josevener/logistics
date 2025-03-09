<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Proposal extends Model
{
    protected $fillable = [
        'user_id',
        'company_info',
        'contact_details',
        'purpose',
        'documentation_path',
        'status',
        'admin_status',
        'actioned_by',
        'approved_by',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
