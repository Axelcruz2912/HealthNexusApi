@extends('medico.layout')
@section('title', 'Agenda Hospitalaria')
@section('nav-agenda', 'active')

@section('content')
<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:1rem;margin-bottom:2rem">
    <div style="background:#FFF7ED;border:2px solid #FDBA74;border-radius:14px;padding:1.2rem;text-align:center">
        <div style="font-size:2rem;font-weight:900;color:#EA580C">{{ $appointments->count() }}</div>
        <div style="font-size:0.8rem;font-weight:700;color:#9A3412">Citas Hoy</div>
    </div>
    <div style="background:#FFEDD5;border:2px solid #F97316;border-radius:14px;padding:1.2rem;text-align:center">
        <div style="font-size:2rem;font-weight:900;color:#F97316">{{ $pending }}</div>
        <div style="font-size:0.8rem;font-weight:700;color:#9A3412">Pendientes</div>
    </div>
    <div style="background:#FEE2E2;border:2px solid #EF4444;border-radius:14px;padding:1.2rem;text-align:center">
        <div style="font-size:2rem;font-weight:900;color:#DC2626">{{ $appointments->where('priority', 'Crítica')->count() }}</div>
        <div style="font-size:0.8rem;font-weight:700;color:#991B1B">Críticas</div>
    </div>
    <div style="background:#FEF2F2;border:2px solid #FCA5A5;border-radius:14px;padding:1.2rem;text-align:center">
        <div style="font-size:2rem;font-weight:900;color:#B91C1C">{{ $appointments->where('status', 'Completada')->count() }}</div>
        <div style="font-size:0.8rem;font-weight:700;color:#991B1B">Completadas</div>
    </div>
</div>

