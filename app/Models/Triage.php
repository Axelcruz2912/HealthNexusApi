<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Triage extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_name', 'age', 'triage_level', 'symptoms', 'status',
        'vitals_ta', 'vitals_fc', 'vitals_temp', 'vitals_spo2',
        'assigned_area', 'is_derived', 'derived_to', 'derived_reason',
        'doctor_id', 'nurse_id',
    ];

    public function vitalSigns()
    {
        return $this->hasMany(VitalSign::class);
    }

    public function nurse()
    {
        return $this->belongsTo(User::class, 'nurse_id');
    }

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function hospitalization()
    {
        return $this->hasOne(Hospitalization::class);
    }

    public function evolutions()
    {
        return $this->hasMany(NurseEvolution::class);
    }

    


    protected static function booted()
    {
        static::created(function ($triage) {
            try {
                // ==========================================
                // ETL 1: CLIP DE OUTLIERS (Recorte en tiempo real)
                // Si el valor fisiológico es imposible, se ajusta al límite clínico
                // ==========================================
                $fc = max(40, min(200, $triage->vitals_fc ?? 80)); // FC entre 40 y 200
                $temp = max(35.0, min(42.0, $triage->vitals_temp ?? 36.5)); // Temp entre 35 y 42
                $spo2 = max(70, min(100, $triage->vitals_spo2 ?? 98)); // SpO2 entre 70 y 100

                // ==========================================
                // ETL 2: ESTANDARIZACIÓN DE TEXTO
                // Asegurar que Triage Level tenga formato "Rojo" en vez de "rojo" o "ROJO"
                // ==========================================
                $level = ucfirst(strtolower($triage->triage_level ?? 'verde'));

                // ==========================================
                // ETL 3: UPSERT EN MONGODB (Prevenir duplicados en la nube)
                // Si el paciente ya está en Atlas hoy, actualiza sus signos; si no, créalo
                // ==========================================
                \App\Models\MongoTriageLog::updateOrCreate(
                    [
                        'patient_id' => $triage->patient_name ?? 'Desconocido',
                        'timestamp' => $triage->created_at->startOfDay() // Agrupa por día
                    ],
                    [
                        'triage_level' => $level,
                        'age' => $triage->age ?? 0,
                        'specialty' => 'Urgencias',
                        'vitals_fc' => $fc,
                        'vitals_temp' => $temp,
                        'vitals_spo2' => $spo2,
                        'status' => $triage->status ?? 'En Espera',
                        'updated_at_dwh' => now()
                    ]
                );
            } catch (\Exception $e) {
                \Log::error("ETL MongoDB Error: " . $e->getMessage());
            }
        });
    }

}
