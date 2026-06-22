@extends('farmacia.layout')
@section('title', 'Ordenes de Compra')
@section('nav-ordenes', 'active')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
    <h3 style="font-weight: 800; color: #7C2D12;"><i class="fas fa-file-invoice-dollar" style="color:#F97316;"></i> Ordenes de Compra</h3>
    <a href="{{ route('farmacia.crearOrden') }}" style="background:#F97316; color:white; text-decoration:none; padding:0.6rem 1.2rem; border-radius:8px; font-weight:700; font-size:0.85rem;"><i class="fas fa-plus"></i> Nueva Orden</a>
</div>

@if(session('success'))
<div style="background:#FFF7ED; color:#9A3412; padding:1rem; border-radius:8px; margin-bottom:1rem; border-left:4px solid #F97316;">
    <i class="fas fa-check-circle"></i> {{ session('success') }}
</div>
@endif

<div style="background: white; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.04); overflow: hidden;">
    <table style="width: 100%; border-collapse: collapse; font-size: 0.85rem;">
        <thead>
            <tr style="background: #7C2D12; color: white; text-align: left;">
                <th style="padding:0.75rem;">Orden</th>
                <th style="padding:0.75rem;">Proveedor</th>
                <th style="padding:0.75rem;">Monto</th>
                <th style="padding:0.75rem;">Entrega Esperada</th>
                <th style="padding:0.75rem;">Estatus</th>
                <th style="padding:0.75rem;">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $order)
            @php $statusColors = ['Borrador' => '#736860', 'Enviada' => '#DC2626', 'En Transito' => '#FF8C42', 'Recibida' => '#F97316', 'Cancelada' => '#C7291C']; @endphp
            <tr style="border-bottom: 1px solid #E5E7EB;">
                <td style="padding:0.75rem; font-weight:700; font-family:monospace;">{{ $order->po_number }}</td>
                <td style="padding:0.75rem;">{{ $order->provider->name }}</td>
                <td style="padding:0.75rem; font-weight:700;">${{ number_format($order->total_amount, 2) }}</td>
                <td style="padding:0.75rem; font-size:0.85rem;">{{ $order->expected_delivery ? $order->expected_delivery->format('d/m/Y') : 'N/A' }}</td>
                <td style="padding:0.75rem;"><span style="background:{{ $statusColors[$order->status] }}; color:white; padding:0.15rem 0.5rem; border-radius:8px; font-size:0.7rem; font-weight:700;">{{ $order->status }}</span></td>
                <td style="padding:0.75rem;">
                    @if($order->status != 'Recibida' && $order->status != 'Cancelada')
                    <form action="{{ route('farmacia.recibirOrden', $order->id) }}" method="POST" style="display:inline;">
                        @csrf @method('PUT')
                        <button type="submit" style="background:#F97316; color:white; border:none; padding:0.3rem 0.6rem; border-radius:4px; font-weight:700; cursor:pointer; font-size:0.75rem;"><i class="fas fa-check"></i> Recibir</button>
                    </form>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
