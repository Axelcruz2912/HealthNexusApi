@php $isAdmin = auth()->user()->role === 'SuperAdmin' || auth()->user()->role === 'Administrador'; @endphp
@extends($isAdmin ? 'superadmin.layout' : 'enfermeria.layout')
@section('title', 'Centro de Control de Urgencias')
@section($isAdmin ? 'nav-urgencias' : 'nav-triage', 'active')

@section('content')

    @if(session('status'))
    <div style="background:#ECFDF5; border:1px solid #A7F3D0; color:#065F46; padding:1rem; border-radius:8px; margin-bottom:1rem; font-weight:600; display:flex; align-items:center; gap:0.5rem;">
        <span style="font-size:1.2rem;">&#10003;</span> {{ session('status') }}
    </div>
    @endif

    @if(session('etl_error'))
    <div style="background:#FEF2F2; border:1px solid #FECACA; color:#991B1B; padding:1rem; border-radius:8px; margin-bottom:1rem; font-weight:600; display:flex; align-items:center; gap:0.5rem;">
        <span style="font-size:1.2rem;">&#9888;</span> {{ session('etl_error') }}
    </div>
    @endif


<!-- PANEL BIG DATA EN VIVO (100% MONGODB ATLAS) -->
<div style="background: linear-gradient(135deg, #0D1117 0%, #161B22 100%); border-radius: 8px; padding: 1.5rem; margin-bottom: 2rem; border: 1px solid #30363D; display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem; color: white; font-family: system-ui;">
    <div style="text-align: center; border-right: 1px solid #30363D; padding-right: 1rem;">
        <div style="font-size: 0.8rem; color: #8B949E; margin-bottom: 0.5rem;">INGRESOS HOY (ATLAS)</div>
        <div style="font-size: 1.8rem; font-weight: 800; color: #3FB950;">{{ $bdTotalDocs }}</div>
        <div style="font-size: 0.7rem; color: #8B949E;">Documentos en la nube</div>
    </div>
    <div style="text-align: center; border-right: 1px solid #30363D; padding-right: 1rem;">
        <div style="font-size: 0.8rem; color: #8B949E; margin-bottom: 0.5rem;">EN ESPERA AHORA</div>
        <div style="font-size: 1.8rem; font-weight: 800; color: #58A6FF;">{{ $bdTodayPatients }}</div>
        <div style="font-size: 0.7rem; color: #8B949E;">Pendientes de atención</div>
    </div>
    <div style="text-align: center; border-right: 1px solid #30363D; padding-right: 1rem;">
        <div style="font-size: 0.8rem; color: #8B949E; margin-bottom: 0.5rem;">FC PROMEDIO HOY</div>
        <div style="font-size: 1.8rem; font-weight: 800; color: #D2A8FF;">{{ $bdAvgFc }} bpm</div>
        <div style="font-size: 0.7rem; color: #8B949E;">Frecuencia Cardiaca Media</div>
    </div>
    <div style="text-align: center; display: flex; align-items: center; justify-content: center;">
        <div>
            <div style="font-size: 1.8rem; font-weight: 800; color: #EF4444;">{{ $bdRojoHoy }}</div>
            <div style="font-size: 0.7rem; color: #EF4444; font-weight: 700;">Triage Rojo Hoy</div>
        </div>
    </div>
</div>
    <div style="text-align: center; border-right: 1px solid #30363D; padding-right: 1rem;">
        <div style="font-size: 0.8rem; color: #8B949E; margin-bottom: 0.5rem;">PACIENTES HOY (ATLAS)</div>
        <div style="font-size: 1.5rem; font-weight: 800; color: #58A6FF;">{{ $bdTodayPatients }}</div>
        <div style="font-size: 0.7rem; color: #8B949E;">Registrados en la nube</div>
    </div>
    <div style="text-align: center; border-right: 1px solid #30363D; padding-right: 1rem;">
        <div style="font-size: 0.8rem; color: #8B949E; margin-bottom: 0.5rem;">FC PROMEDIO HOY</div>
        <div style="font-size: 1.5rem; font-weight: 800; color: #D2A8FF;">{{ $bdAvgFc }} bpm</div>
        <div style="font-size: 0.7rem; color: #8B949E;">Frecuencia Cardiaca Media</div>
    </div>
    <div style="text-align: center; display: flex; align-items: center; justify-content: center;">
        <div>
            <div style="font-size: 2rem; color: #3FB950;">&#10003;</div>
            <div style="font-size: 0.7rem; color: #3FB950; font-weight: 700;">ETL Sincronizado</div>
        </div>
    </div>
