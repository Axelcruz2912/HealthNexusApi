@extends('medico.layout')
@section('title', 'Defunciones')
@section('nav-defunciones', 'active')
@section('content')
<div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:0.75rem;margin-bottom:1.25rem">
    <div class="card" style="padding:1.25rem;text-align:center;border-top:3px solid #1C1917">
        <i class="fas fa-cross" style="font-size:1.5rem;color:#1C1917"></i>
        <div style="font-size:1.8rem;font-weight:900;color:#1C1917">{{$defunciones->count()}}</div>
        <div style="font-size:0.7rem;color:#78716C;font-weight:700">Total Defunciones</div>
    </div>
    <div class="card" style="padding:1.25rem;text-align:center;border-top:3px solid #DC2626">
        <i class="fas fa-calendar" style="font-size:1.5rem;color:#DC2626"></i>
        <div style="font-size:1.8rem;font-weight:900;color:#DC2626">{{$totalMes}}</div>
        <div style="font-size:0.7rem;color:#78716C;font-weight:700">Este Mes</div>
    </div>
    <div class="card" style="padding:1.25rem;text-align:center;border-top:3px solid #EA580C">
        <i class="fas fa-chart-bar" style="font-size:1.5rem;color:#EA580C"></i>
        <div style="font-size:0.75rem;font-weight:700;color:#57534E;margin-top:0.3rem">Causas Principales</div>
        @foreach($causas as $c)<div style="font-size:0.65rem;color:#78716C">{{$c->cause_of_death}} ({{$c->total}})</div>@endforeach
    </div>
</div>

@if($defunciones->isEmpty())
<div class="card" style="padding:3rem;text-align:center">
    <i class="fas fa-check-circle" style="font-size:3rem;color:#2D9E6A;margin-bottom:1rem"></i>
    <h3 style="font-weight:800;color:#166534">Sin defunciones registradas</h3>
    <p style="color:#4ADE80">Se registran desde el perfil del paciente</p>
</div>
@else
<div class="card" style="overflow:hidden">
    <table style="width:100%;border-collapse:collapse">
        <thead><tr style="background:#F5F0EB">
            <th style="padding:0.6rem;text-align:left;font-size:0.7rem;color:#78716C;font-weight:700">Certificado</th>
            <th style="padding:0.6rem;text-align:left;font-size:0.7rem;color:#78716C;font-weight:700">Paciente</th>
            <th style="padding:0.6rem;text-align:left;font-size:0.7rem;color:#78716C;font-weight:700">Causa</th>
            <th style="padding:0.6rem;text-align:center;font-size:0.7rem;color:#78716C;font-weight:700">Fecha/Hora</th>
            <th style="padding:0.6rem;text-align:center;font-size:0.7rem;color:#78716C;font-weight:700">Autopsia</th>
            <th style="padding:0.6rem;text-align:center;font-size:0.7rem;color:#78716C;font-weight:700">Familia</th>
            <th style="padding:0.6rem;text-align:center;font-size:0.7rem;color:#78716C;font-weight:700">Acciones</th>
        </tr></thead>
        <tbody>
        @foreach($defunciones as $d)
        @php $p = \App\Models\Triage::find($d->triage_id); @endphp
        <tr style="border-bottom:1px solid #F5F0EB">
            <td style="padding:0.6rem;font-weight:700;font-size:0.75rem;color:#DC2626">{{$d->death_certificate_number}}</td>
            <td style="padding:0.6rem;font-weight:700;font-size:0.8rem">{{$p->patient_name ?? 'N/A'}}</td>
            <td style="padding:0.6rem;font-size:0.75rem;color:#57534E">{{Str::limit($d->cause_of_death, 35)}}</td>
            <td style="padding:0.6rem;text-align:center;font-size:0.72rem;color:#78716C">{{\Carbon\Carbon::parse($d->death_time)->format('d/m/Y H:i')}}</td>
            <td style="padding:0.6rem;text-align:center">{{$d->autopsy_required ? '<span style="color:#DC2626;font-weight:700">Sí</span>' : '<span style="color:#A8A29E">No</span>'}}</td>
            <td style="padding:0.6rem;text-align:center"><span style="background:{{$d->notified_family==='Sí'?'#F0FDF4':'#FEF2F2'}};color:{{$d->notified_family==='Sí'?'#166534':'#991B1B'}};padding:0.1rem 0.4rem;border-radius:6px;font-size:0.65rem;font-weight:700">{{$d->notified_family}}</span></td>
            <td style="padding:0.6rem;text-align:center">
                <a href="{{route('medico.verDefuncion', $d->id)}}" style="padding:0.2rem 0.5rem;background:#FFF7ED;border:1px solid #FDBA74;border-radius:4px;text-decoration:none;color:#EA580C;font-weight:700;font-size:0.65rem"><i class="fas fa-eye"></i></a>
                <a href="{{route('medico.certificadoDefuncion', $d->id)}}" target="_blank" style="padding:0.2rem 0.5rem;background:#F0FDF4;border:1px solid #BBF7D0;border-radius:4px;text-decoration:none;color:#166534;font-weight:700;font-size:0.65rem"><i class="fas fa-print"></i></a>
            </td>
        </tr>
        @endforeach
        </tbody>
    </table>
</div>
@endif
@endsection
