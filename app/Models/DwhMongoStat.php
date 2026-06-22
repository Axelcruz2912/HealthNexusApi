<?php
namespace App\Models;

use MongoDB\Laravel\Eloquent\Model; // Eloquent especial para MongoDB

class DwhMongoStat extends Model {
    protected $connection = 'mongodb'; // ¡Conexión a Atlas!
    protected $collection = 'dwh_triage_analytics'; // Colección en la nube
    
    protected $fillable = [
        'fecha', 'triage_level', 'total_pacientes', 
        'mean_age', 'median_age', 'mode_age', 'std_dev_age',
        'min_age', 'max_age', 'cv_age',
        'avg_fc', 'avg_temp', 'avg_spo2',
        'outliers', 'partition_ml', 'etl_timestamp'
    ];
}
