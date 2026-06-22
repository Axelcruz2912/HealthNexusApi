@extends('farmacia.layout')
@section('title', 'Crear Orden de Compra')
@section('nav-ordenes', 'active')

@section('content')
<div style="background: white; padding: 2rem; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.04); max-width: 800px;">
    <h3 style="font-weight: 800; margin-bottom: 1.5rem;"><i class="fas fa-file-invoice-dollar" style="color:#F97316;"></i> Nueva Orden de Compra</h3>
    
    <form action="{{ route('farmacia.storeOrden') }}" method="POST">
        @csrf
        <div style="margin-bottom:1.5rem;">
            <label style="font-size:0.8rem; font-weight:700; color:#736860;">Proveedor:</label>
            <select name="provider_id" required style="width:100%; padding:0.6rem; border:1px solid #E5E7EB; border-radius:6px;">
                @foreach($providers as $prov)
                <option value="{{ $prov->id }}">{{ $prov->name }} - {{ $prov->supply_type }}</option>
                @endforeach
            </select>
        </div>

        <div style="margin-bottom:1rem;">
            <label style="font-size:0.8rem; font-weight:700; color:#C7291C;">Medicamentos con Stock Bajo (seleccionar para reordenar):</label>
        </div>

        @foreach($lowStock as $i => $med)
        <div style="background:#FFF5EB; padding:0.75rem; border-radius:8px; margin-bottom:0.5rem; display:flex; justify-content:space-between; align-items:center; border-left:3px solid #FF8C42;">
            <label style="display:flex; align-items:center; gap:0.5rem; cursor:pointer;">
                <input type="checkbox" name="items[{{ $i }}][medication_id]" value="{{ $med->id }}" style="width:18px; height:18px;">
                <div>
                    <span style="font-weight:700;">{{ $med->name }}</span>
                    <span style="font-size:0.8rem; color:#736860;">| Stock: {{ $med->stock }} / Min: {{ $med->min_stock }} | ${{ number_format($med->price, 2) }}/ud</span>
                </div>
            </label>
            <input type="number" name="items[{{ $i }}][quantity]" value="{{ $med->min_stock * 2 }}" min="1" style="width:80px; padding:0.4rem; border:1px solid #E5E7EB; border-radius:4px; text-align:center;">
        </div>
        @endforeach

        <button type="submit" style="width:100%; background:#F97316; color:white; border:none; padding:0.8rem; border-radius:8px; font-weight:700; cursor:pointer; margin-top:1rem;">
            <i class="fas fa-paper-plane"></i> Generar Orden de Compra
        </button>
    </form>
</div>
@endsection
