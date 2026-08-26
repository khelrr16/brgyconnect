<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlotterRecord extends Model
{
    protected $fillable = [
        'blotter_number',
        'resident_id',
        'created_by',
        'incident_date',
        'incident_time',
        'incident_type',
        'incident_location',
        'complainant_name',
        'respondent_name',
        'incident_description',
        'action_taken',
        'status',
        'remarks',
    ];

    protected $casts = [
        'incident_date' => 'date',
    ];

    public function resident()
    {
        return $this->belongsTo(Resident::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function parties()
    {
        return $this->hasMany(BlotterParty::class);
    }

    public function attachments()
    {
        return $this->hasMany(BlotterAttachment::class);
    }

    public function hearings()
    {
        return $this->hasMany(BlotterHearing::class);
    }
}
