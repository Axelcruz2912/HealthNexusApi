@extends('medico.layout')
@section('title', 'Catálogo Médico')
@section('nav-farmaciaStock', 'active')
@section('content')
<style>
.med-card { background:white; border-radius:12px; padding:1rem; border:1px solid #E2E8F0; transition:all 0.3s; }
.med-card:hover { box-shadow:0 4px 15px rgba(0,0,0,0.1); transform:translateY(-2px); }
.stock-high { color:#10B981; } .stock-low { color:#F59E0B; } .stock-critical { color:#EF4444; }
.level-A { background:#FEE2E2; color:#DC2626; } .level-B { background:#FEF3C7; color:#D97706; } .level-C { background:#D1FAE5; color:#059669; }
</style>

<div style="background:white; padding:1.5rem; border-radius:12px; box-shadow:0 4px 6px rgba(0,0,0,0.04); margin-bottom:1.5rem; display:flex; justify-content:space-between; align-items:center;">
    <h3 style="font-weight:800;"><i class="fas fa-capsules" style="color:#10B981;"></i> Catálogo de Medicamentos e Insumos</h3>
    <div style="display:flex; gap:0.5rem;">
        <input type="text" id="searchMed" placeholder="Buscar..." style="padding:0.5rem 1rem; border:1px solid #E2E8F0; border-radius:8px; font-size:0.85rem;" onkeyup="filterMeds()">
    </div>
</div>

<div style="display:grid; grid-template-columns:repeat(auto-fill,minmax(220px,1fr)); gap:1rem;" id="medGrid">
    @foreach($insumos as $m)
    <div class="med-card" data-name="{{ strtolower($m->name) }}">
        <div style="display:flex; justify-content:space-between; align-items:start; margin-bottom:0.5rem;">
            <h5 style="font-weight:800; font-size:0.9rem; margin:0;">{{ $m->name }}</h5>
            <span class="level-{{ $m->required_level }}" style="padding:0.15rem 0.5rem; border-radius:4px; font-size:0.7rem; font-weight:800;">Nivel {{ $m->required_level }}</span>
        </div>
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:0.5rem;">
            <span class="{{ $m->stock > 20 ? 'stock-high' : ($m->stock > 5 ? 'stock-low' : 'stock-critical') }}" style="font-weight:800;">
                <i class="fas fa-box"></i> Stock: {{ $m->stock }}
            </span>
            <span style="font-weight:700; color:#6366F1;">${{ number_format($m->price ?? 0, 2) }}</span>
        </div>
        @if($m->stock < 10)
        <button onclick="solicitarMed({{ $m->id }}, '{{ $m->name }}')" style="width:100%; padding:0.4rem; background:linear-gradient(135deg,#F59E0B,#EF4444); color:white; border:none; border-radius:6px; font-weight:700; font-size:0.75rem; cursor:pointer;">
            <i class="fas fa-exclamation-triangle"></i> Solicitar a Farmacia
        </button>
        @else
        <button onclick="solicitarMed({{ $m->id }}, '{{ $m->name }}')" style="width:100%; padding:0.4rem; background:#E2E8F0; color:#475569; border:none; border-radius:6px; font-weight:700; font-size:0.75rem; cursor:pointer;">
            <i class="fas fa-shopping-cart"></i> Solicitar
        </button>
        @endif
    </div>
    @endforeach
</div>

<div style="margin-top:1rem;">{{ $insumos->withQueryString()->links() }}</div>

<!-- Modal Solicitar -->
<div class="modal fade" id="solModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius:16px;">
            <div class="modal-header" style="background:linear-gradient(135deg,#10B981,#059669); color:white; border-radius:16px 16px 0 0;">
                <h5 class="modal-title"><i class="fas fa-shopping-cart"></i> Solicitar a Farmacia</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('medico.storeServicio') }}">
                    @csrf
                    <input type="hidden" name="tipo" value="Solicitud Farmacia">
                    <input type="hidden" name="medication_id" id="solMedId">
                    <p id="solMedName" style="font-weight:800; font-size:1.1rem;"></p>
                    <div style="margin-bottom:1rem;">
                        <label style="font-weight:700;">Cantidad solicitada</label>
                        <input type="number" name="prioridad" value="Normal" class="form-control" min="1" value="1">
                    </div>
                    <div style="margin-bottom:1rem;">
                        <label style="font-weight:700;">Notas</label>
                        <textarea name="descripcion" class="form-control" rows="2" placeholder="Urgencia o notas..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-success w-100" style="font-weight:800;">Enviar Solicitud</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function solicitarMed(id, name) {
    document.getElementById('solMedId').value = id;
    document.getElementById('solMedName').textContent = name;
    new bootstrap.Modal(document.getElementById('solModal')).show();
}
function filterMeds() {
    const q = document.getElementById('searchMed').value.toLowerCase();
    document.querySelectorAll('.med-card').forEach(c => {
        c.style.display = c.dataset.name.includes(q) ? '' : 'none';
    });
}
</script>
@endsection
