@extends('enfermeria.layout')
@section('title', 'Signos Vitales')
@section('nav-signos', 'active')

@section('content')
<div style="display:grid; grid-template-columns:1fr 1fr; gap:1.5rem;">
    <!-- Formulario -->
    <div style="background:white; padding:1.5rem; border-radius:12px; box-shadow:0 4px 6px rgba(0,0,0,0.04);">
        <h3 style="font-weight:800; margin-bottom:1.25rem;"><i class="fas fa-stethoscope" style="color:#DC2626;"></i> Registrar Signos Vitales</h3>
        <form method="POST" action="{{ route('enfermeria.storeVitals') }}">
            @csrf
            <div style="margin-bottom:1rem;">
                <label style="font-size:0.8rem; font-weight:700; color:#64748B;">Paciente</label>
                <select name="triage_id" required style="width:100%; padding:0.6rem; border:1px solid #E2E8F0; border-radius:8px; margin-top:0.25rem;">
                    @foreach($patients as $p)<option value="{{ $p->id }}">{{ $p->patient_name }} (Triage {{ $p->triage_level }})</option>@endforeach
                </select>
            </div>
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:0.75rem; margin-bottom:0.75rem;">
                <div><label style="font-size:0.75rem; font-weight:700; color:#64748B;">TA (ej. 120/80)</label><input type="text" name="ta" required style="width:100%; padding:0.5rem; border:1px solid #E2E8F0; border-radius:6px;"></div>
                <div><label style="font-size:0.75rem; font-weight:700; color:#64748B;">FC (lpm)</label><input type="text" name="fc" required style="width:100%; padding:0.5rem; border:1px solid #E2E8F0; border-radius:6px;"></div>
            </div>
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:0.75rem; margin-bottom:0.75rem;">
                <div><label style="font-size:0.75rem; font-weight:700; color:#64748B;">Temp (C)</label><input type="text" name="temp" required style="width:100%; padding:0.5rem; border:1px solid #E2E8F0; border-radius:6px;"></div>
                <div><label style="font-size:0.75rem; font-weight:700; color:#64748B;">SpO2 (%)</label><input type="text" name="spo2" required style="width:100%; padding:0.5rem; border:1px solid #E2E8F0; border-radius:6px;"></div>
            </div>
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:0.75rem; margin-bottom:0.75rem;">
                <div><label style="font-size:0.75rem; font-weight:700; color:#64748B;">FR (rpm)</label><input type="text" name="fr" style="width:100%; padding:0.5rem; border:1px solid #E2E8F0; border-radius:6px;"></div>
                <div><label style="font-size:0.75rem; font-weight:700; color:#64748B;">Glucosa (mg/dL)</label><input type="text" name="glucose" style="width:100%; padding:0.5rem; border:1px solid #E2E8F0; border-radius:6px;"></div>
            </div>
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:0.75rem; margin-bottom:1rem;">
                <div><label style="font-size:0.75rem; font-weight:700; color:#64748B;">Escala Dolor (0-10)</label><input type="number" name="pain_scale" min="0" max="10" style="width:100%; padding:0.5rem; border:1px solid #E2E8F0; border-radius:6px;"></div>
                <div><label style="font-size:0.75rem; font-weight:700; color:#64748B;">Notas</label><input type="text" name="notes" style="width:100%; padding:0.5rem; border:1px solid #E2E8F0; border-radius:6px;"></div>
            </div>
            <button type="submit" style="width:100%; background:#DC2626; color:white; border:none; padding:0.75rem; border-radius:8px; font-weight:700; cursor:pointer;"><i class="fas fa-save"></i> Guardar Signos Vitales</button>
        </form>
    </div>

    <!-- Registros Recientes -->
    <div style="background:white; padding:1.5rem; border-radius:12px; box-shadow:0 4px 6px rgba(0,0,0,0.04);">
        <h3 style="font-weight:800; margin-bottom:1.25rem;"><i class="fas fa-history" style="color:#64748B;"></i> Registros Recientes</h3>
        @foreach($recentVitals as $v)
        <div style="border-left:3px solid {{ $v->is_critical ? '#DC2626' : '#DC2626' }}; padding:0.75rem; margin-bottom:0.75rem; background:{{ $v->is_critical ? '#FEF2F2' : '#FAFAFA' }}; border-radius:0 8px 8px 0;">
            <div style="display:flex; justify-content:space-between;">
                <span style="font-weight:700;">{{ $v->triage->patient_name ?? 'N/A' }}</span>
                <span style="font-size:0.7rem; color:#94A3B8;">{{ $v->created_at->format('H:i') }}</span>
            </div>
            <div style="font-size:0.75rem; font-family:monospace; color:#475569; margin-top:0.25rem;">
                TA:{{ $v->ta }} | FC:{{ $v->fc }} | T:{{ $v->temp }} | SpO2:{{ $v->spo2 }}%
                @if($v->fr) | FR:{{ $v->fr }}@endif
                @if($v->glucose) | Gluc:{{ $v->glucose }}@endif
            </div>
            @if($v->is_critical)<span style="background:#DC2626; color:white; padding:0.1rem 0.4rem; border-radius:4px; font-size:0.65rem; font-weight:700;">CRITICO</span>@endif
        </div>
        @endforeach
    </div>
</div>
{{ $patients->withQueryString()->links() }}
@endsection
