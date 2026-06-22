<?php
namespace App\Models;
use MongoDB\Laravel\Eloquent\Model;

class MongoTriageLog extends Model {
    protected $connection = 'mongodb';
    protected $collection = 'triage_logs'; // Colección masiva en Atlas
    
    protected $fillable = [
        'patient_id', 'triage_level', 'age', 'specialty',
        'vitals_fc', 'vitals_temp', 'vitals_spo2',
        'assigned_doctor_id', 'is_derived', 'timestamp'
    ];
}
