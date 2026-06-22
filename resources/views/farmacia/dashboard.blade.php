@extends('farmacia.layout')
@section('title', 'Dashboard Farmacia')
@section('nav-dashboard', 'active')

@section('content')
<div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;margin-bottom:1.5rem">
    <!-- FARMACIA CENTRAL -->
    <div style="background:white;border-radius:16px;padding:1.5rem;box-shadow:0 4px 12px rgba(249,115,22,0.08);border-top:5px solid #F97316">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.2rem">
            <h3 style="font-weight:900;color:#9A3412;font-size:1.1rem"><i class="fas fa-warehouse" style="color:#F97316"></i> Farmacia Central</h3>
            <span style="background:#FFEDD5;color:#9A3412;padding:0.2rem 0.6rem;border-radius:12px;font-size:0.7rem;font-weight:800">ALMACÉN</span>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem">
            <div style="background:#FFF7ED;border-radius:12px;padding:1rem;text-align:center">
                <div style="font-size:2rem;font-weight:900;color:#EA580C">{{ $centralStock }}</div>
                <div style="font-size:0.75rem;font-weight:700;color:#9A3412">Unidades en Stock</div>
            </div>
            <div style="background:#FFF7ED;border-radius:12px;padding:1rem;text-align:center">
                <div style="font-size:1.4rem;font-weight:900;color:#F97316">${{ number_format($centralValue, 2) }}</div>
                <div style="font-size:0.75rem;font-weight:700;color:#9A3412">Valor Inventario</div>
            </div>
            <div style="background:#FFEDD5;border-radius:12px;padding:1rem;text-align:center">
                <div style="font-size:2rem;font-weight:900;color:#D97706">{{ $centralLow }}</div>
                <div style="font-size:0.75rem;font-weight:700;color:#92400E">Stock Bajo</div>
            </div>
            <div style="background:#FEF2F2;border-radius:12px;padding:1rem;text-align:center">
                <div style="font-size:2rem;font-weight:900;color:#DC2626">{{ $centralOut }}</div>
                <div style="font-size:0.75rem;font-weight:700;color:#991B1B">Agotados</div>
            </div>
        </div>
    </div>

    <!-- FARMACIA HOSPITALARIA -->
    <div style="background:white;border-radius:16px;padding:1.5rem;box-shadow:0 4px 12px rgba(220,38,38,0.08);border-top:5px solid #DC2626">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.2rem">
            <h3 style="font-weight:900;color:#991B1B;font-size:1.1rem"><i class="fas fa-hospital" style="color:#DC2626"></i> Farmacia Hospitalaria</h3>
            <span style="background:#FEE2E2;color:#991B1B;padding:0.2rem 0.6rem;border-radius:12px;font-size:0.7rem;font-weight:800">PISO</span>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem">
            <div style="background:#FFF7ED;border-radius:12px;padding:1rem;text-align:center">
                <div style="font-size:2rem;font-weight:900;color:#EA580C">{{ $hospStock }}</div>
                <div style="font-size:0.75rem;font-weight:700;color:#9A3412">Unidades Disponibles</div>
            </div>
            <div style="background:#FFF7ED;border-radius:12px;padding:1rem;text-align:center">
                <div style="font-size:1.4rem;font-weight:900;color:#F97316">${{ number_format($hospValue, 2) }}</div>
                <div style="font-size:0.75rem;font-weight:700;color:#9A3412">Valor Inventario</div>
            </div>
            <div style="background:#FEF2F2;border-radius:12px;padding:1rem;text-align:center">
                <div style="font-size:2rem;font-weight:900;color:#DC2626">{{ $dispensed_today }}</div>
                <div style="font-size:0.75rem;font-weight:700;color:#991B1B">Dispensado Hoy</div>
            </div>
            <div style="background:#FEE2E2;border-radius:12px;padding:1rem;text-align:center">
                <div style="font-size:2rem;font-weight:900;color:#B91C1C">{{ $controladosUsados }}</div>
                <div style="font-size:0.75rem;font-weight:700;color:#991B1B">Controlados Hoy</div>
            </div>
        </div>
    </div>
</div>

<!-- STATS RAPIDOS -->
<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:1rem;margin-bottom:1.5rem">
    <div class="stat-card" style="background:white;border-color:#F97316">
        <div style="display:flex;justify-content:space-between;align-items:center">
            <div>
                <div style="font-size:0.7rem;font-weight:800;color:#9A3412;text-transform:uppercase">Pedidos Pendientes</div>
                <div style="font-size:1.8rem;font-weight:900;color:#EA580C">{{ $pending_orders }}</div>
            </div>
            <i class="fas fa-file-invoice" style="font-size:1.5rem;color:#FDBA74"></i>
        </div>
    </div>
    <div class="stat-card" style="background:white;border-color:#DC2626">
        <div style="display:flex;justify-content:space-between;align-items:center">
            <div>
                <div style="font-size:0.7rem;font-weight:800;color:#991B1B;text-transform:uppercase">Reabastecimiento</div>
                <div style="font-size:1.8rem;font-weight:900;color:#DC2626">{{ $pending_restock }}</div>
            </div>
            <i class="fas fa-boxes-stacked" style="font-size:1.5rem;color:#FCA5A5"></i>
        </div>
    </div>
    <div class="stat-card" style="background:white;border-color:#D97706">
        <div style="display:flex;justify-content:space-between;align-items:center">
            <div>
                <div style="font-size:0.7rem;font-weight:800;color:#92400E;text-transform:uppercase">Caducan 7 días</div>
                <div style="font-size:1.8rem;font-weight:900;color:#D97706">{{ $expiring_critical }}</div>
            </div>
            <i class="fas fa-calendar-exclamation" style="font-size:1.5rem;color:#FDE68A"></i>
        </div>
    </div>
    <div class="stat-card" style="background:white;border-color:#B91C1C">
        <div style="display:flex;justify-content:space-between;align-items:center">
            <div>
                <div style="font-size:0.7rem;font-weight:800;color:#991B1B;text-transform:uppercase">Carros Alerta</div>
                <div style="font-size:1.8rem;font-weight:900;color:#B91C1C">{{ $cart_alerts }}</div>
            </div>
            <i class="fas fa-first-aid" style="font-size:1.5rem;color:#FECACA"></i>
        </div>
    </div>
