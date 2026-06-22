@extends('medico.layout')
@section('title', 'Ambulancias y Traslados')

@section('content')
<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:1rem;margin-bottom:2rem">
    <div style="background:white;border-radius:12px;padding:1.2rem;box-shadow:0 2px 8px rgba(0,0,0,0.04);border-top:3px solid #16A34A">
        <div style="font-size:1.6rem;font-weight:900;color:#16A34A">{{ $disponibles }}</div>
        <div style="font-size:0.75rem;font-weight:700;color:#736860">Disponibles</div>
    </div>
    <div style="background:white;border-radius:12px;padding:1.2rem;box-shadow:0 2px 8px rgba(0,0,0,0.04);border-top:3px solid #EA580C">
        <div style="font-size:1.6rem;font-weight:900;color:#EA580C">{{ $activas }}</div>
        <div style="font-size:0.75rem;font-weight:700;color:#736860">En Ruta</div>
    </div>
    <div style="background:white;border-radius:12px;padding:1.2rem;box-shadow:0 2px 8px rgba(0,0,0,0.04);border-top:3px solid #7C3AED">
        <div style="font-size:1.6rem;font-weight:900;color:#7C3AED">{{ $misTraslados }}</div>
        <div style="font-size:0.75rem;font-weight:700;color:#736860">Mis Traslados</div>
    </div>
    <div style="background:white;border-radius:12px;padding:1.2rem;box-shadow:0 2px 8px rgba(0,0,0,0.04);border-top:3px solid #DC2626">
        <div style="font-size:1.6rem;font-weight:900;color:#DC2626">{{ $criticosPendientes }}</div>
        <div style="font-size:0.75rem;font-weight:700;color:#736860">Criticos Pendientes</div>
    </div>
</div>

<div style="background:white;border-radius:12px;padding:1.5rem;box-shadow:0 2px 8px rgba(0,0,0,0.04);margin-bottom:2rem">
    <h3 style="font-weight:800;color:#1C1917;margin-bottom:1rem"><i class="fas fa-truck-medical" style="color:#EA580C"></i> Flota de Ambulancias</h3>
    <div style="overflow-x:auto">
        <table style="width:100%;border-collapse:collapse;font-size:0.82rem">
            <thead>
                <tr style="background:#FFF7ED">
                    <th style="padding:0.6rem;text-align:left;font-weight:700;color:#9A3412">Unidad</th>
                    <th style="padding:0.6rem;text-align:left;font-weight:700;color:#9A3412">Estado</th>
                    <th style="padding:0.6rem;text-align:left;font-weight:700;color:#9A3412">Prioridad</th>
                    <th style="padding:0.6rem;text-align:left;font-weight:700;color:#9A3412">Ubicacion</th>
                    <th style="padding:0.6rem;text-align:left;font-weight:700;color:#9A3412">Destino</th>
                </tr>
            </thead>
            <tbody>
                @foreach($ambulancias as $amb)
                @php
                    $statusBg = $amb->status === 'Disponible' ? '#F0FDF4' : ($amb->status === 'En Ruta' ? '#FFF7ED' : '#FEF2F2');
                    $statusColor = $amb->status === 'Disponible' ? '#16A34A' : ($amb->status === 'En Ruta' ? '#EA580C' : '#DC2626');
                    $prioColor = $amb->priority === 'Critica' ? '#DC2626' : ($amb->priority === 'Alta' ? '#F59E0B' : '#6B7280');
                @endphp
                <tr style="border-bottom:1px solid #F5F5F4">
                    <td style="padding:0.6rem;font-weight:700;color:#1C1917"><i class="fas fa-ambulance" style="color:{{ $statusColor }};margin-right:6px"></i>{{ $amb->code or 'AMB-'.str_pad($amb->id,3,'0',STR_PAD_LEFT) }}</td>
                    <td style="padding:0.6rem"><span style="background:{{ $statusBg }};color:{{ $statusColor }};padding:0.15rem 0.5rem;border-radius:8px;font-size:0.7rem;font-weight:700">{{ $amb->status }}</span></td>
                    <td style="padding:0.6rem"><span style="color:{{ $prioColor }};font-weight:700;font-size:0.75rem">{{ $amb->priority or 'Normal' }}</span></td>
                    <td style="padding:0.6rem;color:#736860">{{ $amb->current_location or 'Base' }}</td>
                    <td style="padding:0.6rem;color:#736860">{{ $amb->destination or '-' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@if($isA)
<div style="background:white;border-radius:12px;padding:1.5rem;box-shadow:0 2px 8px rgba(0,0,0,0.04)">
    <h3 style="font-weight:800;color:#DC2626;margin-bottom:1rem"><i class="fas fa-exclamation-triangle"></i> Mis Pacientes en Traslado</h3>
    @if($misPacientesTraslado->count() > 0)
    @foreach($misPacientesTraslado as $p)
    <div style="border:1px solid #FCA5A5;border-radius:8px;padding:0.8rem;margin-bottom:0.5rem;background:#FEF2F2">
        <div style="font-weight:700;color:#1C1917">{{ $p->patient_name or 'Paciente #'.$p->id }}</div>
        <div style="font-size:0.75rem;color:#736860">Triage: <span style="color:#DC2626;font-weight:700">{{ $p->triage_level }}</span> | Destino: {{ $p->destination or 'Pendiente' }}</div>
    </div>
    @endforeach
    @else
    <p style="text-align:center;color:#A8A29E;padding:1rem;font-weight:600">Sin pacientes en traslado actualmente</p>
    @endif
</div>
@endif
@endsection
