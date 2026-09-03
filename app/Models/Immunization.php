<?php

namespace App\Models;

use App\Models\VaccineDose;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Immunization extends Model
{
    use HasFactory;

    protected $fillable = [
        'family_id',
        'resident_id',
        'parent_first_name',
        'parent_last_name',
        'parent_middle_name',
        'block',
        'lot',
        'unit',
        'street',
        'subdivision',
        'infant_first_name',
        'infant_last_name',
        'infant_middle_name',
        'birthday',
        'sex',
        'low_birth_weight',
        'breastfeeding_initiated',
        'breastfeeding_initiated_date',
        'exclusive_bf_5m29d',
        'exclusive_bf_date',
        'complementary_feeding_status',
        'vitamin_a_date',
        'mnp_90_sachets_date',
        'mnp_completed_date',
        'fic_date',
        'cic_date',
    ];

    protected static function booted(): void
    {
        static::creating(function (Immunization $immunization): void {
            if (! empty($immunization->family_id)) {
                return;
            }

            $lastFamilyId = static::query()
                ->where('family_id', 'like', 'FF-%')
                ->orderByDesc('id')
                ->value('family_id');

            $nextNumber = 1;

            if ($lastFamilyId && preg_match('/(\d+)$/', $lastFamilyId, $matches)) {
                $nextNumber = ((int) $matches[1]) + 1;
            }

            $immunization->family_id = 'FF-' . str_pad($nextNumber, 6, '0', STR_PAD_LEFT);
        });
    }

    protected $casts = [
        'birthday'                      => 'date',
        'low_birth_weight'              => 'boolean',
        'breastfeeding_initiated'       => 'boolean',
        'breastfeeding_initiated_date'  => 'date',
        'exclusive_bf_5m29d'            => 'boolean',
        'exclusive_bf_date'             => 'date',
        'vitamin_a_date'                => 'date',
        'mnp_90_sachets_date'           => 'date',
        'mnp_completed_date'            => 'date',
        'fic_date'                      => 'date',
        'cic_date'                      => 'date',
    ];

    // --- Relationships ---

    public function resident()
    {
        return $this->belongsTo(Resident::class);
    }

    public function nutritionalAssessments()
    {
        return $this->hasMany(NutritionalAssessment::class);
    }

    public function vaccineDoses()
    {
        return $this->hasMany(VaccineDose::class);
    }

    // --- Accessors ---

    public function getAgeAttribute(): string
    {
        return $this->birthday?->diffForHumans(null, true) ?? 'Unknown';
    }

    public function getFullNameAttribute(): string
    {
        return trim("{$this->infant_first_name} {$this->infant_middle_name} {$this->infant_last_name}");
    }

    public function getIsFullyImmunizedAttribute(): bool
    {
        return $this->fic_date !== null;
    }

    public function getCompletionStatusAttribute(): string
    {
        if ($this->cic_date !== null) {
            return 'CIC';
        }

        if ($this->fic_date !== null) {
            return 'FIC';
        }

        return 'In progress';
    }
}