@extends('farmacia.layout')
@section('title', 'Inventario A/B/C/Enfermera')
@section('nav-inventario', 'active')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
    <h3 style="font-weight: 800; color: #7C2D12;">Inventario Farmaceutico Completo</h3>
    <button onclick="document.getElementById('modal-add').style.display='flex'" style="background:#F97316; color:white; border:none; padding:0.6rem 1.2rem; border-radius:8px; font-weight:700; cursor:pointer;">
        <i class="fas fa-plus"></i> Nuevo Medicamento
    </button>
</div>

@if(session('success'))
<div style="background:#FFF7ED; color:#9A3412; padding:1rem; border-radius:8px; margin-bottom:1rem; border-left:4px solid #F97316;">
    <i class="fas fa-check-circle"></i> {{ session('success') }}
</div>
@endif

<!-- Filtros por nivel -->
<div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem; margin-bottom: 1.5rem;">
    @php $levels = ['A' => ['color'=>'#C7291C','label'=>'Especialista'], 'B' => ['color'=>'#FF8C42','label'=>'Hospitalizacion'], 'C' => ['color'=>'#F97316','label'=>'Basico'], 'Enfermera' => ['color'=>'#DC2626','label'=>'Enfermeria']]; @endphp
    @foreach($levels as $level => $info)
    @php $count = $medications->where('required_level', $level)->count(); @endphp
    <div onclick="filterByLevel('{{ $level }}')" style="background: white; padding: 1.5rem; border-radius: 12px; border-top: 4px solid {{ $info['color'] }}; box-shadow: 0 4px 6px rgba(0,0,0,0.04); cursor: pointer; transition: 0.2s;" onmouseover="this.style.transform='translateY(-3px)'" onmouseout="this.style.transform='none'">
        <div style="font-size:0.8rem; color:#736860; font-weight:700; text-transform:uppercase;">Nivel {{ $level }}</div>
        <div style="font-size:0.75rem; color:{{ $info['color'] }}; font-weight:600;">{{ $info['label'] }}</div>
        <div style="font-size:2rem; font-weight:800; color:#7C2D12; margin-top:0.5rem;">{{ $count }}</div>
    </div>
    @endforeach
</div>

