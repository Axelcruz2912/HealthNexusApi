@extends('farmacia.layout')
@section('title', 'Carros de Emergencia')
@section('nav-crashcarts', 'active')

@section('content')
<div style="background: #C7291C; color: white; padding: 2rem; border-radius: 12px; margin-bottom: 2rem; display: flex; align-items: center; gap: 1.5rem;">
    <i class="fas fa-first-aid" style="font-size: 3rem;"></i>
    <div>
        <h3 style="font-weight: 800;">Carros de Emergencia (Crash Carts)</h3>
        <p style="opacity: 0.9;">Verificacion diaria obligatoria. Todo carro debe estar completo antes de turno.</p>
    </div>
</div>

<div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1.5rem;">
    @foreach($carts as $cart)
    @php $statusColors = ['Completo' => '#F97316', 'Incompleto' => '#C7291C', 'En Uso' => '#FF8C42', 'Reabasteciendo' => '#DC2626']; @endphp
    <div style="background: white; padding: 1.5rem; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.04); border-top: 4px solid {{ $statusColors[$cart->status] ?? '#736860' }};">
        <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 1rem;">
            <div>
                <h4 style="font-weight: 800; color: #7C2D12;">{{ $cart->name }}</h4>
                <div style="font-size:0.85rem; color:#736860;"><i class="fas fa-map-marker-alt"></i> {{ $cart->location }}</div>
            </div>
            <span style="background:{{ $statusColors[$cart->status] ?? '#736860' }}; color:white; padding:0.3rem 0.8rem; border-radius:10px; font-size:0.75rem; font-weight:700;">{{ $cart->status }}</span>
        </div>

        <div style="background:#F9FAFB; padding:1rem; border-radius:8px; margin-bottom:1rem;">
            <div style="font-size:0.75rem; font-weight:700; color:#736860; margin-bottom:0.5rem; text-transform:uppercase;">Contenido:</div>
            <div style="font-size:0.85rem; color:#7C2D12;">{{ $cart->contents }}</div>
        </div>

        <div style="display:flex; justify-content:space-between; align-items:center;">
            <div style="font-size:0.8rem; color:#736860;">
                @if($cart->last_checked)
                    <i class="fas fa-check-circle" style="color:#F97316;"></i> Verificado: {{ \Carbon\Carbon::parse($cart->last_checked)->format('d/m/Y H:i') }} por {{ $cart->checked_by }}
                @else
                    <i class="fas fa-exclamation-circle" style="color:#C7291C;"></i> SIN VERIFICAR
                @endif
            </div>
            <form action="{{ route('farmacia.checkCart', $cart->id) }}" method="POST">
                @csrf
                <button type="submit" style="background:#F97316; color:white; border:none; padding:0.5rem 1rem; border-radius:6px; font-weight:700; cursor:pointer; font-size:0.8rem;">
                    <i class="fas fa-clipboard-check"></i> Verificar
                </button>
            </form>
        </div>
    </div>
    @endforeach
</div>
@endsection
