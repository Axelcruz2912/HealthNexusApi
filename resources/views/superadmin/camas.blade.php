@extends('superadmin.layout')
@section('title', 'Control de Recursos - Camas')
@section('nav-recursos', 'active')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
    <h3 style="font-weight: 800; color: #1E1A17;">Mapa de Ocupación Hospitalaria</h3>
    <button onclick="document.getElementById('modal-add-bed').style.display='flex'" style="background: #F05A4E; color: white; border: none; padding: 0.6rem 1.2rem; border-radius: 8px; font-weight: 700; cursor: pointer;">
        <i class="fas fa-plus"></i> Agregar Cama
    </button>
</div>

<div style="display: flex; gap: 1rem; margin-bottom: 1.5rem;">
    <div style="background: white; padding: 1rem; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); display: flex; align-items: center; gap: 0.5rem;">
        <div style="width: 15px; height: 15px; background: #2D9E6A; border-radius: 3px;"></div><span style="font-size:0.85rem;">Disponible</span>
    </div>
    <div style="background: white; padding: 1rem; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); display: flex; align-items: center; gap: 0.5rem;">
        <div style="width: 15px; height: 15px; background: #C7291C; border-radius: 3px;"></div><span style="font-size:0.85rem;">Ocupada</span>
    </div>
    <div style="background: white; padding: 1rem; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); display: flex; align-items: center; gap: 0.5rem;">
        <div style="width: 15px; height: 15px; background: #FF8C42; border-radius: 3px;"></div><span style="font-size:0.85rem;">Limpieza</span>
    </div>
    <div style="background: white; padding: 1rem; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); display: flex; align-items: center; gap: 0.5rem;">
        <div style="width: 15px; height: 15px; background: #736860; border-radius: 3px;"></div><span style="font-size:0.85rem;">Mantenimiento</span>
    </div>
</div>

@php $floors = $beds->groupBy('floor'); @endphp
@foreach($floors as $floor => $floorBeds)
<div style="background: white; padding: 1.5rem; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.04); margin-bottom: 1.5rem;">
    <h4 style="font-weight: 700; margin-bottom: 1rem; color: #1E1A17; border-bottom: 1px solid #E5E7EB; padding-bottom: 0.5rem;">Piso {{ $floor }} - {{ $floor == 1 ? 'Urgencias' : ($floor == 2 ? 'UCI' : ($floor == 3 ? 'Pediatría' : 'Quirófanos')) }}</h4>
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(120px, 1fr)); gap: 1rem;">
        @foreach($floorBeds as $bed)
        <div style="background: {{ $bed->status == 'Disponible' ? '#EBF9F2' : ($bed->status == 'Ocupada' ? '#FFF1F0' : ($bed->status == 'Limpieza' ? '#FFF5EB' : '#F4F6F8')) }}; border: 2px solid {{ $bed->status == 'Disponible' ? '#2D9E6A' : ($bed->status == 'Ocupada' ? '#C7291C' : ($bed->status == 'Limpieza' ? '#FF8C42' : '#736860')) }}; border-radius: 8px; padding: 0.75rem; text-align: center;">
            <div style="font-size: 0.75rem; color: #736860; font-weight: 600;">Hab. {{ $bed->room_number }}</div>
            <div style="font-size: 1.2rem; font-weight: 800; color: #1E1A17;">Cama {{ $bed->bed_number }}</div>
            <div style="font-size: 0.7rem; font-weight: 700; color: {{ $bed->status == 'Disponible' ? '#065F46' : ($bed->status == 'Ocupada' ? '#8C1A11' : ($bed->status == 'Limpieza' ? '#9a3412' : '#736860')) }}; margin-top: 0.25rem;">{{ $bed->status }}</div>
            <form action="{{ route('superadmin.updateBedStatus', $bed->id) }}" method="POST" style="margin-top:0.5rem;">
                @csrf @method('PUT')
                <select name="status" onchange="this.form.submit()" style="width:100%; font-size:0.7rem; padding:0.2rem; border:1px solid #E5E7EB; border-radius:4px;">
                    <option {{ $bed->status == 'Disponible' ? 'selected' : '' }}>Disponible</option>
                    <option {{ $bed->status == 'Ocupada' ? 'selected' : '' }}>Ocupada</option>
                    <option {{ $bed->status == 'Limpieza' ? 'selected' : '' }}>Limpieza</option>
                    <option {{ $bed->status == 'Mantenimiento' ? 'selected' : '' }}>Mantenimiento</option>
                </select>
            </form>
        </div>
        @endforeach
    </div>
</div>
@endforeach

<!-- Modal Agregar Cama -->
<div id="modal-add-bed" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:100; align-items:center; justify-content:center;">
    <div style="background:white; padding:2rem; border-radius:12px; width:400px;">
        <h3 style="font-weight:800; margin-bottom:1rem;">Registrar Nueva Cama</h3>
        <form action="{{ route('superadmin.storeBed') }}" method="POST">
            @csrf
            <div style="margin-bottom:1rem;"><label style="font-size:0.8rem; font-weight:700; color:#736860;">Piso</label><input type="number" name="floor" required style="width:100%; padding:0.6rem; border:1px solid #E5E7EB; border-radius:6px; margin-top:0.3rem;"></div>
            <div style="margin-bottom:1rem;"><label style="font-size:0.8rem; font-weight:700; color:#736860;">Número de Habitación</label><input type="text" name="room_number" required style="width:100%; padding:0.6rem; border:1px solid #E5E7EB; border-radius:6px; margin-top:0.3rem;"></div>
            <div style="margin-bottom:1rem;"><label style="font-size:0.8rem; font-weight:700; color:#736860;">Letra de Cama</label><input type="text" name="bed_number" required style="width:100%; padding:0.6rem; border:1px solid #E5E7EB; border-radius:6px; margin-top:0.3rem;"></div>
            <div style="margin-bottom:1rem;"><label style="font-size:0.8rem; font-weight:700; color:#736860;">Tipo</label>
                <select name="type" style="width:100%; padding:0.6rem; border:1px solid #E5E7EB; border-radius:6px; margin-top:0.3rem;"><option>General</option><option>UCI</option><option>Pediatría</option><option>Quirófano</option></select>
            </div>
            <div style="display:flex; gap:10px; margin-top:1.5rem;">
                <button type="button" onclick="document.getElementById('modal-add-bed').style.display='none'" style="flex:1; padding:0.6rem; border:1px solid #E5E7EB; background:white; border-radius:6px; cursor:pointer;">Cancelar</button>
                <button type="submit" style="flex:1; padding:0.6rem; background:#F05A4E; color:white; border:none; border-radius:6px; cursor:pointer; font-weight:700;">Guardar</button>
            </div>
        </form>
    </div>
</div>
{{ $beds->withQueryString()->links() }}
@endsection