</div>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
    <div>
        <h3 style="font-weight: 800; color: #1E1A17;">Sala de Urgencias - Triage Manchester</h3>
        <p style="color: #736860; font-size: 0.85rem;">Clasificación, asignación y derivación en tiempo real.</p>
    </div>
    <button onclick="document.getElementById('modal-add-patient').style.display='flex'" style="background: #C7291C; color: white; border: none; padding: 0.6rem 1.2rem; border-radius: 8px; font-weight: 700; cursor: pointer;">
        <a href="{{ auth()->user()->role === 'SuperAdmin' || auth()->user()->role === 'Administrador' ? route('superadmin.bigdata') : route('enfermeria.bigdata') }}" style="background: #2563EB; color: white; border: none; padding: 0.6rem 1.2rem; border-radius: 8px; font-weight: 700; cursor: pointer; text-decoration: none; margin-right: 10px;">
        <i class="fas fa-chart-bar"></i> Dashboard Big Data
    </a>
    <i class="fas fa-ambulance"></i> Ingreso de Urgencia
    </button>
</div>

<div style="display: grid; grid-template-columns: repeat(5, 1fr); gap: 1rem; margin-bottom: 2rem;">
    @php $colors = ['Rojo' => ['bg'=>'#FFE0DC','border'=>'#C7291C','txt'=>'#8C1A11'], 'Naranja'=>['bg'=>'#FFF5EB','border'=>'#FF8C42','txt'=>'#9a3412'], 'Amarillo'=>['bg'=>'#FFFCE8','border'=>'#F59E0B','txt'=>'#92400E'], 'Verde'=>['bg'=>'#EBF9F2','border'=>'#2D9E6A','txt'=>'#065F46'], 'Azul'=>['bg'=>'#EFF6FF','border'=>'#3B82F6','txt'=>'#1E3A8A']]; @endphp
    @foreach($colors as $color => $styles)
        @php $patients = $triages->where('triage_level', $color); @endphp
        <div style="background: {{ $styles['bg'] }}; border: 2px solid {{ $styles['border'] }}; border-radius: 12px; padding: 1rem;">
            <h4 style="font-weight: 800; color: {{ $styles['txt'] }}; text-align: center; margin-bottom: 1rem; text-transform: uppercase; border-bottom: 2px solid {{ $styles['border'] }};">{{ $color }}</h4>
            @foreach($patients as $patient)
            <div style="background: white; padding: 0.75rem; border-radius: 6px; margin-bottom: 0.5rem; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                <div style="font-weight: 700; font-size: 0.85rem; color: #1E1A17;">{{ $patient->patient_name }} <span style="font-size:0.7rem; color:#736860;">({{ $patient->age }}a)</span></div>
                <div style="font-size: 0.75rem; color: #736860; margin: 0.25rem 0;">{{ $patient->symptoms }}</div>
                
                @if($patient->vitals_ta)
                <div style="background:#F4F6F8; padding:0.3rem; border-radius:4px; font-size:0.7rem; font-family:monospace; margin-top:0.3rem; border-left: 3px solid #2D9E6A;">
                    TA: {{ $patient->vitals_ta }} | FC: {{ $patient->vitals_fc }} | Temp: {{ $patient->vitals_temp }} | SpO2: {{ $patient->vitals_spo2 }}%
                </div>
                @endif

                <div style="display:flex; gap:5px; margin-top:0.5rem;">
                    @if(!$patient->vitals_ta)
                    <button onclick="openVitalsModal({{ $patient->id }})" style="flex:1; background:#2D9E6A; color:white; border:none; padding:0.3rem; border-radius:4px; font-size:0.7rem; cursor:pointer;"><i class="fas fa-heartbeat"></i> Signos</button>
                    @endif
                    @if(!$patient->is_derived && $patient->status != 'Dado de Alta')
                    <button onclick="openDerivationModal({{ $patient->id }})" style="flex:1; background:#FF8C42; color:white; border:none; padding:0.3rem; border-radius:4px; font-size:0.7rem; cursor:pointer;"><i class="fas fa-route"></i> Derivar</button>
                    @endif
                    @if($patient->is_derived)
                    <a href="{{ route('superadmin.paseSalida', $patient->id) }}" target="_blank" style="flex:1; background:#1E1A17; color:white; text-decoration:none; text-align:center; padding:0.3rem; border-radius:4px; font-size:0.7rem;"><i class="fas fa-file-pdf"></i> Pase</a>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    @endforeach