<div style="display:grid;grid-template-columns:2fr 1fr;gap:1.5rem">
    <!-- LISTA DE CITAS -->
    <div style="background:white;border-radius:16px;padding:1.5rem;box-shadow:0 4px 12px rgba(249,115,22,0.06);border-top:4px solid #EA580C">
        <h3 style="font-weight:900;color:#9A3412;margin-bottom:1.2rem"><i class="fas fa-calendar-alt" style="color:#EA580C"></i> Agenda de Hoy - {{ now()->format('d/m/Y') }}</h3>
        
        @if($appointments->count() > 0)
            @foreach($appointments as $apt)
            @php
                $statusColors = [
                    'Programada' => '#F59E0B', 'Confirmada' => '#F97316', 'En Espera' => '#EA580C', 
                    'En Curso' => '#DC2626', 'Completada' => '#059669', 'Cancelada' => '#6B7280', 
                    'No Asistió' => '#991B1B', 'Reagendada' => '#7C3AED'
                ];
                $typeIcons = [
                    'Consulta' => 'fa-stethoscope', 'Cirugía' => 'fa-procedures', 'Revisión' => 'fa-clipboard-check',
                    'Estudio' => 'fa-x-ray', 'Urgencia' => 'fa-ambulance', 'Hospitalización' => 'fa-bed',
                    'Monitoreo' => 'fa-heartbeat', 'Surtido' => 'fa-pills', 'Pago' => 'fa-file-invoice-dollar'
                ];
                $priorityColors = ['Normal' => '#F97316', 'Urgente' => '#EA580C', 'Crítica' => '#DC2626'];
            @endphp
            <div style="border:1px solid #FED7AA;border-radius:10px;padding:1rem;margin-bottom:0.8rem;display:flex;justify-content:space-between;align-items:center;background:{{ $apt->status == 'Completada' ? '#F9FAFB' : 'white' }};opacity:{{ $apt->status == 'Completada' ? '0.6' : '1' }}">
                <div style="display:flex;align-items:center;gap:1rem">
                    <div style="text-align:center;min-width:60px">
                        <div style="font-size:1.2rem;font-weight:900;color:#EA580C">{{ $apt->scheduled_at->format('H:i') }}</div>
                        <div style="font-size:0.6rem;color:#9A3412">{{ $apt->estimated_end ? $apt->estimated_end->format('H:i') : '' }}</div>
                    </div>
                    <div style="border-left:3px solid {{ $priorityColors[$apt->priority] }};padding-left:1rem">
                        <div style="font-weight:800;font-size:0.9rem;color:#1E1A17">
                            <i class="fas {{ $typeIcons[$apt->type] ?? 'fa-calendar' }}" style="color:{{ $priorityColors[$apt->priority] }}"></i>
                            {{ $apt->patient->patient_name ?? 'Paciente' }}
                        </div>
                        <div style="font-size:0.75rem;color:#78716C">Dr. {{ $apt->doctor->name }} | {{ $apt->type }} @if($apt->location) | {{ $apt->location }} @endif</div>
                        @if($apt->notes)<div style="font-size:0.7rem;color:#D97706;margin-top:0.2rem"><i class="fas fa-comment"></i> {{ $apt->notes }}</div>@endif
                    </div>
                </div>
                <div style="display:flex;flex-direction:column;align-items:flex-end;gap:0.3rem">
                    <span style="background:{{ $statusColors[$apt->status] }}20;color:{{ $statusColors[$apt->status] }};padding:0.2rem 0.6rem;border-radius:6px;font-size:0.7rem;font-weight:800">{{ $apt->status }}</span>
                    @if($apt->priority != 'Normal')
                    <span style="background:{{ $priorityColors[$apt->priority] }};color:white;padding:0.15rem 0.5rem;border-radius:4px;font-size:0.6rem;font-weight:800">{{ $apt->priority }}</span>
                    @endif
                    @if($apt->status != 'Completada' && $apt->status != 'Cancelada')
                    <form action="{{ route('agenda.updateStatus', $apt->id) }}" method="POST" style="display:flex;gap:0.2rem">
                        @csrf
                        <button type="submit" name="status" value="Completada" style="padding:0.2rem 0.5rem;background:#059669;color:white;border:none;border-radius:4px;font-size:0.65rem;cursor:pointer"><i class="fas fa-check"></i></button>
                        <button type="submit" name="status" value="Cancelada" style="padding:0.2rem 0.5rem;background:#6B7280;color:white;border:none;border-radius:4px;font-size:0.65rem;cursor:pointer"><i class="fas fa-times"></i></button>
                    </form>
                    @endif
                </div>
            </div>
            @endforeach
        @else
            <p style="text-align:center;color:#D97706;padding:3rem;font-weight:700"><i class="fas fa-calendar-check" style="font-size:2rem;display:block;margin-bottom:0.5rem"></i>Sin citas programadas para hoy</p>
        @endif
    </div>

    <!-- FORMULARIO NUEVA CITA -->
    <div style="background:white;border-radius:16px;padding:1.5rem;box-shadow:0 4px 12px rgba(249,115,22,0.06);border-top:4px solid #F97316">
        <h3 style="font-weight:900;color:#9A3412;margin-bottom:1.2rem"><i class="fas fa-plus-circle" style="color:#F97316"></i> Agendar Cita</h3>
        <form action="{{ route('agenda.store') }}" method="POST">
            @csrf
            <label style="font-weight:800;font-size:0.8rem;color:#7C2D12">Paciente</label>
            <select name="patient_id" required style="width:100%;padding:0.6rem;border:2px solid #FDBA74;border-radius:8px;font-size:0.85rem;margin-top:0.3rem;margin-bottom:0.8rem;background:#FFF7ED">
                <option value="">Seleccionar...</option>
                @foreach($patients as $p)<option value="{{ $p->id }}">{{ $p->patient_name }} ({{ $p->triage_level }})</option>@endforeach
            </select>

            <label style="font-weight:800;font-size:0.8rem;color:#7C2D12">Médico / Especialista</label>
            <select name="doctor_id" required style="width:100%;padding:0.6rem;border:2px solid #FDBA74;border-radius:8px;font-size:0.85rem;margin-top:0.3rem;margin-bottom:0.8rem;background:#FFF7ED">
                <option value="">Seleccionar...</option>
                @foreach($doctors as $d)<option value="{{ $d->id }}">{{ $d->name }} - {{ $d->specialty->name ?? 'General' }}</option>@endforeach
            </select>

            <label style="font-weight:800;font-size:0.8rem;color:#7C2D12">Especialidad (Opcional)</label>
            <select name="specialty_id" style="width:100%;padding:0.6rem;border:2px solid #FDBA74;border-radius:8px;font-size:0.85rem;margin-top:0.3rem;margin-bottom:0.8rem;background:#FFF7ED">
                <option value="">Ninguna</option>
                @foreach($specialties as $s)<option value="{{ $s->id }}" style="color:{{ $s->color }}">{{ $s->name }}</option>@endforeach
            </select>

            <label style="font-weight:800;font-size:0.8rem;color:#7C2D12">Tipo de Cita</label>
            <select name="type" required style="width:100%;padding:0.6rem;border:2px solid #FDBA74;border-radius:8px;font-size:0.85rem;margin-top:0.3rem;margin-bottom:0.8rem;background:#FFF7ED">
                <option value="Consulta">🩺 Consulta</option>
                <option value="Revisión">📋 Revisión</option>
                <option value="Urgencia">🚑 Urgencia</option>
                <option value="Cirugía">🔪 Cirugía</option>
                <option value="Estudio">🔬 Estudio</option>
                <option value="Hospitalización">🛏️ Hospitalización</option>
                <option value="Monitoreo">❤️ Monitoreo</option>
                <option value="Surtido">💊 Surtido Farmacia</option>
                <option value="Pago">💰 Pago</option>
            </select>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:0.5rem">
                <div>
                    <label style="font-weight:800;font-size:0.8rem;color:#7C2D12">Fecha/Hora</label>
                    <input type="datetime-local" name="scheduled_at" required style="width:100%;padding:0.6rem;border:2px solid #FDBA74;border-radius:8px;font-size:0.85rem;margin-top:0.3rem;background:#FFF7ED">
                </div>
                <div>
                    <label style="font-weight:800;font-size:0.8rem;color:#7C2D12">Prioridad</label>
                    <select name="priority" style="width:100%;padding:0.6rem;border:2px solid #FDBA74;border-radius:8px;font-size:0.85rem;margin-top:0.3rem;background:#FFF7ED">
                        <option value="Normal">Normal</option>
                        <option value="Urgente">Urgente</option>
                        <option value="Crítica">Crítica</option>
                    </select>
                </div>
            </div>

            <label style="font-weight:800;font-size:0.8rem;color:#7C2D12;margin-top:0.8rem;display:block">Ubicación</label>
            <input type="text" name="location" placeholder="Consultorio, Quirófano, etc." style="width:100%;padding:0.6rem;border:2px solid #FDBA74;border-radius:8px;font-size:0.85rem;margin-top:0.3rem;margin-bottom:0.8rem;background:#FFF7ED">

            <label style="font-weight:800;font-size:0.8rem;color:#7C2D12">Notas</label>
            <textarea name="notes" rows="2" style="width:100%;padding:0.6rem;border:2px solid #FDBA74;border-radius:8px;font-size:0.85rem;margin-top:0.3rem;margin-bottom:1rem;background:#FFF7ED"></textarea>

            <button type="submit" style="width:100%;padding:0.8rem;background:linear-gradient(135deg,#F97316,#EA580C);color:white;border:none;border-radius:10px;font-weight:800;font-size:0.9rem;cursor:pointer">
                <i class="fas fa-calendar-plus"></i> Agendar Cita
            </button>
        </form>
    </div>
</div>
@endsection
