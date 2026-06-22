@extends('medico.layout')
@section('title', 'Hospitalización')
@section('nav-hospitalización', 'active')
@section('content')
<div style="background:white; padding:1.5rem; border-radius:12px; box-shadow:0 4px 6px rgba(0,0,0,0.04); margin-bottom:1.5rem;">
    <h3 style="font-weight:800;"><i class="fas fa-bed" style="color:#3B82F6;"></i> Hospitalización</h3>
</div>
<div style="background:white; padding:1.5rem; border-radius:12px; box-shadow:0 4px 6px rgba(0,0,0,0.04); margin-bottom:1.5rem;">
    <h4 style="font-weight:800; margin-bottom:1rem;">Ingresar Paciente</h4>
    <form method="POST" action="{{ route('medico.storeHospitalizacion') }}">
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
                <label style="font-size:0.8rem; font-weight:700; color:#64748B;">Cama</label>
                <select name="bed_id" style="width:100%; padding:0.75rem; border:1px solid #E2E8F0; border-radius:8px;" required>
                    <option value="">Seleccionar</option>
                    @foreach($camas as $c)
                    @if($c->status === 'Disponible')
                    <option value="{{ $c->id }}">P{{ $c->floor }}-H{{ $c->room_number }}-C{{ $c->bed_number }} ({{ $c->type ?? 'General' }})</option>
                    @endif
                    @endforeach
                </select>
            </div>
            <div>
                <label style="font-size:0.8rem; font-weight:700; color:#64748B;">Diagnóstico</label>
                <input type="text" name="diagnostico" style="width:100%; padding:0.75rem; border:1px solid #E2E8F0; border-radius:8px;" required>
            </div>
        </div>
        <button type="submit" style="padding:0.75rem 2rem; background:#3B82F6; color:white; border:none; border-radius:8px; font-weight:800; cursor:pointer;">
            <i class="fas fa-bed"></i> Hospitalizar
        </button>
    </form>
</div>
@endsection
