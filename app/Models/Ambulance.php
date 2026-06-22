<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ambulance extends Model
{
    use HasFactory;

    protected $fillable = [
        'code', 'type', 'status', 'location',
        'latitude', 'longitude', 'driver_id', 'patient_id',
        'destination', 'origin', 'priority', 'notes',
        'iot_data', 'dispatched_at', 'arrived_at'
    ];

    protected $casts = [
        'iot_data' => 'array',
        'dispatched_at' => 'datetime',
        'arrived_at' => 'datetime',
    ];

    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    public function patient()
    {
        return $this->belongsTo(Triage::class, 'patient_id');
    }

    public function getSpeedAttribute()
    {
        return isset($this->iot_data['speed']) ? $this->iot_data['speed'] : 0;
    }

    public function getFuelAttribute()
    {
        return isset($this->iot_data['fuel']) ? $this->iot_data['fuel'] : 0;
    }

    public function getHeartRateAttribute()
    {
        return isset($this->iot_data['heart_rate']) ? $this->iot_data['heart_rate'] : 0;
    }

    public function getOxygenAttribute()
    {
        return isset($this->iot_data['oxygen']) ? $this->iot_data['oxygen'] : 0;
    }

    public function getTempAttribute()
    {
        return isset($this->iot_data['temperature']) ? $this->iot_data['temperature'] : 0;
    }
}
