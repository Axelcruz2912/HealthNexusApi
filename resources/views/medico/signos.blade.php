@extends('medico.layout')
@section('title', 'Signos Vitales')
@section('nav-signos', 'active')
@section('content')
<div style="background:white; padding:1.5rem; border-radius:12px; box-shadow:0 4px 6px rgba(0,0,0,0.04); margin-bottom:1.5rem;">
    <h3 style="font-weight:800;"><i class="fas fa-heartbeat" style="color:#DC2626;"></i> Signos Vitales</h3>
</div>
<div style="background:white; border-radius:12px; overflow:hidden; box-shadow:0 4px 6px rgba(0,0,0,0.04);">
    <table style="width:100%; border-collapse:collapse;">
        <thead><tr style="background:#F1F5F9;">
            <th style="padding:0.75rem; text-align:left; font-size:0.8rem; color:#64748B;">Paciente</th>
            <th style="padding:0.75rem; text-align:center; font-size:0.8rem; color:#64748B;">PA</th>
            <th style="padding:0.75rem; text-align:center; font-size:0.8rem; color:#64748B;">FC</th>
            <th style="padding:0.75rem; text-align:center; font-size:0.8rem; color:#64748B;">Temp</th>
            <th style="padding:0.75rem; text-align:center; font-size:0.8rem; color:#64748B;">SpO2</th>
            <th style="padding:0.75rem; text-align:left; font-size:0.8rem; color:#64748B;">Hora</th>
        </tr></thead>
        <tbody>
        @foreach($vitals as $v)
        <tr style="border-bottom:1px solid #F1F5F9;">
            <td style="padding:0.75rem; font-weight:700;">{{ $v->triage->patient_name ?? 'N/A' }}</td>
            <td style="padding:0.75rem; text-align:center;">{{ $v->systolic ?? '-' }}/{{ $v->diastolic ?? '-' }}</td>
            <td style="padding:0.75rem; text-align:center;">{{ $v->heart_rate ?? '-' }}</td>
            <td style="padding:0.75rem; text-align:center;">{{ $v->temperature ?? '-' }}°</td>
            <td style="padding:0.75rem; text-align:center; font-weight:700; color:{{ ($v->spo2 ?? 100) < 90 ? '#DC2626' : '#2D9E6A' }};">{{ $v->spo2 ?? '-' }}%</td>
            <td style="padding:0.75rem; font-size:0.8rem; color:#94A3B8;">{{ $v->created_at->format('H:i') }}</td>
        </tr>
        @endforeach
        </tbody>
    </table>
</div>
@endsection
