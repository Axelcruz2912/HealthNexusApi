<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hospitalization extends Model
{
    protected $fillable = [
        'triage_id', 'bed_id', 'patient_id', 'patient_name', 'doctor_id', 'nurse_id', 'admission_date',
        'discharge_date', 'diagnosis', 'diagnostico', 'status', 'notes',
    ];

    public function triage() { return $this->belongsTo(Triage::class); }
    public function bed() { return $this->belongsTo(Bed::class); }
    public function doctor() { return $this->belongsTo(User::class, 'doctor_id'); }
    public function nurse() { return $this->belongsTo(User::class, 'nurse_id'); }
}
