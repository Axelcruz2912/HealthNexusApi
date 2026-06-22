@extends('superadmin.layout')

@section('title', 'Centro Inteligente de Auditoria Hospitalaria')

@section('content')
<style>
    .aud-card { background: white; border-radius: 12px; padding: 1rem; box-shadow: 0 1px 4px rgba(0,0,0,0.06); border: 1px solid #FED7AA; margin-bottom: 0.75rem; }
    .aud-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(130px, 1fr)); gap: 0.5rem; margin-bottom: 0.75rem; }
    .aud-kpi { text-align: center; padding: 0.6rem; border-radius: 8px; border: 1px solid #FED7AA; background: white; }
    .aud-kpi .label { font-size: 0.55rem; color: #78716C; text-transform: uppercase; font-weight: 700; }
    .aud-kpi .value { font-size: 1.2rem; font-weight: 800; margin-top: 0.1rem; }
    .aud-kpi i { font-size: 0.85rem; display: block; margin-bottom: 0.1rem; }
    .aud-table { width: 100%; border-collapse: collapse; font-size: 0.7rem; }
    .aud-table th { background: #E85D3A; color: white; padding: 0.3rem 0.5rem; text-align: left; font-size: 0.58rem; text-transform: uppercase; }
    .aud-table td { padding: 0.3rem 0.5rem; border-bottom: 1px solid #FFF7ED; }
    .aud-table tr:hover { background: #FFF7ED; }
    .aud-table tr.suspicious { background: #FEF2F2; }
    .badge { padding: 0.1rem 0.4rem; border-radius: 20px; font-size: 0.55rem; font-weight: 700; }
    .badge-green { background: #D1FAE5; color: #065F46; }
    .badge-yellow { background: #FEF3C7; color: #92400E; }
    .badge-orange { background: #FFEDD5; color: #C2410C; }
    .badge-red { background: #FEE2E2; color: #991B1B; }
    .badge-gray { background: #F5F5F4; color: #78716C; }
    .btn-sm { padding: 0.2rem 0.5rem; border-radius: 6px; font-size: 0.6rem; font-weight: 700; border: none; cursor: pointer; text-decoration: none; display: inline-flex; align-items: center; gap: 0.2rem; }
    .btn-orange { background: #E85D3A; color: white; }
    .btn-red { background: #DC2626; color: white; }
    .btn-green { background: #059669; color: white; }
    .btn-ghost { background: transparent; color: #78716C; border: 1px solid #FED7AA; }
    .filter-bar { display: flex; gap: 0.4rem; flex-wrap: wrap; align-items: center; margin-bottom: 0.75rem; }
    .filter-bar input, .filter-bar select { padding: 0.3rem 0.5rem; border: 1px solid #FED7AA; border-radius: 6px; font-size: 0.7rem; }
    .filter-bar input:focus, .filter-bar select:focus { outline: none; border-color: #E85D3A; }
    .tab-bar { display: flex; gap: 0.15rem; margin-bottom: 0.75rem; border-bottom: 2px solid #FED7AA; padding-bottom: 0; overflow-x: auto; flex-wrap: nowrap; }
    .tab { padding: 0.3rem 0.55rem; font-size: 0.6rem; font-weight: 700; color: #78716C; cursor: pointer; border: none; background: none; border-bottom: 2px solid transparent; margin-bottom: -2px; white-space: nowrap; }
    .tab.active { color: #E85D3A; border-bottom-color: #E85D3A; }
    .tab:hover { color: #C2410C; }
    .tab-content { display: none; }
    .tab-content.active { display: block; }
    .section-title { font-size: 0.8rem; font-weight: 800; color: #1E1A17; margin-bottom: 0.4rem; display: flex; align-items: center; gap: 0.3rem; }
    .section-title i { color: #E85D3A; }
    .risk-dot { width: 8px; height: 8px; border-radius: 50%; display: inline-block; }
    .risk-bajo { background: #059669; }
    .risk-medio { background: #F59E0B; }
    .risk-alto { background: #EA580C; }
    .risk-critico { background: #DC2626; animation: pulse 1.5s infinite; }
    @keyframes pulse { 0%,100%{opacity:1;} 50%{opacity:0.4;} }
</style>

<!-- KPIs -->
<div class="aud-grid">
    <div class="aud-kpi" style="border-left:3px solid #E85D3A;">
        <i class="fas fa-database" style="color:#E85D3A;"></i>
        <div class="label">Total Eventos</div>
        <div class="value" style="color:#E85D3A;">{{ number_format($stats['total']) }}</div>
    </div>
    <div class="aud-kpi" style="border-left:3px solid #FB923C;">
        <i class="fas fa-calendar-day" style="color:#FB923C;"></i>
        <div class="label">Hoy</div>
        <div class="value" style="color:#FB923C;">{{ number_format($stats['today']) }}</div>
    </div>
    <div class="aud-kpi" style="border-left:3px solid #DC2626;">
        <i class="fas fa-triangle-exclamation" style="color:#DC2626;"></i>
        <div class="label">Sospechosos</div>
        <div class="value" style="color:#DC2626;">{{ number_format($stats['suspicious']) }}</div>
    </div>
    <div class="aud-kpi" style="border-left:3px solid #991B1B;">
        <i class="fas fa-skull-crossbones" style="color:#991B1B;"></i>
        <div class="label">Criticos</div>
        <div class="value" style="color:#991B1B;">{{ number_format($stats['critical']) }}</div>
    </div>
    <div class="aud-kpi" style="border-left:3px solid #EA580C;">
        <i class="fas fa-exclamation" style="color:#EA580C;"></i>
        <div class="label">Alto Riesgo</div>
        <div class="value" style="color:#EA580C;">{{ number_format($stats['high']) }}</div>
    </div>
    <div class="aud-kpi" style="border-left:3px solid #F59E0B;">
        <i class="fas fa-cubes" style="color:#F59E0B;"></i>
        <div class="label">Modulos</div>
        <div class="value" style="color:#F59E0B;">{{ $stats['modules'] }}</div>
    </div>
    <div class="aud-kpi" style="border-left:3px solid #059669;">
        <i class="fas fa-users" style="color:#059669;"></i>
        <div class="label">Usuarios Hoy</div>
        <div class="value" style="color:#059669;">{{ $stats['users_active'] }}</div>
    </div>
</div>

<!-- ALERTAS -->
@if($alerts->count() > 0)
<div class="aud-card" style="border-color:#DC2626; background:#FEF2F2;">
    <div class="section-title" style="color:#991B1B;"><i class="fas fa-bell" style="color:#DC2626;"></i> Alertas Recientes</div>
    <div style="display:flex; gap:0.4rem; overflow-x:auto; padding-bottom:0.3rem;">
        @foreach($alerts->take(5) as $a)
        <div style="min-width:180px; padding:0.5rem; background:white; border-radius:8px; border-left:3px solid {{ $a->risk_level === 'critico' ? '#DC2626' : '#EA580C' }};">
            <div style="font-size:0.58rem; color:#A8A29E;">{{ date('H:i d/m', strtotime($a->created_at)) }}</div>
            <div style="font-size:0.68rem; font-weight:700;">{{ $a->action }}</div>
            <div style="font-size:0.58rem; color:#78716C;">{{ $a->user_name }} - {{ $a->module }}</div>
            @if($a->risk_reason)<div style="font-size:0.55rem; color:#DC2626; font-weight:700;">{{ $a->risk_reason }}</div>@endif
        </div>
        @endforeach
    </div>
</div>
@endif

<!-- TABS -->
<div class="tab-bar">
    <button class="tab active" onclick="switchTab('timeline')">Timeline</button>
    <button class="tab" onclick="switchTab('dashboard')">Dashboard</button>
    <button class="tab" onclick="switchTab('accesos')">Accesos</button>
    <button class="tab" onclick="switchTab('paciente')">Por Paciente</button>
    <button class="tab" onclick="switchTab('medica')">Aud. Medica</button>
    <button class="tab" onclick="switchTab('hospital')">Aud. Hospital</button>
    <button class="tab" onclick="switchTab('finanzas')">Aud. Finanzas</button>
    <button class="tab" onclick="switchTab('farmacia')">Aud. Farmacia</button>
    <button class="tab" onclick="switchTab('usuarios')">Usuarios</button>
    <button class="tab" onclick="switchTab('huella')">Huella Digital</button>
    <button class="tab" onclick="switchTab('ia')">IA Seguridad</button>
    <button class="tab" onclick="switchTab('riesgo')">Score Riesgo</button>
    <button class="tab" onclick="switchTab('negligencia')">Negligencia</button>
    <button class="tab" onclick="switchTab('exportar')">Exportar</button>
</div>

<!-- TAB: TIMELINE -->
<div id="tab-timeline" class="tab-content active">
    <form method="GET" action="{{ route('superadmin.auditoria') }}" class="filter-bar">
        <input type="text" name="patient" placeholder="Paciente..." value="{{ request('patient') }}" style="max-width:120px;">
        <input type="text" name="user" placeholder="Usuario..." value="{{ request('user') }}" style="max-width:120px;">
        <select name="module" style="max-width:130px;">
            <option value="">Todos los modulos</option>
            @foreach(['Enfermeria','Farmacia','Finanzas','Urgencias','Hospitalizacion','Seguridad','Medico','Laboratorio','Administracion'] as $m)
            <option value="{{ $m }}" {{ request('module') === $m ? 'selected' : '' }}>{{ $m }}</option>
            @endforeach
        </select>
        <select name="risk" style="max-width:100px;">
            <option value="">Todo riesgo</option>
            <option value="bajo" {{ request('risk') === 'bajo' ? 'selected' : '' }}>Bajo</option>
            <option value="medio" {{ request('risk') === 'medio' ? 'selected' : '' }}>Medio</option>
            <option value="alto" {{ request('risk') === 'alto' ? 'selected' : '' }}>Alto</option>
            <option value="critico" {{ request('risk') === 'critico' ? 'selected' : '' }}>Critico</option>
        </select>
        <label style="font-size:0.65rem; font-weight:700; display:flex; align-items:center; gap:0.2rem;">
            <input type="checkbox" name="suspicious" value="1" {{ request('suspicious') ? 'checked' : '' }}> Sospechoso
        </label>
        <input type="date" name="from" value="{{ request('from') }}" style="max-width:120px;">
        <input type="date" name="to" value="{{ request('to') }}" style="max-width:120px;">
        <button type="submit" class="btn-sm btn-orange"><i class="fas fa-search"></i> Filtrar</button>
        <a href="{{ route('superadmin.auditoria') }}" class="btn-sm btn-ghost"><i class="fas fa-times"></i></a>
    </form>
    <div class="aud-card">
        <table class="aud-table">
            <thead><tr><th>Fecha/Hora</th><th>Usuario</th><th>Accion</th><th>Modulo</th><th>Detalles</th><th>IP</th><th>Riesgo</th></tr></thead>
            <tbody>
                @foreach($logs as $log)
                <tr class="{{ $log->is_suspicious ? 'suspicious' : '' }}">
                    <td style="white-space:nowrap; font-size:0.65rem; color:#78716C;">{{ date('d/m/Y H:i', strtotime($log->created_at)) }}</td>
                    <td><div style="font-weight:700;">{{ $log->user_name }}</div><span class="badge badge-gray">{{ $log->user_role }}</span></td>
                    <td style="font-weight:700; color:{{ str_contains(strtoupper($log->action), 'LOGIN') ? '#059669' : (str_contains(strtoupper($log->action), 'FALLIDO') ? '#DC2626' : '#1E1A17') }};">{{ $log->action }}</td>
                    <td><span class="badge badge-orange">{{ $log->module }}</span></td>
                    <td style="max-width:200px; overflow:hidden; text-overflow:ellipsis; font-size:0.65rem;">{{ $log->details }}</td>
                    <td style="font-family:monospace; font-size:0.6rem; color:#78716C;">{{ $log->ip_address }}</td>
                    <td><span class="risk-dot risk-{{ $log->risk_level ?? 'bajo' }}"></span><span class="badge {{ ($log->risk_level ?? 'bajo') === 'critico' ? 'badge-red' : (($log->risk_level ?? 'bajo') === 'alto' ? 'badge-orange' : (($log->risk_level ?? 'bajo') === 'medio' ? 'badge-yellow' : 'badge-green')) }}">{{ $log->risk_level ?? 'bajo' }}</span></td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div style="display:flex; justify-content:center; padding:0.75rem 0;">{{ $logs->withQueryString()->links() }}</div>
    </div>
</div>

<!-- TAB: DASHBOARD -->
<div id="tab-dashboard" class="tab-content">
    <div style="display:grid; grid-template-columns:1fr 1fr; gap:0.75rem;">
        <div class="aud-card">
            <div class="section-title"><i class="fas fa-chart-bar"></i> Actividad 24h</div>
            <div style="display:flex; align-items:flex-end; gap:3px; height:80px;">
                @for($h = 0; $h < 24; $h++)
                    @php $val = $hourlyData[$h] ?? 0; $maxVal = $hourlyData->max() ?? 1; @endphp
                    <div style="flex:1; background:linear-gradient(to top, #E85D3A, #FB923C); border-radius:2px 2px 0 0; height:{{ max($val / max($maxVal, 1) * 100, 2) }}%;" title="{{ $h }}:00 - {{ $val }} eventos"></div>
                @endfor
            </div>
        </div>
        <div class="aud-card">
            <div class="section-title"><i class="fas fa-ranking-star"></i> Top Acciones (7 dias)</div>
            <table class="aud-table"><thead><tr><th>Accion</th><th>Total</th></tr></thead>
            @foreach($topActions as $a)<tr><td>{{ $a->action }}</td><td style="font-weight:700;">{{ number_format($a->total) }}</td></tr>@endforeach
            </table>
        </div>
        <div class="aud-card">
            <div class="section-title"><i class="fas fa-cubes"></i> Por Modulo (7 dias)</div>
            <table class="aud-table"><thead><tr><th>Modulo</th><th>Total</th><th>Sospechoso</th></tr></thead>
            @foreach($byModule as $m)<tr><td><span class="badge badge-orange">{{ $m->module }}</span></td><td style="font-weight:700;">{{ number_format($m->total) }}</td><td style="color:{{ $m->suspicious > 0 ? '#DC2626' : '#059669' }};">{{ $m->suspicious }}</td></tr>@endforeach
            </table>
        </div>
        <div class="aud-card">
            <div class="section-title"><i class="fas fa-users"></i> Usuarios Activos (7 dias)</div>
            <table class="aud-table"><thead><tr><th>Usuario</th><th>Rol</th><th>Acciones</th><th>Sospechoso</th></tr></thead>
            @foreach($topUsers as $u)<tr><td style="font-weight:700;">{{ $u->user_name }}</td><td><span class="badge badge-gray">{{ $u->user_role }}</span></td><td>{{ number_format($u->total) }}</td><td style="color:{{ $u->suspicious > 0 ? '#DC2626' : '#059669' }};">{{ $u->suspicious }}</td></tr>@endforeach
            </table>
        </div>
    </div>
</div>

<!-- TAB: ACCESOS -->
<div id="tab-accesos" class="tab-content">
    <div style="display:grid; grid-template-columns:repeat(3,1fr); gap:0.5rem; margin-bottom:0.75rem;">
        <div class="aud-kpi" style="border-left:3px solid #059669;"><i class="fas fa-right-to-bracket" style="color:#059669;"></i><div class="label">Login OK (7d)</div><div class="value" style="color:#059669;">{{ number_format($loginExitoso) }}</div></div>
        <div class="aud-kpi" style="border-left:3px solid #DC2626;"><i class="fas fa-ban" style="color:#DC2626;"></i><div class="label">Login Fallido (7d)</div><div class="value" style="color:#DC2626;">{{ number_format($loginFallido) }}</div></div>
        <div class="aud-kpi" style="border-left:3px solid #991B1B;"><i class="fas fa-lock" style="color:#991B1B;"></i><div class="label">Bloqueos (7d)</div><div class="value" style="color:#991B1B;">{{ number_format($bloqueos) }}</div></div>
    </div>
    <div class="aud-card">
        <div class="section-title"><i class="fas fa-door-open"></i> Registro de Accesos</div>
        <table class="aud-table">
            <thead><tr><th>Fecha/Hora</th><th>Usuario</th><th>Accion</th><th>IP</th><th>Dispositivo</th></tr></thead>
            <tbody>
            @foreach($accesos as $a)
            <tr class="{{ in_array($a->action, ['LOGIN FALLIDO','Sesion Bloqueada','Intento Fuerza Bruta']) ? 'suspicious' : '' }}">
                <td style="font-size:0.65rem; color:#78716C;">{{ date('d/m/Y H:i', strtotime($a->created_at)) }}</td>
                <td style="font-weight:700;">{{ $a->user_name }}</td>
                <td>
                    @if($a->action === 'LOGIN') <span class="badge badge-green">LOGIN</span>
                    @elseif($a->action === 'LOGOUT') <span class="badge badge-gray">LOGOUT</span>
                    @else <span class="badge badge-red">{{ $a->action }}</span>
                    @endif
                </td>
                <td style="font-family:monospace; font-size:0.6rem;">{{ $a->ip_address }}</td>
                <td style="font-size:0.6rem; color:#78716C;">{{ Str::limit($a->user_agent ?? '', 30) }}</td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- TAB: POR PACIENTE -->
<div id="tab-paciente" class="tab-content">
    <div class="aud-card">
        <div class="section-title"><i class="fas fa-user-clock"></i> Historial Completo por Paciente</div>
        <form method="GET" action="{{ route('superadmin.auditoria') }}" class="filter-bar" style="margin-bottom:0.75rem;">
            <input type="text" name="patient" placeholder="Buscar paciente..." value="{{ request('patient') }}" style="max-width:200px;" required>
            <button type="submit" class="btn-sm btn-orange"><i class="fas fa-search"></i> Buscar</button>
        </form>
        @if(request('patient'))
            @php $patientLogs = App\Models\AuditLog::where('patient_name', 'like', '%'.request('patient').'%')->orderBy('created_at','asc')->limit(50)->get(); @endphp
            @if($patientLogs->count() > 0)
            <div style="padding-left:1rem; border-left:3px solid #E85D3A;">
                @foreach($patientLogs as $pl)
                <div style="display:flex; gap:0.5rem; margin-bottom:0.5rem;">
                    <div class="risk-dot {{ $pl->module === 'Urgencias' ? 'risk-critico' : 'risk-alto' }}" style="margin-top:4px;"></div>
                    <div>
                        <div style="font-size:0.65rem; color:#A8A29E;">{{ date('d/m/Y H:i', strtotime($pl->created_at)) }}</div>
                        <div style="font-size:0.75rem; font-weight:700;">{{ $pl->action }} <span class="badge badge-orange">{{ $pl->module }}</span></div>
                        <div style="font-size:0.65rem; color:#57534E;">{{ $pl->details }}</div>
                        <div style="font-size:0.58rem; color:#A8A29E;">Por: {{ $pl->user_name }} ({{ $pl->user_role }})</div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <p style="text-align:center; color:#78716C; padding:1rem;">No se encontraron registros para este paciente.</p>
            @endif
        @else
        <p style="text-align:center; color:#78716C; padding:1rem;">Escribe el nombre de un paciente para ver su historial completo.</p>
        @endif
    </div>
</div>

<!-- TAB: AUDITORIA MEDICA -->
<div id="tab-medica" class="tab-content">
    <div style="display:grid; grid-template-columns:repeat(3,1fr); gap:0.5rem; margin-bottom:0.75rem;">
        <div class="aud-kpi" style="border-left:3px solid #E85D3A;"><i class="fas fa-prescription" style="color:#E85D3A;"></i><div class="label">Recetas (7d)</div><div class="value" style="color:#E85D3A;">{{ number_format($recetas) }}</div></div>
        <div class="aud-kpi" style="border-left:3px solid #DC2626;"><i class="fas fa-scissors" style="color:#DC2626;"></i><div class="label">Cirugias (7d)</div><div class="value" style="color:#DC2626;">{{ number_format($cirugias) }}</div></div>
        <div class="aud-kpi" style="border-left:3px solid #1E1A17;"><i class="fas fa-file-medical" style="color:#1E1A17;"></i><div class="label">Defunciones</div><div class="value" style="color:#1E1A17;">{{ number_format($defunciones) }}</div></div>
    </div>
    <div class="aud-card">
        <div class="section-title"><i class="fas fa-stethoscope"></i> Auditoria Medica</div>
        <table class="aud-table">
            <thead><tr><th>Fecha</th><th>Medico</th><th>Accion</th><th>Paciente</th><th>Detalles</th></tr></thead>
            @foreach($medicaLogs as $l)
            <tr>
                <td style="font-size:0.65rem; color:#78716C;">{{ date('d/m H:i', strtotime($l->created_at)) }}</td>
                <td style="font-weight:700;">{{ $l->user_name }}</td>
                <td><span class="badge {{ $l->action === 'Certificado Defuncion' ? 'badge-red' : 'badge-orange' }}">{{ $l->action }}</span></td>
                <td>{{ $l->patient_name ?? '-' }}</td>
                <td style="font-size:0.6rem; color:#57534E;">{{ Str::limit($l->details, 50) }}</td>
            </tr>
            @endforeach
        </table>
    </div>
</div>

<!-- TAB: AUDITORIA HOSPITALIZACION -->
<div id="tab-hospital" class="tab-content">
    <div style="display:grid; grid-template-columns:repeat(3,1fr); gap:0.5rem; margin-bottom:0.75rem;">
        <div class="aud-kpi" style="border-left:3px solid #E85D3A;"><i class="fas fa-bed-pulse" style="color:#E85D3A;"></i><div class="label">Ingresos (7d)</div><div class="value" style="color:#E85D3A;">{{ number_format($ingresos) }}</div></div>
        <div class="aud-kpi" style="border-left:3px solid #059669;"><i class="fas fa-door-open" style="color:#059669;"></i><div class="label">Altas (7d)</div><div class="value" style="color:#059669;">{{ number_format($altas) }}</div></div>
        <div class="aud-kpi" style="border-left:3px solid #F59E0B;"><i class="fas fa-arrow-right-arrow-left" style="color:#F59E0B;"></i><div class="label">Traslados (7d)</div><div class="value" style="color:#F59E0B;">{{ number_format($traslados) }}</div></div>
    </div>
    <div class="aud-card">
        <div class="section-title"><i class="fas fa-hospital"></i> Auditoria Hospitalizacion</div>
        <table class="aud-table">
            <thead><tr><th>Fecha</th><th>Usuario</th><th>Accion</th><th>Paciente</th><th>Detalles</th></tr></thead>
            @foreach($hospLogs as $l)
            <tr>
                <td style="font-size:0.65rem; color:#78716C;">{{ date('d/m H:i', strtotime($l->created_at)) }}</td>
                <td style="font-weight:700;">{{ $l->user_name }}</td>
                <td><span class="badge {{ $l->action === 'Fallecimiento' ? 'badge-red' : ($l->action === 'Alta Medica' ? 'badge-green' : 'badge-orange') }}">{{ $l->action }}</span></td>
                <td>{{ $l->patient_name ?? '-' }}</td>
                <td style="font-size:0.6rem; color:#57534E;">{{ Str::limit($l->details, 50) }}</td>
            </tr>
            @endforeach
        </table>
    </div>
</div>

<!-- TAB: AUDITORIA FINANZAS -->
<div id="tab-finanzas" class="tab-content">
    <div class="aud-card">
        <div class="section-title"><i class="fas fa-file-invoice-dollar"></i> Auditoria Financiera</div>
        <table class="aud-table">
            <thead><tr><th>Fecha</th><th>Usuario</th><th>Accion</th><th>Detalles</th><th>Riesgo</th></tr></thead>
            @foreach($finLogs as $l)
            <tr class="{{ $l->is_suspicious ? 'suspicious' : '' }}">
                <td style="font-size:0.65rem; color:#78716C;">{{ date('d/m H:i', strtotime($l->created_at)) }}</td>
                <td style="font-weight:700;">{{ $l->user_name }}</td>
                <td><span class="badge {{ in_array($l->action, ['Factura Cancelada','Cobro Duplicado','Poliza Falsa Detectada']) ? 'badge-red' : 'badge-orange' }}">{{ $l->action }}</span></td>
                <td style="font-size:0.6rem; color:#57534E;">{{ Str::limit($l->details, 60) }}</td>
                <td><span class="risk-dot risk-{{ $l->risk_level ?? 'bajo' }}"></span></td>
            </tr>
            @endforeach
        </table>
    </div>
</div>

<!-- TAB: AUDITORIA FARMACIA -->
<div id="tab-farmacia" class="tab-content">
    <div style="display:grid; grid-template-columns:1fr 1fr; gap:0.75rem;">
        <div class="aud-card">
            <div class="section-title"><i class="fas fa-pills"></i> Auditoria Farmaceutica</div>
            <table class="aud-table">
                <thead><tr><th>Fecha</th><th>Usuario</th><th>Accion</th><th>Detalles</th></tr></thead>
                @foreach($pharmaLogs as $l)
                <tr>
                    <td style="font-size:0.6rem; color:#78716C;">{{ date('d/m H:i', strtotime($l->created_at)) }}</td>
                    <td style="font-weight:600;">{{ $l->user_name }}</td>
                    <td><span class="badge badge-orange">{{ $l->action }}</span></td>
                    <td style="font-size:0.6rem;">{{ Str::limit($l->details, 45) }}</td>
                </tr>
                @endforeach
            </table>
        </div>
        <div class="aud-card">
            <div class="section-title"><i class="fas fa-prescription-bottle-medical"></i> Medicamentos Controlados</div>
            @if($controlados->count() > 0)
            <table class="aud-table">
                <thead><tr><th>Fecha</th><th>Usuario</th><th>Detalles</th></tr></thead>
                @foreach($controlados as $l)
                <tr class="suspicious">
                    <td style="font-size:0.6rem;">{{ date('d/m H:i', strtotime($l->created_at)) }}</td>
                    <td style="font-weight:700;">{{ $l->user_name }}</td>
                    <td style="font-size:0.6rem;">{{ $l->details }}</td>
                </tr>
                @endforeach
            </table>
            @else
            <p style="text-align:center; color:#78716C; font-size:0.75rem; padding:1rem;">Sin registros de controlados</p>
            @endif
        </div>
    </div>
</div>

<!-- TAB: USUARIOS -->
<div id="tab-usuarios" class="tab-content">
    <div class="aud-card">
        <div class="section-title"><i class="fas fa-user-shield"></i> Actividad por Usuario (30 dias)</div>
        <table class="aud-table">
            <thead><tr><th>Usuario</th><th>Rol</th><th>Acciones</th><th>Sospechoso</th><th>Critico</th><th>Alto</th><th>Dias Activo</th><th>Score</th></tr></thead>
            @foreach($riskUsers as $u)
            @php $score = ($u->critical * 10) + ($u->high * 5) + ($u->suspicious * 3); $label = $score >= 20 ? 'CRITICO' : ($score >= 10 ? 'ALTO' : ($score >= 5 ? 'MEDIO' : 'BAJO')); @endphp
            <tr class="{{ $label === 'CRITICO' ? 'suspicious' : '' }}">
                <td style="font-weight:700;">{{ $u->user_name }}</td>
                <td><span class="badge badge-gray">{{ $u->user_role }}</span></td>
                <td>{{ number_format($u->total_actions) }}</td>
                <td style="color:#DC2626;">{{ $u->suspicious }}</td>
                <td style="color:#991B1B; font-weight:800;">{{ $u->critical }}</td>
                <td style="color:#EA580C;">{{ $u->high }}</td>
                <td>{{ $u->active_days }}</td>
                <td><span class="risk-dot risk-{{ strtolower($label) }}"></span><span class="badge {{ $label === 'CRITICO' ? 'badge-red' : ($label === 'ALTO' ? 'badge-orange' : ($label === 'MEDIO' ? 'badge-yellow' : 'badge-green')) }}">{{ $label }} ({{ $score }})</span></td>
            </tr>
            @endforeach
        </table>
    </div>
</div>

<!-- TAB: HUELLA DIGITAL -->
<div id="tab-huella" class="tab-content">
    <div class="aud-card">
        <div class="section-title"><i class="fas fa-fingerprint"></i> Huella Digital de Usuarios</div>
        <p style="font-size:0.68rem; color:#78716C; margin-bottom:0.75rem;">Patrones de comportamiento por usuario en los ultimos 30 dias.</p>
        @foreach($topUsersAll->take(10) as $name => $items)
        @php
            $hourly = $items->groupBy(function($i){ return (int)date('G', strtotime($i->created_at)); });
            $peakHour = 0; $peakCount = 0;
            foreach($hourly as $h => $hItems) { if($hItems->count() > $peakCount) { $peakCount = $hItems->count(); $peakHour = $h; } }
            $topModule = $items->groupBy('module')->sortByDesc->count()->keys()->first() ?? 'N/A';
            $ips = $items->groupBy('ip_address')->count();
            $suspRate = $items->count() > 0 ? ($items->where('is_suspicious', true)->count() / $items->count() * 100) : 0;
        @endphp
        <div style="padding:0.6rem; border:1px solid #FED7AA; border-radius:8px; margin-bottom:0.5rem;">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:0.4rem;">
                <div style="font-weight:800; font-size:0.8rem;">{{ $name }} <span class="badge badge-gray">{{ $items->first()->user_role ?? 'N/A' }}</span></div>
                <div style="font-size:0.65rem; color:#78716C;">{{ $items->count() }} acciones | {{ $ips }} IPs</div>
            </div>
            <div style="display:grid; grid-template-columns:repeat(24,1fr); gap:1px; height:20px; margin-bottom:0.3rem;">
                @for($h=0;$h<24;$h++)
                @php $hCount = isset($hourly[$h]) ? $hourly[$h]->count() : 0; @endphp
                <div style="background:{{ $hCount > 0 ? 'rgba(232,93,58,'.max($hCount/max($peakCount,1),0.15).')' : '#FFF7ED' }}; border-radius:1px;" title="{{ $h }}:00 - {{ $hCount }} acciones"></div>
                @endfor
            </div>
            <div style="display:flex; gap:0.5rem; font-size:0.6rem; color:#78716C;">
                <span><i class="fas fa-clock" style="color:#E85D3A;"></i> Hora pico: {{ $peakHour }}:00</span>
                <span><i class="fas fa-cube" style="color:#E85D3A;"></i> Modulo: {{ $topModule }}</span>
                <span style="color:{{ $suspRate > 10 ? '#DC2626' : '#059669' }};"><i class="fas fa-shield-halved"></i> Sospecha: {{ number_format($suspRate,1) }}%</span>
            </div>
        </div>
        @endforeach
    </div>
</div>

<!-- TAB: IA SEGURIDAD -->
<div id="tab-ia" class="tab-content">
    <div class="aud-card">
        <div class="section-title"><i class="fas fa-robot"></i> IA - Deteccion Automatica de Anomalias</div>
        <p style="font-size:0.68rem; color:#78716C; margin-bottom:0.75rem;">Analisis en tiempo real de patrones anomalos.</p>
        @if($anomalias->count() > 0)
            @foreach($anomalias as $a)
            <div style="padding:0.6rem; border-radius:8px; margin-bottom:0.4rem; border-left:3px solid {{ $a->severity === 'critico' ? '#DC2626' : ($a->severity === 'alto' ? '#EA580C' : '#F59E0B') }}; background:{{ $a->severity === 'critico' ? '#FEF2F2' : ($a->severity === 'alto' ? '#FFF7ED' : '#FEF3C7') }};">
                <div style="display:flex; justify-content:space-between; align-items:center;">
                    <div>
                        <i class="fas {{ $a->icon }}" style="color:{{ $a->severity === 'critico' ? '#DC2626' : ($a->severity === 'alto' ? '#EA580C' : '#F59E0B') }};"></i>
                        <span style="font-weight:800; font-size:0.8rem;">{{ $a->tipo }}</span>
                        <span class="badge {{ $a->severity === 'critico' ? 'badge-red' : ($a->severity === 'alto' ? 'badge-orange' : 'badge-yellow') }}">{{ strtoupper($a->severity) }}</span>
                    </div>
                    <span class="badge badge-orange">{{ $a->module }}</span>
                </div>
                <div style="font-size:0.7rem; margin-top:0.3rem; color:#57534E;">{{ $a->desc }}</div>
            </div>
            @endforeach
        @else
        <p style="text-align:center; color:#059669; font-weight:700; padding:1rem;"><i class="fas fa-circle-check"></i> Sin anomalias detectadas</p>
        @endif
    </div>
</div>

<!-- TAB: SCORE RIESGO -->
<div id="tab-riesgo" class="tab-content">
    <div style="display:grid; grid-template-columns:1fr 1fr; gap:0.75rem;">
        <div class="aud-card">
            <div class="section-title"><i class="fas fa-gauge-high"></i> Distribucion de Riesgo</div>
            @foreach(['bajo','medio','alto','critico'] as $rl)
            @php $cnt = $riskDist[$rl] ?? 0; $pct = $stats['total'] > 0 ? ($cnt / $stats['total'] * 100) : 0; @endphp
            <div style="margin-bottom:0.4rem;">
                <div style="display:flex; justify-content:space-between; font-size:0.7rem; font-weight:700;">
                    <span style="color:{{ $rl === 'critico' ? '#991B1B' : ($rl === 'alto' ? '#EA580C' : ($rl === 'medio' ? '#92400E' : '#065F46')) }};"><span class="risk-dot risk-{{ $rl }}"></span> {{ ucfirst($rl) }}</span>
                    <span>{{ number_format($cnt) }} ({{ number_format($pct,1) }}%)</span>
                </div>
                <div style="background:#FFF7ED; border-radius:4px; height:8px; overflow:hidden;">
                    <div style="width:{{ $pct }}%; background:{{ $rl === 'critico' ? '#DC2626' : ($rl === 'alto' ? '#EA580C' : ($rl === 'medio' ? '#F59E0B' : '#059669')) }}; height:100%; border-radius:4px;"></div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="aud-card">
            <div class="section-title"><i class="fas fa-crosshairs"></i> Areas de Mayor Riesgo</div>
            <table class="aud-table">
                <thead><tr><th>Modulo</th><th>Total</th><th>Sosp.</th><th>Crit.</th><th>Nivel</th></tr></thead>
                @foreach($riskAreas as $a)
                @php $areaRisk = $a->critical > 5 ? 'CRITICO' : ($a->suspicious > 10 ? 'ALTO' : ($a->suspicious > 3 ? 'MEDIO' : 'BAJO')); @endphp
                <tr>
                    <td><span class="badge badge-orange">{{ $a->module }}</span></td>
                    <td>{{ number_format($a->total) }}</td>
                    <td style="color:#DC2626;">{{ $a->suspicious }}</td>
                    <td style="color:#991B1B;">{{ $a->critical }}</td>
                    <td><span class="badge {{ $areaRisk === 'CRITICO' ? 'badge-red' : ($areaRisk === 'ALTO' ? 'badge-orange' : ($areaRisk === 'MEDIO' ? 'badge-yellow' : 'badge-green')) }}">{{ $areaRisk }}</span></td>
                </tr>
                @endforeach
            </table>
        </div>
    </div>
</div>

<!-- TAB: NEGLIGENCIA -->
<div id="tab-negligencia" class="tab-content">
    <div class="aud-card">
        <div class="section-title"><i class="fas fa-user-injured"></i> Detector de Negligencia</div>
        <p style="font-size:0.68rem; color:#78716C; margin-bottom:0.5rem;">Pacientes con mas de 2 horas sin acciones registradas.</p>
        @if($negligencia->count() > 0)
            @foreach($negligencia as $n)
            <div style="padding:0.5rem; border-radius:8px; margin-bottom:0.4rem; border-left:3px solid #DC2626; background:#FEF2F2;">
                <div style="display:flex; justify-content:space-between; align-items:center;">
                    <div>
                        <span style="font-weight:800; color:#991B1B;">{{ $n->patient_name }}</span>
                        <span class="badge {{ $n->triage_level === 'Rojo' ? 'badge-red' : 'badge-orange' }}">{{ $n->triage_level }}</span>
                        <span class="badge badge-gray">{{ $n->status }}</span>
                    </div>
                    <div style="text-align:right;">
                        <div style="font-size:0.65rem; color:#78716C;">Ingreso: {{ date('d/m/Y H:i', strtotime($n->created_at)) }}</div>
                        <div style="font-size:0.65rem; color:#78716C;">Area: {{ $n->assigned_area ?? 'Sin asignar' }}</div>
                    </div>
                </div>
            </div>
            @endforeach
        @else
        <p style="text-align:center; color:#059669; font-weight:700; padding:1rem;"><i class="fas fa-circle-check"></i> Sin pacientes en negligencia</p>
        @endif
    </div>
</div>

<!-- TAB: EXPORTAR -->
<div id="tab-exportar" class="tab-content">
    <div class="aud-card">
        <div class="section-title"><i class="fas fa-file-export"></i> Exportacion Forense</div>
        <p style="font-size:0.68rem; color:#78716C; margin-bottom:0.75rem;">Exportar para investigaciones, auditorias legales o reportes gubernamentales.</p>
        <div style="display:grid; grid-template-columns:repeat(3,1fr); gap:0.75rem;">
            <form method="GET" action="{{ route('superadmin.auditoria.export.pdf') }}" target="_blank" style="text-align:center; padding:1rem; background:#FFF7ED; border-radius:10px; border:1px solid #FED7AA;">
                <i class="fas fa-file-pdf" style="font-size:2rem; color:#DC2626;"></i>
                <div style="font-weight:800; font-size:0.85rem; margin:0.5rem 0;">PDF</div>
                <div style="font-size:0.58rem; color:#78716C; margin-bottom:0.5rem;">Reporte formal</div>
                <button type="submit" class="btn-sm btn-red"><i class="fas fa-download"></i> Descargar</button>
            </form>
            <form method="GET" action="{{ route('superadmin.auditoria.export.csv') }}" style="text-align:center; padding:1rem; background:#FFF7ED; border-radius:10px; border:1px solid #FED7AA;">
                <i class="fas fa-file-csv" style="font-size:2rem; color:#059669;"></i>
                <div style="font-weight:800; font-size:0.85rem; margin:0.5rem 0;">CSV / Excel</div>
                <div style="font-size:0.58rem; color:#78716C; margin-bottom:0.5rem;">Datos tabulares</div>
                <button type="submit" class="btn-sm btn-green"><i class="fas fa-download"></i> Descargar</button>
            </form>
            <form method="GET" action="{{ route('superadmin.auditoria.export.json') }}" target="_blank" style="text-align:center; padding:1rem; background:#FFF7ED; border-radius:10px; border:1px solid #FED7AA;">
                <i class="fas fa-file-code" style="font-size:2rem; color:#E85D3A;"></i>
                <div style="font-weight:800; font-size:0.85rem; margin:0.5rem 0;">JSON</div>
                <div style="font-size:0.58rem; color:#78716C; margin-bottom:0.5rem;">Datos estructurados</div>
                <button type="submit" class="btn-sm btn-orange"><i class="fas fa-download"></i> Descargar</button>
            </form>
        </div>
    </div>
</div>

<script>
function switchTab(name) {
    document.querySelectorAll('.tab-content').forEach(t => t.classList.remove('active'));
    document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
    document.getElementById('tab-' + name).classList.add('active');
    event.target.classList.add('active');
}
</script>
@endsection
