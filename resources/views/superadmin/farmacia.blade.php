@extends('superadmin.layout')
@section('title', 'Centro de Operaciones Farmacéuticas')
@section('nav-farmacia', 'active')

@section('content')
<!-- Alerta Prioridad ER (Triage Rojo) -->
@if($critical_patients->count() > 0)
<div style="background: #C7291C; color: white; padding: 1.5rem; border-radius: 12px; margin-bottom: 2rem; display:flex; align-items:center; gap:1rem; animation: pulse 2s infinite;">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>@keyframes pulse { 0% { opacity: 1; } 50% { opacity: 0.7; } 100% { opacity: 1; } }</style>
    <i class="fas fa-ambulance" style="font-size: 2rem;"></i>
    <div>
        <h3 style="font-weight:800;">PRIORIDAD MÁXIMA - URGENCIAS CRÍTICAS</h3>
        <p style="font-size:0.9rem;">Hay {{ $critical_patients->count() }} paciente(s) Triage Rojo. Apartar medicamentos de Urgencias inmediatamente.</p>
    </div>
</div>
@endif

<!-- Inventario Segmentado -->
<div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem; margin-bottom: 2rem;">
    @foreach(['Central', 'Hospitalaria', 'Quirófano', 'Urgencias'] as $origin)
    @php $count = $medications->where('origin', $origin)->count(); @endphp
    <div style="background: white; padding: 1.5rem; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.04); border-top: 4px solid {{ $origin == 'Quirófano' ? '#C7291C' : ($origin == 'Urgencias' ? '#FF8C42' : '#2D9E6A') }};">
        <h4 style="font-weight: 800; margin-bottom: 0.5rem;">{{ $origin }}</h4>
        <p style="font-size: 2rem; font-weight: 800;">{{ $count }}</p>
        <p style="font-size: 0.8rem; color: #736860;">Medicamentos registrados</p>
    </div>
    @endforeach
</div>

<!-- Receta Médica y Validación A/B/C -->
<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
    
    <!-- Formulario Receta -->
    <div style="background: white; padding: 2rem; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.04);">
        <h3 style="font-weight: 800; margin-bottom: 1.5rem;"><i class="fas fa-prescription" style="color: #F05A4E;"></i> Receta Médica (Simulación de Perfil)</h3>
        <form action="{{ route('superadmin.farmacia.prescribe') }}" method="POST">
            @csrf
            <div style="margin-bottom:1rem;">
                <label style="font-size:0.8rem; font-weight:700;">Actuar como Médico:</label>
                <select name="doctor_role" required style="width:100%; padding:0.5rem; border:1px solid #E5E7EB; border-radius:6px;">
                    <option value="Médico A">Nivel A (Especialista - Controlados/Opioides)</option>
                    <option value="Médico B">Nivel B (Hospitalización - Intermedios)</option>
                    <option value="Médico C" selected>Nivel C (Pasante - Básicos)</option>
                </select>
            </div>
            <div style="margin-bottom:1rem;">
                <label style="font-size:0.8rem; font-weight:700;">Paciente (Urgencias):</label>
                <select name="patient_id" required style="width:100%; padding:0.5rem; border:1px solid #E5E7EB; border-radius:6px;">
                    @foreach($critical_patients as $p)<option value="{{ $p->id }}">{{ $p->patient_name }} (Triage {{ $p->triage_level }})</option>@endforeach
                    @foreach($normal_patients as $p)<option value="{{ $p->id }}">{{ $p->patient_name }}</option>@endforeach
                </select>
            </div>
            <div style="margin-bottom:1rem;">
                <label style="font-size:0.8rem; font-weight:700;">Medicamento:</label>
                <select name="medication_id" required style="width:100%; padding:0.5rem; border:1px solid #E5E7EB; border-radius:6px;">
                    @foreach($medications as $med)<option value="{{ $med->id }}">{{ $med->name }} (Nivel: {{ $med->required_level }} | Origen: {{ $med->origin }} | Stock: {{ $med->stock }})</option>@endforeach
                </select>
            </div>
            <button type="submit" style="width:100%; background:#1E1A17; color:white; border:none; padding:0.8rem; border-radius:8px; font-weight:700; cursor:pointer;"><i class="fas fa-file-medical"></i> Intentar Prescribir</button>
        </form>
    </div>

    <!-- Log de Recetas (Auditoría) -->
    <div style="background: white; padding: 2rem; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.04);">
        <h3 style="font-weight: 800; margin-bottom: 1.5rem;"><i class="fas fa-history" style="color: #3B82F6;"></i> Validación Reciente</h3>
        @if(session('prescription_result'))
            <div style="background: {{ session('prescription_status') == 'Denegada' ? '#FFF1F0' : '#EBF9F2' }}; padding: 1.5rem; border-radius: 8px; margin-bottom: 1rem; border-left: 4px solid {{ session('prescription_status') == 'Denegada' ? '#C7291C' : '#2D9E6A' }};">
                <h4 style="color: {{ session('prescription_status') == 'Denegada' ? '#8C1A11' : '#065F46' }}">{{ session('prescription_status') == 'Denegada' ? 'ACCESO DENEGADO' : 'RECETA AUTORIZADA' }}</h4>
                <p style="font-size:0.9rem; color:#1E1A17;">{{ session('prescription_result') }}</p>
            </div>
        @endif

        @foreach($prescriptions as $presc)
        <div style="border-left: 3px solid {{ $presc->status == 'Denegada' ? '#C7291C' : '#2D9E6A' }}; padding-left: 1rem; margin-bottom: 1rem;">
            <div style="font-size:0.8rem; color:#736860;">{{ $presc->created_at->format('H:i') }} - {{ $presc->doctor->name ?? 'Sistema' }}</div>
            <div style="font-weight:700;">{{ $presc->medication->name ?? 'N/A' }} para {{ $presc->patient->patient_name ?? 'N/A' }}</div>
            <div style="font-size:0.85rem; font-weight:700; color: {{ $presc->status == 'Denegada' ? '#C7291C' : '#2D9E6A' }}">{{ $presc->status }} {{ $presc->is_priority ? '(PRIORIDAD ER)' : '' }}</div>
            @if($presc->denial_reason)<div style="font-size:0.8rem; color:#8C1A11;">Motivo: {{ $presc->denial_reason }}</div>@endif
        </div>
        @endforeach
    </div>
</div>

<!-- Tabla Completa de Inventario -->
<div style="background: white; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.04); overflow: hidden; margin-top: 2rem;">
    <table style="width: 100%; border-collapse: collapse;">
        <thead><tr style="background: #1E1A17; color: white; text-align:left;"><th style="padding:1rem;">Medicamento</th><th style="padding:1rem;">Nivel Requerido</th><th style="padding:1rem;">Origen</th><th style="padding:1rem;">Stock</th></tr></thead>
        <tbody>
            @foreach($medications as $med)
            <tr style="border-bottom:1px solid #E5E7EB;">
                <td style="padding:1rem; font-weight:700;">{{ $med->name }}</td>
                <td style="padding:1rem;"><span style="background:{{ $med->required_level == 'A' ? '#C7291C' : ($med->required_level == 'B' ? '#FF8C42' : '#2D9E6A') }}; color:white; padding:0.2rem 0.5rem; border-radius:10px; font-size:0.75rem; font-weight:700;">Nivel {{ $med->required_level }}</span></td>
                <td style="padding:1rem; font-size:0.9rem;">{{ $med->origin }}</td>
                <td style="padding:1rem; font-weight:800; color:{{ $med->stock <= $med->min_stock ? '#C7291C' : '#1E1A17' }}">{{ $med->stock }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
