@extends('farmacia.layout')
@section('title', 'Exportar Recetas e Inventario')
@section('nav-exportar', 'active')

@section('content')
<div style="background: white; padding: 1.5rem; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.04); margin-bottom: 1.5rem;">
    <h3 style="font-weight: 800; color: #7C2D12;"><i class="fas fa-file-medical-alt" style="color:#F97316;"></i> Exportar Informacion</h3>
    <p style="color: #736860; font-size: 0.85rem;">Genera reportes del inventario y movimientos de farmacia.</p>
</div>

<div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem;">
    <a href="{{ route('farmacia.export.pdf') }}" style="background: white; padding: 2rem; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.04); text-decoration: none; text-align: center; border-top: 4px solid #C7291C; transition: 0.2s;" onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='none'">
        <i class="fas fa-file-pdf" style="font-size: 3rem; color: #C7291C;"></i>
        <h4 style="font-weight: 800; color: #7C2D12; margin-top: 1rem;">Inventario PDF</h4>
        <p style="font-size: 0.8rem; color: #736860; margin-top: 0.5rem;">Reporte completo del inventario actual</p>
    </a>
    <a href="{{ route('farmacia.export.csv') }}" style="background: white; padding: 2rem; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.04); text-decoration: none; text-align: center; border-top: 4px solid #F97316; transition: 0.2s;" onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='none'">
        <i class="fas fa-file-csv" style="font-size: 3rem; color: #F97316;"></i>
        <h4 style="font-weight: 800; color: #7C2D12; margin-top: 1rem;">Inventario CSV</h4>
        <p style="font-size: 0.8rem; color: #736860; margin-top: 0.5rem;">Datos en formato Excel compatible</p>
    </a>
    <div style="background: white; padding: 2rem; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.04); text-align: center; border-top: 4px solid #DC2626; opacity: 0.6;">
        <i class="fas fa-prescription" style="font-size: 3rem; color: #DC2626;"></i>
        <h4 style="font-weight: 800; color: #7C2D12; margin-top: 1rem;">Recetas (Proximo)</h4>
        <p style="font-size: 0.8rem; color: #736860; margin-top: 0.5rem;">Reporte de recetas dispensadas</p>
    </div>
</div>
@endsection