</div>

<!-- TABLAS INFERIORES -->
<div style="display:grid;grid-template-columns:1.2fr 0.8fr;gap:1.5rem">
    <!-- ULTIMAS DISPENSACIONES -->
    <div style="background:white;border-radius:14px;padding:1.2rem;box-shadow:0 2px 8px rgba(0,0,0,0.04);border-top:3px solid #DC2626">
        <h5 style="font-weight:900;margin-bottom:1rem;color:#991B1B"><i class="fas fa-prescription" style="color:#DC2626"></i> Últimas Dispensaciones</h5>
        <table style="width:100%;border-collapse:collapse;font-size:0.8rem">
            <thead><tr style="background:#FEF2F2"><th style="padding:0.5rem;text-align:left;color:#991B1B">Hora</th><th style="padding:0.5rem;color:#991B1B">Paciente</th><th style="padding:0.5rem;color:#991B1B">Medicamento</th><th style="padding:0.5rem;color:#991B1B">Cant</th></tr></thead>
            <tbody>
            @foreach($recentDispensed as $d)
            <tr style="border-bottom:1px solid #FFF0E0">
                <td style="padding:0.4rem;color:#9A3412;font-weight:700">{{ $d->created_at->format('H:i') }}</td>
                <td style="padding:0.4rem;font-weight:600">{{ $d->patient_name }}</td>
                <td style="padding:0.4rem"><span style="color:#EA580C">{{ $d->medication_name }}</span> @if($d->interaction_alert)<i class="fas fa-exclamation-triangle" style="color:#DC2626" title="{{ $d->interaction_details }}"></i>@endif</td>
                <td style="padding:0.4rem;font-weight:800;text-align:center">{{ $d->quantity }}</td>
            </tr>
            @endforeach
            @if($recentDispensed->count() == 0)<tr><td colspan="4" style="text-align:center;padding:1rem;color:#D97706">Sin dispensaciones hoy</td></tr>@endif
            </tbody>
        </table>
    </div>

    <!-- TOP MEDICAMENTOS + CADUCOS -->
    <div style="display:flex;flex-direction:column;gap:1.5rem">
        <div style="background:white;border-radius:14px;padding:1.2rem;box-shadow:0 2px 8px rgba(0,0,0,0.04);border-top:3px solid #F97316">
            <h5 style="font-weight:900;margin-bottom:1rem;color:#9A3412"><i class="fas fa-fire" style="color:#F97316"></i> Top Hoy</h5>
            @foreach($topDispensed as $t)
            <div style="display:flex;align-items:center;gap:0.6rem;margin-bottom:0.6rem">
                <div style="flex:1;background:#FFF7ED;border-radius:6px;padding:0.4rem 0.6rem;font-size:0.8rem;font-weight:700;color:#9A3412">{{ $t->medication_name }}</div>
                <div style="background:#F97316;color:white;border-radius:4px;padding:0.2rem 0.5rem;font-size:0.7rem;font-weight:900">{{ $t->total }}</div>
            </div>
            @endforeach
            @if($topDispensed->count() == 0)<p style="text-align:center;color:#D97706;font-size:0.8rem">Sin datos hoy</p>@endif
        </div>

        @if($expiring_soon->count() > 0)
        <div style="background:white;border-radius:14px;padding:1.2rem;box-shadow:0 2px 8px rgba(0,0,0,0.04);border-top:3px solid #B91C1C">
            <h5 style="font-weight:900;margin-bottom:1rem;color:#991B1B"><i class="fas fa-calendar-times" style="color:#DC2626"></i> Próximos a Caducar</h5>
            @foreach($expiring_soon->take(4) as $m)
            @php $days = now()->diffInDays($m->expiry_date, false); @endphp
            <div style="display:flex;justify-content:space-between;align-items:center;padding:0.3rem 0;border-bottom:1px solid #FFF0E0">
                <span style="font-size:0.8rem;font-weight:700">{{ $m->name }}</span>
                <span style="font-size:0.7rem;font-weight:900;color:{{ $days <= 7 ? '#DC2626' : '#D97706' }}">{{ $days <= 0 ? 'CADUCADO' : $days . 'd' }}</span>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</div>
@endsection
