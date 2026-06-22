<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class FactTriage extends Model { protected $fillable = ['fecha', 'dim_doctor_id', 'triage_level', 'original_age', 'imputed_age', 'was_imputed', 'was_capped']; }
