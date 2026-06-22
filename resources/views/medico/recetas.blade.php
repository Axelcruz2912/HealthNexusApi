@extends('medico.layout')
@section('title', 'Recetas')
@section('nav-recetas', 'active')
@section('content')
<div style="background:white; padding:1.5rem; border-radius:12px; box-shadow:0 4px 6px rgba(0,0,0,0.04); margin-bottom:1.5rem;">
    <h3 style="font-weight:800;"><i class="fas fa-prescription" style="color:#3B82F6;"></i> Recetas Médicas</h3>
</div>
<div style="display:grid; grid-template-columns:1fr 1.5fr; gap:1.5rem;">
    <div style="background:white; padding:1.5rem; border-radius:12px; box-shadow:0 4px 6px rgba(0,0,0,0.04);">
        <h4 style="font-weight:800; margin-bottom:1rem;"><i class="fas fa-plus-circle"></i> Nueva Receta</h4>
        <form method="POST" action="{{ route('medico.storeReceta') }}">
            @csrf
            <div style="margin-bottom:0.75rem;">
                <label style="font-size:0.8rem; font-weight:700; color:#64748B;">Paciente</label>
                <select name="triage_id" style="width:100%; padding:0.5rem; border:1px solid #E2E8F0; border-radius:8px;" required>
                    <option value="">Seleccionar paciente...</option>
                    @foreach($pacientes as $p)
                    <option value="{{ $p->id }}">{{ $p->patient_name }} (Triage: {{ $p->triage_level }})</option>
                    @endforeach
                </select>
            </div>
            <div style="margin-bottom:0.75rem;">
                <label style="font-size:0.8rem; font-weight:700; color:#64748B;">Medicamento</label>
                <select name="medication_id" style="width:100%; padding:0.5rem; border:1px solid #E2E8F0; border-radius:8px;" required>
                    <option value="">Seleccionar medicamento...</option>
                    @foreach($medicamentos as $m)
                    <option value="{{ $m->id }}">{{ $m->name }} (Stock: {{ $m->stock }})</option>
                    @endforeach
                </select>
            </div>
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:0.5rem; margin-bottom:0.75rem;">
                <div>
                    <label style="font-size:0.8rem; font-weight:700; color:#64748B;">Cantidad</label>
                    <input type="number" name="quantity" value="1" min="1" style="width:100%; padding:0.5rem; border:1px solid #E2E8F0; border-radius:8px;">
                </div>
                <div>
                    <label style="font-size:0.8rem; font-weight:700; color:#64748B;">Dosis</label>
                    <input type="text" name="dosis" placeholder="Ej: 500mg" style="width:100%; padding:0.5rem; border:1px solid #E2E8F0; border-radius:8px;">
                </div>
            </div>
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:0.5rem; margin-bottom:0.75rem;">
                <div>
                    <label style="font-size:0.8rem; font-weight:700; color:#64748B;">Frecuencia</label>
                    <input type="text" name="frecuencia" placeholder="Ej: Cada 8hrs" style="width:100%; padding:0.5rem; border:1px solid #E2E8F0; border-radius:8px;">
                </div>
                <div>
                    <label style="font-size:0.8rem; font-weight:700; color:#64748B;">Duración</label>
                    <input type="text" name="duracion" placeholder="Ej: 7 días" style="width:100%; padding:0.5rem; border:1px solid #E2E8F0; border-radius:8px;">
                </div>
            </div>
            <div style="margin-bottom:0.75rem;">
                <label style="font-size:0.8rem; font-weight:700; color:#64748B;">Indicaciones</label>
                <textarea name="indicaciones" rows="2" style="width:100%; padding:0.5rem; border:1px solid #E2E8F0; border-radius:8px;" placeholder="Indicaciones adicionales..."></textarea>
            </div>
            <button type="submit" style="width:100%; padding:0.75rem; background:#3B82F6; color:white; border:none; border-radius:8px; font-weight:800; cursor:pointer;">
                <i class="fas fa-paper-plane"></i> Enviar a Farmacia
            </button>
        </form>
    </div>
    <div style="background:white; padding:1.5rem; border-radius:12px; box-shadow:0 4px 6px rgba(0,0,0,0.04);">
        <h4 style="font-weight:800; margin-bottom:1rem;"><i class="fas fa-history"></i> Mis Recetas Emitidas</h4>
        <table style="width:100%; border-collapse:collapse; font-size:0.85rem;">
            <thead>
                <tr style="background:#F1F5F9;">
                    <th style="padding:0.5rem; text-align:left; border-bottom:2px solid #E2E8F0;">Paciente</th>
                    <th style="padding:0.5rem; text-align:left; border-bottom:2px solid #E2E8F0;">Medicamento</th>
                    <th style="padding:0.5rem; text-align:left; border-bottom:2px solid #E2E8F0;">Dosis</th>
                    <th style="padding:0.5rem; text-align:left; border-bottom:2px solid #E2E8F0;">Frec.</th>
                    <th style="padding:0.5rem; text-align:left; border-bottom:2px solid #E2E8F0;">Estado</th>
                    <th style="padding:0.5rem; text-align:center; border-bottom:2px solid #E2E8F0;">Acción</th>
                </tr>
            </thead>
            <tbody>
                @foreach($misRecetas as $r)
                <tr style="border-bottom:1px solid #F1F5F9;">
                    <td style="padding:0.5rem;">{{ $r->patient_name ?? 'N/A' }}</td>
                    <td style="padding:0.5rem;">{{ $r->medication_name ?? 'N/A' }}</td>
                    <td style="padding:0.5rem;">{{ $r->dosis ?? $r->quantity ?? '-' }}</td>
                    <td style="padding:0.5rem;">{{ $r->frecuencia ?? '-' }}</td>
                    <td style="padding:0.5rem;">
                        @php $sc = $r->status == 'Pendiente' ? '#F59E0B' : ($r->status == 'Dispensada' ? '#10B981' : '#EF4444'); @endphp
                        <span style="background:{{ $sc }}20; color:{{ $sc }}; padding:0.2rem 0.5rem; border-radius:4px; font-weight:700; font-size:0.75rem;">{{ $r->status }}</span>
                    </td>
                    <td style="padding:0.5rem; text-align:center;">
                        @if($r->status == 'Pendiente')
                        <form method="POST" action="{{ route('medico.cancelarReceta', $r->id) }}" style="display:inline;" onsubmit="return confirm('¿Cancelar receta?')">
                            @csrf @method('PUT')
                            <button style="background:#EF4444; color:white; border:none; border-radius:4px; padding:0.2rem 0.5rem; cursor:pointer; font-size:0.75rem;">Cancelar</button>
                        </form>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div style="margin-top:1rem;">{{ $misRecetas->withQueryString()->links() }}</div>
    </div>
</div>
@endsection
