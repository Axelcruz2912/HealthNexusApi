<?php
namespace App\Models;
use MongoDB\Laravel\Eloquent\Model;

class MongoBillingLog extends Model {
    protected $connection = 'mongodb';
    protected $collection = 'billing_events';
    protected $fillable = ['invoice_id', 'patient_id', 'amount', 'status', 'timestamp'];
}
