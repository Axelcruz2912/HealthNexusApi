<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SuperAdmin | HealthNexus</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Inter', sans-serif; }
        body { display: flex; height: 100vh; background: #F9FAFB; color: #1E1A17; }
        
        /* Animaciones */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }

        /* Sidebar Claro con acentos Naranja/Rojo */
        .sidebar { width: 260px; background: #FFFFFF; border-right: 1px solid #E5E7EB; padding: 1.5rem; display: flex; flex-direction: column; box-shadow: 2px 0 10px rgba(0,0,0,0.02); }
        .sidebar img { max-width: 160px; margin-bottom: 2.5rem; align-self: center; }
        .sidebar a { color: #736860; text-decoration: none; margin-bottom: 0.75rem; font-size: 0.95rem; display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem 1rem; border-radius: 10px; transition: all 0.2s ease; }
        .sidebar a:hover { background: #FFF1EE; color: #F05A4E; transform: translateX(5px); }
        .sidebar a.active { background: #F05A4E; color: white; font-weight: 700; box-shadow: 0 4px 12px rgba(240,90,78,0.3); }
        .sidebar .logout { margin-top: auto; color: #C7291C; border-top: 1px solid #E5E7EB; padding-top: 1rem; }
        .sidebar .logout:hover { background: #FFF1F0; }

        /* Contenido Principal */
        .main { flex: 1; padding: 2rem; overflow-y: auto; background: #F4F6F8; }
        .topbar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; animation: fadeInUp 0.5s ease-out; }
        .topbar h1 { font-size: 1.8rem; font-weight: 800; color: #1E1A17; }
        .user-info { display: flex; align-items: center; gap: 0.75rem; }
        .badge { background: linear-gradient(135deg, #F05A4E, #FF8C42); color: white; padding: 0.4rem 1rem; border-radius: 20px; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; box-shadow: 0 2px 8px rgba(240,90,78,0.4); }

        /* Tarjetas Animadas */
        .cards { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem; margin-bottom: 2rem; }
        .card { background: white; padding: 1.5rem; border-radius: 16px; box-shadow: 0 4px 6px rgba(0,0,0,0.04); border-left: 5px solid #F05A4E; transition: all 0.3s ease; animation: fadeInUp 0.6s ease-out forwards; opacity: 0; }
        .card:nth-child(1) { animation-delay: 0.2s; }
        .card:nth-child(2) { animation-delay: 0.4s; border-left-color: #FF8C42; }
        .card:nth-child(3) { animation-delay: 0.6s; border-left-color: #C7291C; }
        .card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(240,90,78,0.15); animation: pulse 1s infinite; }
        .card h3 { font-size: 0.85rem; color: #736860; text-transform: uppercase; margin-bottom: 0.5rem; letter-spacing: 0.5px; }
        .card .number { font-size: 2.5rem; font-weight: 800; background: linear-gradient(135deg, #1E1A17, #F05A4E); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .card i { float: right; font-size: 2.5rem; color: #FFF1EE; transition: color 0.3s; }
        .card:hover i { color: #F05A4E; }

        /* Módulo Grande */
        .module-card { background: white; padding: 3rem; border-radius: 16px; box-shadow: 0 4px 6px rgba(0,0,0,0.04); text-align: center; animation: fadeInUp 0.8s ease-out forwards; opacity: 0; border-top: 4px solid #FF8C42; }
        .module-card h2 { font-size: 1.5rem; color: #1E1A17; margin-bottom: 0.5rem; font-weight: 800; }
        .module-card p { color: #736860; margin-bottom: 1.5rem; }
        .btn-primary { background: linear-gradient(135deg, #F05A4E, #FF8C42); color: white; border: none; padding: 0.75rem 1.5rem; border-radius: 10px; font-weight: 700; cursor: pointer; transition: 0.3s; box-shadow: 0 4px 12px rgba(240,90,78,0.3); }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 6px 16px rgba(240,90,78,0.4); }
    </style>
</head>
<body>
    <div class="sidebar">
        <img src="https://z-cdn-media.chatglm.cn/files/e422f718-2f1b-43b1-8d33-abab22ae033a.png?auth_key=1880130553-c96ef22a7ec1475a8024ee420ae894cb-0-01473062212a4e246495206bff72dde3" alt="HealthNexus">
        <a href="#" class="active"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
        <a href="#"><i class="fas fa-users-cog"></i> Gestion RH</a>
        <a href="#"><i class="fas fa-shield-alt"></i> Gobierno y Acreditacion</a>
        <a href="#"><i class="fas fa-file-invoice-dollar"></i> Finanzas</a>
        <a href="#"><i class="fas fa-hospital"></i> Operacion Hospitalaria</a>
        <a href="#"><i class="fas fa-database"></i> Big Data / MongoDB</a>
        <a href="{{ route('logout') }}" class="logout" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>
    </div>
    
    <div class="main">
        <div class="topbar">
            <h1>Panel de Control Maestro</h1>
            <div class="user-info">
                <span style="font-weight: 600;">{{ auth()->user()->name }}</span>
                <span class="badge">{{ auth()->user()->role }}</span>
            </div>
        </div>

        <div class="cards">
            <div class="card">
                <i class="fas fa-user-md"></i>
                <h3>Personal Hospitalario Activo</h3>
                <div class="number">24</div>
            </div>
            <div class="card">
                <i class="fas fa-procedures"></i>
                <h3>Ocupacion Hospitalaria</h3>
                <div class="number">78%</div>
            </div>
            <div class="card">
                <i class="fas fa-heartbeat"></i>
                <h3>Urgencias Hoy</h3>
                <div class="number">12</div>
            </div>
        </div>

        <div class="module-card">
            <i class="fas fa-chart-line" style="font-size: 3rem; color: #F05A4E; margin-bottom: 1rem;"></i>
            <h2>Modulo de Analiticas e IA</h2>
            <p>Aqui conectaremos PySpark y FastAPI para predicciones en tiempo real.</p>
            <button class="btn-primary">Inicializar Motor de Datos</button>
        </div>
    </div>
</body>
</html>
