<?php
namespace App\Models;
use MongoDB\Laravel\Eloquent\Model;

class MongoDoctorLog extends Model {
    protected $connection = 'mongodb';
    protected $collection = 'doctor_activity_logs';
    protected $fillable = ['doctor_id', 'action', 'patient_id', 'timestamp', 'specialty'];
}
