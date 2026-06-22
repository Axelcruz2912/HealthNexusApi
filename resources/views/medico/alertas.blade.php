@extends('medico.layout')
@section('title', 'Alertas')
@section('nav-alertas', 'active')
@section('content')
<div style="background:white; padding:1.5rem; border-radius:12px; box-shadow:0 4px 6px rgba(0,0,0,0.04); margin-bottom:1.5rem;">
    <h3 style="font-weight:800;"><i class="fas fa-bell" style="color:#F59E0B;"></i> Alertas Médicas</h3>
</div>
@foreach($alerts as $a)
<div style="background:white; padding:1rem; border-radius:8px; margin-bottom:0.75rem; border-left:4px solid {{ $a->severity === 'Crítica' ? '#DC2626' : '#F59E0B' }}; box-shadow:0 2px 4px rgba(0,0,0,0.04); {{ !$a->is_read ? '' : 'opacity:0.6;' }}">
    <div style="display:flex; justify-content:space-between; align-items:center;">
        <div>
            <span style="font-weight:800; color:{{ $a->severity === 'Crítica' ? '#DC2626' : '#D97706' }};">{{ $a->type }}</span>
            <span style="font-size:0.75rem; background:{{ $a->severity === 'Crítica' ? '#FEF2F2' : '#FFFBEB' }}; color:{{ $a->severity === 'Crítica' ? '#DC2626' : '#D97706' }}; padding:0.1rem 0.5rem; border-radius:10px; font-weight:700;">{{ $a->severity }}</span>
        </div>
        @if(!$a->is_read)
        <form method="POST" action="{{ route('medico.markAlertRead', $a->id) }}">@csrf <button style="background:#2D9E6A; color:white; border:none; padding:0.3rem 0.8rem; border-radius:6px; font-size:0.75rem; font-weight:700; cursor:pointer;">Leído</button></form>
        @endif
    </div>
    <div style="font-size:0.85rem; color:#475569; margin-top:0.25rem;">{{ $a->message }}</div>
    <div style="font-size:0.7rem; color:#94A3B8;">{{ $a->created_at->format('d/m/Y H:i') }}</div>
</div>
@endforeach
@endsection
