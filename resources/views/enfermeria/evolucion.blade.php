@extends('enfermeria.layout')
@section('title', 'Evolucion Enfermeria')
@section('nav-evolución', 'active')

@section('content')
<div style="display:grid; grid-template-columns:1fr 1fr; gap:1.5rem;">
    <!-- Nueva Nota -->
    <div style="background:white; padding:1.5rem; border-radius:12px; box-shadow:0 4px 6px rgba(0,0,0,0.04);">
        <h3 style="font-weight:800; margin-bottom:1.25rem;"><i class="fas fa-notes-medical" style="color:#DC2626;"></i> Nota de Evolucion</h3>
        <form method="POST" action="{{ route('enfermeria.storeEvolution') }}">
            @csrf
            <div style="margin-bottom:0.75rem;">
                <label style="font-size:0.8rem; font-weight:700; color:#64748B;">Paciente</label>
                <select name="triage_id" required style="width:100%; padding:0.6rem; border:1px solid #E2E8F0; border-radius:8px;">
                    @foreach($patients as $p)<option value="{{ $p->id }}">{{ $p->patient_name }} ({{ $p->status }})</option>@endforeach
                </select>
            </div>
            <div style="margin-bottom:0.75rem;">
                <label style="font-size:0.8rem; font-weight:700; color:#64748B;">Prioridad</label>
                <select name="priority" style="width:100%; padding:0.6rem; border:1px solid #E2E8F0; border-radius:8px;">
                    <option value="Normal">Normal</option>
                    <option value="Urgente">Urgente</option>
                </select>
            </div>
            <div style="margin-bottom:0.75rem;">
                <label style="font-size:0.8rem; font-weight:700; color:#64748B;">Notas de Evolucion</label>
                <textarea name="notes" required rows="4" style="width:100%; padding:0.6rem; border:1px solid #E2E8F0; border-radius:8px;"></textarea>
            </div>
            <div style="margin-bottom:1rem;">
                <label style="display:flex; align-items:center; gap:0.5rem; cursor:pointer;">
                    <input type="checkbox" name="alert_doctor" value="1" style="width:18px; height:18px; accent-color:#DC2626;">
                    <span style="font-weight:700; color:#DC2626; font-size:0.85rem;"><i class="fas fa-exclamation-triangle"></i> Alertar al Medico</span>
                </label>
            </div>
            <button type="submit" style="width:100%; background:#DC2626; color:white; border:none; padding:0.75rem; border-radius:8px; font-weight:700; cursor:pointer;"><i class="fas fa-save"></i> Guardar Nota</button>
        </form>
    </div>

    <!-- Notas Recientes -->
    <div style="background:white; padding:1.5rem; border-radius:12px; box-shadow:0 4px 6px rgba(0,0,0,0.04);">
        <h3 style="font-weight:800; margin-bottom:1.25rem;"><i class="fas fa-history" style="color:#64748B;"></i> Notas Recientes</h3>
        @foreach($evolutions as $e)
        <div style="border-left:3px solid {{ $e->priority === 'Urgente' ? '#DC2626' : '#DC2626' }}; padding:0.75rem; margin-bottom:0.75rem; background:{{ $e->alert_doctor ? '#FEF2F2' : '#FAFAFA' }}; border-radius:0 8px 8px 0;">
            <div style="display:flex; justify-content:space-between;">
                <span style="font-weight:700;">{{ $e->triage->patient_name ?? 'N/A' }}</span>
                <span style="font-size:0.7rem; color:#94A3B8;">{{ $e->created_at->format('d/m H:i') }}</span>
            </div>
            <p style="font-size:0.85rem; color:#475569; margin:0.25rem 0;">{{ $e->notes }}</p>
            <div style="display:flex; gap:0.5rem; margin-top:0.25rem;">
                <span style="background:{{ $e->priority === 'Urgente' ? '#FEF2F2' : '#FEF2F2' }}; color:{{ $e->priority === 'Urgente' ? '#DC2626' : '#DC2626' }}; padding:0.1rem 0.4rem; border-radius:4px; font-size:0.65rem; font-weight:700;">{{ $e->priority }}</span>
                @if($e->alert_doctor)<span style="background:#FEF2F2; color:#DC2626; padding:0.1rem 0.4rem; border-radius:4px; font-size:0.65rem; font-weight:700;">ALERTA MEDICO</span>@endif
            </div>
        </div>
        @endforeach
    </div>
</div>
{{ $patients->withQueryString()->links() }}
@endsection
