@extends('superadmin.layout')
@section('title', 'Detección de Actividad Sospechosa')
@section('nav-auditoria', 'active')

@section('content')
<div style="background: #C7291C; color: white; padding: 2rem; border-radius: 12px; margin-bottom: 2rem; display: flex; align-items: center; gap: 1.5rem;">
    <i class="fas fa-shield-alt" style="font-size: 3rem; opacity: 0.8;"></i>
    <div>
        <h3 style="font-weight: 800; margin-bottom: 0.5rem;">Centro de Ciberseguridad Hospitalaria</h3>
        <p style="opacity: 0.9;">Detección automática de anomalías, accesos indebidos y comportamientos de riesgo por perfil.</p>
    </div>
</div>

@if($suspicious->isEmpty())
<div style="background: #EBF9F2; padding: 2rem; border-radius: 12px; text-align: center; color: #065F46;">
    <i class="fas fa-check-circle" style="font-size: 2rem; margin-bottom: 1rem;"></i>
    <h3 style="font-weight: 800;">Sistema Limpio</h3>
    <p>No se ha detectado actividad sospechosa reciente.</p>
</div>
@else
<div style="background: white; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.04); overflow: hidden;">
    <table style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="background: #8C1A11; color: white; text-align: left;">
                <th style="padding: 1rem; font-size: 0.8rem;">Fecha</th>
                <th style="padding: 1rem; font-size: 0.8rem;">Usuario</th>
                <th style="padding: 1rem; font-size: 0.8rem;">Motivo de Alerta</th>
                <th style="padding: 1rem; font-size: 0.8rem;">Acción Ejecutada</th>
            </tr>
        </thead>
        <tbody>
            @foreach($suspicious as $log)
            <tr style="border-bottom: 1px solid #E5E7EB; background: #FFF1F0;">
                <td style="padding: 1rem; font-size: 0.85rem;">{{ \Carbon\Carbon::parse($log->created_at)->format('d/m/Y H:i:s') }}</td>
                <td style="padding: 1rem; font-weight: 700;">{{ $log->user_name }} <span style="font-size:0.7rem; color:#8C1A11;">({{ $log->user_role }})</span></td>
                <td style="padding: 1rem; color: #C7291C; font-weight: 700;"><i class="fas fa-exclamation-triangle"></i> {{ $log->risk_reason ?? 'Comportamiento anómalo' }}</td>
                <td style="padding: 1rem; font-size: 0.85rem;">{{ $log->details }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif
{{ $suspicious->withQueryString()->links() }}
@endsection