<div style="background: white; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.04); overflow: hidden;">
    <table style="width: 100%; border-collapse: collapse; font-size: 0.85rem;">
        <thead>
            <tr style="background: #7C2D12; color: white; text-align: left;">
                <th style="padding:0.75rem;">Medicamento</th>
                <th style="padding:0.75rem;">Nivel</th>
                <th style="padding:0.75rem;">Enfermera</th>
                <th style="padding:0.75rem;">Origen</th>
                <th style="padding:0.75rem;">Stock</th>
                <th style="padding:0.75rem;">Lote</th>
                <th style="padding:0.75rem;">Caducidad</th>
                <th style="padding:0.75rem;">Ubicacion</th>
            </tr>
        </thead>
        <tbody>
            @foreach($medications as $med)
            <tr style="border-bottom: 1px solid #E5E7EB; {{ $med->isExpired() ? 'background:#FFF1F0;' : '' }}">
                <td style="padding:0.75rem; font-weight:700;">{{ $med->name }}</td>
                <td style="padding:0.75rem;">
                    <span style="background:{{ $med->level_color }}; color:white; padding:0.15rem 0.5rem; border-radius:10px; font-size:0.7rem; font-weight:700;">{{ $med->required_level }}</span>
                </td>
                <td style="padding:0.75rem;">
                    @if($med->enfermera_can_administer)
                    <span style="background:#DC2626; color:white; padding:0.15rem 0.4rem; border-radius:8px; font-size:0.7rem; font-weight:700;"><i class="fas fa-check"></i> SI</span>
                    @else
                    <span style="color:#736860; font-size:0.75rem;">No</span>
                    @endif
                </td>
                <td style="padding:0.75rem;">{{ $med->origin }}</td>
                <td style="padding:0.75rem; font-weight:800; color:{{ $med->stock_color }};">{{ $med->stock }}</td>
                <td style="padding:0.75rem; font-size:0.8rem; font-family:monospace;">{{ $med->lot_number ?? 'S/N' }}</td>
                <td style="padding:0.75rem;">
                    @if($med->expiry_date)
                        <span style="color:{{ $med->expiry_color }}; font-weight:{{ $med->isExpiringSoon() || $med->isExpired() ? '800' : '400' }};">
                            {{ $med->expiry_date->format('d/m/Y') }}
                            @if($med->isExpired()) <i class="fas fa-skull-crossbones" style="color:#C7291C;"></i>
                            @elseif($med->isExpiringSoon()) <i class="fas fa-exclamation-triangle" style="color:#FF8C42;"></i>
                            @endif
                        </span>
                    @else
                        <span style="color:#736860;">Sin fecha</span>
                    @endif
                </td>
                <td style="padding:0.75rem; font-size:0.8rem;">{{ $med->location ?? 'N/A' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Modal Agregar Medicamento -->
<div id="modal-add" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.6); z-index:100; align-items:center; justify-content:center;">
    <div style="background:white; padding:2rem; border-radius:12px; width:550px; max-height:90vh; overflow-y:auto;">
        <h3 style="font-weight:800; margin-bottom:1.5rem;"><i class="fas fa-pills" style="color:#F97316;"></i> Registrar Medicamento</h3>
        <form action="{{ route('farmacia.storeMedication') }}" method="POST">
            @csrf
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px; margin-bottom:1rem;">
                <div><label style="font-size:0.8rem; font-weight:700;">Nombre</label><input type="text" name="name" required style="width:100%; padding:0.5rem; border:1px solid #E5E7EB; border-radius:6px;"></div>
                <div><label style="font-size:0.8rem; font-weight:700;">Principio Activo</label><input type="text" name="active_ingredient" required style="width:100%; padding:0.5rem; border:1px solid #E5E7EB; border-radius:6px;"></div>
            </div>
            <div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:10px; margin-bottom:1rem;">
                <div><label style="font-size:0.8rem; font-weight:700;">Stock</label><input type="number" name="stock" required style="width:100%; padding:0.5rem; border:1px solid #E5E7EB; border-radius:6px;"></div>
                <div><label style="font-size:0.8rem; font-weight:700;">Min Stock</label><input type="number" name="min_stock" value="10" style="width:100%; padding:0.5rem; border:1px solid #E5E7EB; border-radius:6px;"></div>
                <div><label style="font-size:0.8rem; font-weight:700;">Precio</label><input type="number" step="0.01" name="price" required style="width:100%; padding:0.5rem; border:1px solid #E5E7EB; border-radius:6px;"></div>
            </div>
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px; margin-bottom:1rem;">
                <div>
                    <label style="font-size:0.8rem; font-weight:700;">Nivel de Prescripcion</label>
                    <select name="required_level" id="level-select" onchange="toggleEnfermeraCheck()" style="width:100%; padding:0.5rem; border:1px solid #E5E7EB; border-radius:6px;">
                        <option value="C">C - Basico / Pasante</option>
                        <option value="B">B - Hospitalizacion</option>
                        <option value="A">A - Especialista / Controlado</option>
                        <option value="Enfermera">Enfermera - Administracion directa</option>
                    </select>
                </div>
                <div>
                    <label style="font-size:0.8rem; font-weight:700;">Origen / Area</label>
                    <select name="origin" style="width:100%; padding:0.5rem; border:1px solid #E5E7EB; border-radius:6px;">
                        <option value="Central">Central</option>
                        <option value="Hospitalaria">Hospitalaria</option>
                        <option value="Quirurgico">Quirurgico</option>
                        <option value="Urgencias">Urgencias</option>
                    </select>
                </div>
            </div>
            <div style="background:#FEF2F2; padding:0.75rem; border-radius:8px; margin-bottom:1rem; border-left:3px solid #DC2626;">
                <label style="display:flex; align-items:center; gap:0.5rem; cursor:pointer; font-weight:700; font-size:0.85rem; color:#7F1D1D;">
                    <input type="checkbox" name="enfermera_can_administer" id="enfermera-check" value="1" style="width:18px; height:18px;">
                    <i class="fas fa-user-nurse" style="color:#DC2626;"></i> Enfermeria puede administrar este medicamento
                </label>
                <p style="font-size:0.75rem; color:#736860; margin-top:0.3rem; margin-left:1.5rem;">Permitir que personal de enfermeria dispense directamente sin receta medica (sueros, viales, oxigeno, etc.)</p>
            </div>
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px; margin-bottom:1rem;">
                <div><label style="font-size:0.8rem; font-weight:700;">Lote</label><input type="text" name="lot_number" required style="width:100%; padding:0.5rem; border:1px solid #E5E7EB; border-radius:6px;"></div>
                <div><label style="font-size:0.8rem; font-weight:700;">Caducidad</label><input type="date" name="expiry_date" required style="width:100%; padding:0.5rem; border:1px solid #E5E7EB; border-radius:6px;"></div>
            </div>
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px; margin-bottom:1.5rem;">
                <div><label style="font-size:0.8rem; font-weight:700;">Ubicacion</label><input type="text" name="location" placeholder="Ej: Estante 3, Anaquel B" style="width:100%; padding:0.5rem; border:1px solid #E5E7EB; border-radius:6px;"></div>
                <div><label style="font-size:0.8rem; font-weight:700;">Proveedor</label><input type="text" name="provider_name" style="width:100%; padding:0.5rem; border:1px solid #E5E7EB; border-radius:6px;"></div>
            </div>
            <div style="display:flex; gap:10px;">
                <button type="button" onclick="document.getElementById('modal-add').style.display='none'" style="flex:1; padding:0.8rem; border:1px solid #E5E7EB; background:white; border-radius:8px; cursor:pointer; font-weight:700;">Cancelar</button>
                <button type="submit" style="flex:1; padding:0.8rem; background:#F97316; color:white; border:none; border-radius:8px; cursor:pointer; font-weight:700;"><i class="fas fa-save"></i> Registrar</button>
            </div>
        </form>
    </div>
</div>

<script>
function toggleEnfermeraCheck() {
    var level = document.getElementById('level-select').value;
    var check = document.getElementById('enfermera-check');
    if (level === 'Enfermera') {
        check.checked = true;
    }
}

function filterByLevel(level) {
    var rows = document.querySelectorAll('tbody tr');
    rows.forEach(function(row) {
        var levelSpan = row.querySelector('span[style*="border-radius:10px"]');
        if (levelSpan && levelSpan.textContent.trim() === level) {
            row.style.display = '';
        } else if (level === 'ALL') {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}
</script>
@endsection
