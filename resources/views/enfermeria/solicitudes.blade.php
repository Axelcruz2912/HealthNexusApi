@extends('enfermeria.layout')
@section('title', 'Solicitudes Farmacia')
@section('nav-solicitudes', 'active')

@section('content')
<div style="background:white; padding:1.5rem; border-radius:12px; box-shadow:0 4px 6px rgba(0,0,0,0.04); margin-bottom:1.5rem;">
    <h3 style="font-weight:800;"><i class="fas fa-prescription-bottle-alt" style="color:#DC2626;"></i> Solicitudes a Farmacia</h3>
    <p style="color:#64748B; font-size:0.85rem;">Solicitar medicamentos e insumos al area de farmacia.</p>
</div>

<div style="background:white; padding:1.5rem; border-radius:12px; box-shadow:0 4px 6px rgba(0,0,0,0.04);">
    <h4 style="font-weight:800; margin-bottom:1rem;">Medicamentos Disponibles para Enfermeria</h4>
    <div style="display:grid; grid-template-columns:repeat(3,1fr); gap:1rem;">
    @foreach($meds as $med)
        <div style="border:1px solid #E2E8F0; border-radius:8px; padding:1rem;">
            <div style="font-weight:700; font-size:0.9rem;">{{ $med->name }}</div>
            <div style="font-size:0.75rem; color:#64748B;">{{ $med->active_ingredient }}</div>
            <div style="display:flex; justify-content:space-between; margin-top:0.5rem;">
                <span style="font-size:0.8rem; font-weight:700; color:{{ $med->stock <= $med->min_stock ? '#DC2626' : '#F97316' }}">Stock: {{ $med->stock }}</span>
                <span style="background:#FEF2F2; color:#991B1B; padding:0.1rem 0.4rem; border-radius:6px; font-size:0.7rem; font-weight:700;">{{ $med->origin }}</span>
            </div>
        </div>
    @endforeach
    </div>
</div>
@endsection
