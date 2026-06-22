@extends('superadmin.layout')
@section('title', 'Control de Ambulancias')

@section('content')
<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:1rem;margin-bottom:2rem">
    <div style="background:white;border-radius:12px;padding:1.2rem;box-shadow:0 2px 8px rgba(0,0,0,0.04);border-top:3px solid #16A34A">
        <div style="font-size:1.8rem;font-weight:900;color:#16A34A">{{ $disponibles }}</div>
        <div style="font-size:0.75rem;font-weight:700;color:#736860">Disponibles</div>
    </div>
    <div style="background:white;border-radius:12px;padding:1.2rem;box-shadow:0 2px 8px rgba(0,0,0,0.04);border-top:3px solid #EA580C">
        <div style="font-size:1.8rem;font-weight:900;color:#EA580C">{{ $activas }}</div>
        <div style="font-size:0.75rem;font-weight:700;color:#736860">En Ruta</div>
    </div>
    <div style="background:white;border-radius:12px;padding:1.2rem;box-shadow:0 2px 8px rgba(0,0,0,0.04);border-top:3px solid #DC2626">
        <div style="font-size:1.8rem;font-weight:900;color:#DC2626">{{ $criticas }}</div>
        <div style="font-size:0.75rem;font-weight:700;color:#736860">Criticas</div>
    </div>
    <div style="background:white;border-radius:12px;padding:1.2rem;box-shadow:0 2px 8px rgba(0,0,0,0.04);border-top:3px solid #7C3AED">
        <div style="font-size:1.8rem;font-weight:900;color:#7C3AED">{{ $total }}</div>
        <div style="font-size:0.75rem;font-weight:700;color:#736860">Total Flota</div>
    </div>
</div>

<div style="background:white;border-radius:12px;padding:2rem;box-shadow:0 2px 8px rgba(0,0,0,0.04);margin-bottom:2rem">
    <h3 style="font-weight:800;color:#1E1A17;margin-bottom:1.5rem"><i class="fas fa-truck-medical" style="color:#F05A4E"></i> Flota Completa - Control Administrativo</h3>
    <div style="overflow-x:auto">
        <table style="width:100%;border-collapse:collapse;font-size:0.85rem">
            <thead>
                <tr style="background:#FFF1EE">
                    <th style="padding:0.7rem;text-align:left;font-weight:700;color:#C7291C">Unidad</th>
                    <th style="padding:0.7rem;text-align:left;font-weight:700;color:#C7291C">Estado</th>
                    <th style="padding:0.7rem;text-align:left;font-weight:700;color:#C7291C">Prioridad</th>
                    <th style="padding:0.7rem;text-align:left;font-weight:700;color:#C7291C">Ubicacion</th>
                    <th style="padding:0.7rem;text-align:left;font-weight:700;color:#C7291C">Destino</th>
                    <th style="padding:0.7rem;text-align:left;font-weight:700;color:#C7291C">Costo Op.</th>
                </tr>
            </thead>
            <tbody>
                @foreach($ambulancias as $amb)
                @php
                    $sBg = $amb->status === 'Disponible' ? '#F0FDF4' : ($amb->status === 'En Ruta' ? '#FFF7ED' : '#FEF2F2');
                    $sC = $amb->status === 'Disponible' ? '#16A34A' : ($amb->status === 'En Ruta' ? '#EA580C' : '#DC2626');
                @endphp
                <tr style="border-bottom:1px solid #F5F5F4">
                    <td style="padding:0.6rem;font-weight:700;color:#1E1A17"><i class="fas fa-ambulance" style="color:{{ $sC }};margin-right:6px"></i>{{ $amb->code or 'AMB-'.str_pad($amb->id,3,'0',STR_PAD_LEFT) }}</td>
                    <td style="padding:0.6rem"><span style="background:{{ $sBg }};color:{{ $sC }};padding:0.15rem 0.5rem;border-radius:8px;font-size:0.7rem;font-weight:700">{{ $amb->status }}</span></td>
                    <td style="padding:0.6rem;font-weight:600;color:{{ $amb->priority === 'Critica' ? '#DC2626' : '#6B7280' }}">{{ $amb->priority or 'Normal' }}</td>
                    <td style="padding:0.6rem;color:#736860">{{ $amb->current_location or 'Base' }}</td>
                    <td style="padding:0.6rem;color:#736860">{{ $amb->destination or '-' }}</td>
                    <td style="padding:0.6rem;font-weight:700;color:#1E1A17">${{ number_format($amb->status === 'En Ruta' ? 2500 : 0) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div style="background:linear-gradient(135deg,#1E1A17,#374151);border-radius:12px;padding:1.5rem;color:white">
    <h4 style="font-weight:800;margin-bottom:0.8rem"><i class="fas fa-chart-line"></i> Resumen Financiero</h4>
    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:1rem">
        <div><div style="font-size:0.7rem;opacity:0.7">Costo Operativo Diario</div><div style="font-size:1.4rem;font-weight:900">${{ number_format($costoOperativo) }}</div></div>
        <div><div style="font-size:0.7rem;opacity:0.7">Unidades Activas</div><div style="font-size:1.4rem;font-weight:900">{{ $activas }}</div></div>
        <div><div style="font-size:0.7rem;opacity:0.7">Tasa de Uso</div><div style="font-size:1.4rem;font-weight:900">{{ $total > 0 ? round(($activas/$total)*100) : 0 }}%</div></div>
    </div>
</div>
@endsection
