@extends('medico.layout')
@section('title', 'Quirófano')
@section('nav-quirofano', 'active')
@section('content')
@php
    $quirofanos = \App\Models\Bed::where('type', 'Quirófano')->get();
    $cirugias = \DB::table('surgeries')->orderBy('scheduled_date', 'desc')->paginate(20);
    $pacientes = \App\Models\Triage::whereIn('status', ['En Atención', 'Hospitalizado'])->get();
@endphp

<style>
.qf-card { border-radius:16px; padding:1.5rem; text-align:center; transition:all 0.3s; }
.qf-free { background:linear-gradient(135deg,#D1FAE5,#A7F3D0); border:2px solid #10B981; }
.qf-busy { background:linear-gradient(135deg,#FEE2E2,#FECACA); border:2px solid #EF4444; }
.qf-prep { background:linear-gradient(135deg,#FEF3C7,#FDE68A); border:2px solid #F59E0B; }
</style>

<div style="background:linear-gradient(135deg,#1E293B,#334155); padding:2rem; border-radius:16px; margin-bottom:1.5rem; color:white;">
    <div style="display:flex; justify-content:space-between; align-items:center;">
        <div>
            <h3 style="font-weight:900; margin:0;"><i class="fas fa-procedures"></i> Quirófano</h3>
            <p style="opacity:0.7; margin:0.5rem 0 0;">Programación y control de cirugías</p>
        </div>
    </div>
</div>

<div style="display:grid; grid-template-columns:repeat(auto-fill,minmax(200px,1fr)); gap:1rem; margin-bottom:1.5rem;">
    @foreach($quirofanos as $q)
    @php
        $class = $q->status == 'Disponible' ? 'qf-free' : ($q->status == 'Ocupada' ? 'qf-busy' : 'qf-prep');
        $icon = $q->status == 'Disponible' ? 'fa-check-circle' : ($q->status == 'Ocupada' ? 'fa-user-md' : 'fa-clock');
    @endphp
    <div class="qf-card {{ $class }}">
        <i class="fas {{ $icon }}" style="font-size:2rem; margin-bottom:0.5rem;"></i>
        <h5 style="font-weight:900; margin:0;">{{ $q->room_number ?? 'QF-'.$q->id }}</h5>
        <div style="font-size:0.8rem; font-weight:700;">{{ $q->status }}</div>
        <div style="font-size:0.7rem; opacity:0.7;">Piso {{ $q->floor }}</div>
    </div>
    @endforeach
    @if($quirofanos->count() == 0)
    <div style="grid-column:1/-1; text-align:center; padding:2rem; color:#94A3B8;">
        <i class="fas fa-info-circle" style="font-size:2rem;"></i>
        <p>No hay quirófanos registrados. Agregue camas tipo "Quirófano" desde el mapa de camas.</p>
    </div>
    @endif
</div>

<div style="display:grid; grid-template-columns:1fr 1.5fr; gap:1.5rem;">
    <div style="background:white; border-radius:16px; padding:1.5rem; box-shadow:0 2px 10px rgba(0,0,0,0.04);">
        <h5 style="font-weight:800; margin-bottom:1rem;"><i class="fas fa-calendar-plus"></i> Programar Cirugía</h5>
        <form method="POST" action="{{ route('medico.storeServicio') }}">
            @csrf
            <input type="hidden" name="tipo" value="Cirugía">
            <div style="margin-bottom:0.75rem;">
                <label style="font-weight:700; font-size:0.85rem;">Paciente</label>
                <select name="triage_id" class="form-select form-select-sm" required>
                    <option value="">Seleccionar...</option>
                    @foreach($pacientes as $p)
                    <option value="{{ $p->id }}">{{ $p->patient_name }}</option>
                    @endforeach
                </select>
            </div>
            <div style="margin-bottom:0.75rem;">
                <label style="font-weight:700; font-size:0.85rem;">Tipo de Cirugía</label>
                <select name="descripcion" class="form-select form-select-sm" required>
                    <option value="Cirugía Mayor">Cirugía Mayor</option>
                    <option value="Cirugía Menor">Cirugía Menor</option>
                    <option value="Cirugía Ambulatoria">Cirugía Ambulatoria</option>
                    <option value="Cirugía de Emergencia">Cirugía de Emergencia</option>
                    <option value="Laparoscopía">Laparoscopía</option>
                    <option value="Trauma">Trauma</option>
                </select>
            </div>
            <div style="margin-bottom:0.75rem;">
                <label style="font-weight:700; font-size:0.85rem;">Prioridad</label>
                <select name="prioridad" class="form-select form-select-sm">
                    <option>Urgente</option>
                    <option>Normal</option>
                    <option>Programada</option>
                </select>
            </div>
            <button type="submit" style="width:100%; padding:0.75rem; background:linear-gradient(135deg,#6366F1,#4F46E5); color:white; border:none; border-radius:10px; font-weight:800; cursor:pointer;">
                <i class="fas fa-calendar-check"></i> Programar
            </button>
        </form>
    </div>
    
    <div style="background:white; border-radius:16px; padding:1.5rem; box-shadow:0 2px 10px rgba(0,0,0,0.04);">
        <h5 style="font-weight:800; margin-bottom:1rem;"><i class="fas fa-list-alt"></i> Cirugías Programadas</h5>
        @if($cirugias->count() > 0)
        <table style="width:100%; border-collapse:collapse; font-size:0.85rem;">
            <thead><tr style="background:#F1F5F9;">
                <th style="padding:0.5rem; text-align:left;">Fecha</th>
                <th style="padding:0.5rem; text-align:left;">Tipo</th>
                <th style="padding:0.5rem; text-align:left;">Prioridad</th>
                <th style="padding:0.5rem; text-align:left;">Estado</th>
            </tr></thead>
            <tbody>
                @foreach($cirugias as $c)
                <tr style="border-bottom:1px solid #F1F5F9;">
                    <td style="padding:0.5rem;">{{ $c->scheduled_date ?? $c->created_at }}</td>
                    <td style="padding:0.5rem; font-weight:700;">{{ $c->type ?? $c->tipo }}</td>
                    <td style="padding:0.5rem;">{{ $c->priority ?? $c->prioridad }}</td>
                    <td style="padding:0.5rem;"><span style="background:#F59E0B20; color:#D97706; padding:0.15rem 0.5rem; border-radius:4px; font-weight:700; font-size:0.75rem;">{{ $c->status }}</span></td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <table style="width:100%; border-collapse:collapse; font-size:0.85rem;">
            <thead><tr style="background:#F1F5F9;">
                <th style="padding:0.5rem; text-align:left;">Fecha</th>
                <th style="padding:0.5rem; text-align:left;">Tipo</th>
                <th style="padding:0.5rem; text-align:left;">Prioridad</th>
                <th style="padding:0.5rem; text-align:left;">Estado</th>
            </tr></thead>
            <tbody>
                @foreach(\DB::table('service_requests')->where('doctor_id', Auth::id())->where('tipo', 'Cirugía')->orderBy('created_at', 'desc')->take(10)->get() as $c)
                <tr style="border-bottom:1px solid #F1F5F9;">
                    <td style="padding:0.5rem;">{{ $c->created_at }}</td>
                    <td style="padding:0.5rem; font-weight:700;">{{ $c->descripcion }}</td>
                    <td style="padding:0.5rem;">{{ $c->prioridad }}</td>
                    <td style="padding:0.5rem;"><span style="background:#F59E0B20; color:#D97706; padding:0.15rem 0.5rem; border-radius:4px; font-weight:700; font-size:0.75rem;">{{ $c->status }}</span></td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>
</div>
@endsection
