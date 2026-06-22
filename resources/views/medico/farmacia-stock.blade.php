@extends('medico.layout')
@section('title', 'Stock Farmacia')
@section('nav-farmaciaStock', 'active')
@section('content')
@php $role = session('doctor_profile', 'Médico C'); @endphp
<div style="background:white; padding:1.5rem; border-radius:12px; box-shadow:0 2px 8px rgba(0,0,0,0.06); margin-bottom:1.5rem;">
    <h3 style="font-weight:800;"><i class="fas fa-capsules" style="color:#EA580C;"></i> Stock de Farmacia</h3>
</div>

<div style="background:white; border-radius:12px; overflow:hidden; box-shadow:0 2px 8px rgba(0,0,0,0.06);">
    <table style="width:100%; border-collapse:collapse;">
        <thead><tr style="background:#FFF7ED;">
            <th style="padding:0.75rem; text-align:left; font-size:0.8rem; color:#9A3412; font-weight:700;">Medicamento</th>
            <th style="padding:0.75rem; text-align:left; font-size:0.8rem; color:#9A3412; font-weight:700;">Presentación</th>
            <th style="padding:0.75rem; text-align:center; font-size:0.8rem; color:#9A3412; font-weight:700;">Stock</th>
            <th style="padding:0.75rem; text-align:center; font-size:0.8rem; color:#9A3412; font-weight:700;">Nivel</th>
        </tr></thead>
        <tbody>
        @foreach($medicamentos as $m)
        <tr style="border-bottom:1px solid #F5F0EB;">
            <td style="padding:0.75rem; font-weight:700;">{{ $m->name }} @if($m->required_level === 'A')<i class="fas fa-lock" style="color:#DC2626; font-size:0.7rem;"></i>@endif</td>
            <td style="padding:0.75rem; color:#78716C; font-size:0.85rem;">{{ $m->presentation }}</td>
            <td style="padding:0.75rem; text-align:center; font-weight:700; color:{{ $m->stock < 10 ? '#DC2626' : ($m->stock < 30 ? '#EA580C' : '#166534') }};">{{ $m->stock }}</td>
            <td style="padding:0.75rem; text-align:center;">
                @if($m->required_level === 'A')<span style="background:#FEF2F2; color:#DC2626; padding:0.15rem 0.5rem; border-radius:10px; font-size:0.7rem; font-weight:700;">🔒 A</span>
                @elseif($m->required_level === 'B')<span style="background:#FFF7ED; color:#EA580C; padding:0.15rem 0.5rem; border-radius:10px; font-size:0.7rem; font-weight:700;">B</span>
                @else<span style="background:#F0FDF4; color:#166534; padding:0.15rem 0.5rem; border-radius:10px; font-size:0.7rem; font-weight:700;">C</span>@endif
            </td>
        </tr>
        @endforeach
        </tbody>
    </table>
</div>
@endsection
