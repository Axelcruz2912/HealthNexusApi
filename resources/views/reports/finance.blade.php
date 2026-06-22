<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte Financiero - HealthNexus</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 11px; color: #1E1A17; }
        h1 { text-align: center; color: #E85D3A; font-size: 20px; margin-bottom: 2px; }
        .subtitle { text-align: center; color: #78716C; font-size: 10px; margin-bottom: 15px; }
        .summary { display: flex; gap: 10px; margin: 10px 0; }
        .card { flex: 1; border: 1px solid #FED7AA; border-radius: 6px; padding: 10px; text-align: center; }
        .card .label { font-size: 9px; color: #78716C; text-transform: uppercase; letter-spacing: 0.5px; }
        .card .value { font-size: 18px; font-weight: bold; color: #1E1A17; margin-top: 3px; }
        .card.paid .value { color: #059669; }
        .card.pending .value { color: #D97706; }
        .card.pharma .value { color: #3B82F6; }
        h2 { color: #E85D3A; font-size: 13px; border-bottom: 2px solid #FED7AA; padding-bottom: 3px; margin-top: 15px; }
        table { width: 100%; border-collapse: collapse; margin-top: 5px; }
        th { background: #E85D3A; color: white; padding: 5px 8px; text-align: left; font-size: 9px; text-transform: uppercase; }
        td { padding: 4px 8px; border-bottom: 1px solid #FED7AA; font-size: 10px; }
        tr:nth-child(even) { background: #FFF7ED; }
        .footer { text-align: center; color: #A8A29E; font-size: 8px; margin-top: 20px; border-top: 1px solid #FED7AA; padding-top: 5px; }
    </style>
</head>
<body>
    <h1>HealthNexus</h1>
    <p class="subtitle">Reporte Financiero Hospitalario — {{ now()->format('d/m/Y H:i') }}</p>

    <div class="summary">
        <div class="card paid">
            <div class="label">Ingresos Cobrados</div>
            <div class="value">${{ number_format($paid, 2) }}</div>
        </div>
        <div class="card pending">
            <div class="label">Pendiente</div>
            <div class="value">${{ number_format($pending, 2) }}</div>
        </div>
        <div class="card pharma">
            <div class="label">Valor Farmacia</div>
            <div class="value">${{ number_format($pharma_value, 2) }}</div>
        </div>
    </div>

    <h2>Top Medicamentos por Costo</h2>
    <table>
        <thead>
            <tr><th>#</th><th>Medicamento</th><th>Prescripciones</th><th>Monto Total</th></tr>
        </thead>
        <tbody>
            @foreach($top as $i => $t)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $t->name }}</td>
                <td>{{ $t->qty }}</td>
                <td>${{ number_format($t->total, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">HealthNexus Hospital Management System — Documento confidencial — Página 1 de 1</div>
</body>
</html>
