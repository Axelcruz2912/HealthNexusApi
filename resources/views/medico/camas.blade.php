@extends('medico.layout')
@section('title', 'Mapa de Camas')
@section('nav-camas', 'active')
@section('content')
<style>
.bed-card { border-radius:12px; padding:1rem; text-align:center; transition:all 0.3s; cursor:pointer; border:2px solid transparent; }
.bed-card:hover { transform:translateY(-3px); box-shadow:0 8px 25px rgba(0,0,0,0.15); }
.bed-free { background:linear-gradient(135deg,#D1FAE5,#A7F3D0); border-color:#10B981; }
.bed-occupied { background:linear-gradient(135deg,#FEE2E2,#FECACA); border-color:#EF4444; }
.bed-cleaning { background:linear-gradient(135deg,#FEF3C7,#FDE68A); border-color:#F59E0B; }
.bed-maintenance { background:linear-gradient(135deg,#E0E7FF,#C7D2FE); border-color:#6366F1; }
.bed-uci { background:linear-gradient(135deg,#FCE7F3,#FBCFE8); border-color:#EC4899; }
.bed-number { font-size:1.5rem; font-weight:900; }
.bed-status { font-size:0.7rem; font-weight:700; text-transform:uppercase; letter-spacing:1px; }
.bed-patient { font-size:0.75rem; margin-top:0.5rem; font-weight:600; }
.bed-type { font-size:0.65rem; opacity:0.7; }
.legend-dot { width:12px; height:12px; border-radius:50%; display:inline-block; }
.floor-section { margin-bottom:2rem; }
.floor-title { font-size:1.1rem; font-weight:900; margin-bottom:0.75rem; padding-bottom:0.5rem; border-bottom:2px solid #E2E8F0; }
</style>

<div style="background:white; padding:1.5rem; border-radius:12px; box-shadow:0 4px 6px rgba(0,0,0,0.04); margin-bottom:1.5rem; display:flex; justify-content:space-between; align-items:center;">
    <h3 style="font-weight:800;"><i class="fas fa-th" style="color:#6366F1;"></i> Mapa de Camas en Tiempo Real</h3>
    <div style="display:flex; gap:1rem; font-size:0.8rem;">
        <span><span class="legend-dot" style="background:#10B981;"></span> Disponible</span>
        <span><span class="legend-dot" style="background:#EF4444;"></span> Ocupada</span>
        <span><span class="legend-dot" style="background:#F59E0B;"></span> Limpieza</span>
        <span><span class="legend-dot" style="background:#6366F1;"></span> Mantenimiento</span>
        <span><span class="legend-dot" style="background:#EC4899;"></span> UCI</span>
    </div>
</div>

@php
 $floors = $camas->groupBy('floor');
 $stats = ['disponibles' => 0, 'ocupadas' => 0, 'limpieza' => 0, 'mantenimiento' => 0];
foreach($camas as $c) {
    if($c->status == 'Disponible') $stats['disponibles']++;
    elseif($c->status == 'Ocupada') $stats['ocupadas']++;
    elseif($c->status == 'Limpieza') $stats['limpieza']++;
    else $stats['mantenimiento']++;
}
@endphp

<div style="display:grid; grid-template-columns:repeat(4,1fr); gap:1rem; margin-bottom:1.5rem;">
    <div style="background:linear-gradient(135deg,#D1FAE5,#A7F3D0); padding:1rem; border-radius:12px; text-align:center;">
        <div style="font-size:2rem; font-weight:900; color:#059669;">{{ $stats['disponibles'] }}</div>
        <div style="font-size:0.8rem; font-weight:700; color:#059669;">Disponibles</div>
    </div>
    <div style="background:linear-gradient(135deg,#FEE2E2,#FECACA); padding:1rem; border-radius:12px; text-align:center;">
        <div style="font-size:2rem; font-weight:900; color:#DC2626;">{{ $stats['ocupadas'] }}</div>
        <div style="font-size:0.8rem; font-weight:700; color:#DC2626;">Ocupadas</div>
    </div>
    <div style="background:linear-gradient(135deg,#FEF3C7,#FDE68A); padding:1rem; border-radius:12px; text-align:center;">
        <div style="font-size:2rem; font-weight:900; color:#D97706;">{{ $stats['limpieza'] }}</div>
        <div style="font-size:0.8rem; font-weight:700; color:#D97706;">Limpieza</div>
    </div>
    <div style="background:linear-gradient(135deg,#E0E7FF,#C7D2FE); padding:1rem; border-radius:12px; text-align:center;">
        <div style="font-size:2rem; font-weight:900; color:#4F46E5;">{{ $stats['mantenimiento'] }}</div>
        <div style="font-size:0.8rem; font-weight:700; color:#4F46E5;">Mantenimiento</div>
    </div>
</div>

@foreach($floors as $floor => $beds)
<div class="floor-section">
    <div class="floor-title"><i class="fas fa-layer-group"></i> Piso {{ $floor }}</div>
    <div style="display:grid; grid-template-columns:repeat(auto-fill,minmax(140px,1fr)); gap:0.75rem;">
        @foreach($beds as $c)
        @php
            $hosp = $hospitalizaciones->firstWhere('bed_id', $c->id);
            $paciente = $hosp ? \App\Models\Triage::find($hosp->triage_id) : null;
            $class = $c->status == 'Disponible' ? 'bed-free' : ($c->status == 'Ocupada' ? 'bed-occupied' : ($c->status == 'Limpieza' ? 'bed-cleaning' : 'bed-maintenance'));
            if($c->type == 'UCI') $class = 'bed-uci';
        @endphp
        <div class="bed-card {{ $class }}" onclick="bedInfo({{ $c->id }})" data-bs-toggle="modal" data-bs-target="#bedModal">
            <div class="bed-type">{{ $c->type ?? 'General' }}</div>
            <div class="bed-number">{{ $c->room_number ?? 'C-'.$c->id }}</div>
            <div class="bed-status">{{ $c->status }}</div>
            @if($paciente)
            <div class="bed-patient"><i class="fas fa-user"></i> {{ $paciente->patient_name }}</div>
            @endif
        </div>
        @endforeach
    </div>
</div>
@endforeach

<div style="margin-top:1rem;">{{ $camas->withQueryString()->links() }}</div>

<!-- Modal Detalle Cama -->
<div class="modal fade" id="bedModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius:16px;">
            <div class="modal-header" style="background:linear-gradient(135deg,#6366F1,#8B5CF6); color:white; border-radius:16px 16px 0 0;">
                <h5 class="modal-title"><i class="fas fa-bed"></i> Detalle de Cama</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="bedModalBody">
                <p>Cargando...</p>
            </div>
        </div>
    </div>
</div>

<script>
function bedInfo(id) {
    fetch('/medico/camas/' + id + '/info')
        .then(r => r.ok ? r.text() : 'No disponible')
        .then(html => document.getElementById('bedModalBody').innerHTML = html)
        .catch(() => document.getElementById('bedModalBody').innerHTML = 'Error al cargar');
}
</script>
@endsection
