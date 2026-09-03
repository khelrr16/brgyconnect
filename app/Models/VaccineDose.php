<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VaccineDose extends Model
{
    use HasFactory;

    protected $fillable = [
        'immunization_id',
        'vaccine',
        'dose_number',
        'date_given',
    ];

    protected $casts = [
        'date_given' => 'date',
    ];

    public function immunization()
    {
        return $this->belongsTo(Immunization::class);
    }

    public function getIsGivenAttribute(): bool
    {
        return $this->date_given !== null;
    }
}