</div>


<!-- Modal Ingreso de Urgencia -->
<div id="modal-add-patient" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.6); z-index:100; align-items:center; justify-content:center;">
    <div style="background:white; padding:2rem; border-radius:8px; width:500px; max-width:90%;">
        <h3 style="font-weight:700; margin-bottom:1rem; color:#1C1917;">Ingreso de Urgencia</h3>
        <form action="{{ url()->current() }}" method="POST">
            @csrf
            <div style="margin-bottom: 0.8rem;">
                <label style="font-size:0.85rem; font-weight:600; color:#374151;">Nombre del Paciente</label>
                <input type="text" name="patient_name" required style="width:100%; padding:0.5rem; border:1px solid #D1D5DB; border-radius:6px; margin-top:0.3rem;">
            </div>
            <div style="display:grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 0.8rem;">
                <div>
                    <label style="font-size:0.85rem; font-weight:600; color:#374151;">Nivel de Triage</label>
                    <select name="triage_level" required style="width:100%; padding:0.5rem; border:1px solid #D1D5DB; border-radius:6px; margin-top:0.3rem;">
                        <option value="Rojo">Rojo</option>
                        <option value="Naranja">Naranja</option>
                        <option value="Amarillo" selected>Amarillo</option>
                        <option value="Verde">Verde</option>
                        <option value="Azul">Azul</option>
                    </select>
                </div>
                <div>
                    <label style="font-size:0.85rem; font-weight:600; color:#374151;">Edad</label>
                    <input type="number" name="age" required style="width:100%; padding:0.5rem; border:1px solid #D1D5DB; border-radius:6px; margin-top:0.3rem;">
                </div>
            </div>
            <div style="margin-bottom: 1rem;">
                <label style="font-size:0.85rem; font-weight:600; color:#374151;">Motivo de Consulta</label>
                <textarea name="chief_complaint" rows="3" style="width:100%; padding:0.5rem; border:1px solid #D1D5DB; border-radius:6px; margin-top:0.3rem;"></textarea>
            </div>
            <div style="display:flex; gap:0.5rem; justify-content:flex-end;">
                <button type="button" onclick="document.getElementById('modal-add-patient').style.display='none'" style="background:#E5E7EB; color:#1C1917; border:none; padding:0.6rem 1.2rem; border-radius:6px; font-weight:600; cursor:pointer;">Cancelar</button>
                <button type="submit" style="background:#C7291C; color:white; border:none; padding:0.6rem 1.2rem; border-radius:6px; font-weight:600; cursor:pointer;">Registrar Paciente</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Tomar Signos Vitales -->
