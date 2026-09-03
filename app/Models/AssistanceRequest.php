<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssistanceRequest extends Model
{
    protected $fillable = [

        'request_id',

        'user_id',
        'resident_id',

        'income_source',
        'occupation',
        'monthly_income',
        'business_type',
        'business_duration',

        'assistance_type',
        'addressed_to',

        'remarks',

        'status',
        'processed_by',
        'processed_at',
        'processing_notes',
        'rejection_reason',
    ];

    protected function casts(): array
    {
        return [

            'monthly_income' => 'decimal:2',

            'addressed_to' => 'array',

            'processed_at' => 'datetime',
        ];
    }


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }


    public function resident(): BelongsTo
    {
        return $this->belongsTo(Resident::class);
    }


    public function processor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by');
    }
}