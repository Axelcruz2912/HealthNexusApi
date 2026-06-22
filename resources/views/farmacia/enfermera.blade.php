@extends('farmacia.layout')
@section('title', 'Medicamentos Enfermeria')
@section('nav-enfermera', 'active')

@section('content')
<div style="background: #FEF2F2; padding: 2rem; border-radius: 12px; margin-bottom: 2rem; display: flex; align-items: center; gap: 1.5rem; border-left: 4px solid #DC2626;">
    <i class="fas fa-user-nurse" style="font-size: 3rem; color: #DC2626;"></i>
    <div>
        <h3 style="font-weight: 800; color: #7F1D1D;">Medicamentos con Acceso de Enfermeria</h3>
        <p style="color: #736860;">Medicamentos que el personal de enfermeria puede administrar directamente sin receta medica: sueros, viales, oxigeno, material de curacion, etc.</p>
    </div>
</div>

@if($meds->count() > 0)
<div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem;">
    @foreach($meds as $med)
    <div style="background: white; padding: 1.5rem; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.04); border-left: 4px solid #DC2626;">
        <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 0.75rem;">
            <div>
                <h4 style="font-weight: 800; color: #7C2D12;">{{ $med->name }}</h4>
                <div style="font-size:0.8rem; color:#736860;">{{ $med->active_ingredient }}</div>
            </div>
            <span style="background:#DC2626; color:white; padding:0.2rem 0.5rem; border-radius:10px; font-size:0.7rem; font-weight:700;">ENF</span>
        </div>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.5rem; font-size: 0.8rem;">
            <div><span style="color:#736860;">Stock:</span> <strong style="color:{{ $med->stock_color }};">{{ $med->stock }}</strong></div>
            <div><span style="color:#736860;">Origen:</span> {{ $med->origin }}</div>
            <div><span style="color:#736860;">Lote:</span> <span style="font-family:monospace;">{{ $med->lot_number }}</span></div>
            <div><span style="color:#736860;">Ubicacion:</span> {{ $med->location ?? 'N/A' }}</div>
        </div>
        @if($med->expiry_date)
        <div style="margin-top:0.5rem; font-size:0.75rem; color:{{ $med->expiry_color }}; font-weight:600;">
            <i class="fas fa-calendar"></i> Caducidad: {{ $med->expiry_date->format('d/m/Y') }}
        </div>
        @endif
    </div>
    @endforeach
</div>
@else
<div style="background: white; padding: 3rem; border-radius: 12px; text-align: center; color: #736860;">
    <i class="fas fa-user-nurse" style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.3;"></i>
    <h3 style="font-weight: 800;">Sin medicamentos asignados</h3>
    <p>Registra medicamentos y marca la casilla "Enfermeria puede administrar".</p>
</div>
@endif
@endsection
