<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Derivation extends Model
{
    protected $fillable = ['patient_id', 'from_doctor_id', 'to_doctor_id', 'specialty_id', 'reason', 'status', 'priority', 'notes', 'responded_at'];

    protected $casts = ['responded_at' => 'datetime'];

    public function patient() { return $this->belongsTo(Triage::class, 'patient_id'); }
    public function fromDoctor() { return $this->belongsTo(User::class, 'from_doctor_id'); }
    public function toDoctor() { return $this->belongsTo(User::class, 'to_doctor_id'); }
    public function specialty() { return $this->belongsTo(Specialty::class); }
}
