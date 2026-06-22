@extends('medico.layout')
@section('title', 'Tratamientos')
@section('nav-tratamientos', 'active')
@section('content')
<div style="background:white; padding:1.5rem; border-radius:12px; box-shadow:0 4px 6px rgba(0,0,0,0.04); margin-bottom:1.5rem;">
    <h3 style="font-weight:800;"><i class="fas fa-pills" style="color:#3B82F6;"></i> Tratamientos</h3>
</div>
<div style="background:white; padding:1.5rem; border-radius:12px; box-shadow:0 4px 6px rgba(0,0,0,0.04);">
    <form method="POST" action="{{ route('medico.storeTratamiento') }}">
        @csrf
        <div style="margin-bottom:1rem;">
            <label style="font-size:0.8rem; font-weight:700; color:#64748B;">Paciente</label>
            <select name="triage_id" style="width:100%; padding:0.75rem; border:1px solid #E2E8F0; border-radius:8px;" required>
                <option value="">Seleccionar</option>
                @foreach($pacientes as $p)<option value="{{ $p->id }}">{{ $p->patient_name }}</option>@endforeach
            </select>
        </div>
        <div style="margin-bottom:1rem;">
            <label style="font-size:0.8rem; font-weight:700; color:#64748B;">Descripción del Tratamiento</label>
            <textarea name="descripcion" rows="4" style="width:100%; padding:0.75rem; border:1px solid #E2E8F0; border-radius:8px;" required></textarea>
        </div>
        <button type="submit" style="padding:0.75rem 2rem; background:#3B82F6; color:white; border:none; border-radius:8px; font-weight:800; cursor:pointer;">
            <i class="fas fa-save"></i> Guardar Tratamiento
        </button>
    </form>
</div>
@endsection
