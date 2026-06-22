@extends('medico.layout')
@section('title', 'Pacientes Hospitalizados')
@section('nav-hospitalizados', 'active')

@section('content')
<h2 style="font-weight:900;color:#9A3412;margin-bottom:1.5rem"><i class="fas fa-hospital-user" style="color:#EA580C"></i> Pacientes Hospitalizados</h2>

@if(session('success'))
<div style="background:#FFEDD5;border:1px solid #FDBA74;color:#9A3412;padding:1rem;border-radius:10px;margin-bottom:1.2rem;font-weight:700">
    <i class="fas fa-check-circle"></i> {{ session('success') }}
</div>
@endif

<!-- PACIENTES PENDIENTES DE ASIGNACIÓN (solo Médico A ve esto) -->
@if(auth()->user()->role == 'Médico A' && $pendientes->count() > 0)
<div style="background:white;border-radius:14px;padding:1.5rem;box-shadow:0 2px 8px rgba(249,115,22,0.08);border-top:4px solid #DC2626;margin-bottom:2rem">
    <h3 style="font-weight:900;color:#991B1B;margin-bottom:1rem"><i class="fas fa-exclamation-circle" style="color:#DC2626"></i> Pendientes de Asignación Médica</h3>
    <table style="width:100%;border-collapse:collapse;font-size:0.85rem">
        <thead><tr style="background:#FEF2F2"><th style="padding:0.6rem;text-align:left;color:#991B1B">Paciente</th><th style="padding:0.6rem;color:#991B1B">Triage</th><th style="padding:0.6rem;color:#991B1B">Motivo</th><th style="padding:0.6rem;color:#991B1B">Acciones</th></tr></thead>
        <tbody>
        @foreach($pendientes as $p)
        <tr style="border-bottom:1px solid #FFF0E0">
            <td style="padding:0.5rem;font-weight:700">{{ $p->patient_name }}</td>
            <td style="padding:0.5rem"><span style="background:{{ $p->triage_level=='Rojo'?'#FEE2E2':($p->triage_level=='Naranja'?'#FFEDD5':'#FFF7ED') }};color:{{ $p->triage_level=='Rojo'?'#DC2626':($p->triage_level=='Naranja'?'#EA580C':'#F97316') }};padding:0.15rem 0.5rem;border-radius:4px;font-size:0.75rem;font-weight:800">{{ $p->triage_level }}</span></td>
            <td style="padding:0.5rem;color:#78716C">{{ Str::limit($p->chief_complaint, 40) }}</td>
            <td style="padding:0.5rem">
                <div style="display:flex;gap:0.5rem;align-items:center">
                    <form action="{{ route('medico.aceptarPaciente', $p->id) }}" method="POST">
                        @csrf
                        <button style="padding:0.3rem 0.8rem;background:#F97316;color:white;border:none;border-radius:6px;font-weight:800;font-size:0.75rem;cursor:pointer"><i class="fas fa-check"></i> Aceptar</button>
                    </form>
                    <form action="{{ route('medico.derivarPaciente', $p->id) }}" method="POST" style="display:flex;gap:0.3rem;align-items:center">
                        @csrf
                        <select name="doctor_id" style="padding:0.3rem;border:1px solid #FDBA74;border-radius:4px;font-size:0.7rem">
                            @foreach($medicosBC as $doc)
                            <option value="{{ $doc->id }}">{{ $doc->name }} ({{ $doc->role }})</option>
                            @endforeach
                        </select>
                        <button style="padding:0.3rem 0.6rem;background:#EA580C;color:white;border:none;border-radius:6px;font-weight:800;font-size:0.75rem;cursor:pointer"><i class="fas fa-share"></i> Derivar</button>
                    </form>
                </div>
            </td>
        </tr>
        @endforeach
        </tbody>
    </table>
</div>
@endif

<!-- MIS PACIENTES ASIGNADOS -->
<div style="background:white;border-radius:14px;padding:1.5rem;box-shadow:0 2px 8px rgba(249,115,22,0.08);border-top:4px solid #F97316">
    <h3 style="font-weight:900;color:#9A3412;margin-bottom:1rem"><i class="fas fa-user-md" style="color:#F97316"></i> Mis Pacientes ({{ $pacientes->total() }})</h3>
    @if($pacientes->count() > 0)
    <table style="width:100%;border-collapse:collapse;font-size:0.85rem">
        <thead><tr style="background:#FFF7ED"><th style="padding:0.6rem;text-align:left;color:#9A3412">Paciente</th><th style="padding:0.6rem;color:#9A3412">Triage</th><th style="padding:0.6rem;color:#9A3412">Motivo</th><th style="padding:0.6rem;color:#9A3412">Fecha</th></tr></thead>
        <tbody>
        @foreach($pacientes as $p)
        <tr style="border-bottom:1px solid #FFF0E0">
            <td style="padding:0.5rem;font-weight:700">{{ $p->patient_name }}</td>
            <td style="padding:0.5rem"><span style="background:{{ $p->triage_level=='Rojo'?'#FEE2E2':($p->triage_level=='Naranja'?'#FFEDD5':'#FFF7ED') }};color:{{ $p->triage_level=='Rojo'?'#DC2626':($p->triage_level=='Naranja'?'#EA580C':'#F97316') }};padding:0.15rem 0.5rem;border-radius:4px;font-size:0.75rem;font-weight:800">{{ $p->triage_level }}</span></td>
            <td style="padding:0.5rem;color:#78716C">{{ Str::limit($p->chief_complaint, 40) }}</td>
            <td style="padding:0.5rem;color:#9A3412;font-size:0.8rem">{{ $p->created_at->format('d/m H:i') }}</td>
        </tr>
        @endforeach
        </tbody>
    </table>
    {{ $pacientes->withQueryString()->links() }}
    @else
    <p style="text-align:center;color:#D97706;padding:2rem;font-weight:700">Sin pacientes asignados</p>
    @endif
</div>
@endsection
