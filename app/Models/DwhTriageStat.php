<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class DwhTriageStat extends Model {
    protected $table = 'dwh_triage_stats';
    
    // Agregar TODOS los campos, incluyendo los nuevos de Signos Vitales
    protected $fillable = [
        'fecha', 'triage_level', 'total_pacientes', 'tiempo_espera_promedio', 'desviacion_espera', 
        'min_wait', 'max_wait', 'cv_wait', 
        'avg_fc', 'avg_temp', 'avg_spo2', // ¡Nuevos!
        'outliers_detectados', 'percentiles', 'p10', 'p90', 'p99', 
        'correlation_triage_wait', 'dataset_partition', 'raw_document'
    ];
    
    protected $casts = ['percentiles' => 'array', 'raw_document' => 'array'];
}
