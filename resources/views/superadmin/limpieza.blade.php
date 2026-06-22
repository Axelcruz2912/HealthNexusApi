@extends('superadmin.layout')
@section('title', 'Motor de Limpieza de Datos')
@section('nav-limpieza', 'active')

@section('content')
<div style="background: white; padding: 1.5rem; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.04); margin-bottom: 1.5rem;">
    <h3 style="font-weight: 800; color: #1E1A17;">Motor de Calidad de Datos (ETL)</h3>
    <p style="color: #736860; font-size: 0.85rem;">Estandarización, validación y limpieza de la base de datos hospitalaria.</p>
</div>

<div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1.5rem;">
    
    <!-- Limpieza de Texto -->
    <div style="background: white; padding: 2rem; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.04); border-top: 4px solid #F05A4E;">
        <h4 style="font-weight: 800; margin-bottom: 1rem;"><i class="fas fa-font" style="color: #F05A4E;"></i> Estandarización de Texto</h4>
        <p style="font-size: 0.85rem; color: #736860; margin-bottom: 1.5rem;">Convierte nombres, CURPs y RFCs a mayúsculas y elimina espacios dobles.</p>
        <form action="{{ route('superadmin.cleanData') }}" method="POST">
            @csrf
            <input type="hidden" name="action" value="uppercase">
            <button type="submit" style="width: 100%; background: #1E1A17; color: white; border: none; padding: 0.8rem; border-radius: 8px; font-weight: 700; cursor: pointer;">
                Ejecutar Estandarización
            </button>
        </form>
    </div>

    <!-- Validación de Estructura -->
    <div style="background: white; padding: 2rem; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.04); border-top: 4px solid #FF8C42;">
        <h4 style="font-weight: 800; margin-bottom: 1rem;"><i class="fas fa-search" style="color: #FF8C42;"></i> Validación de Documentos</h4>
        <p style="font-size: 0.85rem; color: #736860; margin-bottom: 1.5rem;">Busca CURPs/RFCs con formatos inválidos o registros sin documentos subidos.</p>
        <form action="{{ route('superadmin.cleanData') }}" method="POST">
            @csrf
            <input type="hidden" name="action" value="validate_docs">
            <button type="submit" style="width: 100%; background: #1E1A17; color: white; border: none; padding: 0.8rem; border-radius: 8px; font-weight: 700; cursor: pointer;">
                Iniciar Validación
            </button>
        </form>
    </div>

    <!-- Búsqueda de Duplicados -->
    <div style="background: white; padding: 2rem; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.04); border-top: 4px solid #2D9E6A;">
        <h4 style="font-weight: 800; margin-bottom: 1rem;"><i class="fas fa-clone" style="color: #2D9E6A;"></i> Eliminación de Duplicados</h4>
        <p style="font-size: 0.85rem; color: #736860; margin-bottom: 1.5rem;">Detecta pacientes o empleados con el mismo RFC/CURP en la base de datos.</p>
        <form action="{{ route('superadmin.cleanData') }}" method="POST">
            @csrf
            <input type="hidden" name="action" value="duplicates">
            <button type="submit" style="width: 100%; background: #1E1A17; color: white; border: none; padding: 0.8rem; border-radius: 8px; font-weight: 700; cursor: pointer;">
                Buscar Duplicados
            </button>
        </form>
    </div>

    <!-- Resultado de la limpieza -->
    <div style="background: white; padding: 2rem; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.04); border-top: 4px solid #1E1A17;">
        <h4 style="font-weight: 800; margin-bottom: 1rem;"><i class="fas fa-terminal" style="color: #1E1A17;"></i> Resultado del Motor</h4>
        @if(session('clean_result'))
            <div style="background: #F4F6F8; border: 1px solid #E5E7EB; border-radius: 8px; padding: 1rem; font-family: monospace; font-size: 0.8rem; max-height: 150px; overflow-y: auto; color: #2D9E6A;">
                {{ session('clean_result') }}
            </div>
        @else
            <div style="text-align: center; color: #736860; padding: 2rem;">
                <i class="fas fa-broom" style="font-size: 2rem; opacity: 0.3; margin-bottom: 0.5rem;"></i>
                <p style="font-size: 0.85rem;">Esperando instrucciones de limpieza...</p>
            </div>
        @endif
    </div>
</div>
@endsection
