@extends('farmacia.layout')
@section('title', 'Deteccion de Anomalias')
@section('nav-anomalias', 'active')

@section('content')
<div style="background: #FFF1F0; padding: 2rem; border-radius: 12px; margin-bottom: 2rem; display: flex; align-items: center; gap: 1.5rem; border-left: 4px solid #C7291C;">
    <i class="fas fa-microscope" style="font-size: 3rem; color: #C7291C;"></i>
    <div>
        <h3 style="font-weight: 800; color: #8C1A11;">Centro de Deteccion de Anomalias</h3>
        <p style="color: #736860;">Medicamentos vencidos, stock critico e intentos de dispensacion denegados.</p>
    </div>
</div>

<div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem; margin-bottom: 2rem;">
    <div style="background: white; padding: 1.5rem; border-radius: 12px; border-top: 4px solid #C7291C; box-shadow: 0 4px 6px rgba(0,0,0,0.04); text-align: center;">
        <i class="fas fa-skull-crossbones" style="font-size: 2rem; color: #C7291C;"></i>
        <div style="font-size: 2rem; font-weight: 800; color: #C7291C; margin-top: 0.5rem;">{{ $expired->count() }}</div>
        <div style="font-size: 0.8rem; color: #736860; font-weight: 700;">VENCIDOS</div>
    </div>
    <div style="background: white; padding: 1.5rem; border-radius: 12px; border-top: 4px solid #FF8C42; box-shadow: 0 4px 6px rgba(0,0,0,0.04); text-align: center;">
        <i class="fas fa-exclamation-triangle" style="font-size: 2rem; color: #FF8C42;"></i>
        <div style="font-size: 2rem; font-weight: 800; color: #FF8C42; margin-top: 0.5rem;">{{ $critical->count() }}</div>
        <div style="font-size: 0.8rem; color: #736860; font-weight: 700;">STOCK CRITICO (<=5)</div>
    </div>
    <div style="background: white; padding: 1.5rem; border-radius: 12px; border-top: 4px solid #7C2D12; box-shadow: 0 4px 6px rgba(0,0,0,0.04); text-align: center;">
        <i class="fas fa-ban" style="font-size: 2rem; color: #7C2D12;"></i>
        <div style="font-size: 2rem; font-weight: 800; color: #7C2D12; margin-top: 0.5rem;">{{ $denied->count() }}</div>
        <div style="font-size: 0.8rem; color: #736860; font-weight: 700;">RECETAS DENEGADAS</div>
    </div>
</div>

@if($expired->count() > 0)
<div style="background: white; padding: 1.5rem; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.04); margin-bottom: 1.5rem;">
    <h4 style="font-weight: 800; color: #C7291C; margin-bottom: 1rem;"><i class="fas fa-skull-crossbones"></i> Medicamentos Vencidos - Retirar Inmediatamente</h4>
    @foreach($expired as $med)
    <div style="background: #FFF1F0; padding: 0.75rem; border-radius: 6px; margin-bottom: 0.5rem; display: flex; justify-content: space-between; align-items: center; border-left: 3px solid #C7291C;">
        <div>
            <strong>{{ $med->name }}</strong>
            <span style="font-size:0.8rem; color:#736860;"> | Lote: {{ $med->lot_number }} | Ubicacion: {{ $med->location }}</span>
        </div>
        <span style="background:#C7291C; color:white; padding:0.2rem 0.6rem; border-radius:8px; font-size:0.75rem; font-weight:700;">VENCIDO: {{ $med->expiry_date->format('d/m/Y') }}</span>
    </div>
    @endforeach
</div>
@endif

@if($critical->count() > 0)
<div style="background: white; padding: 1.5rem; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.04); margin-bottom: 1.5rem;">
    <h4 style="font-weight: 800; color: #FF8C42; margin-bottom: 1rem;"><i class="fas fa-exclamation-triangle"></i> Stock Critico</h4>
    @foreach($critical as $med)
    <div style="background: #FFF5EB; padding: 0.75rem; border-radius: 6px; margin-bottom: 0.5rem; display: flex; justify-content: space-between; align-items: center; border-left: 3px solid #FF8C42;">
        <div><strong>{{ $med->name }}</strong> <span style="font-size:0.8rem; color:#736860;">| {{ $med->origin }}</span></div>
        <span style="background:#FF8C42; color:white; padding:0.2rem 0.6rem; border-radius:8px; font-size:0.75rem; font-weight:700;">Stock: {{ $med->stock }} / Min: {{ $med->min_stock }}</span>
    </div>
    @endforeach
</div>
@endif

@if($denied->count() > 0)
<div style="background: white; padding: 1.5rem; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.04);">
    <h4 style="font-weight: 800; margin-bottom: 1rem;"><i class="fas fa-ban"></i> Intentos de Dispensacion Denegados</h4>
    @foreach($denied as $log)
    <div style="background: #FFF1F0; padding: 0.75rem; border-radius: 6px; margin-bottom: 0.5rem; border-left: 3px solid #C7291C;">
        <div style="font-size:0.75rem; color:#736860;">{{ \Carbon\Carbon::parse($log->created_at)->format('d/m/Y H:i') }}</div>
        <div style="font-size:0.85rem;">{{ $log->details }}</div>
    </div>
    @endforeach
</div>
@endif
@endsection
