@extends('especialidades.layout')

@section('content')
<div style="padding:1.5rem">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem">
        <h2 style="font-weight:900;color:#9A3412"><i class="fas fa-calendar-alt" style="color:#EA580C"></i> Agenda Central Inteligente</h2>
        <div style="display:flex;gap:0.8rem">
            <button onclick="window.print()" style="padding:0.4rem 1rem;background:#2563EB;color:white;border:none;border-radius:8px;font-weight:800;font-size:0.8rem;cursor:pointer"><i class="fas fa-print"></i> Imprimir Tickets</button>
        </div>
    </div>

    @php
        $user = auth()->user();
        $hoy = \App\Models\Appointment::where('doctor_id', $user->id)
            ->whereDate('scheduled_at', now()->toDateString())
            ->orderBy('scheduled_at')
            ->get();
        $manana = \App\Models\Appointment::where('doctor_id', $user->id)
            ->whereDate('scheduled_at', now()->addDay()->toDateString())
            ->orderBy('scheduled_at')
            ->get();
        $semana = \App\Models\Appointment::where('doctor_id', $user->id)
            ->whereBetween('scheduled_at', [now()->addDays(2)->startOfDay(), now()->endOfWeek()])
            ->orderBy('scheduled_at')
            ->get();
    @endphp

    <!-- HOY -->
    <div style="background:white;border-radius:16px;padding:1.5rem;box-shadow:0 2px 8px rgba(0,0,0,0.05);margin-bottom:1.5rem;border-top:4px solid #EA580C">
        <h3 style="font-weight:900;color:#9A3412;margin-bottom:1rem"><i class="fas fa-sun" style="color:#F97316"></i> Hoy - {{ now()->format('d/m/Y') }} ({{ $hoy->count() }} citas)</h3>
        @if($hoy->count() > 0)
        <div style="display:grid;gap:0.8rem">
            @foreach($hoy as $apt)
            <div class="ticket-card" data-ticket="{{ $apt->id }}" style="background:#FFF7ED;border-radius:12px;padding:1rem;display:flex;justify-content:space-between;align-items:center;border-left:4px solid #EA580C;position:relative">
                <div style="flex:1">
                    <div style="font-weight:900;color:#9A3412;font-size:1rem">{{ $apt->patient_name }}</div>
                    <div style="font-size:0.8rem;color:#78716C;margin-top:0.2rem"><i class="fas fa-stethoscope"></i> {{ $apt->reason }}</div>
                    <div style="font-size:0.75rem;color:#A8A29E;margin-top:0.2rem">Dr. {{ $user->name }} - {{ $user->specialty ? $user->specialty->name : 'General' }}</div>
                </div>
                <div style="text-align:right">
                    <div style="font-weight:900;color:#EA580C;font-size:1.1rem">{{ \Carbon\Carbon::parse($apt->scheduled_at)->format('H:i') }}</div>
                    <span style="background:{{ $apt->status == 'Confirmada' ? '#DCFCE7' : '#FEF9C3' }};color:{{ $apt->status == 'Confirmada' ? '#16A34A' : '#CA8A04' }};padding:0.2rem 0.5rem;border-radius:4px;font-size:0.7rem;font-weight:800">{{ $apt->status }}</span>
                </div>
                <button onclick="printTicket({{ $apt->id }})" style="position:absolute;top:0.5rem;right:0.5rem;background:none;border:none;cursor:pointer;color:#9CA3AF;font-size:0.8rem" title="Imprimir ticket"><i class="fas fa-print"></i></button>
            </div>
            @endforeach
        </div>
        @else
        <p style="text-align:center;color:#D97706;padding:1.5rem;font-weight:700"><i class="fas fa-calendar-check"></i> Sin citas para hoy</p>
        @endif
    </div>

    <!-- MANANA -->
    <div style="background:white;border-radius:16px;padding:1.5rem;box-shadow:0 2px 8px rgba(0,0,0,0.05);margin-bottom:1.5rem;border-top:4px solid #2563EB">
        <h3 style="font-weight:900;color:#1E40AF;margin-bottom:1rem"><i class="fas fa-cloud-sun" style="color:#2563EB"></i> Manana - {{ now()->addDay()->format('d/m/Y') }} ({{ $manana->count() }} citas)</h3>
        @if($manana->count() > 0)
        <div style="display:grid;gap:0.8rem">
            @foreach($manana as $apt)
            <div class="ticket-card" data-ticket="{{ $apt->id }}" style="background:#EFF6FF;border-radius:12px;padding:1rem;display:flex;justify-content:space-between;align-items:center;border-left:4px solid #2563EB">
                <div>
                    <div style="font-weight:800;color:#1E40AF">{{ $apt->patient_name }}</div>
                    <div style="font-size:0.8rem;color:#78716C">{{ $apt->reason }}</div>
                </div>
                <div style="font-weight:900;color:#2563EB;font-size:1rem">{{ \Carbon\Carbon::parse($apt->scheduled_at)->format('H:i') }}</div>
            </div>
            @endforeach
        </div>
        @else
        <p style="text-align:center;color:#2563EB;padding:1.5rem;font-weight:700"><i class="fas fa-calendar"></i> Sin citas para manana</p>
        @endif
    </div>

    <!-- ESTA SEMANA -->
    <div style="background:white;border-radius:16px;padding:1.5rem;box-shadow:0 2px 8px rgba(0,0,0,0.05);border-top:4px solid #7C3AED">
        <h3 style="font-weight:900;color:#6D28D9;margin-bottom:1rem"><i class="fas fa-calendar-week" style="color:#7C3AED"></i> Esta Semana ({{ $semana->count() }} citas)</h3>
        @if($semana->count() > 0)
        <div style="display:grid;gap:0.8rem">
            @foreach($semana as $apt)
            <div class="ticket-card" data-ticket="{{ $apt->id }}" style="background:#F5F3FF;border-radius:12px;padding:1rem;display:flex;justify-content:space-between;align-items:center;border-left:4px solid #7C3AED">
                <div>
                    <div style="font-weight:800;color:#6D28D9">{{ $apt->patient_name }}</div>
                    <div style="font-size:0.8rem;color:#78716C">{{ $apt->reason }}</div>
                </div>
                <div style="font-weight:800;color:#7C3AED;font-size:0.9rem">{{ \Carbon\Carbon::parse($apt->scheduled_at)->format('d/m H:i') }}</div>
            </div>
            @endforeach
        </div>
        @else
        <p style="text-align:center;color:#7C3AED;padding:1.5rem;font-weight:700"><i class="fas fa-calendar"></i> Sin citas esta semana</p>
        @endif
    </div>
