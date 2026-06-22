<?php
namespace App\Models;
use MongoDB\Laravel\Eloquent\Model;

class MongoMedicationLog extends Model {
    protected $connection = 'mongodb';
    protected $collection = 'medication_dispense_logs';
    protected $fillable = ['patient_id', 'medication_name', 'dosage', 'administered_at', 'nurse_id'];
}
