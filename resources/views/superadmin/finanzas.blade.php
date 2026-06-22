@extends('superadmin.layout')

@section('title', 'Centro Financiero Hospitalario')

@section('content')
<style>
    .fin-card { background: white; border-radius: 12px; padding: 1.25rem; box-shadow: 0 1px 4px rgba(0,0,0,0.06); border: 1px solid #FED7AA; margin-bottom: 1rem; }
    .fin-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap: 0.75rem; margin-bottom: 1rem; }
    .fin-kpi { text-align: center; padding: 0.75rem; border-radius: 10px; border: 1px solid #FED7AA; background: white; }
    .fin-kpi .label { font-size: 0.6rem; color: #78716C; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 700; }
    .fin-kpi .value { font-size: 1.2rem; font-weight: 800; margin-top: 0.15rem; }
    .fin-kpi i { font-size: 0.9rem; margin-bottom: 0.15rem; display: block; }
    .fin-table { width: 100%; border-collapse: collapse; font-size: 0.72rem; }
    .fin-table th { background: #E85D3A; color: white; padding: 0.35rem 0.5rem; text-align: left; font-size: 0.6rem; text-transform: uppercase; }
    .fin-table td { padding: 0.3rem 0.5rem; border-bottom: 1px solid #FFF7ED; }
    .fin-table tr:hover { background: #FFF7ED; }
    .badge { padding: 0.1rem 0.45rem; border-radius: 20px; font-size: 0.58rem; font-weight: 700; }
    .badge-green { background: #D1FAE5; color: #065F46; }
    .badge-yellow { background: #FFEDD5; color: #C2410C; }
    .badge-red { background: #FEE2E2; color: #991B1B; }
    .badge-blue { background: #FFF7ED; color: #EA580C; }
    .badge-gray { background: #F5F5F4; color: #78716C; }
    .risk-box { padding: 0.4rem 0.8rem; border-radius: 8px; font-weight: 800; font-size: 0.75rem; display: inline-flex; align-items: center; gap: 0.3rem; }
    .risk-estable { background: #D1FAE5; color: #065F46; }
    .risk-moderado { background: #FEF3C7; color: #92400E; }
    .risk-alto { background: #FEE2E2; color: #991B1B; }
    .fin-section { font-size: 0.8rem; font-weight: 800; color: #1E1A17; margin-bottom: 0.5rem; display: flex; align-items: center; gap: 0.35rem; }
    .fin-section i { color: #E85D3A; }
    .btn-sm { padding: 0.2rem 0.5rem; border-radius: 6px; font-size: 0.6rem; font-weight: 700; border: none; cursor: pointer; text-decoration: none; display: inline-flex; align-items: center; gap: 0.2rem; }
    .btn-orange { background: #E85D3A; color: white; }
    .btn-orange:hover { background: #C2410C; }
    .btn-red { background: #DC2626; color: white; }
    .btn-red:hover { background: #991B1B; }
    .btn-green { background: #059669; color: white; }
    .btn-green:hover { background: #047857; }
    .btn-ghost { background: transparent; color: #78716C; border: 1px solid #FED7AA; }
    .btn-ghost:hover { background: #FFF7ED; }
    .modal-overlay { position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 9999; display: flex; align-items: center; justify-content: center; }
    .modal-box { background: white; border-radius: 16px; padding: 1.5rem; max-width: 500px; width: 90%; box-shadow: 0 20px 60px rgba(0,0,0,0.2); }
    .modal-box h3 { font-size: 1rem; font-weight: 800; color: #1E1A17; margin-bottom: 1rem; }
    .form-group { margin-bottom: 0.75rem; }
    .form-group label { display: block; font-size: 0.7rem; font-weight: 700; color: #57534E; margin-bottom: 0.2rem; }
    .form-group input, .form-group select, .form-group textarea { width: 100%; padding: 0.4rem 0.6rem; border: 1px solid #FED7AA; border-radius: 8px; font-size: 0.8rem; }
    .form-group input:focus, .form-group select:focus { outline: none; border-color: #E85D3A; box-shadow: 0 0 0 3px rgba(232,93,58,0.1); }
    .tab-bar { display: flex; gap: 0.25rem; margin-bottom: 1rem; border-bottom: 2px solid #FED7AA; padding-bottom: 0; }
    .tab { padding: 0.4rem 0.8rem; font-size: 0.7rem; font-weight: 700; color: #78716C; cursor: pointer; border: none; background: none; border-bottom: 2px solid transparent; margin-bottom: -2px; }
    .tab.active { color: #E85D3A; border-bottom-color: #E85D3A; }
    .tab:hover { color: #C2410C; }
    .tab-content { display: none; }
    .tab-content.active { display: block; }
</style>

<!-- KPIs PRINCIPALES -->
<div class="fin-grid">
    <div class="fin-kpi" style="border-left: 3px solid #059669;">
        <i class="fas fa-arrow-trend-up" style="color:#059669;"></i>
        <div class="label">Cobrado</div>
        <div class="value" style="color:#059669;">${{ number_format($paid, 0) }}</div>
    </div>
    <div class="fin-kpi" style="border-left: 3px solid #EA580C;">
        <i class="fas fa-clock" style="color:#EA580C;"></i>
        <div class="label">Pendiente</div>
        <div class="value" style="color:#EA580C;">${{ number_format($pending, 0) }}</div>
    </div>
    <div class="fin-kpi" style="border-left: 3px solid #DC2626;">
        <i class="fas fa-shield-halved" style="color:#DC2626;"></i>
        <div class="label">Seguros</div>
        <div class="value" style="color:#DC2626;">${{ number_format($insurance, 0) }}</div>
    </div>
    <div class="fin-kpi" style="border-left: 3px solid #F59E0B;">
        <i class="fas fa-triangle-exclamation" style="color:#F59E0B;"></i>
        <div class="label">Vencido</div>
        <div class="value" style="color:#F59E0B;">${{ number_format($vencido, 0) }}</div>
    </div>
    <div class="fin-kpi" style="border-left: 3px solid #E85D3A;">
        <i class="fas fa-hospital" style="color:#E85D3A;"></i>
        <div class="label">Total</div>
        <div class="value" style="color:#E85D3A;">${{ number_format($total, 0) }}</div>
    </div>
    <div class="fin-kpi" style="border-left: 3px solid #FB923C;">
        <i class="fas fa-pills" style="color:#FB923C;"></i>
        <div class="label">Farmacia</div>
        <div class="value" style="color:#FB923C;">${{ number_format($pharma_value, 0) }}</div>
    </div>
</div>

<!-- RIESGO + ACCIONES -->
<div style="display:flex; align-items:center; gap:0.75rem; flex-wrap:wrap; margin-bottom:1rem;">
    <span style="font-size:0.75rem; font-weight:700;"><i class="fas fa-gauge-high" style="color:#E85D3A;"></i> Riesgo:</span>
    <span class="risk-box {{ $riskScore === 'ESTABLE' ? 'risk-estable' : ($riskScore === 'MODERADO' ? 'risk-moderado' : 'risk-alto') }}">
        <i class="fas {{ $riskScore === 'ESTABLE' ? 'fa-circle-check' : 'fa-circle-exclamation' }}"></i> {{ $riskScore }}
    </span>
    <div style="flex:1;"></div>
    <button onclick="openModal('nuevaFactura')" class="btn-sm btn-orange"><i class="fas fa-plus"></i> Nueva Factura</button>
    <button onclick="openModal('nuevoSeguro')" class="btn-sm btn-green"><i class="fas fa-plus"></i> Nuevo Seguro</button>
    <a href="{{ route('superadmin.finanzas.export.pdf') }}" class="btn-sm btn-ghost" target="_blank"><i class="fas fa-file-pdf"></i> PDF</a>
    <a href="{{ route('superadmin.finanzas.export.csv') }}" class="btn-sm btn-ghost"><i class="fas fa-file-csv"></i> CSV</a>
    <a href="{{ route('superadmin.finanzas.lock') }}" class="btn-sm btn-red"><i class="fas fa-lock"></i> Bloquear</a>
</div>

<!-- TABS -->
<div class="tab-bar">
    <button class="tab active" onclick="switchTab('resumen')">Resumen</button>
    <button class="tab" onclick="switchTab('facturas')">Facturas</button>
    <button class="tab" onclick="switchTab('seguros')">Seguros</button>
    <button class="tab" onclick="switchTab('fraude')">Fraude</button>
    <button class="tab" onclick="switchTab('costos')">Costos</button>
    <button class="tab" onclick="switchTab('aprobaciones')">Aprobaciones</button>
</div>

<!-- TAB: RESUMEN -->
<div id="tab-resumen" class="tab-content active">
    <div style="display:grid; grid-template-columns: 1fr 1fr; gap:1rem;">
        <!-- INGRESOS POR AREA -->
        <div class="fin-card">
            <div class="fin-section"><i class="fas fa-chart-pie"></i> Ingresos por Area</div>
            <table class="fin-table">
                <thead><tr><th>Area</th><th>Monto</th></tr></thead>
                <tr><td><i class="fas fa-kit-medical" style="color:#DC2626;"></i> Urgencias</td><td style="font-weight:700;">${{ number_format($ingresosUrgencias, 0) }}</td></tr>
                <tr><td><i class="fas fa-scissors" style="color:#E85D3A;"></i> Cirugias</td><td style="font-weight:700;">${{ number_format($ingresosCirugia, 0) }}</td></tr>
                <tr><td><i class="fas fa-bed-pulse" style="color:#F59E0B;"></i> Hospitalizacion</td><td style="font-weight:700;">${{ number_format($ingresosHospitalizacion, 0) }}</td></tr>
                <tr><td><i class="fas fa-pills" style="color:#FB923C;"></i> Farmacia</td><td style="font-weight:700;">${{ number_format($ingresosFarmacia, 0) }}</td></tr>
                <tr><td><i class="fas fa-microscope" style="color:#EA580C;"></i> Estudios</td><td style="font-weight:700;">${{ number_format($ingresosEstudios, 0) }}</td></tr>
                <tr><td><i class="fas fa-heart-pulse" style="color:#991B1B;"></i> UCI</td><td style="font-weight:700;">${{ number_format($ingresosUCI, 0) }}</td></tr>
            </table>
        </div>

        <!-- INGRESOS DIARIOS -->
        <div class="fin-card">
            <div class="fin-section"><i class="fas fa-calendar-day"></i> Ingresos Ultimos 7 Dias</div>
            <table class="fin-table">
                <thead><tr><th>Fecha</th><th>Facturas</th><th>Monto</th></tr></thead>
                @foreach($ingresosDiarios as $d)
                <tr><td>{{ $d->fecha }}</td><td>{{ $d->qty }}</td><td style="font-weight:700;">${{ number_format($d->total, 0) }}</td></tr>
                @endforeach
            </table>
        </div>

        <!-- SEGUROS POR PROVEEDOR -->
        <div class="fin-card">
            <div class="fin-section"><i class="fas fa-building-columns"></i> Seguros por Proveedor</div>
            <table class="fin-table">
                <thead><tr><th>Proveedor</th><th>Total</th><th>Vigentes</th></tr></thead>
                @foreach($segurosPorProveedor as $s)
                <tr><td>{{ $s->provider }}</td><td>{{ $s->total }}</td><td style="color:#059669; font-weight:700;">{{ $s->vigentes }}</td></tr>
                @endforeach
            </table>
        </div>

        <!-- ALERTAS SEGUROS -->
        <div class="fin-card">
            <div class="fin-section"><i class="fas fa-triangle-exclamation"></i> Alertas de Seguros</div>
            <div style="display:flex; gap:0.5rem; flex-wrap:wrap;">
                <div style="text-align:center; padding:0.6rem; background:#FEE2E2; border-radius:8px; min-width:90px;">
                    <div style="font-size:1.3rem; font-weight:800; color:#DC2626;">{{ $polizasFalsas }}</div>
                    <div style="font-size:0.58rem; color:#991B1B; font-weight:700;">Polizas Falsas</div>
                </div>
                <div style="text-align:center; padding:0.6rem; background:#FFEDD5; border-radius:8px; min-width:90px;">
                    <div style="font-size:1.3rem; font-weight:800; color:#C2410C;">{{ $sinCobertura }}</div>
                    <div style="font-size:0.58rem; color:#9A3412; font-weight:700;">Sin Cobertura</div>
                </div>
                <div style="text-align:center; padding:0.6rem; background:#FEF3C7; border-radius:8px; min-width:90px;">
                    <div style="font-size:1.3rem; font-weight:800; color:#92400E;">{{ $segurosVencidos }}</div>
                    <div style="font-size:0.58rem; color:#78350F; font-weight:700;">Vencidos</div>
                </div>
            </div>
        </div>

        <!-- TOP CONCEPTOS -->
        <div class="fin-card" style="grid-column: span 2;">
            <div class="fin-section"><i class="fas fa-ranking-star"></i> Top Conceptos por Costo</div>
            <table class="fin-table">
                <thead><tr><th>Concepto</th><th>Cantidad</th><th>Monto Total</th><th>% del Total</th></tr></thead>
                @foreach($topInvoices as $t)
                <tr>
                    <td style="font-weight:600;">{{ $t->concept }}</td>
                    <td>{{ $t->qty }}</td>
                    <td style="font-weight:700;">${{ number_format($t->total, 0) }}</td>
                    <td>
                        <div style="background:#FFF7ED; border-radius:4px; height:6px; overflow:hidden;">
                            <div style="width:{{ min(($t->total / max($total, 1)) * 100, 100) }}%; background:linear-gradient(to right, #E85D3A, #FB923C); height:100%;"></div>
                        </div>
                        <span style="font-size:0.55rem; color:#78716C;">{{ number_format(($t->total / max($total, 1)) * 100, 1) }}%</span>
                    </td>
                </tr>
                @endforeach
            </table>
        </div>
    </div>
</div>

<!-- TAB: FACTURAS -->
<div id="tab-facturas" class="tab-content">
    <div class="fin-card">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:0.75rem;">
            <div class="fin-section" style="margin-bottom:0;"><i class="fas fa-file-invoice-dollar"></i> Todas las Facturas</div>
            <button onclick="openModal('nuevaFactura')" class="btn-sm btn-orange"><i class="fas fa-plus"></i> Nueva</button>
        </div>
        <table class="fin-table">
            <thead><tr><th>Paciente</th><th>Concepto</th><th>Monto</th><th>Estado</th><th>Fecha</th><th>Acciones</th></tr></thead>
            @foreach($invoices as $inv)
            <tr>
                <td>{{ $inv->patient_name }}</td>
                <td>{{ $inv->concept }}</td>
                <td style="font-weight:700;">${{ number_format($inv->amount, 0) }}</td>
                <td>
                    @if($inv->status === 'Pagado') <span class="badge badge-green">Pagado</span>
                    @elseif($inv->status === 'Pendiente') <span class="badge badge-yellow">Pendiente</span>
                    @elseif($inv->status === 'Seguro') <span class="badge badge-blue">Seguro</span>
                    @else <span class="badge badge-red">Vencido</span>
                    @endif
                </td>
                <td style="color:#A8A29E;">{{ date('d/m/Y', strtotime($inv->created_at)) }}</td>
                <td>
                    <div style="display:flex; gap:0.2rem;">
                        @if($inv->status === 'Pendiente')
                        <form method="POST" action="{{ route('superadmin.finanzas.updateInvoice', $inv->id) }}" style="display:inline;">
                            @csrf @method('PUT')
                            <input type="hidden" name="status" value="Pagado">
                            <button type="submit" class="btn-sm btn-green" title="Marcar Pagado"><i class="fas fa-check"></i></button>
                        </form>
                        @endif
                        @if($inv->status !== 'Seguro')
                        <form method="POST" action="{{ route('superadmin.finanzas.updateInvoice', $inv->id) }}" style="display:inline;">
                            @csrf @method('PUT')
                            <input type="hidden" name="status" value="Seguro">
                            <button type="submit" class="btn-sm btn-ghost" title="Marcar Seguro"><i class="fas fa-shield"></i></button>
                        </form>
                        @endif
                        <form method="POST" action="{{ route('superadmin.finanzas.deleteInvoice', $inv->id) }}" style="display:inline;" onsubmit="return confirm('Eliminar factura #{{ $inv->id }}?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-sm btn-red" title="Eliminar"><i class="fas fa-trash"></i></button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </table>
        <div style="display:flex; justify-content:center; padding:0.75rem 0;">
            {{ $invoices->withQueryString()->links() }}
        </div>
    </div>
</div>

<!-- TAB: SEGUROS -->
<div id="tab-seguros" class="tab-content">
    <div class="fin-card">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:0.75rem;">
            <div class="fin-section" style="margin-bottom:0;"><i class="fas fa-shield-halved"></i> Seguros / Polizas</div>
            <button onclick="openModal('nuevoSeguro')" class="btn-sm btn-green"><i class="fas fa-plus"></i> Nuevo</button>
        </div>
        <table class="fin-table">
            <thead><tr><th>Paciente</th><th>Poliza</th><th>Proveedor</th><th>Estado</th><th>Acciones</th></tr></thead>
            @php $allInsurances = DB::table('insurances')->orderBy('created_at','desc')->paginate(20); @endphp
            @foreach($allInsurances as $ins)
            <tr>
                <td>{{ $ins->patient_name }}</td>
                <td style="font-weight:600;">{{ $ins->policy_number }}</td>
                <td>{{ $ins->provider }}</td>
                <td>
                    @if($ins->status === 'Vigente') <span class="badge badge-green">Vigente</span>
                    @elseif($ins->status === 'Vencida') <span class="badge badge-yellow">Vencida</span>
                    @elseif($ins->status === 'Falsa/Fraude') <span class="badge badge-red">FRAUDE</span>
                    @else <span class="badge badge-gray">Sin Cobertura</span>
                    @endif
                </td>
                <td>
                    <div style="display:flex; gap:0.2rem;">
                        @if($ins->status !== 'Vigente')
                        <form method="POST" action="{{ route('superadmin.finanzas.updateInsurance', $ins->id) }}" style="display:inline;">
                            @csrf @method('PUT')
                            <input type="hidden" name="status" value="Vigente">
                            <button type="submit" class="btn-sm btn-green" title="Activar"><i class="fas fa-check"></i></button>
                        </form>
                        @endif
                        @if($ins->status !== 'Falsa/Fraude')
                        <form method="POST" action="{{ route('superadmin.finanzas.updateInsurance', $ins->id) }}" style="display:inline;">
                            @csrf @method('PUT')
                            <input type="hidden" name="status" value="Falsa/Fraude">
                            <button type="submit" class="btn-sm btn-red" title="Marcar Fraude"><i class="fas fa-ban"></i></button>
                        </form>
                        @endif
                        <form method="POST" action="{{ route('superadmin.finanzas.deleteInsurance', $ins->id) }}" style="display:inline;" onsubmit="return confirm('Eliminar seguro?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-sm btn-red" title="Eliminar"><i class="fas fa-trash"></i></button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </table>
        <div style="display:flex; justify-content:center; padding:0.75rem 0;">
            {{ $allInsurances->withQueryString()->links() }}
        </div>
    </div>
</div>

<!-- TAB: FRAUDE -->
<div id="tab-fraude" class="tab-content">
    <div style="display:grid; grid-template-columns: 1fr 1fr; gap:1rem;">
        <!-- COBROS DUPLICADOS -->
        <div class="fin-card">
            <div class="fin-section"><i class="fas fa-copy"></i> Cobros Duplicados</div>
            @if($cobrosDuplicados->count() > 0)
            <table class="fin-table">
                <thead><tr><th>Paciente</th><th>Concepto</th><th>Monto</th><th>Veces</th></tr></thead>
                @foreach($cobrosDuplicados as $d)
                <tr><td>{{ $d->patient_name }}</td><td>{{ $d->concept }}</td><td style="font-weight:700;">${{ number_format($d->amount, 0) }}</td><td style="color:#DC2626; font-weight:800;">{{ $d->qty }}x</td></tr>
                @endforeach
            </table>
            @else
            <p style="text-align:center; color:#059669; font-weight:700; font-size:0.8rem;"><i class="fas fa-circle-check"></i> Sin duplicados detectados</p>
            @endif
        </div>

        <!-- GASTOS SOSPECHOSOS -->
        <div class="fin-card">
            <div class="fin-section"><i class="fas fa-magnifying-glass-chart"></i> Gastos Sospechosos (> $50,000)</div>
            @if($gastosSospechosos->count() > 0)
            <table class="fin-table">
                <thead><tr><th>Paciente</th><th>Concepto</th><th>Monto</th></tr></thead>
                @foreach($gastosSospechosos as $g)
                <tr><td>{{ $g->patient_name }}</td><td>{{ $g->concept }}</td><td style="color:#DC2626; font-weight:800;">${{ number_format($g->amount, 0) }}</td></tr>
                @endforeach
            </table>
            @else
            <p style="text-align:center; color:#059669; font-weight:700; font-size:0.8rem;"><i class="fas fa-circle-check"></i> Sin gastos sospechosos</p>
            @endif
        </div>

        <!-- PACIENTES CON DEUDA -->
        <div class="fin-card" style="grid-column: span 2;">
            <div class="fin-section"><i class="fas fa-money-bill-transfer"></i> Pacientes con Deuda</div>
            <table class="fin-table">
                <thead><tr><th>Paciente</th><th>Deuda Total</th><th>Facturas</th><th>Accion</th></tr></thead>
                @foreach($pacientesDeuda as $p)
                <tr>
                    <td>{{ $p->patient_name }}</td>
                    <td style="color:#DC2626; font-weight:700;">${{ number_format($p->deuda, 0) }}</td>
                    <td>{{ $p->facturas }}</td>
                    <td><button class="btn-sm btn-ghost" onclick="alert('Contactar a {{ $p->patient_name }}')"><i class="fas fa-phone"></i> Contactar</button></td>
                </tr>
                @endforeach
            </table>
        </div>
    </div>
</div>

<!-- TAB: COSTOS -->
<div id="tab-costos" class="tab-content">
    <div style="display:grid; grid-template-columns: 1fr 1fr; gap:1rem;">
        <!-- COSTO POR PACIENTE -->
        <div class="fin-card">
            <div class="fin-section"><i class="fas fa-bed-pulse"></i> Costo por Paciente Hospitalizado</div>
            <table class="fin-table">
                <thead><tr><th>Paciente</th><th>Ingreso</th><th>Dias</th><th>Costo Est.</th></tr></thead>
                @foreach($costoPorPaciente as $p)
                <tr>
                    <td>{{ $p->patient_name }}</td>
                    <td>{{ $p->admission_date }}</td>
                    <td style="font-weight:700;">{{ $p->dias }}</td>
                    <td style="font-weight:700;">${{ number_format($p->dias * 3500, 0) }}</td>
                </tr>
                @endforeach
            </table>
        </div>

        <!-- FARMACIA COSTOSA -->
        <div class="fin-card">
            <div class="fin-section"><i class="fas fa-pills"></i> Farmacia - Medicamentos Costosos</div>
            <table class="fin-table">
                <thead><tr><th>Medicamento</th><th>Precio</th><th>Stock</th><th>Valor Total</th></tr></thead>
                @foreach($farmaciaCostosa as $m)
                <tr>
                    <td>{{ $m->name }}</td>
                    <td style="font-weight:700;">${{ number_format($m->price, 2) }}</td>
                    <td>{{ $m->stock }}</td>
                    <td style="font-weight:700;">${{ number_format($m->price * $m->stock, 0) }}</td>
                </tr>
                @endforeach
            </table>
        </div>
    </div>
</div>

<!-- TAB: APROBACIONES -->
<div id="tab-aprobaciones" class="tab-content">
    <div style="display:grid; grid-template-columns: 1fr 1fr; gap:1rem;">
        <div class="fin-card">
            <div class="fin-section"><i class="fas fa-stamp"></i> Aprobaciones Pendientes</div>
            <div style="display:flex; gap:0.75rem; margin-bottom:1rem;">
                <div style="text-align:center; padding:0.75rem; background:#FEE2E2; border-radius:8px; flex:1;">
                    <div style="font-size:1.8rem; font-weight:800; color:#DC2626;">{{ $cirugiasCostosas }}</div>
                    <div style="font-size:0.6rem; color:#991B1B; font-weight:700;">Cirugias Costosas</div>
                    <div style="font-size:0.55rem; color:#78716C;">Pendientes > $20,000</div>
                </div>
                <div style="text-align:center; padding:0.75rem; background:#FFEDD5; border-radius:8px; flex:1;">
                    <div style="font-size:1.8rem; font-weight:800; color:#EA580C;">{{ $medsCaros }}</div>
                    <div style="font-size:0.6rem; color:#9A3412; font-weight:700;">Medicamentos Caros</div>
                    <div style="font-size:0.55rem; color:#78716C;">Pendientes > $500</div>
                </div>
            </div>
        </div>
        <div class="fin-card">
            <div class="fin-section"><i class="fas fa-list-check"></i> Acciones Requeridas</div>
            <div style="font-size:0.75rem; color:#57534E;">
                @if($cirugiasCostosas > 0)
                <div style="padding:0.5rem; background:#FEF2F2; border-radius:6px; margin-bottom:0.4rem;">
                    <i class="fas fa-scissors" style="color:#DC2626;"></i> <strong>{{ $cirugiasCostosas }} cirugias</strong> requieren autorizacion por monto superior a $20,000
                </div>
                @endif
                @if($medsCaros > 0)
                <div style="padding:0.5rem; background:#FFF7ED; border-radius:6px; margin-bottom:0.4rem;">
                    <i class="fas fa-pills" style="color:#EA580C;"></i> <strong>{{ $medsCaros }} recetas</strong> con medicamentos de alto costo pendientes
                </div>
                @endif
                @if($polizasFalsas > 0)
                <div style="padding:0.5rem; background:#FEE2E2; border-radius:6px; margin-bottom:0.4rem;">
                    <i class="fas fa-shield-halved" style="color:#DC2626;"></i> <strong>{{ $polizasFalsas }} polizas</strong> marcadas como fraude requieren investigacion
                </div>
                @endif
                @if($vencido > 0)
                <div style="padding:0.5rem; background:#FEF3C7; border-radius:6px; margin-bottom:0.4rem;">
                    <i class="fas fa-clock" style="color:#92400E;"></i> <strong>${{ number_format($vencido, 0) }}</strong> en facturas vencidas requieren seguimiento
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- MODAL: NUEVA FACTURA -->
<div id="modal-nuevaFactura" class="modal-overlay" style="display:none;" onclick="if(event.target===this)closeModal('nuevaFactura')">
    <div class="modal-box">
        <h3><i class="fas fa-file-invoice-dollar" style="color:#E85D3A;"></i> Nueva Factura</h3>
        <form method="POST" action="{{ route('superadmin.finanzas.storeInvoice') }}">
            @csrf
            <div class="form-group">
                <label>Paciente</label>
                <input type="text" name="patient_name" required placeholder="Nombre del paciente">
            </div>
            <div class="form-group">
                <label>Concepto</label>
                <select name="concept" required>
                    <option value="Consulta Urgencias">Consulta Urgencias</option>
                    <option value="Cirugia">Cirugia</option>
                    <option value="Hospitalizacion">Hospitalizacion</option>
                    <option value="UCI">UCI</option>
                    <option value="Medicamentos">Medicamentos</option>
                    <option value="Estudio Laboratorio">Estudio Laboratorio</option>
                    <option value="Rayos X">Rayos X</option>
                    <option value="Tomografia">Tomografia</option>
                </select>
            </div>
            <div class="form-group">
                <label>Monto ($)</label>
                <input type="number" name="amount" required step="0.01" min="0" placeholder="0.00">
            </div>
            <div class="form-group">
                <label>Estado</label>
                <select name="status" required>
                    <option value="Pendiente">Pendiente</option>
                    <option value="Pagado">Pagado</option>
                    <option value="Seguro">Seguro</option>
                    <option value="Vencido">Vencido</option>
                </select>
            </div>
            <div style="display:flex; gap:0.5rem; justify-content:flex-end; margin-top:1rem;">
                <button type="button" onclick="closeModal('nuevaFactura')" class="btn-sm btn-ghost">Cancelar</button>
                <button type="submit" class="btn-sm btn-orange"><i class="fas fa-save"></i> Guardar</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL: NUEVO SEGURO -->
<div id="modal-nuevoSeguro" class="modal-overlay" style="display:none;" onclick="if(event.target===this)closeModal('nuevoSeguro')">
    <div class="modal-box">
        <h3><i class="fas fa-shield-halved" style="color:#059669;"></i> Nuevo Seguro</h3>
        <form method="POST" action="{{ route('superadmin.finanzas.storeInsurance') }}">
            @csrf
            <div class="form-group">
                <label>Paciente</label>
                <input type="text" name="patient_name" required placeholder="Nombre del paciente">
            </div>
            <div class="form-group">
                <label>Numero de Poliza</label>
                <input type="text" name="policy_number" required placeholder="Ej: GNP-123456">
            </div>
            <div class="form-group">
                <label>Proveedor</label>
                <select name="provider" required>
                    <option value="IMSS">IMSS</option>
                    <option value="ISSSTE">ISSSTE</option>
                    <option value="Seguro Popular">Seguro Popular</option>
                    <option value="GNP">GNP</option>
                    <option value="AXA">AXA</option>
                    <option value="Mapfre">Mapfre</option>
                    <option value="MetLife">MetLife</option>
                </select>
            </div>
            <div class="form-group">
                <label>Estado</label>
                <select name="status" required>
                    <option value="Vigente">Vigente</option>
                    <option value="Vencida">Vencida</option>
                    <option value="Sin Cobertura">Sin Cobertura</option>
                    <option value="Falsa/Fraude">Falsa/Fraude</option>
                </select>
            </div>
            <div style="display:flex; gap:0.5rem; justify-content:flex-end; margin-top:1rem;">
                <button type="button" onclick="closeModal('nuevoSeguro')" class="btn-sm btn-ghost">Cancelar</button>
                <button type="submit" class="btn-sm btn-green"><i class="fas fa-save"></i> Guardar</button>
            </div>
        </form>
    </div>
</div>

<script>
function switchTab(name) {
    document.querySelectorAll('.tab-content').forEach(t => t.classList.remove('active'));
    document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
    document.getElementById('tab-' + name).classList.add('active');
    event.target.classList.add('active');
}
function openModal(name) {
    document.getElementById('modal-' + name).style.display = 'flex';
}
function closeModal(name) {
    document.getElementById('modal-' + name).style.display = 'none';
}
</script>
@endsection
