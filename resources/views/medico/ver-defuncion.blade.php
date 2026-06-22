@extends('medico.layout')
@section('title', 'Detalle Defunción')
@section('nav-defunciones', 'active')
@section('content')
<div style="margin-bottom:1rem">
    <a href="{{route('medico.defunciones')}}" style="color:#78716C;text-decoration:none;font-size:0.85rem;font-weight:600"><i class="fas fa-arrow-left"></i> Regresar</a>
</div>

<div style="background:linear-gradient(135deg,#1C1917,#44403C);border-radius:14px;padding:2rem;color:#fff;margin-bottom:1.5rem;text-align:center">
    <i class="fas fa-cross" style="font-size:2.5rem;opacity:0.3;margin-bottom:0.75rem"></i>
    <h2 style="font-weight:900;font-size:1.3rem">{{$paciente->patient_name ?? 'N/A'}}</h2>
    <p style="opacity:0.7;font-size:0.85rem">{{$paciente->age ?? '?'}} años • {{$paciente->gender ?? 'N/A'}}</p>
    <div style="margin-top:0.5rem;display:inline-block;background:rgba(255,255,255,0.15);padding:0.3rem 1rem;border-radius:8px;font-size:0.75rem;font-weight:700">{{$defuncion->death_certificate_number}}</div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1.5rem">
    <div class="card" style="padding:1.25rem">
        <h4 style="font-weight:800;margin-bottom:0.75rem;font-size:0.85rem;color:#991B1B"><i class="fas fa-heart-broken"></i> Causas</h4>
        <div style="background:#FEF2F2;border:1px solid #FECACA;border-radius:8px;padding:0.75rem;margin-bottom:0.75rem">
            <div style="font-size:0.6rem;color:#78716C;font-weight:700;letter-spacing:0.5px;margin-bottom:0.15rem">CAUSA PRINCIPAL</div>
            <div style="font-size:0.9rem;font-weight:800;color:#991B1B">{{$defuncion->cause_of_death}}</div>
        </div>
        @if($defuncion->immediate_cause)
        <div style="background:#FFF7ED;border:1px solid #FDBA74;border-radius:8px;padding:0.75rem;margin-bottom:0.75rem">
            <div style="font-size:0.6rem;color:#92400E;font-weight:700;margin-bottom:0.15rem">CAUSA INMEDIATA</div>
            <div style="font-size:0.85rem;font-weight:700;color:#92400E">{{$defuncion->immediate_cause}}</div>
        </div>
        @endif
        @if($defuncion->clinical_summary)
        <div style="font-size:0.6rem;color:#78716C;font-weight:700;margin-bottom:0.15rem">RESUMEN CLÍNICO</div>
        <div style="font-size:0.8rem;color:#57534E;line-height:1.5">{{$defuncion->clinical_summary}}</div>
        @endif
    </div>

    <div class="card" style="padding:1.25rem">
        <h4 style="font-weight:800;margin-bottom:0.75rem;font-size:0.85rem;color:#57534E"><i class="fas fa-file-alt"></i> Certificado</h4>
        <div style="display:grid;gap:0">
            <div style="display:flex;justify-content:space-between;padding:0.4rem 0;border-bottom:1px solid #F5F0EB"><span style="font-size:0.75rem;color:#A8A29E">Fecha/Hora</span><span style="font-size:0.75rem;font-weight:700">{{\Carbon\Carbon::parse($defuncion->death_time)->format('d/m/Y H:i')}}</span></div>
            <div style="display:flex;justify-content:space-between;padding:0.4rem 0;border-bottom:1px solid #F5F0EB"><span style="font-size:0.75rem;color:#A8A29E">Médico</span><span style="font-size:0.75rem;font-weight:700">{{$doctor->name ?? 'N/A'}}</span></div>
            <div style="display:flex;justify-content:space-between;padding:0.4rem 0;border-bottom:1px solid #F5F0EB"><span style="font-size:0.75rem;color:#A8A29E">Autopsia</span><span style="font-size:0.75rem;font-weight:700;color:{{$defuncion->autopsy_required?'#DC2626':'#2D9E6A'}}">{{$defuncion->autopsy_required ? 'SÍ' : 'No'}}</span></div>
            <div style="display:flex;justify-content:space-between;padding:0.4rem 0;border-bottom:1px solid #F5F0EB"><span style="font-size:0.75rem;color:#A8A29E">Familia</span><span style="font-size:0.75rem;font-weight:700">{{$defuncion->notified_family}}</span></div>
        </div>
        @if($defuncion->notes)
        <div style="margin-top:0.75rem;font-size:0.6rem;color:#78716C;font-weight:700;margin-bottom:0.15rem">NOTAS</div>
        <div style="font-size:0.8rem;color:#57534E">{{$defuncion->notes}}</div>
        @endif
    </div>
</div>

<div style="text-align:center">
    <a href="{{route('medico.certificadoDefuncion', $defuncion->id)}}" target="_blank" style="display:inline-block;padding:0.75rem 2rem;background:linear-gradient(135deg,#1C1917,#44403C);color:#fff;border-radius:10px;text-decoration:none;font-weight:800;font-size:0.9rem"><i class="fas fa-print"></i> Imprimir Certificado</a>
</div>
@endsection
