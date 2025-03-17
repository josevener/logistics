<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'fname',
        'mname',
        'lname',
        'email',
        'address',
        'contact_info',
        'name',
    ];

    /**
     * Set the name attribute by concatenating first, middle, and last names.
     */
    public function setNameAttribute()
    {
        $this->attributes['name'] = $this->fname . ' ' . ($this->mname ? $this->mname . ' ' : '') . $this->lname;
    }
}
