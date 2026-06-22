@extends('medico.layout')
@section('title', 'Registrar Paciente')
@section('nav-pacientes', 'active')
@section('content')
@php $isA = session('doctor_profile') === 'Médico A'; @endphp

<div style="margin-bottom:1rem">
    <a href="{{route('medico.pacientes')}}" style="color:#78716C;text-decoration:none;font-size:0.85rem;font-weight:600"><i class="fas fa-arrow-left"></i> Regresar a Pacientes</a>
</div>

<div style="background:linear-gradient(135deg,#EA580C,#DC2626);border-radius:14px;padding:1.5rem;color:#fff;margin-bottom:1.5rem">
    <h3 style="font-weight:900;font-size:1.2rem"><i class="fas fa-user-plus"></i> Registrar Nuevo Paciente</h3>
    <p style="opacity:0.8;font-size:0.8rem;margin-top:0.3rem">Completa los datos para ingresar al paciente al sistema</p>
</div>

<form method="POST" action="{{route('medico.storeNuevoPaciente')}}">
@csrf

{{-- SECCIÓN 1: Datos Personales --}}
<div class="card" style="padding:1.25rem;margin-bottom:1rem">
    <h4 style="font-weight:800;margin-bottom:0.75rem;font-size:0.85rem;color:#EA580C"><i class="fas fa-id-card"></i> Datos Personales</h4>
    <div style="display:grid;grid-template-columns:2fr 1fr 1fr;gap:0.75rem">
        <div>
            <label style="font-size:0.7rem;font-weight:700;color:#78716C">Nombre Completo *</label>
            <input type="text" name="patient_name" class="inp" required placeholder="Juan Pérez García">
        </div>
        <div>
            <label style="font-size:0.7rem;font-weight:700;color:#78716C">Edad *</label>
            <input type="number" name="age" class="inp" required placeholder="45" min="0" max="120">
        </div>
        <div>
            <label style="font-size:0.7rem;font-weight:700;color:#78716C">Sexo *</label>
            <select name="gender" class="inp" required>
                <option value="">Seleccionar</option>
                <option>Masculino</option>
                <option>Femenino</option>
            </select>
        </div>
    </div>
    <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:0.75rem;margin-top:0.75rem">
        <div>
            <label style="font-size:0.7rem;font-weight:700;color:#78716C">Tipo de Sangre</label>
            <select name="blood_type" class="inp">
                <option value="">Desconocido</option>
                <option>A+</option><option>A-</option>
                <option>B+</option><option>B-</option>
                <option>AB+</option><option>AB-</option>
                <option>O+</option><option>O-</option>
            </select>
        </div>
        <div>
            <label style="font-size:0.7rem;font-weight:700;color:#78716C">Seguro Médico</label>
            <input type="text" name="insurance" class="inp" placeholder="IMSS, ISSSTE, Particular...">
        </div>
        <div>
            <label style="font-size:0.7rem;font-weight:700;color:#78716C">Contacto Emergencia</label>
            <input type="text" name="emergency_contact" class="inp" placeholder="María Pérez 555-1234">
        </div>
    </div>
</div>

