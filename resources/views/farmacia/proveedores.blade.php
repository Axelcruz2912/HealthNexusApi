@extends('farmacia.layout')
@section('title', 'Proveedores y Compras')
@section('nav-proveedores', 'active')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
    <h3 style="font-weight: 800; color: #7C2D12;"><i class="fas fa-truck" style="color:#F97316;"></i> Proveedores y Score</h3>
    <a href="{{ route('farmacia.crearOrden') }}" style="background:#F97316; color:white; text-decoration:none; padding:0.6rem 1.2rem; border-radius:8px; font-weight:700; font-size:0.85rem;"><i class="fas fa-plus"></i> Nueva Orden de Compra</a>
</div>

<div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1.5rem;">
    @foreach($providers as $prov)
    @php $totalScore = ($prov->delivery_score + $prov->price_score + $prov->quality_score) / 3; @endphp
    <div style="background: white; padding: 1.5rem; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.04); border-left: 4px solid {{ $totalScore >= 8 ? '#F97316' : ($totalScore >= 6 ? '#FF8C42' : '#C7291C') }};">
        <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 1rem;">
            <div>
                <h4 style="font-weight: 800; color: #7C2D12;">{{ $prov->name }}</h4>
                <div style="font-size:0.8rem; color:#736860;">{{ $prov->supply_type }} | RFC: {{ $prov->rfc }}</div>
            </div>
            <div style="text-align:center;">
                <div style="font-size:1.8rem; font-weight:800; color:{{ $totalScore >= 8 ? '#F97316' : ($totalScore >= 6 ? '#FF8C42' : '#C7291C') }};">{{ number_format($totalScore, 1) }}</div>
                <div style="font-size:0.65rem; color:#736860; font-weight:700;">SCORE</div>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 0.5rem; margin-bottom: 1rem;">
            <div style="background:#F9FAFB; padding:0.5rem; border-radius:6px; text-align:center;">
                <div style="font-size:0.65rem; color:#736860; font-weight:700;">ENTREGA</div>
                <div style="font-weight:800; color:#7C2D12;">{{ $prov->delivery_score }}/10</div>
            </div>
            <div style="background:#F9FAFB; padding:0.5rem; border-radius:6px; text-align:center;">
                <div style="font-size:0.65rem; color:#736860; font-weight:700;">PRECIO</div>
                <div style="font-weight:800; color:#7C2D12;">{{ $prov->price_score }}/10</div>
            </div>
            <div style="background:#F9FAFB; padding:0.5rem; border-radius:6px; text-align:center;">
                <div style="font-size:0.65rem; color:#736860; font-weight:700;">CALIDAD</div>
                <div style="font-weight:800; color:#7C2D12;">{{ $prov->quality_score }}/10</div>
            </div>
        </div>

        <div style="font-size:0.8rem; color:#736860; display:grid; grid-template-columns:1fr 1fr; gap:0.25rem;">
            <div><i class="fas fa-box"></i> {{ $prov->total_orders }} ordenes</div>
            <div><i class="fas fa-clock"></i> {{ $prov->avg_delivery_days }} dias promedio</div>
            <div><i class="fas fa-exclamation-triangle" style="color:{{ $prov->late_deliveries > 5 ? '#C7291C' : '#736860' }}"></i> {{ $prov->late_deliveries }} entregas tardias</div>
            <div><i class="fas fa-phone"></i> {{ $prov->phone }}</div>
        </div>
    </div>
    @endforeach
</div>
@endsection
