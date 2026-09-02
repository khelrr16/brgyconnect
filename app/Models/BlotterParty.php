<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlotterParty extends Model
{
    protected $fillable = [
        'blotter_record_id',
        'resident_id',
        'role',
        'first_name',
        'middle_name',
        'last_name',
        'extension_name',
        'contact_number',
        'address',
    ];

    public function blotterRecord()
    {
        return $this->belongsTo(BlotterRecord::class);
    }

    public function resident()
    {
        return $this->belongsTo(Resident::class);
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
}