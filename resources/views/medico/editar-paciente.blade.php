@extends('medico.layout')
@section('title', 'Editar Paciente')
@section('nav-pacientes', 'active')
@section('content')
@php $isA = session('doctor_profile') === 'Médico A'; @endphp
<div style="margin-bottom:1rem">
    <a href="{{route('medico.pacientes')}}" style="color:#78716C;text-decoration:none;font-size:0.85rem;font-weight:600"><i class="fas fa-arrow-left"></i> Regresar</a>
</div>

<div style="background:linear-gradient(135deg,#EA580C,#DC2626);border-radius:14px;padding:1.5rem;color:#fff;margin-bottom:1.5rem">
    <h3 style="font-weight:900;font-size:1.1rem"><i class="fas fa-user-edit"></i> Editar Paciente</h3>
    <p style="opacity:0.8;font-size:0.8rem">{{$paciente->patient_name}} — {{$paciente->status}}</p>
</div>

<form method="POST" action="{{route('medico.actualizarPaciente', $paciente->id)}}">
@csrf @method('PUT')
<div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:0.75rem;margin-bottom:1rem">
    <div class="card" style="padding:1rem">
        <label style="font-size:0.7rem;font-weight:700;color:#78716C">Nombre</label>
        <input type="text" name="patient_name" value="{{$paciente->patient_name}}" class="inp" required>
    </div>
    <div class="card" style="padding:1rem">
        <label style="font-size:0.7rem;font-weight:700;color:#78716C">Edad</label>
        <input type="number" name="age" value="{{$paciente->age}}" class="inp">
    </div>
    <div class="card" style="padding:1rem">
        <label style="font-size:0.7rem;font-weight:700;color:#78716C">Sexo</label>
        <select name="gender" class="inp"><option {{$paciente->gender==='Masculino'?'selected':''}}>Masculino</option><option {{$paciente->gender==='Femenino'?'selected':''}}>Femenino</option></select>
    </div>
</div>
<div style="margin-bottom:0.75rem">
    <label style="font-size:0.7rem;font-weight:700;color:#78716C">Motivo de Consulta</label>
    <textarea name="chief_complaint" class="inp" rows="2">{{$paciente->chief_complaint}}</textarea>
</div>
<div style="display:grid;grid-template-columns:1fr 1fr;gap:0.75rem;margin-bottom:0.75rem">
    <div>
        <label style="font-size:0.7rem;font-weight:700;color:#78716C">Diagnóstico</label>
        <textarea name="diagnostico" class="inp" rows="3">{{$paciente->diagnostico}}</textarea>
    </div>
    <div>
        <label style="font-size:0.7rem;font-weight:700;color:#78716C">CIE-10</label>
        <input type="text" name="cie10" value="{{$paciente->cie10}}" class="inp" placeholder="J18.9">
        <label style="font-size:0.7rem;font-weight:700;color:#78716C;margin-top:0.5rem;display:block">Tratamiento</label>
        <textarea name="tratamiento" class="inp" rows="2">{{$paciente->tratamiento}}</textarea>
    </div>
</div>
<div style="margin-bottom:0.75rem">
    <label style="font-size:0.7rem;font-weight:700;color:#78716C">Notas del Médico</label>
    <textarea name="doctor_notes" class="inp" rows="2">{{$paciente->doctor_notes}}</textarea>
</div>
<div style="margin-bottom:1rem">
    <label style="font-size:0.7rem;font-weight:700;color:#78716C">Estado</label>
    <select name="status" class="inp">
        <option {{$paciente->status==='En Espera'?'selected':''}}>En Espera</option>
        <option {{$paciente->status==='En Atención'?'selected':''}}>En Atención</option>
        <option {{$paciente->status==='Hospitalizado'?'selected':''}}>Hospitalizado</option>
    </select>
</div>
@if($isA)
<div style="margin-bottom:1rem">
    <label style="font-size:0.7rem;font-weight:700;color:#78716C">Asignar a Médico</label>
    <select name="assigned_doctor" class="inp">
        <option value="">Sin asignar</option>
        @foreach($medicos as $m)<option value="{{$m->id}}" {{$paciente->assigned_doctor==$m->id?'selected':''}}>{{$m->name}} ({{$m->role}})</option>@endforeach
    </select>
