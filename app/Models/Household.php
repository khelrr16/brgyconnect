<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Household extends Model
{
    use HasFactory;

    protected $fillable = [
        'household_id',
        'block',
        'lot',
        'unit',
        'street',
        'subdivision',
    ];
    
    protected static function booted(): void
    {
        static::creating(function (Household $household) {

            if (empty($household->household_id)) {

                $lastId = static::query()
                    ->whereNotNull('household_id')
                    ->orderByDesc('id')
                    ->value('household_id');

                $nextNumber = 1;

                if ($lastId && preg_match('/(\d+)$/', $lastId, $matches)) {
                    $nextNumber = ((int) $matches[1]) + 1;
                }

                $household->household_id =
                    'HH-' . str_pad($nextNumber, 6, '0', STR_PAD_LEFT);
            }
        });
    }

    public function residents(): HasMany
    {
        return $this->hasMany(Resident::class);
    }

    public function getFullAddressAttribute(): string
    {
        return collect([
            $this->block ? 'Block ' . $this->block : null,
            $this->lot ? 'Lot ' . $this->lot : null,
            $this->unit ? 'Unit ' . $this->unit : null,
            $this->street,
            $this->subdivision,
        ])->filter()->implode(', ');
    }
}