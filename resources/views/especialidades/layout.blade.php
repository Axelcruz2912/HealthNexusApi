<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Especialista - HealthNexus</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        * { margin:0; padding:0; box-sizing:border-box; font-family:'Segoe UI',system-ui,sans-serif; }
        body { background:#FFF7ED; min-height:100vh; }
        .topbar { background:linear-gradient(135deg,#EA580C,#DC2626); color:white; padding:0.8rem 2rem; display:flex; justify-content:space-between; align-items:center; position:fixed; top:0; left:0; right:0; z-index:100; height:56px; }
        .topbar h1 { font-size:1.1rem; font-weight:900; }
        .main-wrap { display:flex; margin-top:56px; min-height:calc(100vh - 56px); }
        .content-area { flex:1; padding:0; overflow-y:auto; margin-right:260px; }
        .sidebar { width:260px; background:white; border-left:1px solid #FDBA74; padding:1rem 0; position:fixed; right:0; top:56px; bottom:0; overflow-y:auto; box-shadow:-2px 0 8px rgba(0,0,0,0.05); }
        .sidebar-section { padding:0.5rem 1rem; margin-bottom:0.3rem; }
        .sidebar-label { font-size:0.65rem; font-weight:900; color:#9A3412; text-transform:uppercase; letter-spacing:1px; padding:0.3rem 0; margin-bottom:0.3rem; }
        .sidebar-link { display:flex; align-items:center; gap:0.6rem; padding:0.5rem 0.8rem; color:#78716C; text-decoration:none; font-size:0.82rem; font-weight:600; border-radius:8px; transition:0.15s; }
        .sidebar-link:hover, .sidebar-link.active { background:#FFEDD5; color:#EA580C; }
        .sidebar-link i { width:18px; text-align:center; }
        .sidebar-link .badge-count { background:#DC2626; color:white; font-size:0.6rem; padding:0.1rem 0.35rem; border-radius:10px; margin-left:auto; font-weight:800; }
        .stat-mini { display:flex; justify-content:space-between; padding:0.4rem 0.8rem; font-size:0.75rem; }
        .stat-mini .num { font-weight:900; }
        .stat-mini.red .num { color:#DC2626; }
        .stat-mini.orange .num { color:#EA580C; }
        .divider { height:1px; background:#FFF0E0; margin:0.5rem 1rem; }
        .logout-btn { display:flex; align-items:center; gap:0.6rem; padding:0.5rem 0.8rem; color:#DC2626; text-decoration:none; font-size:0.82rem; font-weight:700; border-radius:8px; transition:0.15s; cursor:pointer; background:none; border:none; width:calc(100% - 2rem); margin:0 1rem; }
        .logout-btn:hover { background:#FEE2E2; }
    </style>
</head>
<body>
    <div class="topbar">
        <h1><i class="fas fa-hospital"></i> HealthNexus <span style="opacity:0.7;font-weight:400">| Especialista</span></h1>
        <div style="display:flex;align-items:center;gap:1rem">
            <span style="font-size:0.8rem;opacity:0.9">{{ auth()->user()->name }}</span>
            <span style="background:rgba(255,255,255,0.2);padding:0.2rem 0.6rem;border-radius:4px;font-size:0.7rem;font-weight:800">{{ auth()->user()->role }}</span>
        </div>
    </div>

    <div class="main-wrap">
        <div class="content-area">
            @yield('content')
        </div>

        <div class="sidebar">
            <div class="sidebar-section">
                <div class="sidebar-label">Navegacion</div>
                <a href="{{ url('/medico/especialista') }}" class="sidebar-link"><i class="fas fa-home"></i> Dashboard</a>
                <a href="{{ url('/medico/especialidades') }}" class="sidebar-link"><i class="fas fa-hospital"></i> Especialidades</a>
                <a href="{{ url('/medico/agenda') }}" class="sidebar-link"><i class="fas fa-calendar-alt"></i> Agenda</a>
            </div>

            <div class="divider"></div>

            <div class="sidebar-section">
                <div class="sidebar-label">Pacientes</div>
                <a href="{{ url('/medico/pacientes') }}" class="sidebar-link">
                    <i class="fas fa-user-injured"></i> Mis Pacientes
                    @php $misP = App\Models\Triage::where('assigned_doctor_id', auth()->id())->whereIn('status', ['En Atencion', 'Hospitalizado'])->count(); @endphp
                    @if($misP > 0)<span class="badge-count">{{ $misP }}</span>@endif
                </a>
                <a href="{{ url('/medico/hospitalizados') }}" class="sidebar-link"><i class="fas fa-bed-pulse"></i> Hospitalizados</a>
                <a href="{{ url('/medico/derivaciones') }}" class="sidebar-link">
                    <i class="fas fa-share-nodes"></i> Derivaciones
                    @php $pendD = 0; if(Schema::hasColumn('derivations','to_doctor_id')) { $pendD = App\Models\Derivation::where('to_doctor_id', auth()->id())->where('status','Pendiente')->count(); } @endphp
                    @if($pendD > 0)<span class="badge-count">{{ $pendD }}</span>@endif
                </a>
            </div>

            <div class="divider"></div>

            <div class="sidebar-section">
                <div class="sidebar-label"><i class="fas fa-truck-medical" style="color:#EA580C"></i> Ambulancia / Traslados</div>
                <a href="{{ url('/medico/ambulancias') }}" class="sidebar-link">
                    <i class="fas fa-truck-medical"></i> Ambulancias
                    @php $ambAct = App\Models\Ambulance::where('status','En Ruta')->count(); @endphp
                    @if($ambAct > 0)<span class="badge-count">{{ $ambAct }}</span>@endif
                </a>
                <a href="{{ url('/medico/hospital-live') }}" class="sidebar-link"><i class="fas fa-tower-broadcast"></i> Hospital Live</a>
            </div>

            <div class="divider"></div>

            <div class="sidebar-section">
                <div class="sidebar-label"><i class="fas fa-brain" style="color:#EA580C"></i> IA Medica</div>
                <a href="{{ url('/medico/asistente-ia') }}" class="sidebar-link"><i class="fas fa-robot"></i> Asistente IA</a>
            </div>

            <div class="divider"></div>

            <div class="sidebar-section">
                <div class="sidebar-label">Rapido</div>
                <div class="stat-mini red"><span>Hospitalizados</span><span class="num">{{ App\Models\Triage::where('status', 'Hospitalizado')->count() }}</span></div>
                <div class="stat-mini orange"><span>Camas Libres</span><span class="num">{{ App\Models\Bed::where('status', 'Disponible')->count() }}</span></div>
                <div class="stat-mini red"><span>Criticos</span><span class="num">{{ App\Models\Triage::where('triage_level', 'Rojo')->whereIn('status', ['En Espera', 'En Atencion'])->count() }}</span></div>
                <div class="stat-mini orange"><span>Ambulancias</span><span class="num">{{ App\Models\Ambulance::where('status', 'En Ruta')->count() }}/{{ App\Models\Ambulance::count() }}</span></div>
            </div>

            <div class="divider"></div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Cerrar Sesion</button>
            </form>
        </div>
    </div>
</body>
</html>
