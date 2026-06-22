@extends('farmacia.layout')
@section('title', 'Traspasos entre Areas')
@section('nav-traspasos', 'active')

@section('content')
<div style="background: white; padding: 1.5rem; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.04); margin-bottom: 1.5rem;">
    <h3 style="font-weight: 800; color: #7C2D12;"><i class="fas fa-exchange-alt" style="color:#F97316;"></i> Traspasos entre Areas</h3>
    <p style="color: #736860; font-size: 0.85rem;">Mueve medicamentos entre las farmacias del hospital.</p>
</div>

@if(session('success'))
<div style="background:#FFF7ED; color:#9A3412; padding:1rem; border-radius:8px; margin-bottom:1rem; border-left:4px solid #F97316;">
    <i class="fas fa-check-circle"></i> {{ session('success') }}
</div>
@endif

@if(session('error'))
<div style="background:#FFF1F0; color:#8C1A11; padding:1rem; border-radius:8px; margin-bottom:1rem; border-left:4px solid #C7291C;">
    <i class="fas fa-times-circle"></i> {{ session('error') }}
</div>
@endif

<div style="background: white; padding: 2rem; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.04); max-width: 600px;">
    <form action="{{ route('farmacia.traspaso') }}" method="POST">
        @csrf
        <div style="margin-bottom:1rem;">
            <label style="font-size:0.8rem; font-weight:700; color:#736860;">Medicamento:</label>
            <select name="medication_id" required style="width:100%; padding:0.6rem; border:1px solid #E5E7EB; border-radius:6px;">
                @foreach($medications as $med)
                <option value="{{ $med->id }}">{{ $med->name }} (Stock: {{ $med->stock }} | Origen: {{ $med->origin }})</option>
                @endforeach
            </select>
        </div>
        <div style="display:grid; grid-template-columns:1fr auto 1fr; gap:10px; align-items:center; margin-bottom:1rem;">
            <div>
                <label style="font-size:0.8rem; font-weight:700; color:#736860;">Origen:</label>
                <select name="origin" required style="width:100%; padding:0.6rem; border:1px solid #E5E7EB; border-radius:6px;">
                    @foreach($origins as $o)<option value="{{ $o }}">{{ $o }}</option>@endforeach
                </select>
            </div>
            <i class="fas fa-arrow-right" style="color:#F97316; font-size:1.5rem; margin-top:1rem;"></i>
            <div>
                <label style="font-size:0.8rem; font-weight:700; color:#736860;">Destino:</label>
                <select name="destination" required style="width:100%; padding:0.6rem; border:1px solid #E5E7EB; border-radius:6px;">
                    @foreach($origins as $o)<option value="{{ $o }}">{{ $o }}</option>@endforeach
                </select>
            </div>
        </div>
        <div style="margin-bottom:1.5rem;">
            <label style="font-size:0.8rem; font-weight:700; color:#736860;">Cantidad:</label>
            <input type="number" name="quantity" value="1" min="1" required style="width:100%; padding:0.6rem; border:1px solid #E5E7EB; border-radius:6px;">
        </div>
        <button type="submit" style="width:100%; background:#F97316; color:white; border:none; padding:0.8rem; border-radius:8px; font-weight:700; cursor:pointer;">
            <i class="fas fa-exchange-alt"></i> Realizar Traspaso
        </button>
    </form>
</div>
@endsection
