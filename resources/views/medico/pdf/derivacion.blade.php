<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Pase de Salida</title>
<style>
body{font-family:Arial,sans-serif;font-size:11px;margin:30px;color:#1E293B}
.header{text-align:center;border-bottom:3px solid #1E293B;padding-bottom:12px;margin-bottom:15px}
.header-inner{display:flex;align-items:center;justify-content:center;gap:12px}
.header-inner img{height:50px}
.header-text{text-align:left}
.ht-title{font-size:20px;font-weight:900}.ht-h{color:#1E293B}.ht-n{color:#DC2626}
.ht-sub{font-size:9px;color:#94A3B8;letter-spacing:2px}
.ht-info{font-size:9px;color:#64748B;margin-top:2px}
.title{font-size:14px;font-weight:900;background:#DC2626;color:white;padding:8px;border-radius:6px;margin:15px 0;text-align:center;letter-spacing:2px}
.section{margin-bottom:12px;border:1px solid #E2E8F0;border-radius:8px;padding:10px}
.st{font-weight:900;font-size:10px;text-transform:uppercase;letter-spacing:1px;color:#64748B;margin-bottom:5px;border-bottom:1px solid #F1F5F9;padding-bottom:3px}
.row{display:flex;justify-content:space-between;margin-bottom:3px}
.lb{font-weight:700;color:#475569}.vl{font-weight:800}
.hdest{background:#FEE2E2;border:2px solid #EF4444;border-radius:8px;padding:12px;text-align:center;margin:10px 0}
.hname{font-size:18px;font-weight:900;color:#DC2626}
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
            <div class="ht-info">RFC: HNX240101XXX | CDMX, Mexico</div>
        </div>
    </div>
</div>
<div class="title">PASE DE SALIDA / DERIVACION</div>
<div class="section">
    <div class="st">Datos del Paciente</div>
    <div class="row"><span class="lb">Paciente:</span><span class="vl">{{ $paciente->patient_name ?? 'N/A' }}</span></div>
    <div class="row"><span class="lb">Edad:</span><span class="vl">{{ $paciente->age ?? 'N/A' }} anos</span></div>
    <div class="row"><span class="lb">Triage:</span><span class="vl" style="color:{{ ($paciente->triage_level ?? '') == 'Rojo' ? '#DC2626' : '#D97706' }}">{{ $paciente->triage_level ?? 'N/A' }}</span></div>
    <div class="row"><span class="lb">Sintomas:</span><span class="vl">{{ $paciente->symptoms ?? 'N/A' }}</span></div>
</div>
<div class="section">
    <div class="st">Datos Clinicos</div>
    <div class="row"><span class="lb">Diagnostico:</span><span class="vl">{{ $paciente->diagnostico ?? 'N/A' }}</span></div>
    <div class="row"><span class="lb">CIE-10:</span><span class="vl">{{ $paciente->cie10 ?? 'N/A' }}</span></div>
    <div class="row"><span class="lb">Tratamiento:</span><span class="vl">{{ $paciente->tratamiento ?? 'N/A' }}</span></div>
</div>
<div class="section">
    <div class="st">Signos Vitales</div>
    <div class="row"><span class="lb">TA:</span><span class="vl">{{ $paciente->vitals_ta ?? '?' }} mmHg</span></div>
    <div class="row"><span class="lb">FC:</span><span class="vl">{{ $paciente->vitals_fc ?? '?' }} lpm</span></div>
    <div class="row"><span class="lb">Temp:</span><span class="vl">{{ $paciente->vitals_temp ?? '?' }} C</span></div>
    <div class="row"><span class="lb">SpO2:</span><span class="vl">{{ $paciente->vitals_spo2 ?? '?' }}%</span></div>
</div>
<div class="hdest">
    <div style="font-size:10px;font-weight:700;color:#991B1B">HOSPITAL DESTINO</div>
    <div class="hname">{{ $derivacion->hospital_destino }}</div>
</div>
<div class="section">
    <div class="st">Motivo de Derivacion</div>
    <p style="font-weight:700">{{ $derivacion->motivo }}</p>
    <div class="row"><span class="lb">Fecha/Hora:</span><span class="vl">{{ $derivacion->created_at }}</span></div>
    <div class="row"><span class="lb">Medico:</span><span class="vl">{{ $doctor->name ?? 'N/A' }}</span></div>
</div>
<div class="section" style="background:#FEF3C7;border-color:#F59E0B">
    <p style="font-size:10px;margin:0">Paciente derivado por falta de recursos/camas. Se recomienda atencion inmediata. Documento generado por HealthNexus Hospital.</p>
</div>
<div class="ft">
    <div class="fm"><div class="fl">Dr. {{ $doctor->name ?? 'Medico Tratante' }}<br>Medico Tratante - HealthNexus</div></div>
    <div class="fm"><div class="fl">SuperAdmin<br>Administracion - HealthNexus</div></div>
</div>
</body>
</html>
