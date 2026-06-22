<?php
namespace App\Models;
use MongoDB\Laravel\Eloquent\Model;

class MongoHospitalizationLog extends Model {
    protected $connection = 'mongodb';
    protected $collection = 'hospitalization_logs';
    protected $fillable = ['patient_id', 'bed_number', 'admission_date', 'discharge_date', 'status'];
}
