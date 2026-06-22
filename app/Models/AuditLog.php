<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class AuditLog extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'audit_logs';

    protected $fillable = [
        'user_id',
        'user_name',
        'user_role',
        'action',        // Ej: LOGIN, MODIFICACIÓN STOCK, DERIVACIÓN
        'module',        // Ej: Farmacia, Urgencias
        'ip_address',
        'details',       // Descripción humana
        'is_suspicious', // Booleano para alertas
        'risk_reason',   // Por qué es sospechoso
        'user_agent'     // Navegador/Dispositivo
    ];
}
