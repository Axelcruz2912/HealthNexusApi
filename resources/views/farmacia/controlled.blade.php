@extends('farmacia.layout')
@section('title', 'Medicamentos Controlados')
@section('nav-controlados', 'active')

@section('content')
<div style="background: #FFF1F0; padding: 2rem; border-radius: 12px; margin-bottom: 2rem; display: flex; align-items: center; gap: 1.5rem; border-left: 4px solid #C7291C;">
    <i class="fas fa-lock" style="font-size: 3rem; color: #C7291C;"></i>
    <div>
        <h3 style="font-weight: 800; color: #8C1A11;">Medicamentos Controlados - Nivel A</h3>
        <p style="color: #736860;">Solo medicos con Nivel A (Especialistas) pueden recetar estos medicamentos. Toda prescripcion queda registrada en auditoria.</p>
    </div>
</div>

<div style="background: white; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.04); overflow: hidden;">
    <table style="width: 100%; border-collapse: collapse; font-size: 0.85rem;">
        <thead>
            <tr style="background: #8C1A11; color: white; text-align: left;">
                <th style="padding:0.75rem;">Medicamento</th>
                <th style="padding:0.75rem;">Principio Activo</th>
                <th style="padding:0.75rem;">Stock</th>
                <th style="padding:0.75rem;">Lote</th>
                <th style="padding:0.75rem;">Caducidad</th>
                <th style="padding:0.75rem;">Proveedor</th>
            </tr>
        </thead>
        <tbody>
            @foreach($controlled_meds as $med)
            <tr style="border-bottom: 1px solid #E5E7EB;">
                <td style="padding:0.75rem; font-weight:700; color:#8C1A11;"><i class="fas fa-lock" style="font-size:0.7rem;"></i> {{ $med->name }}</td>
                <td style="padding:0.75rem;">{{ $med->active_ingredient }}</td>
                <td style="padding:0.75rem; font-weight:800; color:{{ $med->stock <= $med->min_stock ? '#C7291C' : '#7C2D12' }};">{{ $med->stock }}</td>
                <td style="padding:0.75rem; font-family:monospace; font-size:0.8rem;">{{ $med->lot_number ?? 'S/N' }}</td>
                <td style="padding:0.75rem;">{{ $med->expiry_date ? $med->expiry_date->format('d/m/Y') : 'N/A' }}</td>
                <td style="padding:0.75rem; font-size:0.8rem;">{{ $med->provider_name ?? 'N/A' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
