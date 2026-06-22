@extends('especialidades.layout')

@section('nav-dashboard', 'active')

@section('content')
<!-- STATS -->
<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:1rem;margin-bottom:1.5rem">
    <div style="background:white;border-radius:14px;padding:1.2rem;text-align:center;box-shadow:0 2px 8px rgba(0,0,0,0.04);border-top:4px solid #DC2626">
        <div style="font-size:2rem;font-weight:900;color:#DC2626">{{ $criticalPatients->count() }}</div>
        <div style="font-size:0.75rem;font-weight:800;color:#9A3412">Críticos</div>
    </div>
    <div style="background:white;border-radius:14px;padding:1.2rem;text-align:center;box-shadow:0 2px 8px rgba(0,0,0,0.04);border-top:4px solid #F97316">
        <div style="font-size:2rem;font-weight:900;color:#F97316">{{ $myPatients->count() }}</div>
        <div style="font-size:0.75rem;font-weight:800;color:#9A3412">Mis Pacientes</div>
    </div>
    <div style="background:white;border-radius:14px;padding:1.2rem;text-align:center;box-shadow:0 2px 8px rgba(0,0,0,0.04);border-top:4px solid #F59E0B">
        <div style="font-size:2rem;font-weight:900;color:#D97706">{{ $todayAppointments->count() }}</div>
        <div style="font-size:0.75rem;font-weight:800;color:#9A3412">Citas Hoy</div>
    </div>
    <div style="background:white;border-radius:14px;padding:1.2rem;text-align:center;box-shadow:0 2px 8px rgba(0,0,0,0.04);border-top:4px solid #10B981">
        <div style="font-size:2rem;font-weight:900;color:#10B981">{{ $bedsAvailable }}</div>
        <div style="font-size:0.75rem;font-weight:800;color:#9A3412">Camas Libres</div>
    </div>
</div>

<!-- MI ESPECIALIDAD -->
@if($mySpecialty)
<div style="background:white;border-radius:16px;padding:1.5rem;box-shadow:0 4px 12px rgba(249,115,22,0.06);border-top:4px solid {{ $mySpecialty->color }};margin-bottom:1.5rem">
    <h3 style="font-weight:900;color:{{ $mySpecialty->color }};margin-bottom:0.8rem">
        <i class="fas {{ $mySpecialty->icon }}" style="color:{{ $mySpecialty->color }}"></i> {{ $mySpecialty->name }}
    </h3>
    @if($mySpecialty->ia_config)
    <div style="background:#FFF7ED;border-radius:8px;padding:0.8rem;margin-bottom:0.5rem">
        <div style="font-weight:800;color:#EA580C;font-size:0.8rem;margin-bottom:0.3rem"><i class="fas fa-robot"></i> IA Médica Activa</div>
        <div style="font-size:0.75rem;color:#9A3412">{{ $mySpecialty->ia_config }}</div>
    </div>
    @endif
    <a href="{{ url('/medico/especialidades/' . $mySpecialty->id) }}" style="display:inline-block;margin-top:0.5rem;color:#EA580C;font-weight:800;font-size:0.85rem;text-decoration:none"><i class="fas fa-arrow-right"></i> Ver detalle</a>
</div>
@endif

<!-- PACIENTES CRÍTICOS -->
@if($criticalPatients->count() > 0)
<div style="background:white;border-radius:16px;padding:1.5rem;box-shadow:0 4px 12px rgba(220,38,38,0.1);border-top:4px solid #DC2626;margin-bottom:1.5rem">
    <h3 style="font-weight:900;color:#991B1B;margin-bottom:1rem"><i class="fas fa-exclamation-triangle" style="color:#DC2626"></i> Pacientes Críticos sin Especialista ({{ $criticalPatients->count() }})</h3>
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
                <option value="{{ $s->id }}" @if($mySpecialty && $s->id == $mySpecialty->id) selected @endif>{{ $s->name }}</option>
                @endforeach
            </select>
            <button style="padding:0.3rem 0.6rem;background:#EA580C;color:white;border:none;border-radius:6px;font-weight:800;font-size:0.7rem;cursor:pointer"><i class="fas fa-share"></i></button>
        </form>
    </div>
    @endforeach
</div>
@endif

<!-- ESPECIALIDADES -->
<div style="background:white;border-radius:16px;padding:1.5rem;box-shadow:0 4px 12px rgba(249,115,22,0.06);border-top:4px solid #EA580C;margin-bottom:1.5rem">
    <h3 style="font-weight:900;color:#9A3412;margin-bottom:1rem"><i class="fas fa-hospital" style="color:#EA580C"></i> Especialidades del Hospital</h3>
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(150px,1fr));gap:0.8rem">
        @foreach($specialties as $spec)
        <a href="{{ url('/medico/especialidades/' . $spec->id) }}" style="text-decoration:none;border:2px solid {{ $spec->color }}40;border-radius:12px;padding:0.8rem;text-align:center;transition:0.2s;background:white;display:block">
            <i class="fas {{ $spec->icon }}" style="font-size:1.5rem;color:{{ $spec->color }};margin-bottom:0.3rem"></i>
            <div style="font-weight:900;color:{{ $spec->color }};font-size:0.8rem">{{ $spec->name }}</div>
            <div style="font-size:0.6rem;color:#78716C">{{ $spec->doctors_count }} médicos</div>
        </a>
        @endforeach
    </div>
</div>

<!-- MIS PACIENTES -->
<div style="background:white;border-radius:16px;padding:1.5rem;box-shadow:0 4px 12px rgba(249,115,22,0.06);border-top:4px solid #F97316">
    <h3 style="font-weight:900;color:#9A3412;margin-bottom:1rem"><i class="fas fa-procedures" style="color:#F97316"></i> Mis Pacientes</h3>
    @if($myPatients->count() > 0)
    <table style="width:100%;border-collapse:collapse;font-size:0.85rem">
        <thead><tr style="background:#FFF7ED"><th style="padding:0.6rem;text-align:left;color:#9A3412">Paciente</th><th style="padding:0.6rem;color:#9A3412">Triage</th><th style="padding:0.6rem;color:#9A3412">Estado</th><th style="padding:0.6rem;color:#9A3412">Síntomas</th></tr></thead>
        <tbody>
        @foreach($myPatients as $p)
        <tr style="border-bottom:1px solid #FFF0E0">
            <td style="padding:0.5rem;font-weight:700">{{ $p->patient_name }}</td>
            <td style="padding:0.5rem"><span style="background:{{ $p->triage_level=='Rojo'?'#FEE2E2':($p->triage_level=='Naranja'?'#FFEDD5':'#DCFCE7') }};color:{{ $p->triage_level=='Rojo'?'#DC2626':($p->triage_level=='Naranja'?'#EA580C':'#16A34A') }};padding:0.15rem 0.5rem;border-radius:4px;font-size:0.75rem;font-weight:800">{{ $p->triage_level }}</span></td>
            <td style="padding:0.5rem;color:#9A3412;font-size:0.8rem">{{ $p->status }}</td>
            <td style="padding:0.5rem;color:#78716C">{{ Str::limit($p->symptoms, 35) }}</td>
        </tr>
        @endforeach
        </tbody>
    </table>
    @else
    <p style="text-align:center;color:#D97706;padding:2rem;font-weight:700">Sin pacientes asignados aún</p>
    @endif
</div>

@endsection
