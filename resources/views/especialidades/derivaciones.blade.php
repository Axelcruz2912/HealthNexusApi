@extends('especialidades.layout')

@section('content')
<div style="padding:1.5rem">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem">
        <h2 style="font-weight:900;color:#9A3412"><i class="fas fa-share-nodes" style="color:#EA580C"></i> Derivaciones</h2>
    </div>

    @if(session('success'))
    <div style="background:#DCFCE7;color:#16A34A;padding:0.8rem;border-radius:8px;margin-bottom:1rem;font-weight:700">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
    @endif

    <!-- MAPA DE DERIVACIONES Y TRASLADOS -->
    <div style="background:white;border-radius:16px;padding:1.5rem;box-shadow:0 2px 8px rgba(0,0,0,0.05);margin-bottom:1.5rem;border-top:4px solid #EA580C">
        <h3 style="font-weight:900;color:#9A3412;margin-bottom:1rem"><i class="fas fa-map-marked-alt" style="color:#EA580C"></i> Mapa de Derivaciones y Traslados</h3>
        <div id="mapa-derivaciones" style="width:100%;height:350px;border-radius:12px;overflow:hidden;border:2px solid #FDBA74"></div>
    </div>

    <!-- CREAR DERIVACION -->
    <div style="background:white;border-radius:16px;padding:1.5rem;box-shadow:0 2px 8px rgba(0,0,0,0.05);margin-bottom:1.5rem;border-top:4px solid #EA580C">
        <h3 style="font-weight:900;color:#9A3412;margin-bottom:1rem"><i class="fas fa-paper-plane" style="color:#EA580C"></i> Nueva Derivacion</h3>
        <form method="POST" action="{{ url('/medico/derivaciones/crear') }}">
            @csrf
            <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:1rem;margin-bottom:1rem">
                <div>
                    <label style="font-weight:800;color:#9A3412;font-size:0.8rem;display:block;margin-bottom:0.3rem">Paciente</label>
                    <select name="triage_id" required style="width:100%;padding:0.5rem;border:2px solid #FDBA74;border-radius:8px;font-size:0.85rem">
                        <option value="">Seleccionar paciente...</option>
                        @foreach($pacientesDerivar as $p)
                        <option value="{{ $p->id }}">{{ $p->patient_name }} - {{ $p->triage_level }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label style="font-weight:800;color:#9A3412;font-size:0.8rem;display:block;margin-bottom:0.3rem">Derivar a Medico</label>
                    <select name="to_doctor_id" style="width:100%;padding:0.5rem;border:2px solid #FDBA74;border-radius:8px;font-size:0.85rem">
                        <option value="">Seleccionar medico...</option>
                        @foreach($todosMedicos as $doc)
                        <option value="{{ $doc->id }}">{{ $doc->name }} ({{ $doc->role }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label style="font-weight:800;color:#9A3412;font-size:0.8rem;display:block;margin-bottom:0.3rem">O Especialidad</label>
                    <select name="specialty_id" style="width:100%;padding:0.5rem;border:2px solid #FDBA74;border-radius:8px;font-size:0.85rem">
                        <option value="">Seleccionar especialidad...</option>
                        @foreach($specialties as $s)
                        <option value="{{ $s->id }}">{{ $s->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div style="display:grid;grid-template-columns:2fr 1fr;gap:1rem;margin-bottom:1rem">
                <div>
                    <label style="font-weight:800;color:#9A3412;font-size:0.8rem;display:block;margin-bottom:0.3rem">Motivo</label>
                    <textarea name="reason" required style="width:100%;padding:0.5rem;border:2px solid #FDBA74;border-radius:8px;font-size:0.85rem;min-height:60px" placeholder="Razon de la derivacion..."></textarea>
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
            <button type="submit" style="padding:0.5rem 1.5rem;background:#EA580C;color:white;border:none;border-radius:8px;font-weight:800;cursor:pointer"><i class="fas fa-paper-plane"></i> Enviar Derivacion</button>
        </form>
    </div>

    <!-- DERIVACIONES RECIBIDAS -->
    <div style="background:white;border-radius:16px;padding:1.5rem;box-shadow:0 2px 8px rgba(0,0,0,0.05);margin-bottom:1.5rem;border-top:4px solid #DC2626">
        <h3 style="font-weight:900;color:#DC2626;margin-bottom:1rem"><i class="fas fa-inbox" style="color:#DC2626"></i> Derivaciones Recibidas ({{ $derivacionesRecibidas->count() }})</h3>
        @if($derivacionesRecibidas->count() > 0)
        <table style="width:100%;border-collapse:collapse;font-size:0.85rem">
            <thead><tr style="background:#FEF2F2"><th style="padding:0.6rem;text-align:left;color:#991B1B">De</th><th style="padding:0.6rem;color:#991B1B">Paciente</th><th style="padding:0.6rem;color:#991B1B">Motivo</th><th style="padding:0.6rem;color:#991B1B">Prioridad</th><th style="padding:0.6rem;color:#991B1B">Acciones</th></tr></thead>
            <tbody>
            @foreach($derivacionesRecibidas as $d)
            @php $pColor = $d->priority == 'Critica' ? '#DC2626' : ($d->priority == 'Urgente' ? '#EA580C' : '#D97706'); @endphp
            <tr style="border-bottom:1px solid #FEE2E2">
                <td style="padding:0.5rem;font-weight:700;color:#7F1D1D">{{ isset($d->fromDoctor) ? $d->fromDoctor->name : 'Medico' }}</td>
                <td style="padding:0.5rem;color:#9A3412">{{ isset($d->triage) ? $d->triage->patient_name : 'Paciente' }}</td>
                <td style="padding:0.5rem;color:#A8A29E">{{ Str::limit($d->reason, 30) }}</td>
                <td style="padding:0.5rem"><span style="background:{{ $pColor }}20;color:{{ $pColor }};padding:0.15rem 0.5rem;border-radius:4px;font-size:0.75rem;font-weight:800">{{ $d->priority }}</span></td>
                <td style="padding:0.5rem">
                    <div style="display:flex;gap:0.3rem;flex-wrap:wrap">
                        <form method="POST" action="{{ url('/medico/derivaciones/' . $d->id . '/aceptar') }}">
                            @csrf
                            <button type="submit" style="padding:0.2rem 0.5rem;background:#EA580C;color:white;border:none;border-radius:4px;font-size:0.7rem;cursor:pointer"><i class="fas fa-check"></i> Aceptar</button>
                        </form>
                        <form method="POST" action="{{ url('/medico/derivaciones/' . $d->id . '/rechazar') }}">
                            @csrf
                            <button type="submit" style="padding:0.2rem 0.5rem;background:#DC2626;color:white;border:none;border-radius:4px;font-size:0.7rem;cursor:pointer"><i class="fas fa-times"></i> Rechazar</button>
                        </form>
                        <form method="POST" action="{{ url('/medico/derivaciones/' . $d->id . '/reagendar') }}">
                            @csrf
                            <select name="nuevo_doctor_id" style="padding:0.2rem;border:1px solid #FDBA74;border-radius:4px;font-size:0.65rem" onchange="if(this.value) this.form.submit()">
                                <option value="">Reagendar a...</option>
                                @foreach($todosMedicos as $doc)
                                <option value="{{ $doc->id }}">{{ $doc->name }}</option>
                                @endforeach
                            </select>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>
        @else
        <p style="text-align:center;color:#EA580C;padding:1.5rem;font-weight:700"><i class="fas fa-inbox"></i> Sin derivaciones pendientes</p>
        @endif
    </div>

    <!-- DERIVACIONES ENVIADAS -->
    <div style="background:white;border-radius:16px;padding:1.5rem;box-shadow:0 2px 8px rgba(0,0,0,0.05);margin-bottom:1.5rem;border-top:4px solid #F97316">
        <h3 style="font-weight:900;color:#EA580C;margin-bottom:1rem"><i class="fas fa-paper-plane" style="color:#F97316"></i> Derivaciones Enviadas ({{ $derivacionesEnviadas->count() }})</h3>
        @if($derivacionesEnviadas->count() > 0)
        <table style="width:100%;border-collapse:collapse;font-size:0.85rem">
            <thead><tr style="background:#FFEDD5"><th style="padding:0.6rem;text-align:left;color:#9A3412">Para</th><th style="padding:0.6rem;color:#9A3412">Paciente</th><th style="padding:0.6rem;color:#9A3412">Motivo</th><th style="padding:0.6rem;color:#9A3412">Estado</th></tr></thead>
            <tbody>
            @foreach($derivacionesEnviadas as $d)
            @php $stColor = $d->status == 'Aceptada' ? '#16A34A' : '#EA580C'; @endphp
            <tr style="border-bottom:1px solid #FFEDD5">
                <td style="padding:0.5rem;font-weight:700;color:#7F1D1D">{{ isset($d->toDoctor) ? $d->toDoctor->name : 'Por asignar' }}</td>
                <td style="padding:0.5rem;color:#9A3412">{{ isset($d->triage) ? $d->triage->patient_name : 'Paciente' }}</td>
                <td style="padding:0.5rem;color:#A8A29E">{{ Str::limit($d->reason, 30) }}</td>
                <td style="padding:0.5rem"><span style="background:{{ $stColor }}20;color:{{ $stColor }};padding:0.15rem 0.5rem;border-radius:4px;font-size:0.75rem;font-weight:800">{{ $d->status }}</span></td>
            </tr>
            @endforeach
            </tbody>
        </table>
        @else
        <p style="text-align:center;color:#D97706;padding:1.5rem;font-weight:700"><i class="fas fa-paper-plane"></i> Sin derivaciones enviadas</p>
        @endif
    </div>

    <!-- HISTORIAL -->
    <div style="background:white;border-radius:16px;padding:1.5rem;box-shadow:0 2px 8px rgba(0,0,0,0.05);border-top:4px solid #9A3412">
        <h3 style="font-weight:900;color:#7F1D1D;margin-bottom:1rem"><i class="fas fa-history" style="color:#9A3412"></i> Historial de Derivaciones</h3>
        @if($derivacionesHistorial->count() > 0)
        <table style="width:100%;border-collapse:collapse;font-size:0.82rem">
            <thead><tr style="background:#FFF7ED"><th style="padding:0.5rem;text-align:left;color:#9A3412">Fecha</th><th style="padding:0.5rem;color:#9A3412">Paciente</th><th style="padding:0.5rem;color:#9A3412">De/Para</th><th style="padding:0.5rem;color:#9A3412">Estado</th></tr></thead>
            <tbody>
            @foreach($derivacionesHistorial as $d)
            @php $hColor = $d->status == 'Aceptada' ? '#16A34A' : ($d->status == 'Rechazada' ? '#DC2626' : '#EA580C'); @endphp
            <tr style="border-bottom:1px solid #FFF0E0">
                <td style="padding:0.5rem;font-size:0.75rem;color:#9A3412">{{ $d->updated_at->format('d/m H:i') }}</td>
                <td style="padding:0.5rem;color:#7F1D1D">{{ isset($d->triage) ? $d->triage->patient_name : 'N/A' }}</td>
                <td style="padding:0.5rem;font-size:0.75rem;color:#A8A29E">{{ isset($d->fromDoctor) ? $d->fromDoctor->name : '?' }} -> {{ isset($d->toDoctor) ? $d->toDoctor->name : '?' }}</td>
                <td style="padding:0.5rem"><span style="color:{{ $hColor }};font-weight:800;font-size:0.75rem">{{ $d->status }}</span></td>
            </tr>
            @endforeach
            </tbody>
        </table>
        @else
        <p style="text-align:center;color:#A8A29E;padding:1.5rem;font-weight:700">Sin historial</p>
        @endif
    </div>
</div>

<script>
// ============================================================
// MAPA DE DERIVACIONES CON MAPBOX
// ============================================================
const MAPBOX_TOKEN = '{{ env("MAPBOX_PUBLIC_TOKEN") }}';

// Datos de derivaciones para el mapa
const derivacionesData = [
    @foreach($derivacionesEnviadas as $d)
    {
        id: {{ $d->id }},
        paciente: "{{ isset($d->triage) ? $d->triage->patient_name : 'N/A' }}",
        de: "{{ isset($d->fromDoctor) ? $d->fromDoctor->name : 'Desconocido' }}",
        para: "{{ isset($d->toDoctor) ? $d->toDoctor->name : 'Por asignar' }}",
        estado: "{{ $d->status }}",
        prioridad: "{{ $d->priority }}",
        motivo: "{{ Str::limit($d->reason, 40) }}",
    },
    @endforeach
];

// Ambulancias activas
const ambulanciasData = [
    @php $ambActivas = App\Models\Ambulance::where('status','En Ruta')->get(); @endphp
    @foreach($ambActivas as $a)
    {
        code: "{{ $a->code }}",
        lat: {{ $a->latitude ?? 19.4326 }},
        lng: {{ $a->longitude ?? -99.1332 }},
        origen: "{{ $a->origin }}",
        destino: "{{ $a->destination }}",
        prioridad: "{{ $a->priority }}",
    },
    @endforeach
];

if (MAPBOX_TOKEN && MAPBOX_TOKEN !== 'pk.tu-mapbox-token-aqui' && MAPBOX_TOKEN.length > 20) {
    const link = document.createElement('link');
    link.href = 'https://api.mapbox.com/mapbox-gl-js/v3.3.0/mapbox-gl.css';
    link.rel = 'stylesheet';
    document.head.appendChild(link);

    const script = document.createElement('script');
    script.src = 'https://api.mapbox.com/mapbox-gl-js/v3.3.0/mapbox-gl.js';
    script.onload = function() {
        mapboxgl.accessToken = MAPBOX_TOKEN;
        const map = new mapboxgl.Map({
            container: 'mapa-derivaciones',
            style: 'mapbox://styles/mapbox/dark-v11',
            center: [-99.1332, 19.4326],
            zoom: 11.5
        });
        map.addControl(new mapboxgl.NavigationControl());

        // Hospital central
        new mapboxgl.Marker({ color: '#F59E0B' })
            .setLngLat([-99.1332, 19.4326])
            .setPopup(new mapboxgl.Popup().setHTML('<div style="padding:8px"><strong style="color:#F59E0B">🏥 Hospital Central</strong><br><span style="font-size:12px">HealthNexus</span></div>'))
            .addTo(map);

        // Marcadores de derivaciones activas
        derivacionesData.forEach((d, i) => {
            const offset = (i - derivacionesData.length/2) * 0.008;
            const pColor = d.prioridad === 'Critica' ? '#DC2626' : (d.prioridad === 'Urgente' ? '#EA580C' : '#F97316');
            const stColor = d.estado === 'Aceptada' ? '#16A34A' : '#EA580C';

            // Origen de la derivacion
            new mapboxgl.Marker({ color: stColor })
                .setLngLat([-99.1332 + offset - 0.015, 19.4326 + offset])
                .setPopup(new mapboxgl.Popup().setHTML(
                    '<div style="padding:8px;font-family:system-ui">' +
                    '<strong style="color:#EA580C">📋 Derivacion #' + d.id + '</strong><br>' +
                    '<span style="font-size:12px">Paciente: ' + d.paciente + '</span><br>' +
                    '<span style="font-size:11px;color:#666">De: ' + d.de + '</span><br>' +
                    '<span style="font-size:11px;color:' + pColor + '">Prioridad: ' + d.prioridad + '</span><br>' +
                    '<span style="font-size:11px;color:' + stColor + '">Estado: ' + d.estado + '</span>' +
                    '</div>'
                ))
                .addTo(map);

            // Destino de la derivacion
            new mapboxgl.Marker({ color: pColor })
                .setLngLat([-99.1332 + offset + 0.015, 19.4326 + offset])
                .setPopup(new mapboxgl.Popup().setHTML(
                    '<div style="padding:8px;font-family:system-ui">' +
                    '<strong style="color:#EA580C">👨‍⚕️ ' + d.para + '</strong><br>' +
                    '<span style="font-size:12px">Derivacion #' + d.id + '</span>' +
                    '</div>'
                ))
                .addTo(map);
        });

        // Ambulancias en ruta
        ambulanciasData.forEach(a => {
            const color = a.prioridad === 'Critica' ? '#DC2626' : '#EA580C';
            new mapboxgl.Marker({ color: color })
                .setLngLat([a.lng, a.lat])
                .setPopup(new mapboxgl.Popup().setHTML(
                    '<div style="padding:8px;font-family:system-ui">' +
                    '<strong style="color:' + color + '">🚑 ' + a.code + '</strong><br>' +
                    '<span style="font-size:12px">' + a.origen + ' → ' + a.destino + '</span><br>' +
                    '<span style="font-size:11px;color:' + color + '">' + a.prioridad + '</span>' +
                    '</div>'
                ))
                .addTo(map);
        });
    };
    document.head.appendChild(script);
} else {
    // Fallback sin token
    document.getElementById('mapa-derivaciones').innerHTML = `
        <div style="background:linear-gradient(135deg,#1C1917,#431407);width:100%;height:100%;display:flex;flex-direction:column;align-items:center;justify-content:center;color:white;border-radius:12px">
            <i class="fas fa-map-marked-alt" style="font-size:3rem;color:#FDBA74;margin-bottom:1rem"></i>
            <div style="font-weight:900;font-size:1.1rem;color:#FDBA74;margin-bottom:0.5rem">Mapa de Derivaciones</div>
            <div style="font-size:0.85rem;color:#A8A29E;text-align:center;max-width:400px">
                Configura MAPBOX_PUBLIC_TOKEN en .env para ver el mapa interactivo<br>
                <a href="https://mapbox.com/" target="_blank" style="color:#EA580C;font-weight:700">Obtener token gratis →</a>
            </div>
            <div style="margin-top:1.5rem;display:flex;gap:1rem;flex-wrap:wrap;justify-content:center">
                @foreach($derivacionesEnviadas as $d)
                <div style="background:#431407;border-radius:8px;padding:0.5rem 0.8rem;border:1px solid #FDBA74">
                    <span style="color:#EA580C;font-weight:800;font-size:0.8rem">📋 #{{ $d->id }}</span>
                    <span style="color:#FDBA74;font-size:0.7rem;margin-left:0.5rem">{{ isset($d->triage) ? $d->triage->patient_name : '?' }} → {{ isset($d->toDoctor) ? $d->toDoctor->name : '?' }}</span>
                </div>
                @endforeach
                @foreach($ambActivas as $a)
                <div style="background:#431407;border-radius:8px;padding:0.5rem 0.8rem;border:1px solid {{ $a->priority == 'Critica' ? '#DC2626' : '#FDBA74' }}">
                    <span style="color:{{ $a->priority == 'Critica' ? '#DC2626' : '#EA580C' }};font-weight:800;font-size:0.8rem">🚑 {{ $a->code }}</span>
                    <span style="color:#FDBA74;font-size:0.7rem;margin-left:0.5rem">{{ $a->origin }} → {{ $a->destination }}</span>
                </div>
                @endforeach
            </div>
        </div>
    `;
}
</script>
@endsection
