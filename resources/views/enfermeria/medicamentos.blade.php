@extends('enfermeria.layout')
@section('title', 'Medicamentos Enfermeria')
@section('nav-meds', 'active')

@section('content')
<div style="background:white; padding:1.5rem; border-radius:12px; box-shadow:0 4px 6px rgba(0,0,0,0.04); margin-bottom:1.5rem;">
    <h3 style="font-weight:800;"><i class="fas fa-pills" style="color:#DC2626;"></i> Medicamentos Autorizados para Enfermeria</h3>
    <p style="color:#64748B; font-size:0.85rem;">Solo puedes administrar los medicamentos marcados para tu perfil.</p>
</div>
<div style="display:grid; grid-template-columns:repeat(3,1fr); gap:1rem;">
@foreach($meds as $med)
<div style="background:white; padding:1.25rem; border-radius:12px; box-shadow:0 4px 6px rgba(0,0,0,0.04); border-top:3px solid #DC2626;">
    <div style="font-weight:800; font-size:0.9rem; margin-bottom:0.25rem;">{{ $med->name }}</div>
    <div style="font-size:0.75rem; color:#64748B; margin-bottom:0.5rem;">{{ $med->active_ingredient }}</div>
    <div style="display:flex; justify-content:space-between; font-size:0.8rem;">
        <span style="font-weight:700; color:{{ $med->stock <= $med->min_stock ? '#DC2626' : '#F97316' }}">Stock: {{ $med->stock }}</span>
        <span style="background:#FEF2F2; color:#991B1B; padding:0.1rem 0.4rem; border-radius:6px; font-size:0.7rem; font-weight:700;">{{ $med->origin }}</span>
    </div>
    @if($med->lot_number)<div style="font-size:0.7rem; color:#94A3B8; margin-top:0.25rem;">Lote: {{ $med->lot_number }} | Ub: {{ $med->location }}</div>@endif
</div>
@endforeach
</div>
@endsection
