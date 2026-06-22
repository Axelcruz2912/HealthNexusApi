<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NurseEvolution extends Model
{
    protected $fillable = [
        'triage_id', 'nurse_id', 'patient_name', 'observation', 'intervention', 'response', 'priority', 'alert_doctor',
    ];

    public function triage() { return $this->belongsTo(Triage::class); }
    public function nurse() { return $this->belongsTo(User::class, 'nurse_id'); }
}
