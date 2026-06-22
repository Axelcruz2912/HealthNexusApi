@extends('farmacia.layout')
@section('title', 'Consumo por Area')
@section('nav-consumo', 'active')

@section('content')
<div style="background: white; padding: 1.5rem; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.04); margin-bottom: 1.5rem;">
    <h3 style="font-weight: 800; color: #7C2D12;"><i class="fas fa-temperature-high" style="color:#F97316;"></i> Consumo y Stock por Area</h3>
    <p style="color: #736860; font-size: 0.85rem;">Distribucion del inventario por origen y nivel de prescripcion.</p>
</div>

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
    <div style="background: white; padding: 1.5rem; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.04);">
        <h4 style="font-weight: 800; margin-bottom: 1rem;">Por Origen / Area</h4>
        @php $totalStock = $origins->sum('total_stock'); @endphp
        @foreach($origins as $origin)
        @php $percent = $totalStock > 0 ? min(100, ($origin->total_stock / $totalStock) * 100) : 0; @endphp
        <div style="margin-bottom: 1.25rem;">
            <div style="display: flex; justify-content: space-between; margin-bottom: 0.25rem;">
                <span style="font-weight: 700;">{{ $origin->origin }}</span>
                <span style="font-size: 0.85rem; color: #736860;">{{ $origin->total_meds }} meds | {{ $origin->total_stock }} uds | ${{ number_format($origin->total_value, 0) }}</span>
            </div>
            <div style="background: #E5E7EB; border-radius: 8px; height: 12px; overflow: hidden;">
                <div style="width: {{ max(5, $percent) }}%; background: linear-gradient(90deg, #F97316, #FB923C); height: 100%; border-radius: 8px;"></div>
            </div>
        </div>
        @endforeach
    </div>

    <div style="background: white; padding: 1.5rem; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.04);">
        <h4 style="font-weight: 800; margin-bottom: 1rem;">Por Nivel de Prescripcion</h4>
        @php $totalLevelStock = $levels->sum('total_stock'); @endphp
        @foreach($levels as $level)
        @php 
            $colors = ['A' => '#C7291C', 'B' => '#FF8C42', 'C' => '#F97316', 'Enfermera' => '#DC2626'];
            $labels = ['A' => 'Especialista', 'B' => 'Hospitalizacion', 'C' => 'Basico', 'Enfermera' => 'Enfermeria'];
            $color = $colors[$level->required_level] ?? '#736860';
            $label = $labels[$level->required_level] ?? $level->required_level;
            $percent = $totalLevelStock > 0 ? min(100, ($level->total_stock / $totalLevelStock) * 100) : 0;
        @endphp
        <div style="margin-bottom: 1.25rem;">
            <div style="display: flex; justify-content: space-between; margin-bottom: 0.25rem;">
                <span style="font-weight: 700; color: {{ $color }};">Nivel {{ $level->required_level }} - {{ $label }}</span>
                <span style="font-size: 0.85rem; color: #736860;">{{ $level->total_meds }} meds | {{ $level->total_stock }} uds</span>
            </div>
            <div style="background: #E5E7EB; border-radius: 8px; height: 12px; overflow: hidden;">
                <div style="width: {{ max(5, $percent) }}%; background: {{ $color }}; height: 100%; border-radius: 8px;"></div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
