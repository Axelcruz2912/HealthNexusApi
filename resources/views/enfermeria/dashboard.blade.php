@extends('enfermeria.layout')
@section('title', 'Dashboard Enfermeria')
@section('nav-dashboard', 'active')

@section('content')
@if($critical > 0)
<div style="background:#FEF2F2; border:2px solid #DC2626; border-radius:12px; padding:1.25rem; margin-bottom:1.5rem; display:flex; align-items:center; gap:1rem; animation: pulse 1.5s infinite;">
    <i class="fas fa-exclamation-triangle" style="font-size:2rem; color:#DC2626;"></i>
    <div>
        <h3 style="font-weight:800; color:#DC2626;">ALERTA CRITICA</h3>
        <p style="font-size:0.9rem; color:#991B1B;">Hay {{ $critical }} paciente(s) en Triage Rojo que requieren atención inmediata.</p>
    </div>
    <a href="{{ route('enfermeria.triage') }}" style="background:#DC2626; color:white; padding:0.6rem 1.2rem; border-radius:8px; text-decoration:none; font-weight:700; font-size:0.85rem; margin-left:auto;">Atender Ahora</a>
</div>
@endif

<div style="display:grid; grid-template-columns:repeat(4,1fr); gap:1.25rem; margin-bottom:2rem;">
    <div style="background:white; padding:1.5rem; border-radius:12px; box-shadow:0 4px 6px rgba(0,0,0,0.04); border-top:4px solid #DC2626;">
        <div style="font-size:0.75rem; font-weight:700; color:#64748B; text-transform:uppercase;">Criticos</div>
        <div style="font-size:2.2rem; font-weight:800; color:#DC2626;">{{ $critical }}</div>
        <div style="font-size:0.75rem; color:#94A3B8;">Triage Rojo</div>
    </div>
    <div style="background:white; padding:1.5rem; border-radius:12px; box-shadow:0 4px 6px rgba(0,0,0,0.04); border-top:4px solid #F97316;">
        <div style="font-size:0.75rem; font-weight:700; color:#64748B; text-transform:uppercase;">Activos</div>
        <div style="font-size:2.2rem; font-weight:800; color:#F97316;">{{ $active }}</div>
        <div style="font-size:0.75rem; color:#94A3B8;">En atención</div>
    </div>
    <div style="background:white; padding:1.5rem; border-radius:12px; box-shadow:0 4px 6px rgba(0,0,0,0.04); border-top:4px solid #DC2626;">
        <div style="font-size:0.75rem; font-weight:700; color:#64748B; text-transform:uppercase;">Hospitalizados</div>
        <div style="font-size:2.2rem; font-weight:800; color:#DC2626;">{{ $hospitalized }}</div>
        <div style="font-size:0.75rem; color:#94A3B8;">Pacientes internos</div>
    </div>
    <div style="background:white; padding:1.5rem; border-radius:12px; box-shadow:0 4px 6px rgba(0,0,0,0.04); border-top:4px solid #F97316;">
        <div style="font-size:0.75rem; font-weight:700; color:#64748B; text-transform:uppercase;">Camas Libres</div>
        <div style="font-size:2.2rem; font-weight:800; color:#F97316;">{{ $bedsAvailable }}</div>
        <div style="font-size:0.75rem; color:#94A3B8;">Disponibles</div>
    </div>
</div>

<div style="display:grid; grid-template-columns:1fr 1fr; gap:1.5rem;">
    <div style="background:white; padding:1.5rem; border-radius:12px; box-shadow:0 4px 6px rgba(0,0,0,0.04);">
        <h3 style="font-weight:800; margin-bottom:1rem;"><i class="fas fa-bell" style="color:#DC2626;"></i> Alertas Recientes</h3>
        @if($alerts->isEmpty())
        <p style="color:#94A3B8; text-align:center; padding:2rem;">No hay alertas pendientes</p>
        @else
        @foreach($alerts as $alert)
        <div style="border-left:3px solid {{ $alert->severity === 'Critica' ? '#DC2626' : '#F97316' }}; padding:0.75rem; margin-bottom:0.75rem; background:#FAFAFA; border-radius:0 8px 8px 0;">
            <div style="font-size:0.75rem; color:#94A3B8;">{{ $alert->created_at->format('H:i') }}</div>
            <div style="font-weight:700; font-size:0.85rem; color:{{ $alert->severity === 'Critica' ? '#DC2626' : '#F97316' }}">{{ $alert->type }}</div>
            <div style="font-size:0.8rem; color:#475569;">{{ $alert->message }}</div>
        </div>
        @endforeach
        @endif
    </div>

    <div style="background:white; padding:1.5rem; border-radius:12px; box-shadow:0 4px 6px rgba(0,0,0,0.04);">
        <h3 style="font-weight:800; margin-bottom:1rem;"><i class="fas fa-ambulance" style="color:#DC2626;"></i> Pacientes Criticos</h3>
        @if($criticalPatients->isEmpty())
        <p style="color:#94A3B8; text-align:center; padding:2rem;">No hay pacientes criticos ahora</p>
        @else
        @foreach($criticalPatients as $p)
        <div style="border-left:3px solid #DC2626; padding:0.75rem; margin-bottom:0.75rem; background:#FEF2F2; border-radius:0 8px 8px 0;">
            <div style="font-weight:700;">{{ $p->patient_name }} <span style="font-size:0.75rem; color:#64748B;">({{ $p->age }}a)</span></div>
            <div style="font-size:0.8rem; color:#475569;">{{ $p->symptoms }}</div>
            @if($p->vitals_ta)
            <div style="font-size:0.7rem; font-family:monospace; color:#64748B; margin-top:0.25rem;">TA:{{ $p->vitals_ta }} FC:{{ $p->vitals_fc }} Temp:{{ $p->vitals_temp }} SpO2:{{ $p->vitals_spo2 }}%</div>
            @endif
        </div>
        @endforeach
        @endif
    </div>
</div>
@endsection
