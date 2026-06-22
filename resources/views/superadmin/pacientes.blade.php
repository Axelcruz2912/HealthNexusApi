@extends('superadmin.layout')
@section('title', 'Pacientes Hospitalizados')
@section('nav-pacientes', 'active')

@section('content')
<div style="background: white; padding: 1.5rem; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.04); margin-bottom: 1.5rem;">
    <h3 style="font-weight: 800; color: #1E1A17;">Pacientes Activos en el Hospital</h3>
    <p style="color: #736860; font-size: 0.85rem;">Listado de pacientes atendidos o internados actualmente.</p>
</div>

<div style="background: white; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.04); overflow: hidden;">
    <table style="width: 100%; border-collapse: collapse;">
        <thead><tr style="background: #1E1A17; color: white; text-align: left;"><th style="padding:1rem;">Paciente</th><th style="padding:1rem;">Triage</th><th style="padding:1rem;">Área Asignada</th><th style="padding:1rem;">Estatus</th><th style="padding:1rem;">Signos Vitales</th></tr></thead>
        <tbody>
            @foreach($patients as $patient)
            <tr style="border-bottom: 1px solid #E5E7EB;">
                <td style="padding: 1rem; font-weight: 700;">{{ $patient->patient_name }}</td>
                <td style="padding: 1rem;"><span style="background: {{ $patient->triage_level == 'Rojo' ? '#C7291C' : ($patient->triage_level == 'Amarillo' ? '#F59E0B' : '#2D9E6A') }}; color:white; padding:0.2rem 0.6rem; border-radius:10px; font-size:0.75rem; font-weight:700;">{{ $patient->triage_level }}</span></td>
                <td style="padding: 1rem;">{{ $patient->assigned_area }}</td>
                <td style="padding: 1rem; font-weight: 600; color: {{ $patient->status == 'Derivado' ? '#C7291C' : '#1E1A17' }}">{{ $patient->status }}</td>
                <td style="padding: 1rem; font-family: monospace; font-size: 0.8rem;">@if($patient->vitals_ta) TA:{{ $patient->vitals_ta }} FC:{{ $patient->vitals_fc }} @else Pendientes @endif</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
{{ $patients->withQueryString()->links() }}
@endsection
