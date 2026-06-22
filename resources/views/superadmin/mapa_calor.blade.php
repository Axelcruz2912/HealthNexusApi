@extends('superadmin.layout')
@section('title', 'Mapa de Calor Hospitalario')
@section('nav-mapa', 'active')

@section('content')
<div style="background: white; padding: 1.5rem; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.04); margin-bottom: 1.5rem;">
    <h3 style="font-weight: 800; color: #1E1A17;">Saturación en Tiempo Real</h3>
    <p style="color: #736860; font-size: 0.85rem;">Visualización de zonas críticas basada en ocupación de camas y urgencias.</p>
</div>

<div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1.5rem;">
    <div style="background: {{ $uci_percent > 80 ? '#C7291C' : ($uci_percent > 50 ? '#FF8C42' : '#2D9E6A') }}; padding: 2rem; border-radius: 12px; color: white; text-align: center;">
        <h2 style="font-size: 3rem; font-weight: 800;">{{ $uci_percent }}%</h2>
        <h4 style="font-weight: 700; text-transform: uppercase;">UCI - Saturación</h4>
        <p style="opacity: 0.9; font-size: 0.85rem; margin-top:0.5rem;">{{ $uci_percent == 100 ? 'SIN CAMAS DISPONIBLES' : 'Operación Normal' }}</p>
    </div>
    
    <div style="background: {{ $urg_percent > 80 ? '#C7291C' : ($urg_percent > 50 ? '#FF8C42' : '#2D9E6A') }}; padding: 2rem; border-radius: 12px; color: white; text-align: center;">
        <h2 style="font-size: 3rem; font-weight: 800;">{{ $urg_percent }}%</h2>
        <h4 style="font-weight: 700; text-transform: uppercase;">Urgencias - Triage</h4>
        <p style="opacity: 0.9; font-size: 0.85rem; margin-top:0.5rem;">{{ $critical_urgencies }} pacientes críticos (Rojo/Naranja)</p>
    </div>

    <div style="background: {{ $farmacia_alerts > 0 ? '#C7291C' : '#2D9E6A' }}; padding: 2rem; border-radius: 12px; color: white; text-align: center;">
        <h2 style="font-size: 3rem; font-weight: 800;">{{ $farmacia_alerts }}</h2>
        <h4 style="font-weight: 700; text-transform: uppercase;">Farmacia - Desabasto</h4>
        <p style="opacity: 0.9; font-size: 0.85rem; margin-top:0.5rem;">Medicamentos por debajo del mínimo</p>
    </div>

    <div style="background: #1E1A17; padding: 2rem; border-radius: 12px; color: white; text-align: center;">
        <h2 style="font-size: 3rem; font-weight: 800;">{{ $total_personal }}</h2>
        <h4 style="font-weight: 700; text-transform: uppercase;">Personal Activo</h4>
        <p style="opacity: 0.9; font-size: 0.85rem; margin-top:0.5rem;">En turno actualmente</p>
    </div>
</div>
@endsection
