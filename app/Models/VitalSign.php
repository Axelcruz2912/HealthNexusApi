<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VitalSign extends Model
{
    protected $fillable = [
        'triage_id', 'nurse_id', 'ta', 'fc', 'temp', 'spo2', 'fr', 'glucose',
        'pain_scale', 'notes', 'is_critical',
    ];

    public function triage() { return $this->belongsTo(Triage::class); }
    public function nurse() { return $this->belongsTo(User::class, 'nurse_id'); }
}
