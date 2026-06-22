@extends('especialidades.layout')

@section('content')
<div style="padding:1.5rem">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem">
        <h2 style="font-weight:900;color:#9A3412"><i class="fas fa-truck-medical" style="color:#EA580C"></i> Ambulancias y Traslados</h2>
        <div style="display:flex;gap:0.8rem">
            <a href="{{ url('/medico/hospital-live') }}" style="padding:0.4rem 1rem;background:linear-gradient(135deg,#EA580C,#DC2626);color:white;border-radius:8px;text-decoration:none;font-weight:800;font-size:0.8rem"><i class="fas fa-tower-broadcast"></i> Hospital Live</a>
        </div>
    </div>

    @if(session('success'))
    <div style="background:#DCFCE7;color:#16A34A;padding:0.8rem;border-radius:8px;margin-bottom:1rem;font-weight:700">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
    @endif

    <!-- STATS -->
    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:1rem;margin-bottom:1.5rem">
        <div style="background:linear-gradient(135deg,#EA580C,#C2410C);border-radius:14px;padding:1.2rem;color:white;text-align:center">
            <div style="font-size:2rem;font-weight:900">{{ $disponibles }}</div>
            <div style="font-size:0.7rem;font-weight:800;opacity:0.9">Disponibles</div>
        </div>
        <div style="background:linear-gradient(135deg,#F97316,#EA580C);border-radius:14px;padding:1.2rem;color:white;text-align:center">
            <div style="font-size:2rem;font-weight:900">{{ $activas }}</div>
            <div style="font-size:0.7rem;font-weight:800;opacity:0.9">En Ruta</div>
        </div>
        <div style="background:linear-gradient(135deg,#DC2626,#B91C1C);border-radius:14px;padding:1.2rem;color:white;text-align:center">
            <div style="font-size:2rem;font-weight:900">{{ $criticosEnRuta }}</div>
            <div style="font-size:0.7rem;font-weight:800;opacity:0.9">Criticos En Ruta</div>
        </div>
        <div style="background:linear-gradient(135deg,#DC2626,#991B1B);border-radius:14px;padding:1.2rem;color:white;text-align:center">
            <div style="font-size:2rem;font-weight:900">{{ $pacientesCriticos->count() }}</div>
            <div style="font-size:0.7rem;font-weight:800;opacity:0.9">Pacientes Criticos</div>
        </div>
    </div>

    <!-- MAPA MAPBOX -->
    <div style="background:white;border-radius:16px;padding:1.5rem;box-shadow:0 2px 8px rgba(0,0,0,0.05);margin-bottom:1.5rem;border-top:4px solid #EA580C">
        <h3 style="font-weight:900;color:#9A3412;margin-bottom:1rem"><i class="fas fa-map-marked-alt" style="color:#EA580C"></i> Mapa en Tiempo Real</h3>
        <div id="mapa-ambulancias" style="width:100%;height:400px;border-radius:12px;overflow:hidden;border:2px solid #FDBA74"></div>
    </div>

    <!-- DESPACHAR AMBULANCIA -->
    <div style="background:white;border-radius:16px;padding:1.5rem;box-shadow:0 2px 8px rgba(0,0,0,0.05);margin-bottom:1.5rem;border-top:4px solid #EA580C">
        <h3 style="font-weight:900;color:#9A3412;margin-bottom:1rem"><i class="fas fa-paper-plane" style="color:#EA580C"></i> Despachar Ambulancia</h3>
        <form method="POST" action="{{ url('/medico/ambulancias/despachar') }}">
            @csrf
            <div style="display:grid;grid-template-columns:1fr 1fr 1fr 1fr;gap:1rem;margin-bottom:1rem">
                <div>
                    <label style="font-weight:800;color:#9A3412;font-size:0.8rem;display:block;margin-bottom:0.3rem">Ambulancia</label>
                    <select name="ambulance_id" required style="width:100%;padding:0.5rem;border:2px solid #FDBA74;border-radius:8px;font-size:0.85rem">
                        <option value="">Seleccionar...</option>
                        @foreach($ambulancias->where('status','Disponible') as $a)
                        <option value="{{ $a->id }}">{{ $a->code }} - {{ $a->type }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label style="font-weight:800;color:#9A3412;font-size:0.8rem;display:block;margin-bottom:0.3rem">Origen</label>
                    <input type="text" name="origin" required placeholder="Ubicacion origen" style="width:100%;padding:0.5rem;border:2px solid #FDBA74;border-radius:8px;font-size:0.85rem">
                </div>
                <div>
                    <label style="font-weight:800;color:#9A3412;font-size:0.8rem;display:block;margin-bottom:0.3rem">Destino</label>
                    <input type="text" name="destination" required placeholder="Hospital destino" style="width:100%;padding:0.5rem;border:2px solid #FDBA74;border-radius:8px;font-size:0.85rem">
                </div>
                <div>
                    <label style="font-weight:800;color:#9A3412;font-size:0.8rem;display:block;margin-bottom:0.3rem">Prioridad</label>
                    <select name="priority" required style="width:100%;padding:0.5rem;border:2px solid #FDBA74;border-radius:8px;font-size:0.85rem">
                        <option value="Normal">Normal</option>
                        <option value="Urgente">Urgente</option>
                        <option value="Critica">Critica</option>
                    </select>
                </div>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:1rem;margin-bottom:1rem">
                <div>
                    <label style="font-weight:800;color:#9A3412;font-size:0.8rem;display:block;margin-bottom:0.3rem">Paciente</label>
                    <select name="patient_id" style="width:100%;padding:0.5rem;border:2px solid #FDBA74;border-radius:8px;font-size:0.85rem">
                        <option value="">Sin paciente</option>
                        @foreach($pacientesCriticos as $p)
                        <option value="{{ $p->id }}">{{ $p->patient_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label style="font-weight:800;color:#9A3412;font-size:0.8rem;display:block;margin-bottom:0.3rem">Conductor</label>
                    <select name="driver_id" style="width:100%;padding:0.5rem;border:2px solid #FDBA74;border-radius:8px;font-size:0.85rem">
                        <option value="">Sin asignar</option>
                        @foreach($conductores as $c)
                        <option value="{{ $c->id }}">{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label style="font-weight:800;color:#9A3412;font-size:0.8rem;display:block;margin-bottom:0.3rem">Notas</label>
                    <input type="text" name="notes" placeholder="Notas" style="width:100%;padding:0.5rem;border:2px solid #FDBA74;border-radius:8px;font-size:0.85rem">
                </div>
            </div>
            <button type="submit" style="padding:0.5rem 1.5rem;background:#EA580C;color:white;border:none;border-radius:8px;font-weight:800;cursor:pointer"><i class="fas fa-paper-plane"></i> Despachar</button>
        </form>
    </div>

    <!-- AMBULANCIAS ACTIVAS CON IoT -->
    <div style="background:white;border-radius:16px;padding:1.5rem;box-shadow:0 2px 8px rgba(0,0,0,0.05);margin-bottom:1.5rem;border-top:4px solid #DC2626">
        <h3 style="font-weight:900;color:#DC2626;margin-bottom:1rem"><i class="fas fa-satellite-dish" style="color:#DC2626"></i> Ambulancias Activas - Monitoreo IoT</h3>
        @php $activasList = $ambulancias->where('status', 'En Ruta'); @endphp
        @if($activasList->count() > 0)
        <div style="display:grid;gap:1rem">
            @foreach($activasList as $a)
            <div style="background:linear-gradient(135deg,#FFF7ED,#FFEDD5);border-radius:16px;padding:1.5rem;border:2px solid #FDBA74">
                <div style="display:flex;justify-content:space-between;align-items:start;margin-bottom:1rem">
                    <div>
                        <div style="font-weight:900;color:#9A3412;font-size:1.1rem"><i class="fas fa-truck-medical" style="color:#EA580C"></i> {{ $a->code }}</div>
                        <div style="color:#A8A29E;font-size:0.8rem">{{ $a->type }}</div>
                    </div>
                    <div style="display:flex;gap:0.5rem">
                        @php $pColor = $a->priority == 'Critica' ? '#DC2626' : ($a->priority == 'Urgente' ? '#EA580C' : '#D97706'); @endphp
                        <span style="background:{{ $pColor }}20;color:{{ $pColor }};padding:0.3rem 0.8rem;border-radius:20px;font-weight:800;font-size:0.75rem">{{ $a->priority }}</span>
                        <span style="background:#FFEDD5;color:#EA580C;padding:0.3rem 0.6rem;border-radius:20px;font-weight:800;font-size:0.7rem"><i class="fas fa-signal"></i> LIVE</span>
                    </div>
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:0.8rem;margin-bottom:1rem">
                    <div style="background:white;border-radius:8px;padding:0.6rem">
                        <div style="font-size:0.7rem;color:#A8A29E;font-weight:700">Origen</div>
                        <div style="font-weight:800;color:#9A3412;font-size:0.85rem">{{ $a->origin }}</div>
                    </div>
                    <div style="background:white;border-radius:8px;padding:0.6rem">
                        <div style="font-size:0.7rem;color:#A8A29E;font-weight:700">Destino</div>
                        <div style="font-weight:800;color:#9A3412;font-size:0.85rem">{{ $a->destination }}</div>
                    </div>
                </div>
                <!-- IoT PANEL -->
                <div style="background:#431407;border-radius:12px;padding:1rem;margin-bottom:1rem">
                    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:0.8rem">
                        <span style="color:#FDBA74;font-weight:800;font-size:0.8rem"><i class="fas fa-microchip"></i> TELEMETRIA IoT</span>
                        <span style="color:#4ADE80;font-size:0.7rem"><i class="fas fa-circle" style="font-size:0.4rem;animation:blink 1s infinite"></i> Conectado</span>
                    </div>
                    <div style="display:grid;grid-template-columns:repeat(5,1fr);gap:0.5rem">
                        <div style="text-align:center">
                            <div style="color:#A8A29E;font-size:0.6rem;font-weight:700">VELOCIDAD</div>
                            <div id="speed-{{ $a->id }}" style="color:#FDBA74;font-size:1.2rem;font-weight:900">{{ $a->speed }}</div>
                            <div style="color:#78716C;font-size:0.55rem">km/h</div>
                        </div>
                        <div style="text-align:center">
                            <div style="color:#A8A29E;font-size:0.6rem;font-weight:700">COMBUSTIBLE</div>
                            <div id="fuel-{{ $a->id }}" style="color:#FBBF24;font-size:1.2rem;font-weight:900">{{ $a->fuel }}</div>
                            <div style="color:#78716C;font-size:0.55rem">%</div>
                        </div>
                        <div style="text-align:center">
                            <div style="color:#A8A29E;font-size:0.6rem;font-weight:700">FC PACIENTE</div>
                            <div id="hr-{{ $a->id }}" style="color:{{ $a->heart_rate > 100 ? '#F87171' : '#4ADE80' }};font-size:1.2rem;font-weight:900">{{ $a->heart_rate }}</div>
                            <div style="color:#78716C;font-size:0.55rem">bpm</div>
                        </div>
                        <div style="text-align:center">
                            <div style="color:#A8A29E;font-size:0.6rem;font-weight:700">O2</div>
                            <div id="o2-{{ $a->id }}" style="color:{{ $a->oxygen < 90 ? '#F87171' : '#4ADE80' }};font-size:1.2rem;font-weight:900">{{ $a->oxygen }}</div>
                            <div style="color:#78716C;font-size:0.55rem">%</div>
                        </div>
                        <div style="text-align:center">
                            <div style="color:#A8A29E;font-size:0.6rem;font-weight:700">TEMP</div>
                            <div id="temp-{{ $a->id }}" style="color:{{ $a->temp > 38 ? '#F87171' : '#4ADE80' }};font-size:1.2rem;font-weight:900">{{ $a->temp }}</div>
                            <div style="color:#78716C;font-size:0.55rem">C</div>
                        </div>
                    </div>
                </div>
                <div style="display:flex;justify-content:space-between;align-items:center">
                    <div style="font-size:0.75rem;color:#A8A29E">Despachada: {{ $a->dispatched_at ? $a->dispatched_at->diffForHumans() : 'N/A' }}</div>
                    <form method="POST" action="{{ url('/medico/ambulancias/' . $a->id . '/llegada') }}">
                        @csrf
                        <button type="submit" style="padding:0.4rem 1rem;background:#16A34A;color:white;border:none;border-radius:8px;font-weight:800;font-size:0.8rem;cursor:pointer"><i class="fas fa-flag-checkered"></i> Registrar Llegada</button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <p style="text-align:center;color:#EA580C;padding:2rem;font-weight:700"><i class="fas fa-check-circle"></i> Sin ambulancias en ruta</p>
        @endif
    </div>

    <!-- AMBULANCIAS DISPONIBLES -->
    <div style="background:white;border-radius:16px;padding:1.5rem;box-shadow:0 2px 8px rgba(0,0,0,0.05);margin-bottom:1.5rem;border-top:4px solid #EA580C">
        <h3 style="font-weight:900;color:#EA580C;margin-bottom:1rem"><i class="fas fa-check-circle"></i> Disponibles</h3>
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:1rem">
            @foreach($ambulancias->where('status','Disponible') as $a)
            <div style="border:2px solid #FDBA74;border-radius:12px;padding:1rem;text-align:center;background:#FFF7ED">
                <i class="fas fa-truck-medical" style="font-size:1.8rem;color:#EA580C"></i>
                <div style="font-weight:900;color:#9A3412;margin-top:0.3rem">{{ $a->code }}</div>
                <div style="font-size:0.75rem;color:#A8A29E">{{ $a->type }}</div>
                <div style="font-size:0.7rem;color:#78716C;margin-top:0.3rem"><i class="fas fa-map-pin"></i> {{ $a->location }}</div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- PACIENTES CRITICOS -->
    <div style="background:white;border-radius:16px;padding:1.5rem;box-shadow:0 2px 8px rgba(0,0,0,0.05);border-top:4px solid #DC2626">
        <h3 style="font-weight:900;color:#DC2626;margin-bottom:1rem"><i class="fas fa-heart-pulse"></i> Pacientes Criticos</h3>
        @if($pacientesCriticos->count() > 0)
        <table style="width:100%;border-collapse:collapse;font-size:0.85rem">
            <thead><tr style="background:#FEF2F2"><th style="padding:0.6rem;text-align:left;color:#991B1B">Paciente</th><th style="padding:0.6rem;color:#991B1B">Triage</th><th style="padding:0.6rem;color:#991B1B">Sintomas</th><th style="padding:0.6rem;color:#991B1B">Tiempo</th></tr></thead>
            <tbody>
            @foreach($pacientesCriticos as $p)
            <tr style="border-bottom:1px solid #FEE2E2">
                <td style="padding:0.5rem;font-weight:700;color:#7F1D1D">{{ $p->patient_name }}</td>
                <td style="padding:0.5rem"><span style="background:#FEE2E2;color:#DC2626;padding:0.15rem 0.5rem;border-radius:4px;font-size:0.75rem;font-weight:800">{{ $p->triage_level }}</span></td>
                <td style="padding:0.5rem;color:#A8A29E">{{ Str::limit($p->symptoms, 40) }}</td>
                <td style="padding:0.5rem;font-size:0.75rem;color:#DC2626;font-weight:700">{{ $p->created_at->diffForHumans() }}</td>
            </tr>
            @endforeach
            </tbody>
        </table>
        @else
        <p style="text-align:center;color:#16A34A;padding:1.5rem;font-weight:700"><i class="fas fa-shield-heart"></i> Sin pacientes criticos</p>
        @endif
    </div>
</div>

<style>
@keyframes blink { 0%,100%{opacity:1} 50%{opacity:0.3} }
</style>

<script>
// ============================================================
// MAPBOX MAPA REAL
// ============================================================
const MAPBOX_TOKEN = '{{ env("MAPBOX_PUBLIC_TOKEN") }}';

if (MAPBOX_TOKEN && MAPBOX_TOKEN !== 'pk.tu-mapbox-token-aqui' && MAPBOX_TOKEN.length > 20) {
    // Cargar Mapbox GL JS
    const link = document.createElement('link');
    link.href = 'https://api.mapbox.com/mapbox-gl-js/v3.3.0/mapbox-gl.css';
    link.rel = 'stylesheet';
    document.head.appendChild(link);

    const script = document.createElement('script');
    script.src = 'https://api.mapbox.com/mapbox-gl-js/v3.3.0/mapbox-gl.js';
    script.onload = function() {
        mapboxgl.accessToken = MAPBOX_TOKEN;
        const map = new mapboxgl.Map({
            container: 'mapa-ambulancias',
            style: 'mapbox://styles/mapbox/dark-v11',
            center: [-99.1332, 19.4326],
            zoom: 12
        });

        map.addControl(new mapboxgl.NavigationControl());

        // Agregar marcadores de ambulancias activas
        @foreach($ambulancias as $a)
        @if($a->status == 'En Ruta' && $a->latitude && $a->longitude)
        new mapboxgl.Marker({ color: '{{ $a->priority == "Critica" ? "#DC2626" : "#EA580C" }}' })
            .setLngLat([{{ $a->longitude }}, {{ $a->latitude }}])
            .setPopup(new mapboxgl.Popup().setHTML(
                '<div style="padding:8px;font-family:system-ui">' +
                '<strong style="color:#EA580C">{{ $a->code }}</strong><br>' +
                '<span style="font-size:12px">{{ $a->type }}</span><br>' +
                '<span style="font-size:11px;color:#666">{{ $a->origin }} → {{ $a->destination }}</span><br>' +
                '<span style="font-size:11px;color:{{ $a->priority == "Critica" ? "#DC2626" : "#EA580C" }}">{{ $a->priority }}</span>' +
                '</div>'
            ))
            .addTo(map);
        @elseif($a->status == 'Disponible' && $a->latitude && $a->longitude)
        new mapboxgl.Marker({ color: '#16A34A' })
            .setLngLat([{{ $a->longitude }}, {{ $a->latitude }}])
            .setPopup(new mapboxgl.Popup().setHTML(
                '<div style="padding:8px;font-family:system-ui">' +
                '<strong style="color:#16A34A">{{ $a->code }}</strong><br>' +
                '<span style="font-size:12px">{{ $a->type }} - Disponible</span>' +
                '</div>'
            ))
            .addTo(map);
        @endif
        @endforeach

        // Hospital marker central
        new mapboxgl.Marker({ color: '#F59E0B' })
            .setLngLat([-99.1332, 19.4326])
            .setPopup(new mapboxgl.Popup().setHTML(
                '<div style="padding:8px;font-family:system-ui">' +
                '<strong style="color:#F59E0B">🏥 Hospital Central</strong><br>' +
                '<span style="font-size:12px">HealthNexus</span>' +
                '</div>'
            ))
            .addTo(map);
    };
    document.head.appendChild(script);
} else {
    // Mapa simulado si no hay token
    document.getElementById('mapa-ambulancias').innerHTML = `
        <div style="background:linear-gradient(135deg,#1C1917,#431407);width:100%;height:100%;display:flex;flex-direction:column;align-items:center;justify-content:center;color:white;border-radius:12px;position:relative;overflow:hidden">
            <div style="position:absolute;top:0;left:0;right:0;bottom:0;background:url('data:image/svg+xml,<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"100\" height=\"100\"><circle cx=\"50\" cy=\"50\" r=\"1\" fill=\"%23FDBA7440\"/></svg>');opacity:0.3"></div>
            <i class="fas fa-map-marked-alt" style="font-size:3rem;color:#FDBA74;margin-bottom:1rem"></i>
            <div style="font-weight:900;font-size:1.2rem;color:#FDBA74;margin-bottom:0.5rem">Mapa de Ambulancias</div>
            <div style="font-size:0.85rem;color:#A8A29E;text-align:center;max-width:400px">
                Para ver el mapa interactivo real, configura tu token de Mapbox:<br>
                <code style="background:#431407;padding:0.2rem 0.5rem;border-radius:4px;color:#FDBA74;font-size:0.75rem">MAPBOX_PUBLIC_TOKEN=pk.eyJ1...</code><br>
                <a href="https://mapbox.com/" target="_blank" style="color:#EA580C;font-weight:700;font-size:0.8rem">Obtener token gratis →</a>
            </div>
            <div style="margin-top:1.5rem;display:flex;gap:1rem">
                @foreach($ambulancias->where('status','En Ruta') as $a)
                <div style="background:#431407;border-radius:8px;padding:0.5rem 0.8rem;border:1px solid #FDBA74">
                    <span style="color:#DC2626;font-weight:800;font-size:0.8rem">🚑 {{ $a->code }}</span>
                    <span style="color:#FDBA74;font-size:0.7rem;margin-left:0.5rem">{{ $a->origin }} → {{ $a->destination }}</span>
                </div>
                @endforeach
            </div>
        </div>
    `;
}

// ============================================================
// IoT SIMULADO - Actualizar cada 5 segundos
// ============================================================
setInterval(function() {
    @foreach($activasList as $a)
    fetch('/medico/ambulancias/{{ $a->id }}/iot')
        .then(r => r.json())
        .then(data => {
            const el = (id) => document.getElementById(id);
            if(el('speed-{{ $a->id }}')) el('speed-{{ $a->id }}').textContent = data.speed;
            if(el('fuel-{{ $a->id }}')) el('fuel-{{ $a->id }}').textContent = data.fuel;
            if(el('hr-{{ $a->id }}')) { el('hr-{{ $a->id }}').textContent = data.heart_rate; el('hr-{{ $a->id }}').style.color = data.heart_rate > 100 ? '#F87171' : '#4ADE80'; }
            if(el('o2-{{ $a->id }}')) { el('o2-{{ $a->id }}').textContent = data.oxygen; el('o2-{{ $a->id }}').style.color = data.oxygen < 90 ? '#F87171' : '#4ADE80'; }
            if(el('temp-{{ $a->id }}')) { el('temp-{{ $a->id }}').textContent = data.temperature; el('temp-{{ $a->id }}').style.color = data.temperature > 38 ? '#F87171' : '#4ADE80'; }
        })
        .catch(() => {});
    @endforeach
}, 5000);
</script>
@endsection
