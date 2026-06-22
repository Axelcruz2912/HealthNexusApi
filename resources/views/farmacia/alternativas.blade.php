@extends('farmacia.layout')
@section('title', 'Medicamentos Alternativos')

@section('content')
<div style="background: white; padding: 1.5rem; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.04); margin-bottom: 1.5rem;">
    <a href="{{ route('farmacia.desabasto') }}" style="color:#F97316; text-decoration:none; font-weight:700; font-size:0.85rem;"><i class="fas fa-arrow-left"></i> Volver a Desabasto</a>
    <h3 style="font-weight: 800; color: #7C2D12; margin-top: 0.5rem;">
        <i class="fas fa-exchange-alt" style="color:#DC2626;"></i> Alternativas para: {{ $med->name }}
    </h3>
    <p style="color: #736860; font-size: 0.85rem;">Stock actual: <strong style="color:#C7291C;">{{ $med->stock }}</strong> unidades | Nivel: {{ $med->required_level }} | Proveedor: {{ $med->provider_name ?? 'N/A' }}</p>
</div>

@if($alternatives->count() > 0)
<div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1.5rem;">
    @foreach($alternatives as $alt)
    <div style="background: white; padding: 1.5rem; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.04); border-left: 4px solid {{ $alt->alternative->stock > 0 ? '#F97316' : '#C7291C' }};">
        <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 1rem;">
            <div>
                <h4 style="font-weight: 800; color: #7C2D12;">{{ $alt->alternative->name }}</h4>
                <div style="font-size:0.8rem; color:#736860;">{{ $alt->alternative->active_ingredient }}</div>
            </div>
            @if($alt->alternative->stock > 0)
            <span style="background:#FFF7ED; color:#9A3412; padding:0.3rem 0.8rem; border-radius:10px; font-size:0.75rem; font-weight:700;">DISPONIBLE</span>
            @else
            <span style="background:#FFF1F0; color:#8C1A11; padding:0.3rem 0.8rem; border-radius:10px; font-size:0.75rem; font-weight:700;">SIN STOCK</span>
            @endif
        </div>

        <div style="background:#F9FAFB; padding:0.75rem; border-radius:8px; margin-bottom:1rem;">
            <div style="font-size:0.75rem; font-weight:700; color:#736860; margin-bottom:0.25rem;">OBSERVACIONES:</div>
            <div style="font-size:0.85rem; color:#7C2D12;">{{ $alt->notes }}</div>
        </div>

        <div style="display:grid; grid-template-columns:1fr 1fr; gap:0.5rem; font-size:0.8rem; color:#736860;">
            <div><i class="fas fa-boxes-stacked"></i> Stock: <strong style="color:{{ $alt->alternative->stock > 0 ? '#F97316' : '#C7291C' }}">{{ $alt->alternative->stock }}</strong></div>
            <div><i class="fas fa-map-marker-alt"></i> {{ $alt->alternative->origin }}</div>
            <div><i class="fas fa-tag"></i> Nivel: {{ $alt->alternative->required_level }}</div>
            <div><i class="fas fa-dollar-sign"></i> ${{ number_format($alt->alternative->price, 2) }}</div>
        </div>
    </div>
    @endforeach
</div>
@else
<div style="background: #FEF2F2; padding: 2rem; border-radius: 12px; text-align: center; color: #7F1D1D;">
    <i class="fas fa-info-circle" style="font-size: 2rem; margin-bottom: 1rem;"></i>
    <h3 style="font-weight: 800;">Sin alternativas registradas</h3>
    <p style="font-size:0.85rem;">No hay medicamentos alternativos configurados para {{ $med->name }}.</p>
    <p style="font-size:0.85rem; margin-top:0.5rem;">Contacta al SuperAdmin para configurar alternativas en el sistema.</p>
</div>
@endif
@endsection
