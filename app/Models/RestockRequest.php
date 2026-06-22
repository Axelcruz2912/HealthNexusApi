<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RestockRequest extends Model
{
    protected $fillable = [
        'request_number', 'medication_id', 'quantity_requested', 'quantity_approved',
        'priority', 'status', 'requested_by', 'approved_by', 'reason', 'notes', 'required_by'
    ];

    protected $casts = ['required_by' => 'date'];

    public function medication() { return $this->belongsTo(Medication::class); }
    public function requester() { return $this->belongsTo(User::class, 'requested_by'); }
    public function approver() { return $this->belongsTo(User::class, 'approved_by'); }

    public function getPriorityColorAttribute() {
        return [
            'Baja' => '#2D9E6A',
            'Media' => '#3B82F6',
            'Alta' => '#FF8C42',
            'Critica' => '#C7291C',
        ][$this->priority] ?? '#736860';
    }

    public function getStatusColorAttribute() {
        return [
            'Solicitada' => '#3B82F6',
            'Aprobada' => '#FF8C42',
            'Orden Generada' => '#736860',
            'Recibida' => '#2D9E6A',
            'Cancelada' => '#C7291C',
        ][$this->status] ?? '#736860';
    }
}
