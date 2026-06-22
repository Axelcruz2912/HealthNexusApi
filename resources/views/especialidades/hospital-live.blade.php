@extends('especialidades.layout')

@section('content')
<div style="padding:1.5rem">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem">
        <h2 style="font-weight:900;color:#9A3412"><i class="fas fa-tower-broadcast" style="color:#EA580C"></i> Hospital Live</h2>
        <div style="display:flex;gap:0.8rem;align-items:center">
            <span style="color:#EA580C;font-size:0.8rem;font-weight:700"><i class="fas fa-circle" style="font-size:0.4rem;animation:blink 1s infinite"></i> EN VIVO</span>
            <a href="{{ url('/medico/ambulancias') }}" style="padding:0.4rem 1rem;background:#EA580C;color:white;border-radius:8px;text-decoration:none;font-weight:800;font-size:0.8rem"><i class="fas fa-truck-medical"></i> Ambulancias</a>
        </div>
    </div>

    @if($modoCrisis)
    <div style="background:linear-gradient(135deg,#DC2626,#B91C1C);color:white;border-radius:16px;padding:1.5rem;margin-bottom:1.5rem;animation:blink 2s infinite">
        <div style="display:flex;justify-content:space-between;align-items:center">
            <div>
                <h3 style="font-weight:900;font-size:1.3rem"><i class="fas fa-triangle-exclamation"></i> MODO CRISIS ACTIVO</h3>
                <p style="opacity:0.9;font-size:0.85rem">Multiples areas en estado critico. Protocolo de emergencia activado.</p>
            </div>
            <div style="background:rgba(255,255,255,0.2);border-radius:12px;padding:1rem;text-align:center">
                <i class="fas fa-exclamation-triangle" style="font-size:2rem"></i>
                <div style="font-weight:900;font-size:0.8rem;margin-top:0.3rem">ACTIVO</div>
            </div>
        </div>
        <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:0.8rem;margin-top:1rem">
            <div style="background:rgba(255,255,255,0.15);border-radius:8px;padding:0.5rem;text-align:center;font-size:0.75rem;font-weight:700">Consultas no urgentes bloqueadas</div>
            <div style="background:rgba(255,255,255,0.15);border-radius:8px;padding:0.5rem;text-align:center;font-size:0.75rem;font-weight:700">Triage rojo priorizado</div>
            <div style="background:rgba(255,255,255,0.15);border-radius:8px;padding:0.5rem;text-align:center;font-size:0.75rem;font-weight:700">Medicos A alertados</div>
            <div style="background:rgba(255,255,255,0.15);border-radius:8px;padding:0.5rem;text-align:center;font-size:0.75rem;font-weight:700">Farmacia en modo emergencia</div>
        </div>
    </div>
    @endif

    <!-- MAPA DE SATURACION -->
    <div style="background:white;border-radius:16px;padding:1.5rem;box-shadow:0 2px 8px rgba(0,0,0,0.05);margin-bottom:1.5rem">
        <h3 style="font-weight:900;color:#9A3412;margin-bottom:1rem"><i class="fas fa-chart-pie" style="color:#EA580C"></i> Mapa de Saturacion</h3>
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:1rem">
            @foreach($saturacion as $area)
            @php
                $bgColor = $area['status'] == 'CRITICO' ? '#FEF2F2' : ($area['status'] == 'ALERTA' ? '#FFFBEB' : '#FFF7ED');
                $borderColor = $area['status'] == 'CRITICO' ? '#DC2626' : ($area['status'] == 'ALERTA' ? '#F59E0B' : '#FDBA74');
                $textColor = $area['status'] == 'CRITICO' ? '#DC2626' : ($area['status'] == 'ALERTA' ? '#D97706' : '#EA580C');
                $barColor = $area['status'] == 'CRITICO' ? '#DC2626' : ($area['status'] == 'ALERTA' ? '#F59E0B' : '#EA580C');
            @endphp
            <div style="background:{{ $bgColor }};border-radius:12px;padding:1rem;border:2px solid {{ $borderColor }}">
                <div style="display:flex;justify-content:space-between;align-items:start">
                    <div style="font-weight:900;color:{{ $textColor }};font-size:0.9rem">{{ $area['name'] }}</div>
                    <span style="background:{{ $barColor }};color:white;padding:0.1rem 0.4rem;border-radius:4px;font-size:0.6rem;font-weight:800">{{ $area['status'] }}</span>
                </div>
                <div style="margin-top:0.5rem">
                    <div style="background:#FED7AA;border-radius:4px;height:8px;overflow:hidden">
                        <div style="background:{{ $barColor }};height:100%;width:{{ min($area['pct'], 100) }}%;border-radius:4px;transition:width 1s"></div>
                    </div>
                    <div style="display:flex;justify-content:space-between;margin-top:0.3rem;font-size:0.7rem;color:#9A3412">
                        <span>{{ $area['patients'] }} pacientes</span>
                        <span>{{ $area['pct'] }}%</span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- METRICAS EN TIEMPO REAL -->
    <div style="display:grid;grid-template-columns:repeat(5,1fr);gap:1rem;margin-bottom:1.5rem">
        <div style="background:linear-gradient(135deg,#FEF2F2,#FECACA);border-radius:14px;padding:1rem;text-align:center">
            <div style="font-size:2rem;font-weight:900;color:#DC2626">{{ $enEspera }}</div>
            <div style="font-size:0.7rem;font-weight:800;color:#991B1B">En Espera</div>
        </div>
        <div style="background:linear-gradient(135deg,#FFEDD5,#FED7AA);border-radius:14px;padding:1rem;text-align:center">
            <div style="font-size:2rem;font-weight:900;color:#EA580C">{{ $enAtencion }}</div>
            <div style="font-size:0.7rem;font-weight:800;color:#9A3412">En Atencion</div>
        </div>
        <div style="background:linear-gradient(135deg,#FEF2F2,#FECACA);border-radius:14px;padding:1rem;text-align:center">
            <div style="font-size:2rem;font-weight:900;color:#DC2626">{{ $hospitalizados }}</div>
            <div style="font-size:0.7rem;font-weight:800;color:#991B1B">Hospitalizados</div>
        </div>
        <div style="background:linear-gradient(135deg,#FEF2F2,#FEE2E2);border-radius:14px;padding:1rem;text-align:center">
            <div style="font-size:2rem;font-weight:900;color:#B91C1C">{{ $criticos }}</div>
            <div style="font-size:0.7rem;font-weight:800;color:#7F1D1D">Criticos</div>
        </div>
        <div style="background:linear-gradient(135deg,#FFEDD5,#FDBA74);border-radius:14px;padding:1rem;text-align:center">
            <div style="font-size:2rem;font-weight:900;color:#EA580C">{{ $ambulanciasActivas }}</div>
            <div style="font-size:0.7rem;font-weight:800;color:#9A3412">Ambulancias</div>
        </div>
    </div>

    <!-- TIMELINE DE EVENTOS -->
    <div style="background:white;border-radius:16px;padding:1.5rem;box-shadow:0 2px 8px rgba(0,0,0,0.05)">
        <h3 style="font-weight:900;color:#9A3412;margin-bottom:1rem"><i class="fas fa-timeline" style="color:#EA580C"></i> Eventos en Tiempo Real</h3>
        @php $events = \App\Models\AuditLog::orderBy('created_at','desc')->take(12)->get(); @endphp
        @if($events->count() > 0)
        <div style="display:grid;gap:0.5rem">
            @foreach($events as $e)
            @php $rowBg = $loop->index < 3 ? 'background:#FFFBEB;' : ''; @endphp
            <div style="display:flex;gap:0.8rem;align-items:center;padding:0.5rem;border-radius:8px;{{ $rowBg }}">
                <div style="width:8px;height:8px;border-radius:50%;background:#EA580C;flex-shrink:0"></div>
                <div style="font-size:0.7rem;color:#9A3412;width:60px">{{ $e->created_at->format('H:i') }}</div>
                <div style="font-size:0.8rem;font-weight:700;color:#7F1D1D">{{ $e->action }}</div>
                <div style="font-size:0.75rem;color:#A8A29E">{{ Str::limit($e->details, 40) }}</div>
            </div>
            @endforeach
        </div>
        @else
        <p style="text-align:center;color:#EA580C;padding:1.5rem;font-weight:700">Sin eventos recientes</p>
        @endif
    </div>
</div>

<style>
@keyframes blink { 0%,100%{opacity:1} 50%{opacity:0.3} }
</style>
@endsection
