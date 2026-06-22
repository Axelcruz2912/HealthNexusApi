@php
 $role = session('doctor_profile', 'Médico C');
 $doctorName = session('doctor_name', 'Médico');
 $isA = $role === 'Médico A';
 $isB = $role === 'Médico B';
 $isC = $role === 'Médico C';
 $color = $isA ? '#DC2626' : ($isB ? '#EA580C' : '#F97316');
 $colorLight = $isA ? '#FEE2E2' : ($isB ? '#FFEDD5' : '#FED7AA');
 $bgLight = $isA ? '#FEF2F2' : ($isB ? '#FFF7ED' : '#FFF7ED');
 $gradBtn = $isA ? 'linear-gradient(135deg,#DC2626,#B91C1C)' : ($isB ? 'linear-gradient(135deg,#EA580C,#C2410C)' : 'linear-gradient(135deg,#F97316,#EA580C)');
 $levelLabel = $isA ? 'ESPECIALISTA' : ($isB ? 'INTERMEDIO' : 'BÁSICO');
@endphp
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - HealthNexus</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800;900&display=swap');
        *{margin:0;padding:0;box-sizing:border-box}
        body{font-family:'Inter',system-ui,sans-serif;background:#F9F8F6;min-height:100vh}
        .sb{position:fixed;left:0;top:0;bottom:0;width:235px;background:#fff;border-right:1px solid #E7E5E4;overflow-y:auto;z-index:100}
        .sb-h{padding:1rem;text-align:center;background:{{$bgLight}};border-bottom:3px solid {{$color}}}
        .sb-h h2{font-size:0.9rem;font-weight:800;color:#1C1917;letter-spacing:-0.3px}
        .sb-h .bdg{display:inline-block;background:{{$color}};color:#fff;padding:0.15rem 0.55rem;border-radius:12px;font-size:0.55rem;font-weight:800;margin-top:0.35rem;letter-spacing:0.5px}
        .sb-h .dn{font-size:0.72rem;color:#57534E;margin-top:0.2rem;font-weight:600}
        .ns{padding:0.4rem 1rem;font-size:0.5rem;text-transform:uppercase;letter-spacing:1.2px;color:#A8A29E;margin-top:0.5rem;font-weight:800}
        .ni{display:flex;align-items:center;padding:0.45rem 1rem;color:#57534E;text-decoration:none;font-size:0.78rem;transition:all 0.15s;gap:0.5rem;border-left:3px solid transparent;font-weight:500}
        .ni:hover{background:{{$bgLight}};color:#1C1917;border-left-color:{{$color}}}
        .ni.a{background:{{$bgLight}};color:{{$color}};font-weight:700;border-left-color:{{$color}}}
        .ni i{width:16px;text-align:center;font-size:0.78rem}
        .mn{margin-left:235px;padding:1.5rem 2rem}
        .tb{background:#fff;padding:0.65rem 1.5rem;border-radius:14px;box-shadow:0 1px 4px rgba(0,0,0,0.06);display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem}
        .tb h2{font-weight:800;font-size:1.05rem;color:#1C1917;letter-spacing:-0.3px}
    </style>
</head>
<body>
<aside class="sb">
    <div class="sb-h">
        <h2><i class="fas fa-stethoscope" style="color:{{$color}}"></i> HealthNexus</h2>
        <div class="bdg">{{$levelLabel}}</div>
        <div class="dn">{{$doctorName}}</div>
    </div>
    <nav>
        <div class="ns">Principal</div>
        <a href="{{route('medico.dashboard')}}" class="ni @yield('nav-dashboard')"><i class="fas fa-home"></i> Inicio</a>

        @if($isC)
        <div class="ns">Mi Consulta</div>
        <a href="{{route('medico.consulta')}}" class="ni @yield('nav-consulta')"><i class="fas fa-user-md"></i> Consulta</a>
        <a href="{{route('medico.diagnosticos')}}" class="ni @yield('nav-diagnosticos')"><i class="fas fa-file-medical"></i> Diagnósticos</a>
        <a href="{{route('medico.recetas')}}" class="ni @yield('nav-recetas')"><i class="fas fa-prescription"></i> Recetas</a>
        <div class="ns">Ver</div>
        <a href="{{route('medico.signos')}}" class="ni @yield('nav-signos')"><i class="fas fa-heartbeat"></i> Signos Vitales</a>
        <a href="{{route('medico.estudios')}}" class="ni @yield('nav-estudios')"><i class="fas fa-microscope"></i> Estudios</a>
        <a href="{{route('medico.evolucion')}}" class="ni @yield('nav-evolución')"><i class="fas fa-notes-medical"></i> Evolución</a>
        @endif

        @if($isB)
        <div class="ns">Pacientes</div>
        <a href="{{route('medico.pacientes')}}" class="ni @yield('nav-pacientes')"><i class="fas fa-users"></i> Pacientes</a>
        <a href="{{route('medico.consulta')}}" class="ni @yield('nav-consulta')"><i class="fas fa-user-md"></i> Consulta</a>
        <a href="{{route('medico.diagnosticos')}}" class="ni @yield('nav-diagnosticos')"><i class="fas fa-file-medical"></i> Diagnósticos</a>
        <div class="ns">Tratamiento</div>
        <a href="{{route('medico.recetas')}}" class="ni @yield('nav-recetas')"><i class="fas fa-prescription"></i> Recetas</a>
        <a href="{{route('medico.signos')}}" class="ni @yield('nav-signos')"><i class="fas fa-heartbeat"></i> Signos Vitales</a>
        <a href="{{route('medico.estudios')}}" class="ni @yield('nav-estudios')"><i class="fas fa-microscope"></i> Estudios</a>
        <div class="ns">Hospitalización</div>
        <a href="{{route('medico.hospitalizacion')}}" class="ni @yield('nav-hospitalización')"><i class="fas fa-bed"></i> Hospitalización</a>
        <a href="{{route('medico.camas')}}" class="ni @yield('nav-camas')"><i class="fas fa-th"></i> Mapa de Camas</a>
        <div class="ns">Sistema</div>
        <a href="{{route('medico.servicios')}}" class="ni @yield('nav-servicios')"><i class="fas fa-concierge-bell"></i> Servicios</a>
        <a href="{{route('medico.evolucion')}}" class="ni @yield('nav-evolución')"><i class="fas fa-notes-medical"></i> Evolución</a>
        <a href="{{route('medico.alertas')}}" class="ni @yield('nav-alertas')"><i class="fas fa-bell"></i> Alertas</a>
        <a href="{{route('medico.reportes')}}" class="ni @yield('nav-reportes')"><i class="fas fa-chart-bar"></i> Reportes</a>
        @endif

        @if($isA)
        <div class="ns">Pacientes</div>
        <a href="{{route('medico.pacientes')}}" class="ni @yield('nav-pacientes')"><i class="fas fa-users"></i> Pacientes</a>
        <a href="{{route('medico.consulta')}}" class="ni @yield('nav-consulta')"><i class="fas fa-user-md"></i> Consulta</a>
        <a href="{{route('medico.diagnosticos')}}" class="ni @yield('nav-diagnosticos')"><i class="fas fa-file-medical"></i> Diagnósticos</a>
        <div class="ns">Tratamiento</div>
        <a href="{{route('medico.recetas')}}" class="ni @yield('nav-recetas')"><i class="fas fa-prescription"></i> Recetas</a>
        <a href="{{route('medico.signos')}}" class="ni @yield('nav-signos')"><i class="fas fa-heartbeat"></i> Signos Vitales</a>
        <a href="{{route('medico.estudios')}}" class="ni @yield('nav-estudios')"><i class="fas fa-microscope"></i> Estudios</a>
        <a href="{{route('medico.tratamientos')}}" class="ni @yield('nav-tratamientos')"><i class="fas fa-pills"></i> Tratamientos</a>
        <div class="ns">Hospitalización</div>
        <a href="{{route('medico.hospitalizacion')}}" class="ni @yield('nav-hospitalización')"><i class="fas fa-bed"></i> Hospitalización</a>
        <a href="{{route('medico.camas')}}" class="ni @yield('nav-camas')"><i class="fas fa-th"></i> Mapa de Camas</a>
        <a href="{{route('medico.hospitalizados')}}" class="ni @yield('nav-hospitalizados')"><i class="fas fa-procedures"></i> Hospitalizados</a>
        <div class="ns">Sistema</div>
        <a href="{{route('medico.servicios')}}" class="ni @yield('nav-servicios')"><i class="fas fa-concierge-bell"></i> Servicios</a>
        <a href="{{route('medico.farmaciaStock')}}" class="ni @yield('nav-farmaciaStock')"><i class="fas fa-capsules"></i> Farmacia</a>
        <a href="{{route('medico.insumos')}}" class="ni @yield('nav-insumos')"><i class="fas fa-box-open"></i> Insumos</a>
        <a href="{{route('medico.evolucion')}}" class="ni @yield('nav-evolución')"><i class="fas fa-notes-medical"></i> Evolución</a>
        <a href="{{route('medico.alertas')}}" class="ni @yield('nav-alertas')"><i class="fas fa-bell"></i> Alertas</a>
        <a href="{{route('medico.reportes')}}" class="ni @yield('nav-reportes')"><i class="fas fa-chart-bar"></i> Reportes</a>
        <div class="ns" style="color:#DC2626;font-weight:800">🔴 Crítico</div>
        <a href="{{route('medico.defunciones')}}" class="ni @yield('nav-defunciones')"><i class="fas fa-cross"></i> Defunciones</a>
        <a href="{{route('medico.uci')}}" class="ni @yield('nav-uci')"><i class="fas fa-procedures"></i> UCI</a>
        <a href="{{route('medico.quirofano')}}" class="ni @yield('nav-quirofano')"><i class="fas fa-cut"></i> Quirófano</a>
        <a href="{{route('medico.iaMedica')}}" class="ni @yield('nav-ia')"><i class="fas fa-brain"></i> IA Médica</a>
        <a href="{{route('medico.derivaciones')}}" class="ni @yield('nav-derivaciones')"><i class="fas fa-ambulance"></i> Derivaciones</a>
        @endif

        @if($isA)
        <div class="ns" style="color:#EA580C">🚑 Ambulancia / Traslados</div>
        <a href="{{url('/medico/ambulancias-medico')}}" class="ni @yield('nav-ambulancias')"><i class="fas fa-truck-medical"></i> Ambulancias</a>
        <a href="{{url('/medico/hospital-live-medico')}}" class="ni @yield('nav-hospital-live')"><i class="fas fa-tower-broadcast"></i> Hospital Live</a>
        <div class="ns" style="color:#EA580C">🧠 IA Avanzada</div>
        <a href="{{url('/medico/asistente-ia-medico')}}" class="ni @yield('nav-asistente-ia')"><i class="fas fa-robot"></i> Asistente IA</a>
        @endif

        @if($isB)
        <div class="ns" style="color:#EA580C">🚑 Ambulancia</div>
        <a href="{{url('/medico/ambulancias-medico')}}" class="ni @yield('nav-ambulancias')"><i class="fas fa-truck-medical"></i> Ambulancias</a>
        <a href="{{url('/medico/hospital-live-medico')}}" class="ni @yield('nav-hospital-live')"><i class="fas fa-tower-broadcast"></i> Hospital Live</a>
        <div class="ns" style="color:#EA580C">🧠 IA</div>
        <a href="{{url('/medico/asistente-ia-medico')}}" class="ni @yield('nav-asistente-ia')"><i class="fas fa-robot"></i> Asistente IA</a>
        @endif

        <div class="ns">Sesión</div>
        <a href="{{route('medico.seleccionar')}}" class="ni" style="color:#EA580C"><i class="fas fa-exchange-alt"></i> Cambiar Perfil</a>
        <a href="{{route('logout')}}" class="ni" style="color:#DC2626" onclick="event.preventDefault();document.getElementById('lf').submit()"><i class="fas fa-sign-out-alt"></i> Salir</a>
    </nav>
</aside>
<main class="mn">
    <div class="tb">
        <h2>@yield('title')</h2>
        <div style="display:flex;align-items:center;gap:0.75rem">
            <span style="background:{{$colorLight}};color:{{$color}};padding:0.2rem 0.6rem;border-radius:10px;font-size:0.65rem;font-weight:800">{{$role}}</span>
            <span style="font-size:0.72rem;color:#A8A29E">{{now()->format('d/m/Y H:i')}}</span>
        </div>
    </div>
    @if(session('success'))<div style="background:#F0FDF4;border:1px solid #BBF7D0;color:#166534;padding:0.6rem 1rem;border-radius:10px;margin-bottom:1rem;font-weight:600;font-size:0.8rem"><i class="fas fa-check-circle"></i> {{session('success')}}</div>@endif
    @if(session('error'))<div style="background:#FEF2F2;border:1px solid #FCA5A5;color:#991B1B;padding:0.6rem 1rem;border-radius:10px;margin-bottom:1rem;font-weight:600;font-size:0.8rem"><i class="fas fa-exclamation-circle"></i> {{session('error')}}</div>@endif
    @yield('content')
</main>
<form id="lf" action="{{route('logout')}}" method="POST" style="display:none">@csrf</form>
</body>
</html>
