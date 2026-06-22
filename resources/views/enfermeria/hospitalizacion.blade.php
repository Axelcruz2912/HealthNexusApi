@extends('enfermeria.layout')
@section('title', 'Hospitalizacion')
@section('nav-hospitalización', 'active')

@section('content')
<div style="display:grid; grid-template-columns:1fr 1fr; gap:1.5rem;">
    <!-- Ingresar Paciente -->
    <div style="background:white; padding:1.5rem; border-radius:12px; box-shadow:0 4px 6px rgba(0,0,0,0.04);">
        <h3 style="font-weight:800; margin-bottom:1.25rem;"><i class="fas fa-bed" style="color:#DC2626;"></i> Ingresar Paciente</h3>
        <form method="POST" action="{{ route('enfermeria.storeHospitalization') }}">
            @csrf
            <div style="margin-bottom:0.75rem;">
                <label style="font-size:0.8rem; font-weight:700; color:#64748B;">Paciente</label>
                <select name="triage_id" required style="width:100%; padding:0.6rem; border:1px solid #E2E8F0; border-radius:8px;">
                    @foreach($activePatients as $p)<option value="{{ $p->id }}">{{ $p->patient_name }} (Triage {{ $p->triage_level }})</option>@endforeach
                </select>
            </div>
            <div style="margin-bottom:0.75rem;">
                <label style="font-size:0.8rem; font-weight:700; color:#64748B;">Cama Disponible</label>
                <select name="bed_id" required style="width:100%; padding:0.6rem; border:1px solid #E2E8F0; border-radius:8px;">
                    @foreach($beds->where('status','Disponible') as $b)<option value="{{ $b->id }}">Piso {{ $b->floor }} - Hab {{ $b->room_number }} Cama {{ $b->bed_number }} ({{ $b->type }})</option>@endforeach
                </select>
            </div>
            <div style="margin-bottom:0.75rem;">
                <label style="font-size:0.8rem; font-weight:700; color:#64748B;">Medico Tratante</label>
                <select name="doctor_id" required style="width:100%; padding:0.6rem; border:1px solid #E2E8F0; border-radius:8px;">
                    @foreach($doctors as $d)<option value="{{ $d->id }}">{{ $d->name }} ({{ $d->role }})</option>@endforeach
                </select>
            </div>
            <div style="margin-bottom:1rem;">
                <label style="font-size:0.8rem; font-weight:700; color:#64748B;">Diagnostico</label>
                <input type="text" name="diagnosis" style="width:100%; padding:0.6rem; border:1px solid #E2E8F0; border-radius:8px;">
            </div>
            <button type="submit" style="width:100%; background:#DC2626; color:white; border:none; padding:0.75rem; border-radius:8px; font-weight:700; cursor:pointer;"><i class="fas fa-plus"></i> Hospitalizar</button>
        </form>
    </div>

    <!-- Pacientes Hospitalizados -->
    <div style="background:white; padding:1.5rem; border-radius:12px; box-shadow:0 4px 6px rgba(0,0,0,0.04);">
        <h3 style="font-weight:800; margin-bottom:1.25rem;"><i class="fas fa-procedures" style="color:#F97316;"></i> Pacientes Internados ({{ $hospitalizations->count() }})</h3>
        @foreach($hospitalizations as $h)
        <div style="border-left:3px solid #DC2626; padding:0.75rem; margin-bottom:0.75rem; background:#F8FAFC; border-radius:0 8px 8px 0;">
            <div style="font-weight:700;">{{ $h->triage->patient_name ?? 'N/A' }}</div>
            <div style="font-size:0.8rem; color:#64748B;">Cama: Piso {{ $h->bed->floor ?? '-' }} Hab {{ $h->bed->room_number ?? '-' }} | Dr: {{ $h->doctor->name ?? '-' }}</div>
            <div style="font-size:0.75rem; color:#94A3B8;">Ingreso: {{ $h->admission_date ?? '-' }}</div>
            @if($h->diagnosis)<div style="font-size:0.8rem; color:#475569; margin-top:0.25rem;">DX: {{ $h->diagnosis }}</div>@endif
        </div>
        @endforeach
    </div>
</div>
{{ $hospitalizations->withQueryString()->links() }}
@endsection
