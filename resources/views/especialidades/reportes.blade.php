@extends('especialidades.layout')

@section('content')
<div style="padding:1.5rem">
    <div style="margin-bottom:1.5rem">
        <h2 style="font-weight:900;color:#9A3412"><i class="fas fa-chart-bar" style="color:#EA580C"></i> Reportes</h2>
    </div>

    <!-- STATS -->
    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:1rem;margin-bottom:1.5rem">
        <div style="background:linear-gradient(135deg,#2563EB,#1D4ED8);border-radius:14px;padding:1.2rem;color:white;text-align:center">
            <div style="font-size:2rem;font-weight:900">{{ $pacientesAtendidos }}</div>
            <div style="font-size:0.75rem;font-weight:800;opacity:0.9">Pacientes Atendidos</div>
        </div>
        <div style="background:linear-gradient(135deg,#16A34A,#15803D);border-radius:14px;padding:1.2rem;color:white;text-align:center">
            <div style="font-size:2rem;font-weight:900">{{ $pacientesActivos }}</div>
            <div style="font-size:0.75rem;font-weight:800;opacity:0.9">Pacientes Activos</div>
        </div>
        <div style="background:linear-gradient(135deg,#DC2626,#B91C1C);border-radius:14px;padding:1.2rem;color:white;text-align:center">
            <div style="font-size:2rem;font-weight:900">{{ $criticosAtendidos }}</div>
            <div style="font-size:0.75rem;font-weight:800;opacity:0.9">Criticos Atendidos</div>
        </div>
        <div style="background:linear-gradient(135deg,#7C3AED,#6D28D9);border-radius:14px;padding:1.2rem;color:white;text-align:center">
            <div style="font-size:2rem;font-weight:900">{{ $derivacionesEnviadas }}</div>
            <div style="font-size:0.75rem;font-weight:800;opacity:0.9">Derivaciones Enviadas</div>
        </div>
    </div>

    <!-- DERIVACIONES STATS -->
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;margin-bottom:1.5rem">
        <div style="background:white;border-radius:16px;padding:1.5rem;box-shadow:0 2px 8px rgba(0,0,0,0.05);border-top:4px solid #16A34A">
            <h3 style="font-weight:900;color:#16A34A;margin-bottom:1rem"><i class="fas fa-check-circle"></i> Derivaciones Aceptadas</h3>
            <div style="text-align:center;padding:1rem">
                <span style="font-size:3rem;font-weight:900;color:#16A34A">{{ $derivacionesAceptadas }}</span>
            </div>
        </div>
        <div style="background:white;border-radius:16px;padding:1.5rem;box-shadow:0 2px 8px rgba(0,0,0,0.05);border-top:4px solid #DC2626">
            <h3 style="font-weight:900;color:#DC2626;margin-bottom:1rem"><i class="fas fa-times-circle"></i> Derivaciones Rechazadas</h3>
            <div style="text-align:center;padding:1rem">
                <span style="font-size:3rem;font-weight:900;color:#DC2626">{{ $derivacionesRechazadas }}</span>
            </div>
        </div>
    </div>

    <!-- ACTIVIDAD RECIENTE -->
    <div style="background:white;border-radius:16px;padding:1.5rem;box-shadow:0 2px 8px rgba(0,0,0,0.05);border-top:4px solid #F97316">
        <h3 style="font-weight:900;color:#9A3412;margin-bottom:1rem"><i class="fas fa-clock"></i> Actividad Reciente</h3>
        @if($logs->count() > 0)
        <table style="width:100%;border-collapse:collapse;font-size:0.82rem">
            <thead><tr style="background:#FFFBEB"><th style="padding:0.5rem;text-align:left;color:#92400E">Fecha</th><th style="padding:0.5rem;color:#92400E">Accion</th><th style="padding:0.5rem;color:#92400E">Modulo</th><th style="padding:0.5rem;color:#92400E">Detalle</th></tr></thead>
            <tbody>
            @foreach($logs as $log)
            <tr style="border-bottom:1px solid #FEF3C7">
                <td style="padding:0.5rem;color:#92400E;font-size:0.75rem">{{ $log->created_at->format('d/m H:i') }}</td>
                <td style="padding:0.5rem;font-weight:700">{{ $log->action }}</td>
                <td style="padding:0.5rem"><span style="background:#FFEDD5;color:#EA580C;padding:0.15rem 0.5rem;border-radius:4px;font-size:0.7rem;font-weight:800">{{ $log->module }}</span></td>
                <td style="padding:0.5rem;color:#78716C">{{ Str::limit($log->details, 50) }}</td>
            </tr>
            @endforeach
            </tbody>
        </table>
        @else
        <p style="text-align:center;color:#D97706;padding:1.5rem;font-weight:700">Sin actividad registrada</p>
        @endif
    </div>
</div>
@endsection
