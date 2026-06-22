<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MedicalAlert extends Model
{
    protected $fillable = [
        'triage_id', 'nurse_id', 'doctor_id', 'type', 'severity',
        'message', 'is_read', 'read_at',
    ];

    public function triage() { return $this->belongsTo(Triage::class); }
    public function nurse() { return $this->belongsTo(User::class, 'nurse_id'); }
    public function doctor() { return $this->belongsTo(User::class, 'doctor_id'); }
}
