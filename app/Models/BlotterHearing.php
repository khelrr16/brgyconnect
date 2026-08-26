<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlotterHearing extends Model
{
    protected $fillable = [
        'blotter_record_id',
        'hearing_date',
        'hearing_time',
        'hearing_type',
        'status',
        'notes',
    ];

    protected $casts = [
        'hearing_date' => 'date',
    ];

    public function blotterRecord()
    {
        return $this->belongsTo(BlotterRecord::class);
    }
}