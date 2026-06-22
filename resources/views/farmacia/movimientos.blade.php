@extends('farmacia.layout')
@section('title', 'Registro de Movimientos')
@section('nav-movimientos', 'active')

@section('content')
<div style="background: white; padding: 1.5rem; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.04); margin-bottom: 1.5rem; display: flex; justify-content: space-between; align-items: center;">
    <h3 style="font-weight: 800; color: #7C2D12;"><i class="fas fa-clipboard-list" style="color:#F97316;"></i> Movimientos de Farmacia</h3>
    <span style="background:#FFF7ED; color:#1B7A4A; padding:0.3rem 0.6rem; border-radius:10px; font-size:0.75rem; font-weight:700;">Ultimos 50 registros</span>
</div>

@if($logs->count() > 0)
<div style="background: white; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.04); overflow: hidden;">
    <table style="width: 100%; border-collapse: collapse; font-size: 0.85rem;">
        <thead>
            <tr style="background: #7C2D12; color: white; text-align: left;">
                <th style="padding:0.75rem;">Fecha/Hora</th>
                <th style="padding:0.75rem;">Accion</th>
                <th style="padding:0.75rem;">Detalles</th>
                <th style="padding:0.75rem;">Usuario</th>
                <th style="padding:0.75rem;">IP</th>
            </tr>
        </thead>
        <tbody>
            @foreach($logs as $log)
            <tr style="border-bottom: 1px solid #E5E7EB; {{ $log->action == 'Dispensacion DENEGADA' ? 'background:#FFF1F0;' : '' }}">
                <td style="padding:0.75rem; font-size:0.8rem; color:#736860;">{{ \Carbon\Carbon::parse($log->created_at)->format('d/m/Y H:i') }}</td>
                <td style="padding:0.75rem;">
                    @php $actionColor = '#F97316'; @endphp
                    @if(strpos($log->action, 'DENEGADA') !== false) @php $actionColor = '#C7291C'; @endphp @endif
                    @if(strpos($log->action, 'Registrado') !== false) @php $actionColor = '#DC2626'; @endphp @endif
                    @if(strpos($log->action, 'Traspaso') !== false) @php $actionColor = '#FF8C42'; @endphp @endif
                    <span style="background:{{ $actionColor }}; color:white; padding:0.15rem 0.5rem; border-radius:8px; font-size:0.7rem; font-weight:700;">{{ $log->action }}</span>
                </td>
                <td style="padding:0.75rem; font-size:0.85rem;">{{ $log->details }}</td>
                <td style="padding:0.75rem; font-weight:600;">{{ $log->user_name }}</td>
                <td style="padding:0.75rem; font-size:0.75rem; color:#736860;">{{ $log->ip_address }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@else
<div style="background: white; padding: 3rem; border-radius: 12px; text-align: center; color: #736860;">
    <i class="fas fa-clipboard-list" style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.3;"></i>
    <h3 style="font-weight: 800;">Sin movimientos registrados</h3>
    <p>Los movimientos aparecen al dispensar, registrar o traspasar medicamentos.</p>
</div>
@endif
@endsection
