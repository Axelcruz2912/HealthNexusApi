<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class PatientMedication extends Model {
    protected $fillable = ['triage_id', 'patient_name', 'medication_id', 'medication_name', 'quantity', 'dispensed_by', 'prescribed_by', 'interaction_alert', 'interaction_details'];
    public function triage() { return $this->belongsTo(Triage::class); }
    public function medication() { return $this->belongsTo(Medication::class); }
    public function dispenser() { return $this->belongsTo(User::class, 'dispensed_by'); }
    public function prescriber() { return $this->belongsTo(User::class, 'prescribed_by'); }
}
