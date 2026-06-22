@extends('medico.layout')
@section('title', 'UCI')
@section('nav-uci', 'active')
@section('content')
<div style="background:#FEF2F2; padding:1.5rem; border-radius:12px; border:2px solid #FCA5A5; margin-bottom:1.5rem;">
    <h3 style="font-weight:800; color:#991B1B;"><i class="fas fa-procedures"></i> Unidad de Cuidados Intensivos</h3>
    <p style="font-size:0.85rem; color:#7F1D1D;">Acceso exclusivo Médico A - Monitoreo crítico 24/7</p>
</div>
@if($criticalPatients->isEmpty())
<div style="background:#F0FDF4; padding:3rem; border-radius:12px; text-align:center; border:2px solid #BBF7D0;">
    <i class="fas fa-check-circle" style="font-size:3rem; color:#2D9E6A;"></i>
    <h3 style="font-weight:800; color:#166534; margin-top:1rem;">Sin pacientes en UCI</h3>
</div>
@else
@foreach($criticalPatients as $p)
<div style="background:white; padding:1.25rem; border-radius:12px; margin-bottom:1rem; border-left:5px solid #DC2626; box-shadow:0 4px 6px rgba(0,0,0,0.04);">
    <div style="display:flex; justify-content:space-between; align-items:center;">
        <div>
            <div style="font-weight:800; font-size:1.1rem;">{{ $p->patient_name }}</div>
            <div style="font-size:0.85rem; color:#64748B;">{{ $p->chief_complaint ?? 'Sin datos' }}</div>
        </div>
        <span style="background:#FEF2F2; color:#DC2626; padding:0.3rem 1rem; border-radius:20px; font-weight:800;">TRIAGE ROJO</span>
    </div>
</div>
@endforeach
@endif
@endsection
