@extends('superadmin.layout')
@section('title', 'Centro de Reportes Automáticos')
@section('nav-reportes', 'active')

@section('content')
<div style="background: white; padding: 1.5rem; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.04); margin-bottom: 1.5rem;">
    <h3 style="font-weight: 800; color: #1E1A17;">Generación de Reportes en Tiempo Real</h3>
    <p style="color: #736860; font-size: 0.85rem;">Exporta información crítica del hospital en formato PDF con un clic.</p>
</div>

<div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem;">
    <a href="{{ route('superadmin.report.personal') }}" target="_blank" style="text-decoration: none; background: white; padding: 2rem; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.04); text-align: center; border-top: 4px solid #F05A4E; transition: 0.2s;" onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='none'">
        <i class="fas fa-users" style="font-size: 2.5rem; color: #F05A4E; margin-bottom: 1rem;"></i>
        <h4 style="font-weight: 800; color: #1E1A17; margin-bottom: 0.5rem;">Reporte de Personal</h4>
        <p style="color: #736860; font-size: 0.85rem;">Listado de empleados, roles, validaciones y estatus.</p>
    </a>
    
    <a href="{{ route('superadmin.report.camas') }}" target="_blank" style="text-decoration: none; background: white; padding: 2rem; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.04); text-align: center; border-top: 4px solid #2D9E6A; transition: 0.2s;" onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='none'">
        <i class="fas fa-bed" style="font-size: 2.5rem; color: #2D9E6A; margin-bottom: 1rem;"></i>
        <h4 style="font-weight: 800; color: #1E1A17; margin-bottom: 0.5rem;">Reporte de Recursos</h4>
        <p style="color: #736860; font-size: 0.85rem;">Ocupación hospitalaria, pisos y estatus de camas.</p>
    </a>
    
    <a href="{{ route('superadmin.report.farmacia') }}" target="_blank" style="text-decoration: none; background: white; padding: 2rem; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.04); text-align: center; border-top: 4px solid #FF8C42; transition: 0.2s;" onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='none'">
        <i class="fas fa-pills" style="font-size: 2.5rem; color: #FF8C42; margin-bottom: 1rem;"></i>
        <h4 style="font-weight: 800; color: #1E1A17; margin-bottom: 0.5rem;">Reporte de Farmacia</h4>
        <p style="color: #736860; font-size: 0.85rem;">Inventario, stock crítico y medicamentos controlados.</p>
    </a>
</div>
@endsection
