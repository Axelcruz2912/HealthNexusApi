@extends('medico.layout')
@section('title', 'Consulta')
@section('nav-consulta', 'active')
@section('content')
<div style="background:white; padding:1.5rem; border-radius:12px; box-shadow:0 4px 6px rgba(0,0,0,0.04); margin-bottom:1.5rem;">
    <h3 style="font-weight:800;"><i class="fas fa-user-md" style="color:#3B82F6;"></i> Consulta Médica</h3>
</div>
<div style="display:grid; grid-template-columns:1fr 1fr; gap:1.5rem;">
    <div style="background:white; padding:1.5rem; border-radius:12px; box-shadow:0 4px 6px rgba(0,0,0,0.04);">
        <h4 style="font-weight:800; margin-bottom:1rem;">Pacientes en Espera</h4>
        @foreach($pacientes as $p)
        @php $tc = $p->triage_level === 'Rojo' ? '#DC2626' : ($p->triage_level === 'Amarillo' ? '#F59E0B' : '#2D9E6A'); @endphp
        <div style="padding:0.75rem; border-radius:8px; margin-bottom:0.5rem; border-left:4px solid {{ $tc }}; background:#F8FAFC; cursor:pointer;" onclick="selectPatient({{ $p->id }}, '{{ $p->patient_name }}')">
            <div style="font-weight:700;">{{ $p->patient_name }}</div>
            <div style="font-size:0.8rem; color:#64748B;">Triage: <span style="color:{{ $tc }}; font-weight:700;">{{ $p->triage_level }}</span> | {{ $p->chief_complaint ?? 'Sin datos' }}</div>
        </div>
        @endforeach
    </div>
    <div style="background:white; padding:1.5rem; border-radius:12px; box-shadow:0 4px 6px rgba(0,0,0,0.04);">
        <h4 style="font-weight:800; margin-bottom:1rem;">Registrar Consulta</h4>
        <form method="POST" action="{{ route('medico.storeConsulta') }}">
            @csrf
            <input type="hidden" name="triage_id" id="triage_id">
            <div style="margin-bottom:1rem;">
                <label style="font-size:0.8rem; font-weight:700; color:#64748B;">Paciente</label>
                <div id="selected-patient" style="padding:0.75rem; background:#F1F5F9; border-radius:8px; font-weight:700;">Selecciona un paciente</div>
            </div>
            <div style="margin-bottom:1rem;">
                <label style="font-size:0.8rem; font-weight:700; color:#64748B;">Diagnóstico</label>
                <textarea name="diagnostico" rows="3" style="width:100%; padding:0.75rem; border:1px solid #E2E8F0; border-radius:8px; font-size:0.9rem;" required></textarea>
            </div>
            <div style="margin-bottom:1rem;">
                <label style="font-size:0.8rem; font-weight:700; color:#64748B;">Tratamiento</label>
                <textarea name="tratamiento" rows="3" style="width:100%; padding:0.75rem; border:1px solid #E2E8F0; border-radius:8px; font-size:0.9rem;" required></textarea>
            </div>
            <div style="margin-bottom:1rem;">
                <label style="font-size:0.8rem; font-weight:700; color:#64748B;">Notas Adicionales</label>
                <textarea name="notas" rows="2" style="width:100%; padding:0.75rem; border:1px solid #E2E8F0; border-radius:8px; font-size:0.9rem;"></textarea>
            </div>
            <button type="submit" style="width:100%; padding:0.75rem; background:#3B82F6; color:white; border:none; border-radius:8px; font-weight:800; cursor:pointer;">
                <i class="fas fa-save"></i> Guardar Consulta
            </button>
        </form>
    </div>
</div>
<script>
function selectPatient(id, name) {
    document.getElementById('triage_id').value = id;
    document.getElementById('selected-patient').textContent = name;
    document.getElementById('selected-patient').style.background = '#DBEAFE';
    document.getElementById('selected-patient').style.color = '#1E40AF';
}
</script>
