@extends('enfermeria.layout')
@section('title', 'Documentacion Clinica')
@section('nav-docs', 'active')

@section('content')
<div style="background:white; padding:1.5rem; border-radius:12px; box-shadow:0 4px 6px rgba(0,0,0,0.04); margin-bottom:1.5rem; display:flex; justify-content:space-between; align-items:center;">
    <div>
        <h3 style="font-weight:800;"><i class="fas fa-file-medical" style="color:#DC2626;"></i> Expedientes y Documentacion</h3>
        <p style="color:#64748B; font-size:0.85rem;">Notas clinicas, evoluciones y registros del paciente.</p>
    </div>
</div>

@foreach($patients as $p)
@php
    $evolutions = $p->evolutions ?? collect();
    $vitals = $p->vitalSigns ?? collect();
@endphp
<div style="background:white; border-radius:12px; box-shadow:0 4px 6px rgba(0,0,0,0.04); margin-bottom:1.25rem; overflow:hidden;">
    <div style="background:#7C2D12; color:white; padding:1rem 1.25rem; display:flex; justify-content:space-between; align-items:center; cursor:pointer;" onclick="document.getElementById('exp-{{ $p->id }}').style.display = document.getElementById('exp-{{ $p->id }}').style.display === 'none' ? 'block' : 'none'">
        <div>
            <span style="font-weight:800; font-size:1rem;">{{ $p->patient_name }}</span>
            <span style="font-size:0.8rem; opacity:0.8;"> - {{ $p->age }} años</span>
        </div>
        <div style="display:flex; align-items:center; gap:0.5rem;">
            @php $colors = ['Rojo'=>'#DC2626','Naranja'=>'#FF8C42','Amarillo'=>'#D97706','Verde'=>'#F97316','Azul'=>'#DC2626']; @endphp
            <span style="background:{{ $colors[$p->triage_level] ?? '#64748B' }}; color:white; padding:0.15rem 0.5rem; border-radius:10px; font-size:0.7rem; font-weight:700;">Triage {{ $p->triage_level }}</span>
            <span style="background:rgba(255,255,255,0.15); padding:0.15rem 0.5rem; border-radius:10px; font-size:0.7rem;">{{ $p->status }}</span>
            <i class="fas fa-chevron-down" style="font-size:0.8rem; opacity:0.6;"></i>
        </div>
    </div>
    <div id="exp-{{ $p->id }}" style="display:none; padding:1.25rem;">
        <!-- Info General -->
        <div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:1rem; margin-bottom:1rem;">
            <div style="background:#F8FAFC; padding:0.75rem; border-radius:8px;">
                <div style="font-size:0.7rem; font-weight:700; color:#64748B; text-transform:uppercase;">Motivo</div>
                <div style="font-size:0.85rem; margin-top:0.25rem;">{{ $p->symptoms }}</div>
            </div>
            <div style="background:#F8FAFC; padding:0.75rem; border-radius:8px;">
                <div style="font-size:0.7rem; font-weight:700; color:#64748B; text-transform:uppercase;">Area Asignada</div>
                <div style="font-size:0.85rem; margin-top:0.25rem; font-weight:700;">{{ $p->assigned_area ?? 'Sin asignar' }}</div>
            </div>
            <div style="background:#F8FAFC; padding:0.75rem; border-radius:8px;">
                <div style="font-size:0.7rem; font-weight:700; color:#64748B; text-transform:uppercase;">Ingreso</div>
                <div style="font-size:0.85rem; margin-top:0.25rem;">{{ $p->created_at->format('d/m/Y H:i') }}</div>
            </div>
        </div>

        <!-- Signos Vitales -->
        @if($p->vitals_ta)
        <div style="background:#FEF2F2; padding:0.75rem; border-radius:8px; margin-bottom:1rem; border-left:3px solid #DC2626;">
            <div style="font-size:0.7rem; font-weight:700; color:#DC2626; margin-bottom:0.25rem;">ULTIMOS SIGNOS VITALES</div>
            <div style="font-size:0.85rem; font-family:monospace;">
                TA: {{ $p->vitals_ta }} | FC: {{ $p->vitals_fc }} lpm | Temp: {{ $p->vitals_temp }}C | SpO2: {{ $p->vitals_spo2 }}%
            </div>
        </div>
        @endif

        <!-- Notas de Evolucion -->
        @if($evolutions->count() > 0)
        <div style="margin-bottom:0.5rem;">
            <div style="font-size:0.7rem; font-weight:700; color:#64748B; text-transform:uppercase; margin-bottom:0.5rem;">Notas de Evolucion ({{ $evolutions->count() }})</div>
            @foreach($evolutions as $e)
            <div style="border-left:2px solid {{ $e->priority === 'Urgente' ? '#DC2626' : '#94A3B8' }}; padding:0.5rem 0.75rem; margin-bottom:0.5rem; background:#FAFAFA; border-radius:0 6px 6px 0;">
                <div style="display:flex; justify-content:space-between; font-size:0.75rem; color:#94A3B8;">
                    <span>{{ $e->nurse->name ?? 'Enfermera' }} - {{ $e->created_at->format('d/m/Y H:i') }}</span>
                    <span style="color:{{ $e->priority === 'Urgente' ? '#DC2626' : '#64748B' }}; font-weight:700;">{{ $e->priority }}</span>
                </div>
                <div style="font-size:0.85rem; color:#7C2D12; margin-top:0.15rem;">{{ $e->notes }}</div>
            </div>
            @endforeach
        </div>
        @else
        <p style="color:#94A3B8; font-size:0.8rem; text-align:center; padding:0.5rem;">Sin notas de evolución registradas</p>
        @endif

        <!-- Acciones Rapidas -->
        <div style="display:flex; gap:0.5rem; margin-top:1rem; padding-top:0.75rem; border-top:1px solid #E2E8F0;">
            <a href="{{ route('enfermeria.signos') }}" style="background:#DC2626; color:white; padding:0.4rem 0.8rem; border-radius:6px; font-size:0.75rem; font-weight:700; text-decoration:none;"><i class="fas fa-stethoscope"></i> Signos</a>
            <a href="{{ route('enfermeria.evolucion') }}" style="background:#F97316; color:white; padding:0.4rem 0.8rem; border-radius:6px; font-size:0.75rem; font-weight:700; text-decoration:none;"><i class="fas fa-notes-medical"></i> Nota</a>
            <a href="{{ route('enfermeria.hospitalizacion') }}" style="background:#F97316; color:white; padding:0.4rem 0.8rem; border-radius:6px; font-size:0.75rem; font-weight:700; text-decoration:none;"><i class="fas fa-bed"></i> Hospitalizar</a>
        </div>
    </div>
</div>
@endforeach
{{ $patients->withQueryString()->links() }}
@endsection
