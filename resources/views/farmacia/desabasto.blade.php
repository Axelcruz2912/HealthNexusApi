@extends('farmacia.layout')
@section('title', 'Alerta de Desabasto y Reabastecimiento')
@section('nav-desabasto', 'active')

@section('content')
@if($out->count() > 0)
<div style="background: #C7291C; color: white; padding: 2rem; border-radius: 12px; margin-bottom: 2rem; display: flex; align-items: center; gap: 1.5rem; animation: pulse 3s infinite;">
    <i class="fas fa-exclamation-circle" style="font-size: 3rem;"></i>
    <div>
        <h3 style="font-weight: 800;">CRITICO: {{ $out->count() }} medicamentos sin stock</h3>
        <p style="opacity: 0.9;">El hospital no puede operar sin estos insumos. Se requiere accion inmediata.</p>
    </div>
</div>
@endif

@if(session('success'))
<div style="background:#FFF7ED; color:#9A3412; padding:1rem; border-radius:8px; margin-bottom:1rem; border-left:4px solid #F97316;">
    <i class="fas fa-check-circle"></i> {{ session('success') }}
</div>
@endif

<div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem; margin-bottom: 2rem;">
    <div style="background: white; padding: 1.5rem; border-radius: 12px; border-top: 4px solid #C7291C; box-shadow: 0 4px 6px rgba(0,0,0,0.04); text-align: center;">
        <div style="font-size: 2.5rem; font-weight: 800; color: #C7291C;">{{ $out->count() }}</div>
        <div style="font-size: 0.85rem; color: #736860; font-weight: 700;">SIN STOCK</div>
    </div>
    <div style="background: white; padding: 1.5rem; border-radius: 12px; border-top: 4px solid #FF8C42; box-shadow: 0 4px 6px rgba(0,0,0,0.04); text-align: center;">
        <div style="font-size: 2.5rem; font-weight: 800; color: #FF8C42;">{{ $low->count() }}</div>
        <div style="font-size: 0.85rem; color: #736860; font-weight: 700;">STOCK BAJO</div>
    </div>
    <div style="background: white; padding: 1.5rem; border-radius: 12px; border-top: 4px solid #DC2626; box-shadow: 0 4px 6px rgba(0,0,0,0.04); text-align: center;">
        <div style="font-size: 2.5rem; font-weight: 800; color: #DC2626;">{{ $requests->where('status', 'Solicitada')->count() }}</div>
        <div style="font-size: 0.85rem; color: #736860; font-weight: 700;">SOLICITUDES PENDIENTES</div>
    </div>
    <div style="background: white; padding: 1.5rem; border-radius: 12px; border-top: 4px solid #F97316; box-shadow: 0 4px 6px rgba(0,0,0,0.04); text-align: center;">
        <div style="font-size: 2.5rem; font-weight: 800; color: #F97316;">{{ $requests->where('status', 'Aprobada')->count() }}</div>
        <div style="font-size: 0.85rem; color: #736860; font-weight: 700;">APROBADAS</div>
    </div>