{{-- SECCIÓN 2: Clasificación Manchester --}}
<div class="card" style="padding:1.25rem;margin-bottom:1rem">
    <h4 style="font-weight:800;margin-bottom:0.75rem;font-size:0.85rem;color:#EA580C"><i class="fas fa-tag"></i> Clasificación de Triaje (Manchester)</h4>
    <div style="display:grid;grid-template-columns:repeat(5,1fr);gap:0.5rem">
        <label style="cursor:pointer">
            <input type="radio" name="triage_level" value="Rojo" style="display:none" required>
            <div class="triage-card" style="padding:0.75rem;border-radius:8px;text-align:center;border:2px solid #FCA5A5;background:#FEF2F2;transition:all 0.15s" onmouseover="this.style.transform='scale(1.03)'" onmouseout="this.style.transform='none'">
                <div style="font-size:1.5rem;margin-bottom:0.2rem">🔴</div>
                <div style="font-weight:900;font-size:0.75rem;color:#DC2626">ROJO</div>
                <div style="font-size:0.6rem;color:#991B1B;font-weight:600">Resucitación</div>
                <div style="font-size:0.55rem;color:#A8A29E">Inmediato</div>
            </div>
        </label>
        <label style="cursor:pointer">
            <input type="radio" name="triage_level" value="Naranja" style="display:none">
            <div class="triage-card" style="padding:0.75rem;border-radius:8px;text-align:center;border:2px solid #FDBA74;background:#FFF7ED;transition:all 0.15s" onmouseover="this.style.transform='scale(1.03)'" onmouseout="this.style.transform='none'">
                <div style="font-size:1.5rem;margin-bottom:0.2rem">🟠</div>
                <div style="font-weight:900;font-size:0.75rem;color:#EA580C">NARANJA</div>
                <div style="font-size:0.6rem;color:#9A3412;font-weight:600">Emergencia</div>
                <div style="font-size:0.55rem;color:#A8A29E">10 min</div>
            </div>
        </label>
        <label style="cursor:pointer">
            <input type="radio" name="triage_level" value="Amarillo" style="display:none">
            <div class="triage-card" style="padding:0.75rem;border-radius:8px;text-align:center;border:2px solid #FDE68A;background:#FFFBEB;transition:all 0.15s" onmouseover="this.style.transform='scale(1.03)'" onmouseout="this.style.transform='none'">
                <div style="font-size:1.5rem;margin-bottom:0.2rem">🟡</div>
                <div style="font-weight:900;font-size:0.75rem;color:#D97706">AMARILLO</div>
                <div style="font-size:0.6rem;color:#92400E;font-weight:600">Urgente</div>
                <div style="font-size:0.55rem;color:#A8A29E">60 min</div>
            </div>
        </label>
        <label style="cursor:pointer">
            <input type="radio" name="triage_level" value="Verde" style="display:none">
            <div class="triage-card" style="padding:0.75rem;border-radius:8px;text-align:center;border:2px solid #BBF7D0;background:#F0FDF4;transition:all 0.15s" onmouseover="this.style.transform='scale(1.03)'" onmouseout="this.style.transform='none'">
                <div style="font-size:1.5rem;margin-bottom:0.2rem">🟢</div>
                <div style="font-weight:900;font-size:0.75rem;color:#166534">VERDE</div>
                <div style="font-size:0.6rem;color:#166534;font-weight:600">Menos Urgente</div>
                <div style="font-size:0.55rem;color:#A8A29E">120 min</div>
            </div>
        </label>
        <label style="cursor:pointer">
            <input type="radio" name="triage_level" value="Azul" style="display:none">
            <div class="triage-card" style="padding:0.75rem;border-radius:8px;text-align:center;border:2px solid #BFDBFE;background:#EFF6FF;transition:all 0.15s" onmouseover="this.style.transform='scale(1.03)'" onmouseout="this.style.transform='none'">
                <div style="font-size:1.5rem;margin-bottom:0.2rem">🔵</div>
                <div style="font-weight:900;font-size:0.75rem;color:#1E40AF">AZUL</div>
                <div style="font-size:0.6rem;color:#1E40AF;font-weight:600">No Urgente</div>
                <div style="font-size:0.55rem;color:#A8A29E">240 min</div>
            </div>
        </label>
    </div>
</div>

