@extends('enfermeria.layout')
@section('title', 'Triage Manchester')
@section('nav-triage', 'active')

@section('content')
<div style="display:grid; grid-template-columns:1fr 1fr; gap:1.5rem;">
    <!-- Formulario Nuevo Triage -->
    <div style="background:white; padding:1.5rem; border-radius:12px; box-shadow:0 4px 6px rgba(0,0,0,0.04);">
        <h3 style="font-weight:800; margin-bottom:1.25rem;"><i class="fas fa-heartbeat" style="color:#DC2626;"></i> Aplicar Triage</h3>
        <form method="POST" action="{{ route('enfermeria.storeTriage') }}">
            @csrf
            <div style="margin-bottom:1rem;">
                <label style="font-size:0.8rem; font-weight:700; color:#64748B;">Nombre del Paciente</label>
                <input type="text" name="patient_name" required style="width:100%; padding:0.6rem; border:1px solid #E2E8F0; border-radius:8px; margin-top:0.25rem;">
            </div>
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:0.75rem; margin-bottom:1rem;">
                <div>
                    <label style="font-size:0.8rem; font-weight:700; color:#64748B;">Edad</label>
                    <input type="number" name="age" required style="width:100%; padding:0.6rem; border:1px solid #E2E8F0; border-radius:8px; margin-top:0.25rem;">
                </div>
                <div>
                    <label style="font-size:0.8rem; font-weight:700; color:#64748B;">Nivel Triage</label>
                    <select name="triage_level" required style="width:100%; padding:0.6rem; border:1px solid #E2E8F0; border-radius:8px; margin-top:0.25rem;">
                        <option value="Rojo" style="color:#DC2626;">Rojo - Resucitacion</option>
                        <option value="Naranja" style="color:#FF8C42;">Naranja - Emergencia</option>
                        <option value="Amarillo" style="color:#D97706;">Amarillo - Urgencia</option>
                        <option value="Verde" style="color:#F97316;" selected>Verde - Urgencia Menor</option>
                        <option value="Azul" style="color:#DC2626;">Azul - No Urgente</option>
                    </select>
                </div>
            </div>
            <div style="margin-bottom:1rem;">
                <label style="font-size:0.8rem; font-weight:700; color:#64748B;">Sintomas</label>
                <textarea name="symptoms" required rows="3" style="width:100%; padding:0.6rem; border:1px solid #E2E8F0; border-radius:8px; margin-top:0.25rem;"></textarea>
            </div>
            <button type="submit" style="width:100%; background:#DC2626; color:white; border:none; padding:0.75rem; border-radius:8px; font-weight:700; cursor:pointer; font-size:0.95rem;"><i class="fas fa-plus-circle"></i> Registrar Triage</button>
        </form>
    </div>

    <!-- Pacientes en Espera -->
    <div style="background:white; padding:1.5rem; border-radius:12px; box-shadow:0 4px 6px rgba(0,0,0,0.04);">
        <h3 style="font-weight:800; margin-bottom:1.25rem;"><i class="fas fa-clock" style="color:#F97316;"></i> Pacientes en Espera</h3>
        @php $colors = ['Rojo'=>'#DC2626','Naranja'=>'#FF8C42','Amarillo'=>'#D97706','Verde'=>'#F97316','Azul'=>'#DC2626']; @endphp
        @if($patients->isEmpty())
        <p style="color:#94A3B8; text-align:center; padding:2rem;">No hay pacientes en espera</p>
        @else
        @foreach($patients as $p)
        <div style="border-left:4px solid {{ $colors[$p->triage_level] ?? '#94A3B8' }}; padding:0.75rem; margin-bottom:0.75rem; background:#FAFAFA; border-radius:0 8px 8px 0;">
            <div style="display:flex; justify-content:space-between; align-items:center;">
                <div>
                    <span style="font-weight:700;">{{ $p->patient_name }}</span> <span style="font-size:0.8rem; color:#64748B;">({{ $p->age }}a)</span>
                    <span style="background:{{ $colors[$p->triage_level] ?? '#94A3B8' }}; color:white; padding:0.1rem 0.5rem; border-radius:10px; font-size:0.7rem; font-weight:700; margin-left:0.5rem;">{{ $p->triage_level }}</span>
                </div>
                <span style="font-size:0.7rem; color:#94A3B8;">{{ $p->created_at->format('H:i') }}</span>
            </div>
            <p style="font-size:0.8rem; color:#475569; margin-top:0.25rem;">{{ $p->symptoms }}</p>
        </div>
        @endforeach
        @endif
    </div>
</div>
{{ $patients->withQueryString()->links() }}
@endsection
