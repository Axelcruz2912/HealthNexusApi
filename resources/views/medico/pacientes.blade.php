@extends('medico.layout')
@section('title', 'Pacientes')
@section('nav-pacientes', 'active')
@section('content')
@php $isA = $role === 'Médico A'; $isB = $role === 'Médico B'; @endphp

<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem">
    <div>
        <h3 style="font-weight:900;font-size:1.1rem;color:#1C1917"><i class="fas fa-users" style="color:#EA580C"></i> Pacientes en el Hospital</h3>
        <p style="font-size:0.75rem;color:#A8A29E;margin-top:0.15rem">{{$pacientes->count()}} pacientes registrados</p>
    </div>
    @if($isA || $isB)
    <a href="{{route('medico.registrarPaciente')}}" style="padding:0.55rem 1.2rem;background:linear-gradient(135deg,#EA580C,#DC2626);color:#fff;border-radius:8px;text-decoration:none;font-weight:800;font-size:0.82rem"><i class="fas fa-user-plus"></i> Registrar Paciente</a>
    @endif
</div>

<div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:0.5rem;margin-bottom:1.25rem">
    @php $rojos = $pacientes->where('triage_level','Rojo')->count(); $naranjas = $pacientes->where('triage_level','Naranja')->count(); $amarillos = $pacientes->where('triage_level','Amarillo')->count(); @endphp
    <div class="card" style="padding:0.75rem;text-align:center;border-top:3px solid #DC2626">
        <div style="font-size:1.5rem;font-weight:900;color:#DC2626">{{$rojos}}</div>
        <div style="font-size:0.65rem;color:#78716C;font-weight:700">Críticos 🔴</div>
    </div>
    <div class="card" style="padding:0.75rem;text-align:center;border-top:3px solid #EA580C">
        <div style="font-size:1.5rem;font-weight:900;color:#EA580C">{{$naranjas}}</div>
        <div style="font-size:0.65rem;color:#78716C;font-weight:700">Urgentes 🟠</div>
    </div>
    <div class="card" style="padding:0.75rem;text-align:center;border-top:3px solid #D97706">
        <div style="font-size:1.5rem;font-weight:900;color:#D97706">{{$amarillos}}</div>
        <div style="font-size:0.65rem;color:#78716C;font-weight:700">Prioritarios 🟡</div>
    </div>
</div>

@if($pacientes->isEmpty())
<div class="card" style="padding:3rem;text-align:center">
    <i class="fas fa-user-plus" style="font-size:3rem;color:#EA580C;margin-bottom:1rem"></i>
    <h3 style="font-weight:800;color:#57534E">Sin pacientes activos</h3>
    <p style="color:#A8A29E;margin-bottom:1rem">Registra un nuevo paciente para comenzar</p>
    @if($isA || $isB)
    <a href="{{route('medico.registrarPaciente')}}" style="display:inline-block;padding:0.6rem 1.5rem;background:linear-gradient(135deg,#EA580C,#DC2626);color:#fff;border-radius:8px;text-decoration:none;font-weight:800;font-size:0.85rem"><i class="fas fa-user-plus"></i> Registrar Paciente</a>
    @endif
</div>
@else
@foreach($pacientes as $p)
@php
 $tc = $p->triage_level === 'Rojo' ? '#DC2626' : ($p->triage_level === 'Naranja' ? '#EA580C' : ($p->triage_level === 'Amarillo' ? '#D97706' : '#F97316'));
 $doctor = $p->assigned_doctor ? \App\Models\User::find($p->assigned_doctor) : null;
@endphp
<div class="card" style="margin-bottom:0.4rem">
    <div style="padding:0.75rem 1rem;border-left:4px solid {{$tc}}">
        <div style="display:flex;justify-content:space-between;align-items:center">
            <div style="flex:1">
                <div style="display:flex;align-items:center;gap:0.5rem">
                    <span style="font-weight:800;font-size:0.85rem">{{$p->patient_name}}</span>
                    <span style="background:{{$tc}}18;color:{{$tc}};padding:0.05rem 0.35rem;border-radius:6px;font-size:0.6rem;font-weight:800">{{$p->triage_level}}</span>
                    <span style="font-size:0.65rem;color:#A8A29E;font-weight:600">{{$p->status}}</span>
                </div>
                <div style="font-size:0.72rem;color:#78716C;margin-top:0.15rem">{{$p->chief_complaint}} @if($p->diagnostico) • <strong>DX:</strong> {{Str::limit($p->diagnostico, 40)}}@endif</div>
                <div style="font-size:0.62rem;color:#A8A29E;margin-top:0.1rem"><i class="fas fa-user-md"></i> {{$doctor->name ?? 'Sin asignar'}} • {{\Carbon\Carbon::parse($p->created_at)->format('d/m H:i')}}</div>
            </div>
            <div style="display:flex;gap:0.25rem;flex-wrap:wrap;justify-content:flex-end">
                <a href="{{route('medico.editarPaciente', $p->id)}}" style="padding:0.25rem 0.5rem;background:#FFF7ED;border:1px solid #FDBA74;border-radius:5px;text-decoration:none;color:#EA580C;font-weight:700;font-size:0.65rem"><i class="fas fa-edit"></i> Editar</a>
                @if($isA)
                <form method="POST" action="{{route('medico.asignarPaciente', $p->id)}}" style="display:inline">@csrf
                    <select name="doctor_id" onchange="this.form.submit()" style="padding:0.2rem;border:1px solid #E7E5E4;border-radius:5px;font-size:0.6rem;font-weight:600">
                        <option>Asignar...</option>
                        @foreach($medicos as $m)<option value="{{$m->id}}">{{$m->name}}</option>@endforeach
                    </select>
                </form>
                @endif
                @if($p->status !== 'Alta' && $p->status !== 'Defunción' && $p->status !== 'Derivado')
                <form method="POST" action="{{route('medico.darAlta', $p->id)}}" style="display:inline">@csrf
                    <button type="submit" style="padding:0.25rem 0.5rem;background:#F0FDF4;border:1px solid #BBF7D0;border-radius:5px;color:#166534;font-weight:700;font-size:0.65rem;cursor:pointer" onclick="return confirm('¿Dar de alta?')"><i class="fas fa-door-open"></i></button>
                </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endforeach
@endif
@endsection
