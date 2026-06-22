<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Farmacia') | HealthNexus</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        *{margin:0;padding:0;box-sizing:border-box;font-family:'Inter',sans-serif}
        body{display:flex;height:100vh;background:#FFF7ED;color:#7C2D12}
        @keyframes fadeIn{from{opacity:0;transform:translateY(10px)}to{opacity:1;transform:translateY(0)}}
        @keyframes pulse{0%,100%{opacity:1}50%{opacity:.5}}
        @keyframes slideIn{from{transform:translateX(-20px);opacity:0}to{transform:translateX(0);opacity:1}}

        .sidebar{width:272px;background:#FFFFFF;border-right:2px solid #FDBA74;display:flex;flex-direction:column;box-shadow:3px 0 15px rgba(249,115,22,0.08);overflow-y:auto}
        .sidebar-header{padding:1.2rem;border-bottom:2px solid #FED7AA;text-align:center;background:linear-gradient(135deg,#FFF7ED,#FFEDD5)}
        .sidebar-header img{max-width:140px;margin-bottom:0.4rem}
        .sidebar-role{background:linear-gradient(135deg,#F97316,#EA580C);color:white;padding:0.25rem 0.9rem;border-radius:20px;font-size:0.7rem;font-weight:800;text-transform:uppercase;letter-spacing:0.5px}
        .sidebar-location{font-size:0.65rem;color:#9A3412;margin-top:0.3rem;font-weight:700}

        .sidebar-menu{padding:0.5rem 0;flex:1}
        .menu-category{padding:0.5rem 1.3rem;font-size:0.6rem;font-weight:900;color:#EA580C;text-transform:uppercase;letter-spacing:1.2px;margin-top:0.8rem;display:flex;align-items:center;gap:0.4rem}
        .menu-category::after{content:'';flex:1;height:1px;background:#FED7AA}
        .menu-item{display:flex;align-items:center;padding:0.55rem 1.3rem;color:#78716C;text-decoration:none;font-size:0.78rem;font-weight:600;transition:all 0.2s;border-left:4px solid transparent;animation:slideIn 0.3s ease-out}
        .menu-item i{width:20px;margin-right:0.7rem;font-size:0.85rem;color:#EA580C}
        .menu-item:hover{background:#FFEDD5;color:#9A3412;border-left-color:#F97316}
        .menu-item.active{background:#FED7AA;color:#9A3412;border-left-color:#DC2626;font-weight:800}
        
        .tag{padding:0.1rem 0.4rem;border-radius:4px;font-size:0.6rem;font-weight:800;text-transform:uppercase;margin-right:0.3rem}
        .tag-central{background:#FED7AA;color:#9A3412}
        .tag-hosp{background:#FECACA;color:#991B1B}

        .sidebar-footer{padding:1rem 1.3rem;border-top:2px solid #FED7AA;background:#FFFBEB}
        .btn-logout{width:100%;padding:0.6rem;background:#FEF2F2;color:#DC2626;border:1px solid #FECACA;border-radius:8px;font-weight:700;cursor:pointer;transition:0.2s}
        .btn-logout:hover{background:#DC2626;color:white}

        .main{flex:1;display:flex;flex-direction:column;overflow-y:auto}
        .topbar{background:white;padding:1rem 2rem;display:flex;justify-content:space-between;align-items:center;box-shadow:0 2px 8px rgba(249,115,22,0.08);border-bottom:2px solid #FED7AA}
        .topbar h1{font-size:1.2rem;font-weight:900;color:#7C2D12;display:flex;align-items:center;gap:0.5rem}
        .topbar h1 i{color:#F97316}
        .user-badge{background:linear-gradient(135deg,#F97316,#EA580C);color:white;padding:0.3rem 0.8rem;border-radius:20px;font-size:0.7rem;font-weight:700;text-transform:uppercase}
        .content{padding:1.5rem;animation:fadeIn 0.4s ease-out;flex:1}

        .stat-card{border-radius:14px;padding:1.2rem;box-shadow:0 2px 6px rgba(0,0,0,0.04);transition:transform 0.2s;border-top:4px solid}
        .stat-card:hover{transform:translateY(-3px);box-shadow:0 6px 15px rgba(249,115,22,0.12)}
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-header">
            <img src="https://z-cdn-media.chatglm.cn/files/e422f718-2f1b-43b1-8d33-abab22ae033a.png?auth_key=1880135553-c96ef22a7ec1475a8024ee420ae894cb-0-01473062212a4e246495206bff72dde3" alt="HealthNexus">
            <div><span class="sidebar-role"><i class="fas fa-pills"></i> Farmacia</span></div>
            <div class="sidebar-location">Hospital General</div>
        </div>
        <div class="sidebar-menu">
            <div class="menu-category">Panel</div>
            <a href="{{ route('farmacia.dashboard') }}" class="menu-item @yield('nav-dashboard')"><i class="fas fa-tachometer-alt"></i> Dashboard</a>

            <div class="menu-category">Farmacia Central</div>
            <a href="{{ route('farmacia.inventory') }}" class="menu-item @yield('nav-inventario')"><i class="fas fa-warehouse"></i> <span><span class="tag tag-central">C</span>Inventario</span></a>
            <a href="{{ route('farmacia.controlled') }}" class="menu-item @yield('nav-controlados')"><i class="fas fa-lock"></i> <span><span class="tag tag-central">C</span>Controlados</span></a>
            <a href="{{ route('farmacia.ordenes') }}" class="menu-item @yield('nav-ordenes')"><i class="fas fa-file-invoice-dollar"></i> <span><span class="tag tag-central">C</span>Compras</span></a>
            <a href="{{ route('farmacia.proveedores') }}" class="menu-item @yield('nav-proveedores')"><i class="fas fa-truck"></i> <span><span class="tag tag-central">C</span>Proveedores</span></a>

            <div class="menu-category">Farmacia Hospitalaria</div>
            <a href="{{ route('farmacia.dispensacion') }}" class="menu-item @yield('nav-dispensacion')"><i class="fas fa-prescription"></i> <span><span class="tag tag-hosp">H</span>Dispensación</span></a>
            <a href="{{ route('farmacia.enfermeraMeds') }}" class="menu-item @yield('nav-enfermera')"><i class="fas fa-user-nurse"></i> <span><span class="tag tag-hosp">H</span>Enfermería</span></a>
            <a href="{{ route('farmacia.crashCarts') }}" class="menu-item @yield('nav-crashcarts')"><i class="fas fa-first-aid"></i> <span><span class="tag tag-hosp">H</span>Carros Emerg.</span></a>
            <a href="{{ route('farmacia.traspasos') }}" class="menu-item @yield('nav-traspasos')"><i class="fas fa-exchange-alt"></i> <span><span class="tag tag-hosp">H</span>Traspasos</span></a>

            <div class="menu-category">Inteligencia</div>
            <a href="{{ route('farmacia.anomalias') }}" class="menu-item @yield('nav-anomalias')"><i class="fas fa-exclamation-triangle"></i> Anomalías</a>
            <a href="{{ route('farmacia.consumo') }}" class="menu-item @yield('nav-consumo')"><i class="fas fa-chart-pie"></i> Consumo</span></a>
            <a href="{{ route('farmacia.desabasto') }}" class="menu-item @yield('nav-desabasto')"><i class="fas fa-box-open"></i> Desabasto IA</a>

            <div class="menu-category">Reportes</div>
            <a href="{{ route('farmacia.exportar') }}" class="menu-item @yield('nav-exportar')"><i class="fas fa-file-pdf"></i> Exportar</a>
            <a href="{{ route('farmacia.carga') }}" class="menu-item @yield('nav-carga')"><i class="fas fa-upload"></i> Carga Masiva</a>
            <a href="{{ route('farmacia.movimientos') }}" class="menu-item @yield('nav-movimientos')"><i class="fas fa-clipboard-list"></i> Movimientos</a>
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
            <h1><i class="fas fa-pills"></i> @yield('title', 'Farmacia')</h1>
            <div style="display:flex;align-items:center;gap:10px">
                <span style="font-weight:600;font-size:0.9rem;color:#78716C">{{ auth()->user()->name }}</span>
                <span class="user-badge">{{ auth()->user()->role }}</span>
            </div>
        </div>
        <div class="content">
            @if(session('success'))
            <div style="background:#F0FDF4;border:1px solid #BBF7D0;color:#9A3412;padding:1rem;border-radius:10px;margin-bottom:1.2rem;font-weight:700;font-size:0.85rem">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
            @endif
            @if(session('error'))
            <div style="background:#FEF2F2;border:1px solid #FECACA;color:#991B1B;padding:1rem;border-radius:10px;margin-bottom:1.2rem;font-weight:700;font-size:0.85rem">
                <i class="fas fa-times-circle"></i> {{ session('error') }}
            </div>
            @endif
            @if(session('warning'))
            <div style="background:#FFFBEB;border:1px solid #FDE68A;color:#92400E;padding:1rem;border-radius:10px;margin-bottom:1.2rem;font-weight:700;font-size:0.85rem">
                <i class="fas fa-exclamation-triangle"></i> {{ session('warning') }}
            </div>
            @endif
            @yield('content')
        </div>
    </div>
</body>
</html>
