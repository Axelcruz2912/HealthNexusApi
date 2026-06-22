@extends('medico.layout')
@section('title', 'Derivaciones')
@section('nav-derivaciones', 'active')
@section('content')
<style>
.hosp-card { border:2px solid #E2E8F0; border-radius:12px; padding:1rem; cursor:pointer; transition:all 0.3s; }
.hosp-card:hover { border-color:#3B82F6; background:#EFF6FF; transform:translateY(-2px); }
.hosp-card.selected { border-color:#3B82F6; background:#DBEAFE; box-shadow:0 4px 15px rgba(59,130,246,0.3); }
.map-container { border-radius:16px; overflow:hidden; position:relative; background:#E0E7FF; height:320px; }
.map-pin { position:absolute; transform:translate(-50%,-50%); cursor:pointer; transition:all 0.3s; }
.map-pin:hover { transform:translate(-50%,-50%) scale(1.2); }
.map-origin { width:20px; height:20px; background:#3B82F6; border:3px solid white; border-radius:50%; box-shadow:0 2px 8px rgba(59,130,246,0.5); }
.map-dest { width:16px; height:16px; background:#EF4444; border:2px solid white; border-radius:50%; box-shadow:0 2px 8px rgba(239,68,68,0.5); }
.last-deriv { background:linear-gradient(135deg,#FEF3C7,#FDE68A); border:2px solid #F59E0B; border-radius:16px; padding:1.5rem; margin-bottom:1.5rem; }
.deriv-row { transition:all 0.2s; } .deriv-row:hover { background:#F8FAFC; }
.deriv-row.last { background:#FEF3C7; border-left:4px solid #F59E0B; }
.triage-rojo { color:#DC2626; font-weight:800; } .triage-amarillo { color:#D97706; font-weight:800; } .triage-verde { color:#059669; font-weight:800; }
</style>

<div style="background:linear-gradient(135deg,#1E293B,#334155); padding:2rem; border-radius:16px; margin-bottom:1.5rem; color:white;">
    <div style="display:flex; justify-content:space-between; align-items:center;">
        <div>
            <h3 style="font-weight:900; margin:0;"><i class="fas fa-ambulance"></i> Derivación de Pacientes</h3>
            <p style="opacity:0.7; margin:0.5rem 0 0;"><i class="fas fa-exclamation-circle"></i> Sin recursos/camas? Derive a hospitales cercanos</p>
        </div>
        <div style="text-align:right;">
            <div style="font-size:2rem; font-weight:900;">{{ $derivaciones->total() }}</div>
            <div style="font-size:0.8rem; opacity:0.7;">Total derivaciones</div>
        </div>
    </div>
</div>

<!-- ÚLTIMA DERIVACIÓN -->
@if($ultimaDerivacion)
<div class="last-deriv">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:0.75rem;">
        <h5 style="font-weight:900; margin:0;"><i class="fas fa-clock" style="color:#D97706;"></i> Última Derivación</h5>
        <span style="background:#D97706; color:white; padding:0.2rem 0.8rem; border-radius:6px; font-weight:700; font-size:0.75rem;">RECIENTE</span>
    </div>
    <div style="display:grid; grid-template-columns:repeat(5,1fr); gap:1rem; align-items:center;">
        <div>
            <div style="font-size:0.7rem; color:#92400E; font-weight:700;">PACIENTE</div>
            <div style="font-weight:900; font-size:1.1rem;">{{ $ultimaDerivacion->patient_name ?? 'N/A' }}</div>
        </div>
        <div>
            <div style="font-size:0.7rem; color:#92400E; font-weight:700;">EDAD / TRIAGE</div>
            <div style="font-weight:800;">{{ $ultimaDerivacion->age ?? '?' }} años / <span class="triage-{{ strtolower($ultimaDerivacion->triage_level ?? 'verde') }}">{{ $ultimaDerivacion->triage_level ?? 'N/A' }}</span></div>
        </div>
        <div>
            <div style="font-size:0.7rem; color:#92400E; font-weight:700;">HOSPITAL DESTINO</div>
            <div style="font-weight:800; color:#DC2626;"><i class="fas fa-hospital"></i> {{ $ultimaDerivacion->hospital_destino }}</div>
        </div>
        <div>
            <div style="font-size:0.7rem; color:#92400E; font-weight:700;">MOTIVO</div>
            <div style="font-weight:700;">{{ Str::limit($ultimaDerivacion->motivo, 30) }}</div>
        </div>
        <div style="text-align:center;">
            <a href="{{ route('medico.derivacion.pdf', $ultimaDerivacion->id) }}" style="background:#DC2626; color:white; padding:0.5rem 1rem; border-radius:8px; text-decoration:none; font-weight:800; font-size:0.85rem; display:inline-block;">
                <i class="fas fa-file-pdf"></i> Pase de Salida
            </a>
        </div>
    </div>
</div>
@endif

<div style="display:grid; grid-template-columns:1fr 1fr; gap:1.5rem; margin-bottom:1.5rem;">
    <!-- MAPA -->
    <div>
        <div class="map-container">
            <div style="position:absolute; inset:0; background:linear-gradient(135deg,#DBEAFE,#E0E7FF); display:flex; align-items:center; justify-content:center;">
                <div style="text-align:center; opacity:0.3;">
                    <i class="fas fa-map-marked-alt" style="font-size:4rem;"></i>
                    <p style="font-weight:800;">CDMX - Zona Hospitalaria</p>
                </div>
            </div>
            <div style="position:absolute; top:50%; left:0; right:0; height:2px; background:#94A3B8; opacity:0.3;"></div>
            <div style="position:absolute; top:0; bottom:0; left:50%; width:2px; background:#94A3B8; opacity:0.3;"></div>
            
            <div class="map-pin map-origin" style="top:50%; left:50%;">
                <div style="position:absolute; top:-28px; left:50%; transform:translateX(-50%); white-space:nowrap; background:#3B82F6; color:white; padding:2px 8px; border-radius:4px; font-size:0.65rem; font-weight:800;">HealthNexus Hospital</div>
            </div>
            <div class="map-pin map-dest" onclick="selectHospByMap('Hospital General de México')" style="top:30%; left:70%;">
                <div style="position:absolute; top:-22px; left:50%; transform:translateX(-50%); white-space:nowrap; background:#EF4444; color:white; padding:2px 6px; border-radius:4px; font-size:0.6rem; font-weight:700;">Gral. 2.3km</div>
            </div>
            <div class="map-pin map-dest" onclick="selectHospByMap('Centro Médico Nacional')" style="top:25%; left:35%;">
                <div style="position:absolute; top:-22px; left:50%; transform:translateX(-50%); white-space:nowrap; background:#EF4444; color:white; padding:2px 6px; border-radius:4px; font-size:0.6rem; font-weight:700;">CMN 3.1km</div>
            </div>
            <div class="map-pin map-dest" onclick="selectHospByMap('Hospital Infantil de México')" style="top:65%; left:75%;">
                <div style="position:absolute; top:-22px; left:50%; transform:translateX(-50%); white-space:nowrap; background:#EF4444; color:white; padding:2px 6px; border-radius:4px; font-size:0.6rem; font-weight:700;">Infantil 4.5km</div>
            </div>
            <div class="map-pin map-dest" onclick="selectHospByMap('Hospital Ángeles Pedregal')" style="top:72%; left:40%;">
                <div style="position:absolute; top:-22px; left:50%; transform:translateX(-50%); white-space:nowrap; background:#EF4444; color:white; padding:2px 6px; border-radius:4px; font-size:0.6rem; font-weight:700;">Ángeles 1.8km</div>
            </div>
            <div class="map-pin map-dest" onclick="selectHospByMap('Hospital Regional de Alta Especialidad')" style="top:40%; left:20%;">
                <div style="position:absolute; top:-22px; left:50%; transform:translateX(-50%); white-space:nowrap; background:#EF4444; color:white; padding:2px 6px; border-radius:4px; font-size:0.6rem; font-weight:700;">Regional 5.2km</div>
            </div>
        </div>
    </div>
    
    <!-- FORMULARIO -->
    <div style="background:white; border-radius:16px; padding:1.5rem; box-shadow:0 2px 10px rgba(0,0,0,0.04);">
        <h5 style="font-weight:800; margin-bottom:1rem;"><i class="fas fa-hospital"></i> Generar Pase de Salida</h5>
        <form method="POST" action="{{ route('medico.derivarPaciente', 0) }}" id="formDerivar">
            @csrf
            <div style="margin-bottom:1rem;">
                <label style="font-weight:700; font-size:0.85rem;">Paciente</label>
                <select name="paciente_id" id="selPac" class="form-select form-select-sm" required onchange="updateFormAction()">
                    <option value="">Seleccionar paciente...</option>
                    @foreach($pacientes as $p)
                    <option value="{{ $p->id }}">{{ $p->patient_name }} - {{ $p->symptoms }} (Triage: {{ $p->triage_level }})</option>
                    @endforeach
                </select>
            </div>
            <div style="margin-bottom:1rem;">
                <label style="font-weight:700; font-size:0.85rem; margin-bottom:0.5rem;">Hospital Destino</label>
                <div style="display:grid; gap:0.5rem;">
                    @foreach($hospitales as $key => $h)
                    <div class="hosp-card" onclick="selectHosp(this, '{{ $key }}')">
                        <div style="display:flex; justify-content:space-between; align-items:center;">
                            <span style="font-weight:800; font-size:0.85rem;"><i class="fas fa-hospital-alt" style="color:#3B82F6;"></i> {{ $h }}</span>
                            <i class="fas fa-map-marker-alt" style="color:#EF4444;"></i>
                        </div>
                    </div>
                    @endforeach
                </div>
                <input type="hidden" name="hospital_destino" id="hospDest" required>
            </div>
            <div style="margin-bottom:1rem;">
                <label style="font-weight:700; font-size:0.85rem;">Motivo</label>
                <textarea name="motivo" class="form-control form-control-sm" rows="3" required placeholder="Falta de recursos, camas UCI, especialidad no disponible..."></textarea>
            </div>
            <button type="submit" style="width:100%; padding:0.85rem; background:linear-gradient(135deg,#EF4444,#DC2626); color:white; border:none; border-radius:12px; font-weight:900; cursor:pointer; font-size:1rem;">
                <i class="fas fa-ambulance"></i> Derivar y Generar Pase de Salida
            </button>
        </form>
    </div>
</div>

<!-- HISTORIAL COMPLETO -->
<div style="background:white; border-radius:16px; padding:1.5rem; box-shadow:0 2px 10px rgba(0,0,0,0.04);">
    <h5 style="font-weight:800; margin-bottom:1rem;"><i class="fas fa-history"></i> Historial de Derivaciones</h5>
    <table style="width:100%; border-collapse:collapse; font-size:0.85rem;">
        <thead>
            <tr style="background:#F1F5F9;">
                <th style="padding:0.75rem; text-align:left; border-bottom:2px solid #E2E8F0;">#</th>
                <th style="padding:0.75rem; text-align:left; border-bottom:2px solid #E2E8F0;">Paciente</th>
                <th style="padding:0.75rem; text-align:left; border-bottom:2px solid #E2E8F0;">Triage</th>
                <th style="padding:0.75rem; text-align:left; border-bottom:2px solid #E2E8F0;">Hospital Destino</th>
                <th style="padding:0.75rem; text-align:left; border-bottom:2px solid #E2E8F0;">Motivo</th>
                <th style="padding:0.75rem; text-align:left; border-bottom:2px solid #E2E8F0;">Fecha</th>
                <th style="padding:0.75rem; text-align:center; border-bottom:2px solid #E2E8F0;">Pase</th>
            </tr>
        </thead>
        <tbody>
            @foreach($derivaciones as $i => $d)
            <tr class="deriv-row {{ $i === 0 ? 'last' : '' }}" style="border-bottom:1px solid #F1F5F9;">
                <td style="padding:0.75rem; font-weight:700;">
                    {{ $derivaciones->total() - ($derivaciones->currentPage() - 1) * $derivaciones->perPage() - $i }}
                    @if($i === 0)<span style="background:#F59E0B; color:white; padding:1px 5px; border-radius:3px; font-size:0.6rem; margin-left:4px;">ÚLTIMO</span>@endif
                </td>
                <td style="padding:0.75rem; font-weight:800;">{{ $d->patient_name ?? 'Desconocido' }}</td>
                <td style="padding:0.75rem;">
                    <span class="triage-{{ strtolower($d->triage_level ?? 'verde') }}">{{ $d->triage_level ?? 'N/A' }}</span>
                </td>
                <td style="padding:0.75rem; font-weight:700;"><i class="fas fa-hospital" style="color:#3B82F6;"></i> {{ $d->hospital_destino }}</td>
                <td style="padding:0.75rem;">{{ Str::limit($d->motivo, 35) }}</td>
                <td style="padding:0.75rem; font-size:0.8rem;">{{ \Carbon\Carbon::parse($d->created_at)->format('d/m/Y H:i') }}</td>
                <td style="padding:0.75rem; text-align:center;">
                    <a href="{{ route('medico.derivacion.pdf', $d->id) }}" style="background:#EF4444; color:white; padding:0.3rem 0.8rem; border-radius:6px; text-decoration:none; font-weight:700; font-size:0.75rem;">
                        <i class="fas fa-file-pdf"></i> Pase
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div style="margin-top:1rem;">{{ $derivaciones->withQueryString()->links() }}</div>
</div>

<script>
function selectHosp(el, val) {
    document.querySelectorAll('.hosp-card').forEach(c => c.classList.remove('selected'));
    el.classList.add('selected');
    document.getElementById('hospDest').value = val;
}
function selectHospByMap(name) {
    document.getElementById('hospDest').value = name;
    document.querySelectorAll('.hosp-card').forEach(c => {
        c.classList.toggle('selected', c.textContent.includes(name));
    });
}
function updateFormAction() {
    let id = document.getElementById('selPac').value;
    document.getElementById('formDerivar').action = '{{ url("/medico/pacientes") }}/' + id + '/derivar';
}
</script>
@endsection
