@extends('enfermeria.layout')
@section('title', 'Reportes')
@section('nav-reportes', 'active')

@section('content')
<div style="background:white; padding:1.5rem; border-radius:12px; box-shadow:0 4px 6px rgba(0,0,0,0.04); margin-bottom:1.5rem;">
    <h3 style="font-weight:800;"><i class="fas fa-chart-bar" style="color:#DC2626;"></i> Reportes de Enfermeria</h3>
</div>
<div style="display:grid; grid-template-columns:repeat(3,1fr); gap:1.25rem;">
    <div style="background:white; padding:2rem; border-radius:12px; box-shadow:0 4px 6px rgba(0,0,0,0.04); text-align:center; cursor:pointer;" onmouseover="this.style.borderTop='4px solid #DC2626'" onmouseout="this.style.borderTop='none'">
        <i class="fas fa-heartbeat" style="font-size:2rem; color:#DC2626; margin-bottom:1rem;"></i>
        <h4 style="font-weight:800;">Triage Aplicados</h4>
        <p style="font-size:0.8rem; color:#64748B;">Reporte de triages del dia</p>
    </div>
    <div style="background:white; padding:2rem; border-radius:12px; box-shadow:0 4px 6px rgba(0,0,0,0.04); text-align:center; cursor:pointer;" onmouseover="this.style.borderTop='4px solid #DC2626'" onmouseout="this.style.borderTop='none'">
        <i class="fas fa-stethoscope" style="font-size:2rem; color:#F97316; margin-bottom:1rem;"></i>
        <h4 style="font-weight:800;">Signos Vitales</h4>
        <p style="font-size:0.8rem; color:#64748B;">Registros de signos vitales</p>
    </div>
    <div style="background:white; padding:2rem; border-radius:12px; box-shadow:0 4px 6px rgba(0,0,0,0.04); text-align:center; cursor:pointer;" onmouseover="this.style.borderTop='4px solid #DC2626'" onmouseout="this.style.borderTop='none'">
        <i class="fas fa-bed" style="font-size:2rem; color:#DC2626; margin-bottom:1rem;"></i>
        <h4 style="font-weight:800;">Hospitalizacion</h4>
        <p style="font-size:0.8rem; color:#64748B;">Pacientes hospitalizados</p>
    </div>
</div>
@endsection
