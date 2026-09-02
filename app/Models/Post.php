<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Post extends Model
{
    protected $fillable = [
        'created_by',
        'type',
        'category',
        'title',
        'slug',
        'excerpt',
        'content',
        'image',
        'status',
        'published_at',
        'is_pinned',

        // Event 
        'is_event',
        'event_date',
        'event_end_date',
        'event_time',
        'event_location',
    ];

    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
            'is_pinned' => 'boolean',
            'is_event' => 'boolean',
             
            'event_date' => 'date', 
            'event_end_date' => 'date',
        ];
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}