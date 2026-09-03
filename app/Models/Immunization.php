<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Immunization extends Model
{
    use HasFactory;

    protected $fillable = [
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
        $required = collect(config('immunization'))
            ->flatMap(fn ($vaccines) => collect($vaccines)->map(
                fn ($doses, $vaccine) => ['vaccine' => $vaccine, 'doses' => $doses]
            ));

        return $required->every(function ($r) {
            return $this->vaccineDoses
                ->where('vaccine', $r['vaccine'])
                ->whereNotNull('date_given')
                ->count() >= $r['doses'];
        });
    }
}