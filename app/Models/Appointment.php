<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $fillable = ['patient_id', 'doctor_id', 'specialty_id', 'created_by', 'scheduled_at', 'estimated_end', 'type', 'status', 'priority', 'location', 'notes', 'reminders', 'metadata'];
    
    protected $casts = [
        'scheduled_at' => 'datetime',
        'estimated_end' => 'datetime',
        'reminders' => 'array',
        'metadata' => 'array',
    ];
    
    public function patient()
    {
        return $this->belongsTo(Triage::class, 'patient_id');
    }
    
    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }
    
    public function specialty()
    {
        return $this->belongsTo(Specialty::class);
    }
    
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
