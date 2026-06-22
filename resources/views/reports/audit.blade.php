<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Auditoria - HealthNexus</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 9px; color: #1E1A17; }
        h1 { text-align: center; color: #E85D3A; font-size: 16px; margin-bottom: 2px; }
        .subtitle { text-align: center; color: #78716C; font-size: 9px; margin-bottom: 8px; }
        .confidential { text-align: center; background: #FEE2E2; color: #991B1B; padding: 4px; font-weight: 800; font-size: 8px; letter-spacing: 2px; margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; }
        th { background: #E85D3A; color: white; padding: 4px 5px; text-align: left; font-size: 7px; text-transform: uppercase; }
        td { padding: 3px 5px; border-bottom: 1px solid #FED7AA; font-size: 8px; }
        tr.suspicious { background: #FEF2F2; }
        .footer { text-align: center; color: #A8A29E; font-size: 7px; margin-top: 12px; border-top: 1px solid #FED7AA; padding-top: 4px; }
    </style>
</head>
<body>
    <div class="confidential">DOCUMENTO CONFIDENCIAL - AUDITORIA HOSPITALARIA</div>
    <h1>HealthNexus - Reporte de Auditoria</h1>
    <p class="subtitle">Generado: {{ now()->format('d/m/Y H:i:s') }} | Registros: {{ $logs->count() }}</p>
    
    <table>
        <thead><tr><th>Fecha</th><th>Usuario</th><th>Rol</th><th>Accion</th><th>Modulo</th><th>Detalles</th><th>IP</th><th>Riesgo</th></tr></thead>
        <tbody>
            @foreach($logs as $log)
            <tr class="{{ $log->is_suspicious ? 'suspicious' : '' }}">
                <td>{{ date('d/m/Y H:i', strtotime($log->created_at)) }}</td>
                <td style="font-weight:600;">{{ $log->user_name }}</td>
                <td>{{ $log->user_role }}</td>
                <td>{{ $log->action }}</td>
                <td>{{ $log->module }}</td>
                <td style="max-width:150px;">{{ Str::limit($log->details, 60) }}</td>
                <td style="font-family:monospace;">{{ $log->ip_address }}</td>
                <td style="font-weight:700;">{{ strtoupper($log->risk_level ?? 'bajo') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <div class="footer">HealthNexus Hospital Management System - Reporte de Auditoria Forense - Documento confidencial</div>
</body>
</html>