</div>

<!-- TICKET DE IMPRESION (oculto) -->
<div id="print-area" style="display:none">
    <style>
        .ticket-print { width: 280px; font-family: monospace; padding: 10px; border: 2px dashed #333; margin: 5px auto; }
        .ticket-print h2 { text-align: center; margin: 0; font-size: 14px; }
        .ticket-print .line { border-top: 1px dashed #666; margin: 6px 0; }
        .ticket-print .row { display: flex; justify-content: space-between; font-size: 11px; margin: 3px 0; }
        .ticket-print .center { text-align: center; font-size: 10px; }
        .ticket-print .big { font-size: 20px; font-weight: bold; text-align: center; margin: 8px 0; }
    </style>
    <div id="ticket-content"></div>
</div>

<style>
@media print {
    body * { visibility: hidden; }
    #print-area, #print-area * { visibility: visible; display: block !important; }
    #print-area { position: absolute; left: 0; top: 0; }
    .ticket-print { page-break-after: always; }
}
</style>

<script>
function printTicket(id) {
    const card = document.querySelector('[data-ticket="' + id + '"]');
    if (!card) return;

    const nombre = card.querySelector('div > div:first-child')?.textContent || '';
    const motivo = card.querySelector('div > div:nth-child(2)')?.textContent || '';
    const hora = card.querySelector('div:last-child > div:first-child')?.textContent || '';
    const doctor = card.querySelector('div > div:nth-child(3)')?.textContent || '';

    const ticket = `
    <div class="ticket-print">
        <h2>🏥 HEALTHNEXUS</h2>
        <div class="line"></div>
        <div class="row"><span>Fecha:</span><span>{{ now()->format('d/m/Y') }}</span></div>
        <div class="row"><span>Hora:</span><span>${hora}</span></div>
        <div class="line"></div>
        <div class="row"><span>Paciente:</span><span>${nombre}</span></div>
        <div class="row"><span>Motivo:</span><span>${motivo}</span></div>
        <div class="row"><span>Doctor:</span><span>${doctor}</span></div>
        <div class="line"></div>
        <div class="big">#${String(id).padStart(4, '0')}</div>
        <div class="line"></div>
        <div class="center">Presentar este ticket en recepcion</div>
        <div class="center">HealthNexus v3 - Agenda Inteligente</div>
    </div>`;

    document.getElementById('ticket-content').innerHTML = ticket;
    window.print();
}
</script>
@endsection
