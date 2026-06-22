<!DOCTYPE html>
<html><head><meta charset="utf-8"><title>Certificado de Defunción - {{$defuncion->death_certificate_number}}</title>
<style>
*{margin:0;padding:0;box-sizing:border-box}
body{font-family:'Times New Roman',serif;color:#1a1a1a;padding:40px;max-width:800px;margin:0 auto}
.header{text-align:center;border-bottom:3px double #000;padding-bottom:20px;margin-bottom:25px}
.header h1{font-size:14px;letter-spacing:3px;text-transform:uppercase;margin-bottom:5px}
.header h2{font-size:22px;font-weight:900}
.header .subtitle{font-size:11px;color:#666;margin-top:5px}
.cert-num{text-align:right;font-size:12px;font-weight:700;margin-bottom:20px;color:#333}
.section{margin-bottom:20px}
.section-title{font-size:10px;text-transform:uppercase;letter-spacing:1.5px;color:#666;border-bottom:1px solid #ccc;padding-bottom:4px;margin-bottom:10px}
.row{display:flex;justify-content:space-between;padding:6px 0;border-bottom:1px dotted #ddd}
.row .label{font-size:12px;color:#666;width:40%}
.row .value{font-size:13px;font-weight:700;width:58%;text-align:right}
.cause-box{border:2px solid #000;padding:15px;margin:10px 0;background:#fafafa}
.cause-box .main-cause{font-size:16px;font-weight:900;color:#000;margin-bottom:8px}
.cause-box .imm-cause{font-size:13px;color:#333}
.cause-box .label-sm{font-size:9px;text-transform:uppercase;letter-spacing:1px;color:#999;margin-bottom:3px}
.footer{margin-top:40px;display:flex;justify-content:space-between}
.footer .sig{text-align:center;width:45%}
.footer .sig .line{border-top:1px solid #000;padding-top:5px;font-size:11px;margin-top:50px}
.watermark{position:fixed;top:50%;left:50%;transform:translate(-50%,-50%) rotate(-45deg);font-size:80px;color:rgba(0,0,0,0.03);font-weight:900;pointer-events:none}
@media print{body{padding:20px}.watermark{display:none}}
</style>
</head><body>
<div class="watermark">CERTIFICADO DE DEFUNCION</div>

<div class="header">
    <h1>HealthNexus Hospital</h1>
    <h2>Certificado de Defunción</h2>
    <div class="subtitle">Documento Médico Oficial — Conforme al Código Sanitario</div>
</div>

<div class="cert-num">No. {{$defuncion->death_certificate_number}}</div>

<div class="section">
    <div class="section-title">Datos del Fallecido</div>
    <div class="row"><span class="label">Nombre Completo</span><span class="value">{{$paciente->patient_name ?? 'N/A'}}</span></div>
    <div class="row"><span class="label">Edad</span><span class="value">{{$paciente->age ?? 'N/A'}} años</span></div>
    <div class="row"><span class="label">Sexo</span><span class="value">{{$paciente->gender ?? 'N/A'}}</span></div>
    <div class="row"><span class="label">Motivo de Ingreso</span><span class="value">{{$paciente->chief_complaint ?? 'N/A'}}</span></div>
</div>

<div class="section">
    <div class="section-title">Datos del Fallecimiento</div>
    <div class="row"><span class="label">Fecha y Hora</span><span class="value">{{\Carbon\Carbon::parse($defuncion->death_time)->format('d \d\e F \d\e Y, H:i')}}</span></div>
    <div class="row"><span class="label">Médico que Certifica</span><span class="value">{{$doctor->name ?? 'N/A'}}</span></div>
    <div class="row"><span class="label">Especialidad</span><span class="value">{{$doctor->role ?? 'N/A'}}</span></div>
    <div class="row"><span class="label">Autopsia Requerida</span><span class="value">{{$defuncion->autopsy_required ? 'SÍ' : 'NO'}}</span></div>
    <div class="row"><span class="label">Familia Notificada</span><span class="value">{{$defuncion->notified_family}}</span></div>
</div>

<div class="section">
    <div class="section-title">Causas de Defunción</div>
    <div class="cause-box">
        <div class="label-sm">CAUSA BÁSICA (Enfermedad que inició la cadena de eventos)</div>
        <div class="main-cause">{{$defuncion->cause_of_death}}</div>
        @if($defuncion->immediate_cause)
        <div class="label-sm" style="margin-top:10px">CAUSA INMEDIATA</div>
        <div class="imm-cause">{{$defuncion->immediate_cause}}</div>
        @endif
    </div>
</div>

@if($defuncion->clinical_summary)
<div class="section">
    <div class="section-title">Resumen Clínico</div>
    <p style="font-size:12px;line-height:1.6;color:#333">{{$defuncion->clinical_summary}}</p>
</div>
@endif

@if($defuncion->notes)
<div class="section">
    <div class="section-title">Observaciones</div>
    <p style="font-size:12px;line-height:1.6;color:#333">{{$defuncion->notes}}</p>
</div>
@endif

<div class="footer">
    <div class="sig">
        <div class="line">
            <strong>Firma del Médico</strong><br>
            {{$doctor->name ?? 'N/A'}}<br>
            Cédula Profesional<br>
            {{$doctor->role ?? 'N/A'}}
        </div>
    </div>
    <div class="sig">
        <div class="line">
            <strong>Sello del Hospital</strong><br>
            HealthNexus Hospital<br>
            Fecha de Emisión: {{now()->format('d/m/Y')}}
        </div>
    </div>
</div>

<script>window.onload=function(){window.print()}</script>
</body></html>