</div>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem;">
    <!-- TABLA DE MEDICAMENTOS CON BOTONES -->
    <div>
        <div style="background: white; padding: 1rem 1.5rem; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.04); margin-bottom: 1rem;">
            <h4 style="font-weight:800; color:#C7291C;"><i class="fas fa-times-circle"></i> Sin Stock - Accion Inmediata</h4>
        </div>

        @foreach($out as $med)
        <div style="background: #FFF1F0; padding: 1rem 1.5rem; border-radius: 10px; margin-bottom: 0.75rem; border-left: 4px solid #C7291C; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.5rem;">
            <div style="flex: 1; min-width: 200px;">
                <div style="font-weight: 800; font-size: 1rem; color: #8C1A11;">{{ $med->name }}</div>
                <div style="font-size: 0.8rem; color: #736860;">
                    Nivel: <span style="background:{{ $med->level_color }}; color:white; padding:0.1rem 0.4rem; border-radius:6px; font-size:0.7rem;">{{ $med->required_level }}</span>
                    | Origen: {{ $med->origin }}
                    | Proveedor: {{ $med->provider_name ?? 'Sin proveedor' }}
                </div>
            </div>
            <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                <button onclick="openRestockModal({{ $med->id }}, '{{ $med->name }}', 'Critica')" style="background:#C7291C; color:white; border:none; padding:0.4rem 0.8rem; border-radius:6px; font-weight:700; cursor:pointer; font-size:0.8rem;">
                    <i class="fas fa-exclamation-triangle"></i> Solicitar Urgente
                </button>
                <a href="{{ route('farmacia.alternatives', $med->id) }}" style="background:#DC2626; color:white; text-decoration:none; padding:0.4rem 0.8rem; border-radius:6px; font-weight:700; font-size:0.8rem;">
                    <i class="fas fa-exchange-alt"></i> Ver Alternativas
                </a>
            </div>
        </div>
        @endforeach

        @if($low->count() > 0)
        <div style="background: white; padding: 1rem 1.5rem; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.04); margin-bottom: 1rem; margin-top: 1.5rem;">
            <h4 style="font-weight:800; color:#FF8C42;"><i class="fas fa-exclamation-triangle"></i> Stock Bajo Minimo</h4>
        </div>

        @foreach($low as $med)
        <div style="background: #FFF5EB; padding: 1rem 1.5rem; border-radius: 10px; margin-bottom: 0.5rem; border-left: 4px solid #FF8C42; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.5rem;">
            <div style="flex: 1; min-width: 200px;">
                <div style="font-weight: 700;">{{ $med->name }}</div>
                <div style="font-size: 0.8rem; color: #736860;">Stock: <strong style="color:#C7291C;">{{ $med->stock }}</strong> / Min: {{ $med->min_stock }} | {{ $med->origin }}</div>
            </div>
            <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                <button onclick="openRestockModal({{ $med->id }}, '{{ $med->name }}', 'Alta')" style="background:#FF8C42; color:white; border:none; padding:0.4rem 0.8rem; border-radius:6px; font-weight:700; cursor:pointer; font-size:0.8rem;">
                    <i class="fas fa-cart-plus"></i> Solicitar
                </button>
                <a href="{{ route('farmacia.alternatives', $med->id) }}" style="background:#DC2626; color:white; text-decoration:none; padding:0.4rem 0.8rem; border-radius:6px; font-weight:700; font-size:0.8rem;">
                    <i class="fas fa-exchange-alt"></i> Alternativas
                </a>
            </div>
        </div>
        @endforeach
        @endif
    </div>

    <!-- PANEL DE SOLICITUDES -->
    <div>
        <div style="background: white; padding: 1rem 1.5rem; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.04); margin-bottom: 1rem;">
            <h4 style="font-weight:800;"><i class="fas fa-clipboard-list" style="color:#DC2626;"></i> Solicitudes de Reabastecimiento</h4>
        </div>

        @if($requests->count() > 0)
        @foreach($requests as $req)
        <div style="background: white; padding: 1rem; border-radius: 10px; margin-bottom: 0.75rem; box-shadow: 0 2px 4px rgba(0,0,0,0.04); border-left: 3px solid {{ $req->priority_color }};">
            <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                <span style="font-family:monospace; font-weight:700; font-size:0.85rem;">{{ $req->request_number }}</span>
                <span style="background:{{ $req->priority_color }}; color:white; padding:0.1rem 0.5rem; border-radius:8px; font-size:0.65rem; font-weight:700;">{{ $req->priority }}</span>
            </div>
            <div style="font-weight:700; font-size:0.9rem;">{{ $req->medication->name }}</div>
            <div style="font-size:0.8rem; color:#736860;">Cantidad: {{ $req->quantity_requested }} | Requerido: {{ $req->required_by ? $req->required_by->format('d/m') : 'N/A' }}</div>
            <div style="display:flex; justify-content:space-between; align-items:center; margin-top:0.5rem;">
                <span style="background:{{ $req->status_color }}; color:white; padding:0.15rem 0.5rem; border-radius:6px; font-size:0.65rem; font-weight:700;">{{ $req->status }}</span>
                @if($req->status == 'Solicitada')
                <form action="{{ route('farmacia.approveRestock', $req->id) }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" style="background:#F97316; color:white; border:none; padding:0.25rem 0.6rem; border-radius:4px; font-weight:700; cursor:pointer; font-size:0.75rem;">
                        <i class="fas fa-check"></i> Aprobar
                    </button>
                </form>
                @endif
            </div>
        </div>
        @endforeach
        @else
        <div style="background: white; padding: 2rem; border-radius: 12px; text-align: center; color: #736860;">
            <i class="fas fa-clipboard-check" style="font-size: 2rem; margin-bottom: 0.5rem; opacity: 0.3;"></i>
            <p style="font-size:0.85rem;">Sin solicitudes activas</p>
        </div>
        @endif
    </div>
