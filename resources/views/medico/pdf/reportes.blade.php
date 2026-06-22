<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Reporte Medico</title>
<style>
body{font-family:Arial,sans-serif;font-size:11px;margin:30px;color:#1E293B}
.header{text-align:center;border-bottom:3px solid #1E293B;padding-bottom:12px;margin-bottom:15px}
.header-inner{display:flex;align-items:center;justify-content:center;gap:12px}
.header-inner img{height:50px}
.header-text{text-align:left}
.ht-title{font-size:20px;font-weight:900}.ht-h{color:#1E293B}.ht-n{color:#3B82F6}
.title{font-size:14px;font-weight:900;background:#3B82F6;color:white;padding:8px;border-radius:6px;margin:15px 0;text-align:center}
table{width:100%;border-collapse:collapse;margin-top:15px}
th,td{border:1px solid #E2E8F0;padding:6px;text-align:left;font-size:10px}
th{background:#F1F5F9;font-weight:800}
.wm{position:fixed;bottom:20px;right:20px;opacity:0.03;font-size:80px;transform:rotate(-15deg);color:#94A3B8}
</style>
</head>
<body>
<div class="wm">HNX</div>
<div class="header">
    <div class="header-inner">
        <div class="header-text">
            <div class="ht-title"><span class="ht-h">Health</span><span class="ht-n">Nexus</span> Hospital</div>
            <div style="font-size:9px;color:#64748B">Reporte Medico | {{ date('d/m/Y H:i') }}</div>
        </div>
    </div>
</div>
<div class="title">REPORTE MEDICO - PACIENTES ATENDIDOS</div>
<table>
<thead><tr><th>Paciente</th><th>Edad</th><th>Triage</th><th>Diagnostico</th><th>Estado</th><th>Fecha</th></tr></thead>
<tbody>
@foreach($pacientes as $p)
<tr><td>{{ $p->patient_name }}</td><td>{{ $p->age }}</td><td>{{ $p->triage_level }}</td><td>{{ $p->diagnostico ?? 'N/A' }}</td><td>{{ $p->status }}</td><td>{{ $p->created_at }}</td></tr>
@endforeach
</tbody>
</table>
</body>
</html>