{{-- SECCIÓN 3: Motivo de Consulta --}}
<div class="card" style="padding:1.25rem;margin-bottom:1rem">
    <h4 style="font-weight:800;margin-bottom:0.75rem;font-size:0.85rem;color:#EA580C"><i class="fas fa-stethoscope"></i> Motivo de Consulta</h4>
    <div style="margin-bottom:0.75rem">
        <label style="font-size:0.7rem;font-weight:700;color:#78716C">Motivo Principal *</label>
        <textarea name="chief_complaint" class="inp" rows="2" required placeholder="Describe el motivo de la consulta..."></textarea>
    </div>
    <div style="margin-bottom:0.75rem">
        <label style="font-size:0.7rem;font-weight:700;color:#78716C">Síntomas</label>
        <textarea name="symptoms" class="inp" rows="2" placeholder="Fiebre, dolor de cabeza, náuseas..."></textarea>
    </div>
    <div>
        <label style="font-size:0.7rem;font-weight:700;color:#78716C">Alergias Conocidas</label>
        <input type="text" name="allergies" class="inp" placeholder="Penicilina, Aspirina, Ninguna conocida...">
    </div>
</div>

{{-- SECCIÓN 4: Diagnóstico Inicial --}}
<div class="card" style="padding:1.25rem;margin-bottom:1rem">
    <h4 style="font-weight:800;margin-bottom:0.75rem;font-size:0.85rem;color:#EA580C"><i class="fas fa-file-medical"></i> Diagnóstico Inicial</h4>
    <div style="display:grid;grid-template-columns:2fr 1fr;gap:0.75rem;margin-bottom:0.75rem">
        <div>
            <label style="font-size:0.7rem;font-weight:700;color:#78716C">Diagnóstico Presuntivo</label>
            <input type="text" name="diagnostico" class="inp" placeholder="Neumonía adquirida en comunidad">
        </div>
        <div>
            <label style="font-size:0.7rem;font-weight:700;color:#78716C">Código CIE-10</label>
            <input type="text" name="cie10" class="inp" placeholder="J18.9">
        </div>
    </div>
    <div>
        <label style="font-size:0.7rem;font-weight:700;color:#78716C">Notas Médicas</label>
        <textarea name="doctor_notes" class="inp" rows="2" placeholder="Observaciones adicionales..."></textarea>
    </div>
</div>

{{-- SECCIÓN 5: Asignación --}}
@if($isA)
<div class="card" style="padding:1.25rem;margin-bottom:1rem">
    <h4 style="font-weight:800;margin-bottom:0.75rem;font-size:0.85rem;color:#EA580C"><i class="fas fa-user-md"></i> Asignar Médico</h4>
    <select name="assigned_doctor" class="inp">
        <option value="">Asignarme a mí</option>
        @foreach($medicos as $m)
        <option value="{{$m->id}}">{{$m->name}} ({{$m->role}})</option>
        @endforeach
    </select>
</div>
@endif

{{-- Botones --}}
<div style="display:flex;gap:0.75rem;margin-top:1.5rem">
    <button type="submit" style="flex:1;padding:0.85rem;background:linear-gradient(135deg,#EA580C,#DC2626);color:#fff;border:none;border-radius:10px;font-weight:900;cursor:pointer;font-size:0.95rem">
        <i class="fas fa-user-plus"></i> Registrar Paciente
    </button>
    <a href="{{route('medico.pacientes')}}" style="padding:0.85rem 1.5rem;background:#E7E5E4;color:#57534E;border-radius:10px;text-decoration:none;font-weight:700;font-size:0.85rem">Cancelar</a>
</div>
</form>

{{-- Script para seleccionar triaje visual --}}
<script>
document.querySelectorAll('input[name="triage_level"]').forEach(radio => {
    radio.addEventListener('change', function() {
        document.querySelectorAll('.triage-card').forEach(card => {
            card.style.transform = 'none';
            card.style.boxShadow = 'none';
            card.style.outline = 'none';
        });
        if (this.checked) {
            const card = this.closest('label').querySelector('.triage-card');
            card.style.boxShadow = '0 0 0 3px rgba(234,88,12,0.5)';
            card.style.transform = 'scale(1.05)';
        }
    });
});
</script>
@endsection
