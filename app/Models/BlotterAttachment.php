<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlotterAttachment extends Model
{
    protected $fillable = [
        'blotter_record_id',
        'file_path',
        'original_name',
        'mime_type',
        'file_size',
    ];

    public function blotterRecord()
    {
        return $this->belongsTo(BlotterRecord::class);
    }
}