<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Prescription extends Model {
    protected $fillable = ['patient_id', 'doctor_id', 'medication_id', 'quantity', 'status', 'denial_reason', 'is_priority'];
}
