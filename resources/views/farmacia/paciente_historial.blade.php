@extends('farmacia.layout')
@section('title', 'Historial Farmaceutico del Paciente')

@section('content')
<div style="background: white; padding: 1.5rem; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.04); margin-bottom: 1.5rem;">
    <a href="{{ route('farmacia.dispensacion') }}" style="color:#F97316; text-decoration:none; font-weight:700; font-size:0.85rem;"><i class="fas fa-arrow-left"></i> Volver a Dispensacion</a>
    <h3 style="font-weight: 800; color: #7C2D12; margin-top: 0.5rem;"><i class="fas fa-user" style="color:#F97316;"></i> {{ $patient->patient_name }}</h3>
    <p style="color: #736860; font-size: 0.85rem;">Triage: {{ $patient->triage_level }} | Edad: {{ $patient->age }}a | Sintomas: {{ $patient->symptoms }}</p>
</div>

@if($medications->count() > 0)
<div style="background: white; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.04); overflow: hidden;">
    <table style="width: 100%; border-collapse: collapse; font-size: 0.85rem;">
        <thead>
            <tr style="background: #7C2D12; color: white; text-align: left;">
                <th style="padding:0.75rem;">Fecha</th>
                <th style="padding:0.75rem;">Medicamento</th>
                <th style="padding:0.75rem;">Cantidad</th>
                <th style="padding:0.75rem;">Recetado por</th>
                <th style="padding:0.75rem;">Dispensado por</th>
                <th style="padding:0.75rem;">Alerta</th>
            </tr>
        </thead>
        <tbody>
            @foreach($medications as $med)
            <tr style="border-bottom: 1px solid #E5E7EB; {{ $med->interaction_alert ? 'background:#FFF1F0;' : '' }}">
                <td style="padding:0.75rem; font-size:0.8rem;">{{ $med->created_at->format('d/m/Y H:i') }}</td>
                <td style="padding:0.75rem; font-weight:700;">{{ $med->medication_name }}</td>
                <td style="padding:0.75rem;">{{ $med->quantity }}</td>
                <td style="padding:0.75rem;">{{ $med->prescriber->name ?? 'N/A' }}</td>
                <td style="padding:0.75rem;">{{ $med->dispenser->name ?? 'N/A' }}</td>
                <td style="padding:0.75rem;">
                    @if($med->interaction_alert)
                    <span style="background:#C7291C; color:white; padding:0.15rem 0.5rem; border-radius:8px; font-size:0.7rem; font-weight:700;"><i class="fas fa-exclamation-triangle"></i> INTERACCION</span>
                    @else
                    <span style="color:#F97316;"><i class="fas fa-check-circle"></i></span>
                    @endif
                </td>
            </tr>
            @if($med->interaction_details)
            <tr><td colspan="6" style="padding:0.5rem 0.75rem; background:#FFF1F0; font-size:0.8rem; color:#8C1A11;"><i class="fas fa-warning"></i> {{ $med->interaction_details }}</td></tr>
            @endif
            @endforeach
        </tbody>
    </table>
</div>
@else
<div style="background: white; padding: 3rem; border-radius: 12px; text-align: center; color: #736860;">
    <h3 style="font-weight: 800;">Sin medicamentos dispensados</h3>
</div>
@endif
@endsection
