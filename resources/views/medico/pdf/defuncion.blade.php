<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Acta de Defuncion</title>
<style>
body{font-family:Arial,sans-serif;font-size:11px;margin:30px;color:#1E293B}
.header{text-align:center;border-bottom:3px solid #1E293B;padding-bottom:12px;margin-bottom:15px}
.header-inner{display:flex;align-items:center;justify-content:center;gap:12px}
.header-inner img{height:50px}
.header-text{text-align:left}
.ht-title{font-size:20px;font-weight:900}.ht-h{color:#1E293B}.ht-n{color:#DC2626}
.ht-sub{font-size:9px;color:#94A3B8;letter-spacing:2px}
.ht-info{font-size:9px;color:#64748B;margin-top:2px}
.title{font-size:14px;font-weight:900;background:#1E293B;color:white;padding:8px;border-radius:6px;margin:15px 0;text-align:center;letter-spacing:2px}
.cert-num{text-align:center;font-size:12px;font-weight:700;color:#64748B;margin-bottom:10px}
.section{margin-bottom:12px;border:1px solid #E2E8F0;border-radius:8px;padding:10px}
.st{font-weight:900;font-size:10px;text-transform:uppercase;letter-spacing:1px;color:#64748B;margin-bottom:5px;border-bottom:1px solid #F1F5F9;padding-bottom:3px}
.row{display:flex;justify-content:space-between;margin-bottom:3px}
.lb{font-weight:700;color:#475569}.vl{font-weight:800}
.ft{margin-top:30px;display:flex;justify-content:space-between}
.fm{text-align:center;width:45%}
.fl{border-top:1px solid #1E293B;margin-top:40px;padding-top:5px;font-weight:700;font-size:10px}
.wm{position:fixed;bottom:20px;right:20px;opacity:0.03;font-size:80px;transform:rotate(-15deg);color:#94A3B8}
</style>
</head>
<body>
<div class="wm">HNX</div>
<div class="header">
    <div class="header-inner">
        <div class="header-text">
            <div class="ht-title"><span class="ht-h">Health</span><span class="ht-n">Nexus</span> Hospital</div>
            <div class="ht-sub">ACCESO &bull; ROLES &bull; CONEXION &bull; SALUD</div>
            <div class="ht-info">Sistema Hospitalario Integral | CDMX, Mexico</div>
        </div>
    </div>
</div>
<div class="title">ACTA DE DEFUNCION</div>
<div class="cert-num">Certificado No: {{ $defuncion->death_certificate_number }}</div>
<div class="section">
    <div class="st">Datos del Fallecido</div>
    <div class="row"><span class="lb">Nombre:</span><span class="vl">{{ $paciente->patient_name ?? 'N/A' }}</span></div>
    <div class="row"><span class="lb">Edad:</span><span class="vl">{{ $paciente->age ?? 'N/A' }} anos</span></div>
    <div class="row"><span class="lb">Triage:</span><span class="vl">{{ $paciente->triage_level ?? 'N/A' }}</span></div>
</div>
<div class="section">
    <div class="st">Datos del Fallecimiento</div>
    <div class="row"><span class="lb">Fecha/Hora:</span><span class="vl">{{ $defuncion->death_time }}</span></div>
    <div class="row"><span class="lb">Familia Notificada:</span><span class="vl">{{ $defuncion->notified_family ?? 'No' }}</span></div>
    <div class="row"><span class="lb">Autopsia:</span><span class="vl">{{ $defuncion->autopsy_required ? 'SI' : 'NO' }}</span></div>
</div>
<div class="section">
    <div class="st">Causas de Muerte</div>
    <div class="row"><span class="lb">Causa Principal:</span><span class="vl" style="color:#DC2626">{{ $defuncion->cause_of_death }}</span></div>
    <div class="row"><span class="lb">Causa Inmediata:</span><span class="vl">{{ $defuncion->immediate_cause ?? 'N/A' }}</span></div>
</div>
<div class="section">
    <div class="st">Resumen Clinico</div>
    <p>{{ $defuncion->clinical_summary ?? 'N/A' }}</p>
</div>
@if($defuncion->notes)
<div class="section">
    <div class="st">Notas</div>
    <p>{{ $defuncion->notes }}</p>
</div>
@endif
<div class="ft">
    <div class="fm"><div class="fl">Dr. {{ $doctor->name ?? 'Medico' }}<br>Medico Certificante</div></div>
    <div class="fm"><div class="fl">Registro Civil<br>Oficialia CDMX</div></div>
</div>
</body>
</html>
