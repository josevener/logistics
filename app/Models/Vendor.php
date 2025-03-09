<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    /** @use HasFactory<\Database\Factories\VendorFactory> */
    use HasFactory;

    protected $fillable = ['contact_info', 'user_id', 'rating'];

    public function contracts()
    {
        return $this->hasMany(Contract::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