<div id="modal-vitals" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.6); z-index:100; align-items:center; justify-content:center;">
    <div style="background:white; padding:2rem; border-radius:12px; width:400px;">
        <h3 style="font-weight:800; margin-bottom:1rem;">Signos Vitales</h3>
        <form id="vitals-form" action="" method="POST">
            @csrf @method('PUT')
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px; margin-bottom:1rem;">
                <div><label style="font-size:0.8rem; font-weight:700; color:#736860;">TA (ej. 120/80)</label><input type="text" name="vitals_ta" required style="width:100%; padding:0.5rem; border:1px solid #E5E7EB; border-radius:6px;"></div>
                <div><label style="font-size:0.8rem; font-weight:700; color:#736860;">FC (lpm)</label><input type="text" name="vitals_fc" required style="width:100%; padding:0.5rem; border:1px solid #E5E7EB; border-radius:6px;"></div>
            </div>
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px; margin-bottom:1rem;">
                <div><label style="font-size:0.8rem; font-weight:700; color:#736860;">Temp (°C)</label><input type="text" name="vitals_temp" required style="width:100%; padding:0.5rem; border:1px solid #E5E7EB; border-radius:6px;"></div>
                <div><label style="font-size:0.8rem; font-weight:700; color:#736860;">SpO2 (%)</label><input type="text" name="vitals_spo2" required style="width:100%; padding:0.5rem; border:1px solid #E5E7EB; border-radius:6px;"></div>
            </div>
            <div style="margin-bottom:1rem;"><label style="font-size:0.8rem; font-weight:700; color:#736860;">Asignar a Área</label>
                <select name="assigned_area" style="width:100%; padding:0.5rem; border:1px solid #E5E7EB; border-radius:6px;">
                    <option value="Urgencias">Urgencias</option>
                    <option value="Médico General">Médico General</option>
                    <option value="UCI">UCI</option>
                    <option value="Quirófano">Quirófano</option>
                </select>
            </div>
            <button type="submit" style="width:100%; padding:0.6rem; background:#2D9E6A; color:white; border:none; border-radius:6px; font-weight:700; cursor:pointer;">Guardar Signos y Asignar</button>
        </form>
    </div>
</div>

<!-- Modal Derivación (Mapa) -->
<div id="modal-derive" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.6); z-index:100; align-items:center; justify-content:center;">
    <div style="background:white; padding:2rem; border-radius:12px; width:600px;">
        <h3 style="font-weight:800; margin-bottom:1rem; color:#C7291C;">Derivación de Paciente - Sin Recursos</h3>
        <p style="font-size:0.85rem; color:#736860; margin-bottom:1rem;">No hay camas/recursos disponibles. Se generará Pase de Salida para hospital cercano.</p>
        <div id="map" style="height: 250px; width: 100%; border-radius: 8px; margin-bottom: 1rem; border: 1px solid #E5E7EB;"></div>
        <form id="derive-form" action="" method="POST">
            @csrf @method('PUT')
            <div style="margin-bottom:1rem;"><label style="font-size:0.8rem; font-weight:700; color:#736860;">Hospital de Destino</label>
                <select name="derivation_hospital" required style="width:100%; padding:0.5rem; border:1px solid #E5E7EB; border-radius:6px;">
                    <option value="Hospital General de México">Hospital General de México (2.3 km)</option>
                    <option value="Instituto Nacional de Nutrición">Instituto Nacional de Nutrición (3.8 km)</option>
                    <option value="Hospital Angeles del Pedregal">Hospital Angeles del Pedregal (5.1 km)</option>
                </select>
            </div>
            <button type="submit" style="width:100%; padding:0.6rem; background:#C7291C; color:white; border:none; border-radius:6px; font-weight:700; cursor:pointer;">Derivar y Generar Pase de Salida</button>
        </form>
    </div>
</div>

<!-- Scripts de Modales y Mapa -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
<script>
function openVitalsModal(patientId) {
    document.getElementById('vitals-form').action = '/superadmin/urgencias/' + patientId + '/vitals';
    document.getElementById('modal-vitals').style.display = 'flex';
}
function openDerivationModal(patientId) {
    document.getElementById('derive-form').action = '/superadmin/urgencias/' + patientId + '/derive';
    document.getElementById('modal-derive').style.display = 'flex';
    setTimeout(function() {
        var map = L.map('map').setView([19.4326, -99.1332], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '© OpenStreetMap' }).addTo(map);
        L.marker([19.4326, -99.1332]).addTo(map).bindPopup('HealthNexus Hospital (Origen)').openPopup();
        L.marker([19.4108, -99.1296]).addTo(map).bindPopup('Hospital General de México');
        L.marker([19.3950, -99.1750]).addTo(map).bindPopup('Hospital Angeles del Pedregal');
    }, 200);
}
</script>
{{ $triages->withQueryString()->links() }}
@endsection
