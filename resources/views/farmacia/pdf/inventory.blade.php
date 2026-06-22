<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Inventario Farmacia - {{ date('d/m/Y') }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 10px; }
        h1 { text-align: center; color: #1E1A17; margin-bottom: 5px; }
        h3 { color: #2D9E6A; margin-top: 15px; }
        .date { text-align: center; color: #736860; margin-bottom: 15px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { background: #1E1A17; color: white; padding: 6px; text-align: left; font-size: 9px; }
        td { padding: 5px; border-bottom: 1px solid #E5E7EB; }
        .level-a { color: #C7291C; font-weight: bold; }
        .level-b { color: #FF8C42; font-weight: bold; }
        .level-c { color: #2D9E6A; }
        .low-stock { background: #FFF5EB; }
    </style>
</head>
<body>
    <h1>HealthNexus - Inventario de Farmacia</h1>
    <div class="date">Generado: {{ date('d/m/Y H:i:s') }}</div>

    @php $origins = $medications->groupBy('origin'); @endphp
    @foreach($origins as $origin => $meds)
    <h3>Farmacia: {{ $origin }}</h3>
    <table>
        <thead>
            <tr><th>Medicamento</th><th>Nivel</th><th>Stock</th><th>Min</th><th>Lote</th><th>Caducidad</th><th>Ubicacion</th><th>Precio</th></tr>
        </thead>
        <tbody>
            @foreach($meds as $med)
            <tr class="{{ $med->stock <= $med->min_stock ? 'low-stock' : '' }}">
                <td>{{ $med->name }}</td>
                <td class="level-{{ strtolower($med->required_level) }}">{{ $med->required_level }}</td>
                <td><strong>{{ $med->stock }}</strong></td>
                <td>{{ $med->min_stock }}</td>
                <td>{{ $med->lot_number }}</td>
                <td>{{ $med->expiry_date ? $med->expiry_date->format('d/m/Y') : 'N/A' }}</td>
                <td>{{ $med->location }}</td>
                <td>${{ number_format($med->price, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endforeach
</body>
</html>
