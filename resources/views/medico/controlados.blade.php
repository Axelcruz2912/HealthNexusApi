@extends('medico.layout')
@section('title', 'Medicamentos Controlados')
@section('nav-controlados', 'active')
@section('content')
<div style="background:#FEF2F2; padding:1.5rem; border-radius:12px; border:2px solid #FCA5A5; margin-bottom:1.5rem;">
    <h3 style="font-weight:800; color:#991B1B;"><i class="fas fa-cannabis"></i> Medicamentos Controlados</h3>
    <p style="font-size:0.85rem; color:#7F1D1D;">Acceso exclusivo Médico A - Opioides y sedantes</p>
</div>
@foreach($meds as $m)
<div style="background:white; padding:1rem; border-radius:8px; margin-bottom:0.5rem; border-left:4px solid #DC2626; box-shadow:0 2px 4px rgba(0,0,0,0.04);">
    <div style="display:flex; justify-content:space-between; align-items:center;">
        <div>
            <span style="font-weight:800;">{{ $m->name }}</span>
            <span style="font-size:0.8rem; color:#64748B;">{{ $m->presentation }}</span>
        </div>
        <span style="background:#FEF2F2; color:#DC2626; padding:0.2rem 0.8rem; border-radius:20px; font-size:0.75rem; font-weight:700;">🔒 Controlado</span>
    </div>
    <div style="font-size:0.8rem; color:#94A3B8; margin-top:0.25rem;">Stock: {{ $m->stock }}</div>
</div>
@endforeach
@endsection
