<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SuperAdmin') | HealthNexus</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Inter', sans-serif; }
        body { display: flex; height: 100vh; background: #F9FAFB; color: #1E1A17; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        .sidebar { width: 280px; background: #FFFFFF; border-right: 1px solid #E5E7EB; display: flex; flex-direction: column; box-shadow: 2px 0 10px rgba(0,0,0,0.02); overflow-y: auto; }
        .sidebar-header { padding: 1.5rem; border-bottom: 1px solid #E5E7EB; text-align: center; }
        .sidebar-header img { max-width: 140px; margin-bottom: 0.5rem; }
        .sidebar-menu { padding: 1rem 0; flex: 1; }
        .menu-category { padding: 0.5rem 1.5rem; font-size: 0.7rem; font-weight: 800; color: #F05A4E; text-transform: uppercase; letter-spacing: 1px; margin-top: 1rem; }
        .menu-item { display: flex; align-items: center; padding: 0.65rem 1.5rem; color: #736860; text-decoration: none; font-size: 0.85rem; font-weight: 600; transition: all 0.2s; border-left: 4px solid transparent; }
        .menu-item i { width: 20px; margin-right: 0.75rem; font-size: 0.9rem; }
        .menu-item:hover { background: #FFF1EE; color: #F05A4E; border-left-color: #FF8C42; }
        .menu-item.active { background: #FFF1EE; color: #C7291C; border-left-color: #C7291C; }
        .sidebar-footer { padding: 1rem 1.5rem; border-top: 1px solid #E5E7EB; }
        .btn-logout { width: 100%; padding: 0.6rem; background: #FFF1F0; color: #C7291C; border: 1px solid #FFE0DC; border-radius: 8px; font-weight: 700; cursor: pointer; transition: 0.2s; }
        .btn-logout:hover { background: #C7291C; color: white; }
        .main { flex: 1; display: flex; flex-direction: column; overflow-y: auto; }
        .topbar { background: white; padding: 1rem 2rem; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 2px 4px rgba(0,0,0,0.02); border-bottom: 1px solid #E5E7EB; }
        .topbar h1 { font-size: 1.2rem; font-weight: 800; color: #1E1A17; }
        .user-badge { background: linear-gradient(135deg, #F05A4E, #FF8C42); color: white; padding: 0.3rem 0.8rem; border-radius: 20px; font-size: 0.7rem; font-weight: 700; text-transform: uppercase; }
        .content { padding: 2rem; animation: fadeIn 0.4s ease-out; flex: 1; }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-header">
            <img src="https://z-cdn-media.chatglm.cn/files/e422f718-2f1b-43b1-8d33-abab22ae033a.png?auth_key=1880130553-c96ef22a7ec1475a8024ee420ae894cb-0-01473062212a4e246495206bff72dde3" alt="HealthNexus">
        </div>
        <div class="sidebar-menu">
            <div class="menu-category">Gobierno y Control</div>
            <a href="{{ route('superadmin.dashboard') }}" class="menu-item @yield('nav-dashboard')"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
            <a href="{{ route('superadmin.personal') }}" class="menu-item @yield('nav-personal')"><i class="fas fa-user-md"></i> Personal</a>
            <a href="{{ route('superadmin.scoreRiesgo') }}" class="menu-item @yield('nav-score')"><i class="fas fa-user-tag"></i> Score Riesgo</a>
            <a href="{{ route('superadmin.roles') }}" class="menu-item @yield('nav-roles')"><i class="fas fa-key"></i> Roles</a>

            <div class="menu-category">Operacion Hospitalaria</div>
            <a href="{{ route('superadmin.pacientes') }}" class="menu-item @yield('nav-pacientes')"><i class="fas fa-procedures"></i> Pacientes</a>
            <a href="{{ route('superadmin.urgencias') }}" class="menu-item @yield('nav-urgencias')"><i class="fas fa-ambulance"></i> Urgencias</a>
            <a href="{{ route('superadmin.farmacia') }}" class="menu-item @yield('nav-farmacia')"><i class="fas fa-pills"></i> Farmacia</a>
            <a href="{{ route('superadmin.camas') }}" class="menu-item @yield('nav-recursos')"><i class="fas fa-bed"></i> Camas</a>

            <div class="menu-category">🚑 Ambulancia y Traslados</div>
            <a href="{{ url('/superadmin/ambulancias') }}" class="menu-item @yield('nav-ambulancias')"><i class="fas fa-truck-medical"></i> Ambulancias</a>
            <a href="{{ url('/superadmin/hospital-live') }}" class="menu-item @yield('nav-hospital-live')"><i class="fas fa-tower-broadcast"></i> Hospital Live</a>

            <div class="menu-category">🧠 IA Medica</div>
            <a href="{{ url('/superadmin/asistente-ia') }}" class="menu-item @yield('nav-asistente-ia')"><i class="fas fa-robot"></i> Asistente IA</a>

            <div class="menu-category">Finanzas y Seguridad</div>
            <a href="{{ route('superadmin.finanzas') }}" class="menu-item @yield('nav-finanzas')"><i class="fas fa-lock"></i> Finanzas (PIN)</a>
            <a href="{{ route('superadmin.auditoria') }}" class="menu-item @yield('nav-auditoria')"><i class="fas fa-scroll"></i> Auditoria</a>
        <a href="{{ url('/superadmin/bigdata') }}" style="display:flex;align-items:center;gap:0.6rem;padding:0.5rem 0.8rem;color:#78716C;text-decoration:none;font-size:0.82rem;font-weight:600;border-radius:8px;transition:0.15s"><i class="fas fa-database" style="width:18px;text-align:center;color:#7C3AED"></i> Big Data & DWH</a>
            <a href="{{ route('superadmin.actividadSospechosa') }}" class="menu-item @yield('nav-sospechosa')"><i class="fas fa-skull-crossbones"></i> Sospechosos</a>
            <a href="{{ route('superadmin.monitorLive') }}" class="menu-item @yield('nav-monitor')"><i class="fas fa-broadcast-tower"></i> Monitor Live</a>

            <div class="menu-category">Datos e IA</div>
            <a href="{{ route('superadmin.mapaCalor') }}" class="menu-item @yield('nav-mapa')"><i class="fas fa-fire-alt"></i> Mapa Calor</a>
            <a href="{{ route('superadmin.ingesta') }}" class="menu-item @yield('nav-ingesta')"><i class="fas fa-upload"></i> Ingesta</a>
            <a href="{{ route('superadmin.limpieza') }}" class="menu-item @yield('nav-limpieza')"><i class="fas fa-broom"></i> Limpieza</a>
            <a href="{{ route('superadmin.reportes') }}" class="menu-item @yield('nav-reportes')"><i class="fas fa-file-pdf"></i> Reportes</a>
        </div>
        <div class="sidebar-footer">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn-logout"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</button>
            </form>
        </div>
    </div>

    <div class="main">
        <div class="topbar">
            <h1>@yield('title', 'Panel de Control')</h1>
            <div style="display: flex; align-items: center; gap: 10px;">
                <span style="font-weight: 600; font-size: 0.9rem;">{{ auth()->user()->name }}</span>
                <span class="user-badge">{{ auth()->user()->role }}</span>
            </div>
        </div>
        <div class="content">
            @yield('content')
        </div>
    </div>
</body>
</html>
