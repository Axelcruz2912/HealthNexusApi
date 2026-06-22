@extends('superadmin.layout')
@section('title', 'Hospital Live - Control Operativo')

@section('content')
@if($modoCrisis)
<div style="background:linear-gradient(135deg,#DC2626,#7F1D1D);color:white;border-radius:12px;padding:2rem;margin-bottom:2rem">
    <h3 style="font-weight:800;font-size:1.2rem"><i class="fas fa-triangle-exclamation"></i> MODO CRISIS HOSPITALARIA</h3>
    <p style="opacity:0.9;font-size:0.85rem;margin-top:0.3rem">Protocolo activo - Control total requerido</p>
</div>
@endif

<div style="display:grid;grid-template-columns:repeat(6,1fr);gap:1rem;margin-bottom:2rem">
    @foreach($metricas as $m)
    <div style="background:white;border-radius:12px;padding:1rem;text-align:center;box-shadow:0 2px 8px rgba(0,0,0,0.04);border-top:3px solid {{ $m['color'] }}">
        <div style="font-size:1.8rem;font-weight:900;color:{{ $m['color'] }}">{{ $m['valor'] }}</div>
        <div style="font-size:0.65rem;font-weight:700;color:#736860">{{ $m['label'] }}</div>
    </div>
    @endforeach
</div>

<div style="background:white;border-radius:12px;padding:2rem;box-shadow:0 2px 8px rgba(0,0,0,0.04);margin-bottom:2rem">
    <h3 style="font-weight:800;color:#1E1A17;margin-bottom:1.5rem"><i class="fas fa-chart-bar" style="color:#F05A4E"></i> Mapa de Saturacion</h3>
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:1rem">
        @foreach($areas as $area)
        <div style="border:2px solid {{ $area['border'] }};border-radius:10px;padding:1rem;background:{{ $area['bg'] }}">
            <div style="display:flex;justify-content:space-between;align-items:start">
                <div style="font-weight:800;color:{{ $area['color'] }};font-size:0.9rem">{{ $area['name'] }}</div>
                <span style="background:{{ $area['status_color'] }};color:white;padding:0.1rem 0.5rem;border-radius:4px;font-size:0.6rem;font-weight:800">{{ $area['status'] }}</span>
            </div>
            <div style="margin-top:0.5rem">
                <div style="background:#E7E5E4;border-radius:3px;height:8px;overflow:hidden">
                    <div style="background:{{ $area['status_color'] }};height:100%;width:{{ min($area['pct'], 100) }}%;border-radius:3px"></div>
                </div>
                <div style="display:flex;justify-content:space-between;margin-top:0.3rem;font-size:0.7rem;color:#736860">
                    <span>{{ $area['pacientes'] }} / {{ $area['capacidad'] }}</span>
                    <span>{{ $area['pct'] }}%</span>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<div style="background:white;border-radius:12px;padding:2rem;box-shadow:0 2px 8px rgba(0,0,0,0.04)">
    <h3 style="font-weight:800;color:#1E1A17;margin-bottom:1rem"><i class="fas fa-timeline" style="color:#F05A4E"></i> Eventos del Hospital</h3>
    @if($eventos->count() > 0)
    <div style="display:grid;gap:0.5rem">
        @foreach($eventos as $e)
        @php $isRecent = $loop->index < 3; $rowBg = $isRecent ? 'background:#FFF1EE;' : ''; @endphp
        <div style="display:flex;gap:0.8rem;align-items:center;padding:0.5rem;border-radius:6px;{{ $rowBg }}">
            <div style="width:6px;height:6px;border-radius:50%;background:#F05A4E;flex-shrink:0"></div>
            <div style="font-size:0.7rem;color:#736860;width:55px">{{ $e->created_at->format('H:i') }}</div>
            <div style="font-size:0.8rem;font-weight:700;color:#1E1A17">{{ $e->action }}</div>
            <div style="font-size:0.75rem;color:#A8A29E">{{ Str::limit($e->details, 40) }}</div>
            <div style="font-size:0.65rem;color:#C7291C;font-weight:600;margin-left:auto">{{ $e->user_name }}</div>
        </div>
        @endforeach
    </div>
    @else
    <p style="text-align:center;color:#A8A29E;padding:1.5rem;font-weight:600">Sin eventos recientes</p>
    @endif
</div>
@endsection
