<!DOCTYPE html><html lang="es"><head><meta charset="UTF-8"><title>Pase de Salida</title><style>body{font-family:sans-serif; font-size:14px; padding:30px;} .header{text-align:center; border-bottom:3px solid #C7291C; padding-bottom:10px; margin-bottom:20px;} .vitals{background:#f4f6f8; padding:15px; border-radius:8px; margin:15px 0;} .footer{margin-top:40px; text-align:center; font-size:12px; color:#666;}</style></head>
<body>
<div class="header">
    <h1 style="color:#C7291C; margin:0;">PASE DE SALIDA / DERIVACIÓN</h1>
    <h2 style="margin:5px 0;">HealthNexus Hospital</h2>
</div>
<p><strong>Paciente:</strong> {{ $triage->patient_name }}</p>
<p><strong>Edad:</strong> {{ $triage->age }} años</p>
<p><strong>Nivel de Triage:</strong> <span style="color:red; font-weight:bold;">{{ $triage->triage_level }}</span></p>
<p><strong>Motivo:</strong> {{ $triage->symptoms }}</p>

<div class="vitals">
    <h3 style="margin:0 0 10px 0;">Signos Vitales al momento de la Derivación</h3>
    <p><strong>TA:</strong> {{ $triage->vitals_ta }} | <strong>FC:</strong> {{ $triage->vitals_fc }} | <strong>Temp:</strong> {{ $triage->vitals_temp }}°C | <strong>SpO2:</strong> {{ $triage->vitals_spo2 }}%</p>
</div>

<div style="border:2px dashed #C7291C; padding:15px; text-align:center; margin-top:20px;">
    <h3 style="color:#C7291C; margin:0;">DERIVADO A:</h3>
    <h2 style="margin:5px 0;">{{ $triage->derivation_hospital }}</h2>
    <p>Motivo: Falta de recursos/camas disponibles en esta institución para el nivel de atención requerido.</p>
</div>

<div class="footer">
    <p>Documento generado automáticamente por HealthNexus - {{ now() }}</p>
    <p>Firma Digital SuperAdmin: ___________________________</p>
</div>
</body></html>