</div>

<!-- Modal Solicitar Reabastecimiento -->
<div id="restock-modal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.6); z-index:200; align-items:center; justify-content:center;">
    <div style="background:white; padding:2rem; border-radius:12px; width:450px; border-top:5px solid #C7291C;">
        <h3 style="font-weight:800; margin-bottom:1.5rem; color:#C7291C;"><i class="fas fa-cart-plus"></i> Solicitar Reabastecimiento</h3>
        <form action="{{ route('farmacia.requestRestock') }}" method="POST">
            @csrf
            <input type="hidden" name="medication_id" id="restock-med-id">
            <div style="background:#FFF1F0; padding:1rem; border-radius:8px; margin-bottom:1rem;">
                <div style="font-size:0.75rem; color:#736860; font-weight:700;">MEDICAMENTO</div>
                <div style="font-weight:800; color:#8C1A11; font-size:1.1rem;" id="restock-med-name"></div>
            </div>
            <div style="margin-bottom:1rem;">
                <label style="font-size:0.8rem; font-weight:700; color:#736860;">Cantidad a Solicitar:</label>
                <input type="number" name="quantity" value="50" min="1" required style="width:100%; padding:0.6rem; border:1px solid #E5E7EB; border-radius:6px;">
            </div>
            <div style="margin-bottom:1rem;">
                <label style="font-size:0.8rem; font-weight:700; color:#736860;">Prioridad:</label>
                <select name="priority" id="restock-priority" style="width:100%; padding:0.6rem; border:1px solid #E5E7EB; border-radius:6px;">
                    <option value="Baja">Baja - No es urgente</option>
                    <option value="Media">Media - Requerido esta semana</option>
                    <option value="Alta">Alta - Necesario en 3 dias</option>
                    <option value="Critica">Critica - Hospital parado sin esto</option>
                </select>
            </div>
            <div style="margin-bottom:1.5rem;">
                <label style="font-size:0.8rem; font-weight:700; color:#736860;">Motivo / Notas:</label>
                <textarea name="reason" rows="2" style="width:100%; padding:0.6rem; border:1px solid #E5E7EB; border-radius:6px; resize:vertical;" placeholder="Ej: Sin stock, proveedor demora 5 dias..."></textarea>
            </div>
            <div style="display:flex; gap:10px;">
                <button type="button" onclick="document.getElementById('restock-modal').style.display='none'" style="flex:1; padding:0.7rem; border:1px solid #E5E7EB; background:white; border-radius:8px; cursor:pointer; font-weight:700;">Cancelar</button>
                <button type="submit" style="flex:1; padding:0.7rem; background:#C7291C; color:white; border:none; border-radius:8px; cursor:pointer; font-weight:700;">
                    <i class="fas fa-paper-plane"></i> Enviar Solicitud
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openRestockModal(medId, medName, priority) {
    document.getElementById('restock-med-id').value = medId;
    document.getElementById('restock-med-name').textContent = medName;
    document.getElementById('restock-priority').value = priority;
    document.getElementById('restock-modal').style.display = 'flex';
}
</script>
@endsection
