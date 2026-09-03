<?php

namespace App\Models;

use App\Models\BlotterRecord;
use App\Models\Immunization;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Resident extends Model
{
    use HasFactory;

    protected $fillable = [
        'household_id',
        'resident_id',
        'first_name',
        'middle_name',
        'last_name',
        'extension_name',
        'birth_date',
        'sex',
        'civil_status',
        'citizenship',
        'place_of_birth',
        'contact_number',
        'registered_voter',

        'house_ownership',
        'relationship_to_head',
        'residence_since',
        'educational_attainment',
        'out_of_school',
        'employment_status',
        'religion',
        'occupation',
        'is_ofw',
        'ofw_country',
        'is_pwd',
        'is_indigenous',
        'indigenous_group',
        'is_solo_parent',
    ];

    protected $casts = [
        'birth_date' => 'date',
    ];

    protected static function booted(): void
    {
        static::creating(function (Resident $resident) {

            if (empty($resident->resident_id)) {

                $lastId = static::query()
                    ->whereNotNull('resident_id')
                    ->orderByDesc('id')
                    ->value('resident_id');

                $nextNumber = 1;

                if ($lastId && preg_match('/(\d+)$/', $lastId, $matches)) {
                    $nextNumber = ((int) $matches[1]) + 1;
                }

                $resident->resident_id =
                    'RES-' . str_pad($nextNumber, 6, '0', STR_PAD_LEFT);
            }
        });
    }

    public function getFullNameAttribute()
    {
        $fullName = $this->first_name;

        if ($this->middle_name) {
            $fullName .= ' ' . $this->middle_name;
        }

        $fullName .= ' ' . $this->last_name;

        if ($this->extension_name) {
            $fullName .= ' ' . $this->extension_name;
        }

        return $fullName;
    }

    public function household(): BelongsTo 
    { 
        return $this->belongsTo(Household::class); 
    }

    public function immunizations()
    {
        return $this->hasMany(Immunization::class);
    }

    public function blotterRecords()
    {
        return $this->hasMany(BlotterRecord::class);
    }

    public function user(): HasOne
    {
        return $this->hasOne(User::class);
    }

    public function assistanceRequests(): HasMany
    {
        return $this->hasMany(AssistanceRequest::class);
    }
}
