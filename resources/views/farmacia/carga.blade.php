@extends('farmacia.layout')
@section('title', 'Carga Masiva de Medicamentos')
@section('nav-carga', 'active')

@section('content')
<div style="background: white; padding: 1.5rem; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.04); margin-bottom: 1.5rem;">
    <h3 style="font-weight: 800; color: #7C2D12;"><i class="fas fa-upload" style="color:#F97316;"></i> Carga Masiva desde CSV</h3>
    <p style="color: #736860; font-size: 0.85rem;">Sube un archivo CSV con los medicamentos a registrar.</p>
</div>

@if(session('success'))
<div style="background:#FFF7ED; color:#9A3412; padding:1rem; border-radius:8px; margin-bottom:1rem; border-left:4px solid #F97316;">
    <i class="fas fa-check-circle"></i> {{ session('success') }}
</div>
@endif

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
    <div style="background: white; padding: 2rem; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.04);">
        <h4 style="font-weight: 800; margin-bottom: 1rem;">Subir Archivo</h4>
        <form action="{{ route('farmacia.uploadCSV') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div style="margin-bottom: 1rem;">
                <input type="file" name="csv_file" accept=".csv,.txt" required style="width: 100%; padding: 1rem; border: 2px dashed #F97316; border-radius: 8px; background: #FFF7ED; cursor: pointer;">
            </div>
            <button type="submit" style="width:100%; background:#F97316; color:white; border:none; padding:0.8rem; border-radius:8px; font-weight:700; cursor:pointer;">
                <i class="fas fa-upload"></i> Cargar Medicamentos
            </button>
        </form>
    </div>

    <div style="background: white; padding: 2rem; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.04);">
        <h4 style="font-weight: 800; margin-bottom: 1rem;">Formato del CSV</h4>
        <div style="background: #F4F6F8; padding: 1rem; border-radius: 8px; font-family: monospace; font-size: 0.8rem; margin-bottom: 1rem;">
            nombre, principio_activo, stock, min_stock, precio, nivel(A/B/C/Enfermera), origen, lote, caducidad, ubicacion, proveedor
        </div>
        <div style="font-size: 0.85rem; color: #736860;">
            <p style="margin-bottom: 0.5rem;"><i class="fas fa-info-circle" style="color:#DC2626;"></i> La primera fila debe ser el encabezado.</p>
            <p style="margin-bottom: 0.5rem;"><i class="fas fa-info-circle" style="color:#DC2626;"></i> Nivel debe ser: A, B, C o Enfermera.</p>
            <p><i class="fas fa-info-circle" style="color:#DC2626;"></i> Origen: Central, Hospitalaria, Quirurgico, Urgencias.</p>
        </div>
    </div>
</div>
@endsection
