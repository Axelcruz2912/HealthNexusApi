@extends('superadmin.layout')
@section('title', 'Centro de Ingesta de Datos')
@section('nav-ingesta', 'active')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
    <div>
        <h3 style="font-weight: 800; color: #1E1A17;">Ingesta Masiva de Datos</h3>
        <p style="color: #736860; font-size: 0.85rem;">Sube archivos CSV para procesar con Pandas/Python y limpiar datos hospitalarios.</p>
    </div>
</div>

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
    <div style="background: white; padding: 2rem; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.04); border-top: 4px solid #F05A4E;">
        <h4 style="font-weight: 800; margin-bottom: 1rem;">1. Cargar Archivo Fuente</h4>
        <form action="{{ route('superadmin.uploadCSV') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div style="border: 2px dashed #E5E7EB; border-radius: 8px; padding: 2rem; text-align: center; margin-bottom: 1rem;">
                <i class="fas fa-file-csv" style="font-size: 2rem; color: #F05A4E; margin-bottom: 0.5rem;"></i><br>
                <input type="file" name="csv_file" accept=".csv,.txt" required style="margin-bottom: 1rem;">
                <p style="color: #736860; font-size: 0.8rem;">Formatos aceptados: CSV, TXT (Separado por comas)</p>
            </div>
            <button type="submit" style="width: 100%; background: #1E1A17; color: white; border: none; padding: 0.8rem; border-radius: 8px; font-weight: 700; cursor: pointer;">
                <i class="fas fa-upload"></i> Procesar y Previsualizar
            </button>
        </form>
    </div>

    <div style="background: white; padding: 2rem; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.04); border-top: 4px solid #2D9E6A;">
        <h4 style="font-weight: 800; margin-bottom: 1rem;">2. Previsualización de Datos</h4>
        @if(session('csv_preview'))
            <div style="overflow-x: auto; border: 1px solid #E5E7EB; border-radius: 6px;">
                <table style="width: 100%; border-collapse: collapse; font-size: 0.8rem;">
                    <thead style="background: #F9FAFB;">
                        <tr>
                            @foreach(session('csv_headers') as $header)
                            <th style="padding: 0.5rem; border: 1px solid #E5E7EB;">{{ $header }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach(session('csv_preview') as $row)
                        <tr>
                            @foreach($row as $cell)
                            <td style="padding: 0.5rem; border: 1px solid #E5E7EB;">{{ $cell }}</td>
                            @endforeach
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <p style="margin-top: 1rem; font-size: 0.8rem; color: #2D9E6A;"><i class="fas fa-check-circle"></i> Datos leídos correctamente. Listo para limpieza con Python.</p>
        @else
            <div style="text-align: center; padding: 3rem; color: #736860;">
                <i class="fas fa-database" style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.3;"></i>
                <p>Sube un archivo para ver su estructura aquí.</p>
            </div>
        @endif
    </div>
</div>
@endsection
