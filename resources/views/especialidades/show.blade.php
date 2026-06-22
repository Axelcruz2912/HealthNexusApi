@extends('especialidades.layout')

@section('nav-especialidades', 'active')

@section('content')
<div style="display:flex;align-items:center;gap:1rem;margin-bottom:1.5rem">
    <a href="{{ url('/medico/especialidades') }}" style="color:#EA580C;font-size:1.2rem;text-decoration:none"><i class="fas fa-arrow-left"></i></a>
    <h2 style="font-weight:900;color:{{ $specialty->color }}">
        <i class="fas {{ $specialty->icon }}" style="color:{{ $specialty->color }}"></i> {{ $specialty->name }}
    </h2>
</div>

@if($specialty->ia_config)
<div style="background:white;border-radius:14px;padding:1.2rem;margin-bottom:1.5rem;border-left:4px solid {{ $specialty->color }}">
    <div style="font-weight:900;color:#EA580C;font-size:0.9rem;margin-bottom:0.5rem"><i class="fas fa-robot"></i> IA Médica Activa</div>
    <div style="font-size:0.85rem;color:#7C2D12">{{ $specialty->ia_config }}</div>
</div>
@endif

<!-- MÉDICOS -->
<div style="background:white;border-radius:16px;padding:1.5rem;box-shadow:0 2px 8px rgba(0,0,0,0.04);margin-bottom:1.5rem;border-top:4px solid {{ $specialty->color }}">
    <h3 style="font-weight:900;color:{{ $specialty->color }};margin-bottom:1rem"><i class="fas fa-user-md"></i> Médicos ({{ $doctors->count() }})</h3>
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:0.8rem">
        @foreach($doctors as $doc)
        <div style="text-align:center;padding:0.8rem;border:1px solid #FDBA74;border-radius:10px">
            <i class="fas fa-user-md" style="font-size:1.5rem;color:#EA580C"></i>
            <div style="font-weight:800;margin-top:0.3rem;font-size:0.85rem">{{ $doc->name }}</div>
            <div style="font-size:0.7rem;color:#F97316;font-weight:700">{{ $doc->role }}</div>
        </div>
        @endforeach
        @if($doctors->count() == 0)
        <p style="color:#D97706;font-weight:700;padding:1rem">Sin médicos asignados</p>
        @endif
    </div>
</div>

<!-- PACIENTES -->
<div style="background:white;border-radius:16px;padding:1.5rem;box-shadow:0 2px 8px rgba(0,0,0,0.04);border-top:4px solid #F97316">
    <h3 style="font-weight:900;color:#9A3412;margin-bottom:1rem"><i class="fas fa-procedures"></i> Pacientes</h3>
    @if($patients->count() > 0)
    <table style="width:100%;border-collapse:collapse;font-size:0.85rem">
        <thead><tr style="background:#FFF7ED"><th style="padding:0.6rem;text-align:left;color:#9A3412">Paciente</th><th style="padding:0.6rem;color:#9A3412">Triage</th><th style="padding:0.6rem;color:#9A3412">Estado</th><th style="padding:0.6rem;color:#9A3412">Síntomas</th></tr></thead>
        <tbody>
        @foreach($patients as $p)
        <tr style="border-bottom:1px solid #FFF0E0">
            <td style="padding:0.5rem;font-weight:700">{{ $p->patient_name }}</td>
            <td style="padding:0.5rem"><span style="background:{{ $p->triage_level=='Rojo'?'#FEE2E2':($p->triage_level=='Naranja'?'#FFEDD5':'#DCFCE7') }};color:{{ $p->triage_level=='Rojo'?'#DC2626':($p->triage_level=='Naranja'?'#EA580C':'#16A34A') }};padding:0.15rem 0.5rem;border-radius:4px;font-size:0.75rem;font-weight:800">{{ $p->triage_level }}</span></td>
            <td style="padding:0.5rem;color:#9A3412;font-size:0.8rem">{{ $p->status }}</td>
            <td style="padding:0.5rem;color:#78716C">{{ Str::limit($p->symptoms, 35) }}</td>
        </tr>
        @endforeach
        </tbody>
    </table>
    {{ $patients->withQueryString()->links() }}
    @else
    <p style="text-align:center;color:#D97706;padding:2rem;font-weight:700">Sin pacientes en esta especialidad</p>
    @endif
</div>
@endsection
