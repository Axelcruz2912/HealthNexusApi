@extends('medico.layout')
@section('title', 'Evolución')
@section('nav-evolución', 'active')
@section('content')
<div style="background:white; padding:1.5rem; border-radius:12px; box-shadow:0 4px 6px rgba(0,0,0,0.04); margin-bottom:1.5rem;">
    <h3 style="font-weight:800;"><i class="fas fa-notes-medical" style="color:#3B82F6;"></i> Evolución</h3>
</div>
<div style="display:grid; grid-template-columns:1fr 1fr; gap:1.5rem;">
    <div style="background:white; padding:1.5rem; border-radius:12px; box-shadow:0 4px 6px rgba(0,0,0,0.04);">
        <h4 style="font-weight:800; margin-bottom:1rem;">Nueva Nota</h4>
        <form method="POST" action="{{ route('medico.storeEvolucion') }}">
            @csrf
            <div style="margin-bottom:1rem;">
                <select name="triage_id" style="width:100%; padding:0.75rem; border:1px solid #E2E8F0; border-radius:8px;" required>
                    <option value="">Paciente</option>
                    @foreach($pacientes as $p)<option value="{{ $p->id }}">{{ $p->patient_name }}</option>@endforeach
                </select>
            </div>
            <div style="margin-bottom:1rem;">
                <select name="priority" style="width:100%; padding:0.75rem; border:1px solid #E2E8F0; border-radius:8px;">
                    <option value="Normal">Normal</option><option value="Urgente">Urgente</option><option value="Crítica">Crítica</option>
                </select>
            </div>
            <div style="margin-bottom:1rem;">
                <textarea name="notes" rows="4" style="width:100%; padding:0.75rem; border:1px solid #E2E8F0; border-radius:8px;" required placeholder="Nota de evolución..."></textarea>
            </div>
            <button type="submit" style="padding:0.75rem 2rem; background:#3B82F6; color:white; border:none; border-radius:8px; font-weight:800; cursor:pointer;">
                <i class="fas fa-save"></i> Guardar
            </button>
        </form>
    </div>
    <div style="background:white; padding:1.5rem; border-radius:12px; box-shadow:0 4px 6px rgba(0,0,0,0.04);">
        <h4 style="font-weight:800; margin-bottom:1rem;">Notas Recientes</h4>
        @foreach($evolutions as $e)
        <div style="padding:0.75rem; border-radius:8px; margin-bottom:0.5rem; border-left:3px solid {{ $e->priority === 'Crítica' ? '#DC2626' : ($e->priority === 'Urgente' ? '#F59E0B' : '#3B82F6') }};">
            <div style="font-weight:700; font-size:0.85rem;">{{ $e->triage->patient_name ?? 'N/A' }} <span style="font-size:0.7rem; color:#94A3B8;">{{ $e->created_at->format('d/m H:i') }}</span></div>
            <div style="font-size:0.8rem; color:#475569;">{{ $e->notes }}</div>
        </div>
        @endforeach
    </div>
</div>
@endsection
