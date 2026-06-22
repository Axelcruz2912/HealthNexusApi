@extends('farmacia.layout')
@section('title', 'Dispensacion de Recetas')
@section('nav-dispensacion', 'active')

@section('content')
<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
    <div style="background: white; padding: 2rem; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.04);">
        <h3 style="font-weight: 800; margin-bottom: 1.5rem;"><i class="fas fa-prescription" style="color: #F97316;"></i> Dispensar Medicamento</h3>
        
        @if(session('success'))
        <div style="background:#FFF7ED; color:#9A3412; padding:1rem; border-radius:8px; margin-bottom:1rem; border-left:4px solid #F97316;">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div style="background:#FFF1F0; color:#8C1A11; padding:1rem; border-radius:8px; margin-bottom:1rem; border-left:4px solid #C7291C;">
            <i class="fas fa-times-circle"></i> {{ session('error') }}
        </div>
        @endif

        <form action="{{ route('farmacia.dispense') }}" method="POST">
            @csrf
            <div style="margin-bottom:1rem;">
                <label style="font-size:0.8rem; font-weight:700; color:#736860;">Recetado por:</label>
                <select name="doctor_id" required style="width:100%; padding:0.6rem; border:1px solid #E5E7EB; border-radius:6px;">
                    @foreach($doctors as $doc)
                    <option value="{{ $doc->id }}">{{ $doc->name }} - {{ $doc->role }}</option>
                    @endforeach
                </select>
            </div>
            <div style="margin-bottom:1rem;">
                <label style="font-size:0.8rem; font-weight:700; color:#736860;">Paciente:</label>
                <select name="patient_id" required style="width:100%; padding:0.6rem; border:1px solid #E5E7EB; border-radius:6px;">
                    @foreach($patients as $p)
                    <option value="{{ $p->id }}">{{ $p->patient_name }} (Triage: {{ $p->triage_level }})</option>
                    @endforeach
                </select>
            </div>
            <div style="margin-bottom:1rem;">
                <label style="font-size:0.8rem; font-weight:700; color:#736860;">Medicamento:</label>
                <select name="medication_id" required style="width:100%; padding:0.6rem; border:1px solid #E5E7EB; border-radius:6px;">
                    @foreach($medications as $med)
                    <option value="{{ $med->id }}">{{ $med->name }} (Stock: {{ $med->stock }} | Nivel: {{ $med->required_level }})</option>
                    @endforeach
                </select>
            </div>
            <div style="margin-bottom:1.5rem;">
                <label style="font-size:0.8rem; font-weight:700; color:#736860;">Cantidad:</label>
                <input type="number" name="quantity" value="1" min="1" max="100" required style="width:100%; padding:0.6rem; border:1px solid #E5E7EB; border-radius:6px;">
            </div>
            <button type="submit" style="width:100%; background:#F97316; color:white; border:none; padding:0.8rem; border-radius:8px; font-weight:700; cursor:pointer;">
                <i class="fas fa-check"></i> Validar y Dispensar
            </button>
        </form>
    </div>

    <div>
        <div style="background: white; padding: 1.5rem; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.04); margin-bottom: 1.5rem;">
            <h4 style="font-weight:800; margin-bottom:1rem;"><i class="fas fa-shield-alt" style="color:#C7291C;"></i> Reglas de Seguridad</h4>
            <div style="font-size:0.85rem; color:#7C2D12;">
                <div style="padding:0.5rem 0; border-bottom:1px solid #E5E7EB;"><i class="fas fa-circle" style="color:#C7291C; font-size:0.5rem;"></i> <strong>Nivel A:</strong> Solo Medico A Especialista / Urgenciologo</div>
                <div style="padding:0.5rem 0; border-bottom:1px solid #E5E7EB;"><i class="fas fa-circle" style="color:#FF8C42; font-size:0.5rem;"></i> <strong>Nivel B:</strong> Medico A o Medico B</div>
                <div style="padding:0.5rem 0; border-bottom:1px solid #E5E7EB;"><i class="fas fa-circle" style="color:#F97316; font-size:0.5rem;"></i> <strong>Nivel C:</strong> Cualquier Medico</div>
                <div style="padding:0.5rem 0;"><i class="fas fa-circle" style="color:#DC2626; font-size:0.5rem;"></i> <strong>Enfermeria:</strong> Solo medicamentos autorizados</div>
            </div>
        </div>
        <div style="background: #FFF1F0; padding: 1.5rem; border-radius: 12px; border-left: 4px solid #C7291C;">
            <h4 style="font-weight:800; color:#8C1A11; margin-bottom:0.5rem;"><i class="fas fa-exclamation-triangle"></i> Advertencia</h4>
            <p style="font-size:0.85rem; color:#736860;">Las violaciones a los permisos A/B/C se reportan automaticamente al SuperAdmin.</p>
        </div>
    </div>
</div>
@endsection