</div>
@endif
<button type="submit" class="btn-p" style="background:linear-gradient(135deg,#EA580C,#DC2626)"><i class="fas fa-save"></i> Guardar Cambios</button>
</form>

<hr style="margin:1.5rem 0;border:none;border-top:1px solid #E7E5E4">
<h4 style="font-weight:800;margin-bottom:0.75rem;font-size:0.85rem;color:#DC2626"><i class="fas fa-exclamation-triangle"></i> Acciones</h4>
<div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:0.75rem">
    @if($paciente->status !== 'Alta' && $paciente->status !== 'Defunción')
    <div class="card" style="padding:1rem;text-align:center">
        <i class="fas fa-door-open" style="font-size:1.5rem;color:#2D9E6A;margin-bottom:0.3rem"></i>
        <h4 style="font-weight:800;font-size:0.8rem">Dar de Alta</h4>
        <form method="POST" action="{{route('medico.darAlta', $paciente->id)}}">@csrf
            <textarea name="discharge_notes" class="inp" rows="2" placeholder="Notas del alta..." style="margin:0.3rem 0"></textarea>
            <button type="submit" class="btn-p" style="width:100%;background:#2D9E6A" onclick="return confirm('¿Dar de alta?')"><i class="fas fa-check"></i> Alta</button>
        </form>
    </div>
    @endif
    @if($isA && $paciente->status !== 'Alta' && $paciente->status !== 'Defunción')
    <div class="card" style="padding:1rem;text-align:center">
        <i class="fas fa-cross" style="font-size:1.5rem;color:#1C1917;margin-bottom:0.3rem"></i>
        <h4 style="font-weight:800;font-size:0.8rem">Registrar Defunción</h4>
        <form method="POST" action="{{route('medico.registrarDefuncion', $paciente->id)}}">@csrf
            <input type="text" name="cause_of_death" class="inp" placeholder="Causa de muerte" required>
            <input type="text" name="immediate_cause" class="inp" placeholder="Causa inmediata" style="margin-top:0.3rem">
            <textarea name="clinical_summary" class="inp" rows="2" placeholder="Resumen clínico..." style="margin-top:0.3rem"></textarea>
            <div style="display:flex;gap:0.3rem;margin:0.3rem 0">
                <label style="font-size:0.65rem;display:flex;align-items:center;gap:0.2rem"><input type="checkbox" name="autopsy_required" value="1"> Autopsia</label>
                <select name="notified_family" class="inp" style="flex:1;padding:0.25rem;font-size:0.65rem"><option value="No">Familia NO notif.</option><option value="Sí">Familia SÍ notif.</option><option value="Pendiente">Pendiente</option></select>
            </div>
            <textarea name="notes" class="inp" rows="1" placeholder="Notas..." style="margin-top:0.3rem"></textarea>
            <button type="submit" style="width:100%;padding:0.5rem;background:#1C1917;color:#fff;border:none;border-radius:6px;font-weight:800;cursor:pointer;font-size:0.75rem;margin-top:0.3rem" onclick="return confirm('⚠️ ¿Registrar DEFUNCIÓN?')"><i class="fas fa-cross"></i> Registrar</button>
        </form>
    </div>
    <div class="card" style="padding:1rem;text-align:center">
        <i class="fas fa-ambulance" style="font-size:1.5rem;color:#EA580C;margin-bottom:0.3rem"></i>
        <h4 style="font-weight:800;font-size:0.8rem">Derivar</h4>
        <form method="POST" action="{{route('medico.derivarPaciente', $paciente->id)}}">@csrf
            <input type="text" name="hospital_destino" class="inp" placeholder="Hospital destino" required>
            <textarea name="motivo" class="inp" rows="2" placeholder="Motivo..." required style="margin-top:0.3rem"></textarea>
            <button type="submit" class="btn-p" style="width:100%;margin-top:0.3rem" onclick="return confirm('¿Derivar?')"><i class="fas fa-ambulance"></i> Derivar</button>
        </form>
    </div>
    @endif
</div>
@endsection
