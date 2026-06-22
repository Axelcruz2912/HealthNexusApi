@extends('medico.layout')
@section('title', 'Estudios')
@section('nav-estudios', 'active')
@section('content')
<div style="background:white; padding:1.5rem; border-radius:12px; box-shadow:0 4px 6px rgba(0,0,0,0.04); margin-bottom:1.5rem;">
    <h3 style="font-weight:800;"><i class="fas fa-microscope" style="color:#3B82F6;"></i> Solicitar Estudios</h3>
</div>
<div style="background:white; padding:1.5rem; border-radius:12px; box-shadow:0 4px 6px rgba(0,0,0,0.04);">
    <form method="POST" action="{{ route('medico.storeEstudio') }}">
        @csrf
        <div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:1rem; margin-bottom:1rem;">
            <div>
                <label style="font-size:0.8rem; font-weight:700; color:#64748B;">Paciente</label>
                <select name="triage_id" style="width:100%; padding:0.75rem; border:1px solid #E2E8F0; border-radius:8px;" required>
                    <option value="">Seleccionar</option>
                    @foreach($pacientes as $p)<option value="{{ $p->id }}">{{ $p->patient_name }}</option>@endforeach
                </select>
            </div>
            <div>
                <label style="font-size:0.8rem; font-weight:700; color:#64748B;">Tipo de Estudio</label>
                <select name="tipo" style="width:100%; padding:0.75rem; border:1px solid #E2E8F0; border-radius:8px;" required>
                    <option>Hemograma</option><option>Química Sanguínea</option><option>Uroanálisis</option>
                    <option>Radiografía</option><option>TAC</option><option>Resonancia Magnética</option>
                    <option>Ultrasonido</option><option>Electrocardiograma</option><option>Gases Arteriales</option>
                </select>
            </div>
            <div>
                <label style="font-size:0.8rem; font-weight:700; color:#64748B;">Prioridad</label>
                <select name="prioridad" style="width:100%; padding:0.75rem; border:1px solid #E2E8F0; border-radius:8px;" required>
                    <option>Normal</option><option>Urgente</option>
                </select>
            </div>
        </div>
        <div style="margin-bottom:1rem;">
            <label style="font-size:0.8rem; font-weight:700; color:#64748B;">Notas</label>
            <textarea name="notas" rows="2" style="width:100%; padding:0.75rem; border:1px solid #E2E8F0; border-radius:8px;" placeholder="Indicaciones para el estudio..."></textarea>
        </div>
        <button type="submit" style="padding:0.75rem 2rem; background:#3B82F6; color:white; border:none; border-radius:8px; font-weight:800; cursor:pointer;">
            <i class="fas fa-paper-plane"></i> Solicitar Estudio
        </button>
    </form>
</div>
@endsection
