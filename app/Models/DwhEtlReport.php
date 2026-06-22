<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class DwhEtlReport extends Model {
    protected $table = 'dwh_etl_reports';
    
    protected $fillable = [
        'initial_records', 
        'nulls_removed', 
        'duplicates_removed', 
        'outliers_removed', 
        'quality_percentage', 
        'data_loss_percentage'
    ];
    
    public $timestamps = true;
}
