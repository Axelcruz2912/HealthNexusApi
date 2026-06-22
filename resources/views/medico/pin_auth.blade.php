<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificacion PIN | HealthNexus</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Inter', sans-serif; }
        body { background: #1E1A17; height: 100vh; display: flex; align-items: center; justify-content: center; }
        .card { background: white; padding: 2.5rem; border-radius: 16px; width: 420px; text-align: center; box-shadow: 0 20px 50px rgba(0,0,0,0.5); }
        .card i { font-size: 3rem; color: #DC2626; margin-bottom: 1rem; }
        h2 { font-weight: 800; color: #1E1A17; margin-bottom: 0.5rem; }
        p { color: #64748B; font-size: 0.9rem; margin-bottom: 1.5rem; }
        .warning { background: #FEF2F2; border: 1px solid #FECACA; border-radius: 10px; padding: 1rem; margin-bottom: 1.5rem; font-size: 0.8rem; color: #991B1B; }
        input { width: 100%; padding: 1rem; border: 2px solid #1E1A17; border-radius: 10px; font-size: 1.5rem; text-align: center; letter-spacing: 12px; margin-bottom: 1rem; outline: none; }
        input:focus { border-color: #DC2626; }
        button { width: 100%; padding: 0.85rem; background: #DC2626; color: white; border: none; border-radius: 10px; font-weight: 700; cursor: pointer; font-size: 1rem; }
        button:hover { background: #B91C1C; }
        a { display: block; margin-top: 1rem; color: #64748B; text-decoration: none; font-size: 0.85rem; }
    </style>
</head>
<body>
    <div class="card">
        <i class="fas fa-shield-alt"></i>
        <h2>Verificacion Requerida</h2>
        <p>Para acceder a modulos criticos necesitas tu PIN medico.</p>
        <div class="warning"><i class="fas fa-exclamation-triangle"></i> Este acceso queda registrado en la auditoria del hospital.</div>
        @if(session('error'))
        <div style="background:#FEF2F2; color:#991B1B; padding:0.75rem; border-radius:8px; margin-bottom:1rem; font-weight:700;"><i class="fas fa-times-circle"></i> {{ session('error') }}</div>
        @endif
        <form method="POST" action="{{ route('medico.pin.verify') }}">
            @csrf
            <input type="password" name="doctor_pin" maxlength="6" required placeholder="PIN" autofocus>
            <button type="submit"><i class="fas fa-unlock"></i> Verificar PIN</button>
        </form>
        <a href="{{ route('medico.dashboard') }}">Cancelar e ir al dashboard basico</a>
    </div>
</body>
</html>
