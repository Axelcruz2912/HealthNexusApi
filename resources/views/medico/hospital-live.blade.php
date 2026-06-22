@extends('medico.layout')
@section('title', 'Hospital Live')

@section('content')
@if($modoCrisis)
<div style="background:linear-gradient(135deg,#DC2626,#7F1D1D);color:white;border-radius:12px;padding:1.5rem;margin-bottom:2rem">
    <h3 style="font-weight:800;font-size:1.1rem"><i class="fas fa-triangle-exclamation"></i> MODO CRISIS - Protocolo Activado</h3>
    <p style="opacity:0.9;font-size:0.82rem;margin-top:0.3rem">Seguimiento de pacientes criticos requerido</p>
</div>
@endif

<div style="display:grid;grid-template-columns:repeat(5,1fr);gap:1rem;margin-bottom:2rem">
    @foreach($metricas as $m)
    <div style="background:white;border-radius:12px;padding:1rem;text-align:center;box-shadow:0 2px 8px rgba(0,0,0,0.04);border-top:3px solid {{ $m['color'] }}">
        <div style="font-size:1.8rem;font-weight:900;color:{{ $m['color'] }}">{{ $m['valor'] }}</div>
        <div style="font-size:0.65rem;font-weight:700;color:#736860">{{ $m['label'] }}</div>
    </div>
    @endforeach
</div>

<div style="background:white;border-radius:12px;padding:1.5rem;box-shadow:0 2px 8px rgba(0,0,0,0.04);margin-bottom:2rem">
    <h3 style="font-weight:800;color:#1C1917;margin-bottom:1rem"><i class="fas fa-chart-bar" style="color:#EA580C"></i> Saturacion por Area</h3>
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:1rem">
        @foreach($areas as $area)
        <div style="border:2px solid {{ $area['border'] }};border-radius:10px;padding:1rem;background:{{ $area['bg'] }}">
            <div style="display:flex;justify-content:space-between;align-items:start">
                <div style="font-weight:800;color:{{ $area['color'] }};font-size:0.85rem">{{ $area['name'] }}</div>
                <span style="background:{{ $area['status_color'] }};color:white;padding:0.1rem 0.4rem;border-radius:4px;font-size:0.55rem;font-weight:800">{{ $area['status'] }}</span>
            </div>
            <div style="margin-top:0.5rem">
                <div style="background:#E7E5E4;border-radius:3px;height:6px;overflow:hidden">
                    <div style="background:{{ $area['status_color'] }};height:100%;width:{{ min($area['pct'], 100) }}%;border-radius:3px"></div>
                </div>
                <div style="display:flex;justify-content:space-between;margin-top:0.25rem;font-size:0.65rem;color:#736860">
                    <span>{{ $area['pacientes'] }}/{{ $area['capacidad'] }}</span>
                    <span>{{ $area['pct'] }}%</span>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

@if($misCriticos->count() > 0)
<div style="background:white;border-radius:12px;padding:1.5rem;box-shadow:0 2px 8px rgba(0,0,0,0.04)">
    <h3 style="font-weight:800;color:#DC2626;margin-bottom:1rem"><i class="fas fa-heartbeat"></i> Mis Pacientes Criticos</h3>
    @foreach($misCriticos as $p)
    <div style="border-left:3px solid #DC2626;padding:0.6rem 1rem;margin-bottom:0.5rem;background:#FEF2F2;border-radius:0 8px 8px 0">
        <div style="font-weight:700;color:#1C1917;font-size:0.85rem">{{ $p->patient_name or 'Paciente #'.$p->id }}</div>
        <div style="font-size:0.72rem;color:#736860">Triage Rojo | {{ $p->status }} | Hab: {{ $p->bed_id or 'Sin asignar' }}</div>
    </div>
    @endforeach
</div>
@endif
@endsection
