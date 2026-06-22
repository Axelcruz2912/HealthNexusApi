@extends('especialidades.layout')

@section('nav-especialidades', 'active')

@section('content')
<h2 style="font-weight:900;color:#7C2D12;margin-bottom:1.5rem"><i class="fas fa-hospital" style="color:#EA580C"></i> Especialidades del Hospital</h2>

<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:1.2rem;margin-bottom:2rem">
    @foreach($specialties as $spec)
    <a href="{{ url('/medico/especialidades/' . $spec->id) }}" style="text-decoration:none;background:white;border-radius:16px;padding:1.5rem;box-shadow:0 4px 12px rgba(0,0,0,0.05);border-top:4px solid {{ $spec->color }};transition:0.2s;display:block">
        <div style="text-align:center;margin-bottom:0.8rem">
            <i class="fas {{ $spec->icon }}" style="font-size:2.5rem;color:{{ $spec->color }}"></i>
        </div>
        <div style="font-weight:900;color:{{ $spec->color }};font-size:1rem;text-align:center;margin-bottom:0.3rem">{{ $spec->name }}</div>
        <div style="font-size:0.75rem;color:#78716C;text-align:center">{{ $spec->doctors_count }} médico(s)</div>
        @if($spec->ia_config)
        <div style="margin-top:0.5rem;background:#FFF7ED;border-radius:6px;padding:0.4rem;font-size:0.65rem;color:#9A3412;text-align:center"><i class="fas fa-robot"></i> IA Activa</div>
        @endif
    </a>
    @endforeach
</div>

@if($criticalPatients->count() > 0)
<div style="background:white;border-radius:16px;padding:1.5rem;box-shadow:0 4px 12px rgba(220,38,38,0.1);border-top:4px solid #DC2626">
    <h3 style="font-weight:900;color:#991B1B;margin-bottom:1rem"><i class="fas fa-exclamation-triangle" style="color:#DC2626"></i> Pacientes Críticos ({{ $criticalPatients->count() }})</h3>
    @foreach($criticalPatients as $p)
    <div style="border:1px solid #FECACA;border-radius:8px;padding:0.8rem;margin-bottom:0.5rem;display:flex;justify-content:space-between;align-items:center">
        <div>
            <span style="font-weight:800;color:#DC2626">{{ $p->patient_name }}</span>
            <span style="font-size:0.75rem;color:#991B1B;margin-left:0.5rem">{{ Str::limit($p->symptoms, 30) }}</span>
        </div>
        <form action="{{ url('/medico/especialidades/derivar/' . $p->id) }}" method="POST" style="display:flex;gap:0.3rem;align-items:center">
            @csrf
            <select name="specialty_id" style="padding:0.3rem;border:1px solid #FDBA74;border-radius:6px;font-size:0.75rem">
                @foreach($specialties as $s)
                <option value="{{ $s->id }}">{{ $s->name }}</option>
                @endforeach
            </select>
            <button style="padding:0.3rem 0.6rem;background:#EA580C;color:white;border:none;border-radius:6px;font-weight:800;font-size:0.7rem;cursor:pointer"><i class="fas fa-share"></i></button>
        </form>
    </div>
    @endforeach
</div>
@endif
@endsection
