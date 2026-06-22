<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Enfermería') | HealthNexus</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Inter', sans-serif; }
        body { display: flex; height: 100vh; background: #FFF8F0; color: #7C2D12; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes slideDown { from { opacity: 0; transform: translateY(-20px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes pulse { 0%,100% { opacity: 1; } 50% { opacity: 0.6; } }

        .main { flex: 1; display: flex; flex-direction: column; overflow-y: auto; }
        .topbar { background: white; padding: 1rem 2rem; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 2px 4px rgba(0,0,0,0.04); border-bottom: 3px solid #E85D3A; }
        .topbar h1 { font-size: 1.15rem; font-weight: 800; color: #7C2D12; }
        .user-badge { background: linear-gradient(135deg, #DC2626, #E85D3A); color: white; padding: 0.3rem 0.8rem; border-radius: 20px; font-size: 0.7rem; font-weight: 700; text-transform: uppercase; }
        .content { padding: 2rem; animation: fadeIn 0.3s ease-out; flex: 1; }

        .sidebar { width: 270px; background: #FFFFFF; border-left: 1px solid #FED7AA; display: flex; flex-direction: column; box-shadow: -2px 0 10px rgba(232,93,58,0.06); overflow-y: auto; }
        .sidebar-header { padding: 1.25rem; border-bottom: 3px solid; border-image: linear-gradient(to right, #DC2626, #E85D3A, #F97316, #F97316, #DC2626) 1; text-align: center; }
        .sidebar-header img { max-width: 120px; margin-bottom: 0.25rem; }
        .sidebar-menu { padding: 0.75rem 0; flex: 1; }
        .menu-category { padding: 0.5rem 1.25rem; font-size: 0.65rem; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; margin-top: 0.75rem; }
        .menu-cat-red { color: #DC2626; }
        .menu-cat-orange { color: #E85D3A; }
        .menu-cat-yellow { color: #D97706; }
        .menu-cat-orange { color: #EA580C; }
        .menu-item { display: flex; align-items: center; padding: 0.6rem 1.25rem; color: #64748B; text-decoration: none; font-size: 0.82rem; font-weight: 600; transition: all 0.2s; border-right: 4px solid transparent; }
        .menu-item i { width: 18px; margin-right: 0.6rem; font-size: 0.85rem; }
        .menu-item:hover { background: #FFF7ED; color: #E85D3A; border-right-color: #FDBA74; }
        .menu-item.active { background: #FFF7ED; color: #C2410C; border-right-color: #E85D3A; font-weight: 700; }
        .sidebar-footer { padding: 0.75rem 1.25rem; border-top: 1px solid #FED7AA; }
        .btn-logout { width: 100%; padding: 0.55rem; background: #FEF2F2; color: #DC2626; border: 1px solid #FECACA; border-radius: 8px; font-weight: 700; cursor: pointer; transition: 0.2s; font-size: 0.85rem; }
        .btn-logout:hover { background: #DC2626; color: white; }

        /* Triage level badges */
        .triage-rojo { background: #DC2626; color: white; }
        .triage-naranja { background: #E85D3A; color: white; }
        .triage-amarillo { background: #F97316; color: #7C2D12; }
        .triage-verde { background: #F97316; color: white; }
        .triage-azul { background: #DC2626; color: white; }

        .toast { position: fixed; top: 1rem; right: 1rem; z-index: 9999; padding: 1rem 1.5rem; border-radius: 12px; font-weight: 700; font-size: 0.9rem; animation: slideDown 0.4s ease-out; box-shadow: 0 10px 25px rgba(0,0,0,0.15); max-width: 400px; }
        .toast-success { background: #F0FDF4; color: #9A3412; border: 2px solid #86EFAC; }
        .toast-error { background: #FEF2F2; color: #991B1B; border: 2px solid #FCA5A5; }
    
    
    
    
    
    
    
    
    
    
    
    

    
    .pagination { display: flex; gap: 6px; list-style: none; padding: 0; margin: 0; align-items: center; justify-content: center; }
    .pagination li a, .pagination li span { display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; border-radius: 50%; font-size: 0.75rem; font-weight: 700; color: #78716C; text-decoration: none; transition: all 0.2s; background: white; border: 2px solid #FED7AA; }
    .pagination li a:hover { background: #FFF7ED; color: #E85D3A; border-color: #E85D3A; box-shadow: 0 3px 8px rgba(232,93,58,0.25); transform: translateY(-1px); }
    .pagination li.active span { background: linear-gradient(135deg, #E85D3A, #DC2626); color: white; border-color: transparent; box-shadow: 0 3px 10px rgba(220,38,38,0.35); }
    .pagination li.disabled span { color: #D4D4D8; border-color: #F5F5F4; background: #FAFAF9; cursor: default; }
    .pag-info { text-align: center; font-size: 0.7rem; color: #A8A29E; margin-top: 0.5rem; font-weight: 600; }

    </style>
</head>
<body>
    <div class="main">
        <div class="topbar">
            <img src="http://localhost/images/logo.png" alt="HealthNexus" style="height:30px; margin-right:8px;"><h1>@yield('title', 'Panel de Enfermería')</h1>
            <div style="display: flex; align-items: center; gap: 10px;">
                <span style="font-weight: 600; font-size: 0.9rem;">{{ auth()->user()->name }}</span>
                <span class="user-badge">{{ auth()->user()->role }}</span>
            </div>
        </div>
        <div class="content">
            @if(session('success'))
            <div class="toast toast-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
            @endif
            @if(session('error'))
            <div class="toast toast-error"><i class="fas fa-times-circle"></i> {{ session('error') }}</div>
            @endif
            @yield('content')
        </div>
    </div>

    <div class="sidebar">
        <div class="sidebar-header">
            <img src="https://z-cdn-media.chatglm.cn/files/e422f718-2f1b-43b1-8d33-abab22ae033a.png?auth_key=1880130553-c96ef22a7ec1475a8024ee420ae894cb-0-01473062212a4e246495206bff72dde3" alt="HealthNexus">
        </div>
        <div class="sidebar-menu">
            <div class="menu-category menu-cat-red">Emergencia</div>
            <a href="{{ route('enfermeria.dashboard') }}" class="menu-item @yield('nav-dashboard')"><i class="fas fa-tachometer-alt"></i> Inicio</a>
            <a href="{{ route('enfermeria.alertas') }}" class="menu-item @yield('nav-alertas')"><i class="fas fa-bell"></i> Alertas LIVE</a>

            <div class="menu-category menu-cat-orange">Atención Paciente</div>
            <a href="{{ route('enfermeria.triage') }}" class="menu-item @yield('nav-triage')"><i class="fas fa-heartbeat"></i> Triage Manchester</a>
            <a href="{{ route('enfermeria.signos') }}" class="menu-item @yield('nav-signos')"><i class="fas fa-stethoscope"></i> Signos Vitales</a>
            <a href="{{ route('enfermeria.pacientes') }}" class="menu-item @yield('nav-pacientes')"><i class="fas fa-users"></i> Pacientes</a>

            <div class="menu-category menu-cat-yellow">Hospitalización</div>
            <a href="{{ route('enfermeria.mapaCamas') }}" class="menu-item @yield('nav-camas')"><i class="fas fa-bed"></i> Mapa de Camas</a>
            <a href="{{ route('enfermeria.hospitalizacion') }}" class="menu-item @yield('nav-hospitalización')"><i class="fas fa-bed"></i> Hospitalización</a>
            <a href="{{ route('enfermeria.evolucion') }}" class="menu-item @yield('nav-evolución')"><i class="fas fa-notes-medical"></i> Evolución</a>
            <a href="{{ route('enfermeria.medicamentos') }}" class="menu-item @yield('nav-meds')"><i class="fas fa-pills"></i> Medicamentos</a>

            <div class="menu-category menu-cat-orange">Documentación</div>
            <a href="{{ route('enfermeria.documentacion') }}" class="menu-item @yield('nav-docs')"><i class="fas fa-file-medical"></i> Documentación</a>
            <a href="{{ route('enfermeria.solicitudesFarmacia') }}" class="menu-item @yield('nav-solicitudes')"><i class="fas fa-prescription-bottle-alt"></i> Solicitudes Farmacia</a>
            <a href="{{ route('enfermeria.reportes') }}" class="menu-item @yield('nav-reportes')"><i class="fas fa-chart-bar"></i> Reportes</a>
        </div>
        <div class="sidebar-footer">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn-logout"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</button>
            </form>
        </div>
    </div>
</body>
</html>
