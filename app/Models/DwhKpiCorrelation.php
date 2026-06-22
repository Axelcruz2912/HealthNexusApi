<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class DwhKpiCorrelation extends Model {
    protected $table = 'dwh_kpi_correlations';
    protected $fillable = ['metric_name','metric_type','value','unit','calculated_at'];
    
    // Desactivar timestamps automáticos de Laravel (usamos calculated_at)
    public $timestamps = false; 
}
