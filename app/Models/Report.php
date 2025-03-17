<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'rating',
        'comment',
        'follow_up',
        'report_by',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'rating' => 'integer',
        'follow_up' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user who submitted the report.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'report_by')->withDefault([
            'name' => 'Anonymous'
        ]);
    }

    /**
     * Format the created_at date.
     */
    public function formattedDate()
    {
        return $this->created_at->format('F d, Y');
    }
}
