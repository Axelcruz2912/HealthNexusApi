@extends('enfermeria.layout')
@section('title', 'Alertas LIVE')
@section('nav-alertas', 'active')

@section('content')
<div style="background:white; padding:1.5rem; border-radius:12px; box-shadow:0 4px 6px rgba(0,0,0,0.04); margin-bottom:1.5rem; display:flex; justify-content:space-between; align-items:center;">
    <h3 style="font-weight:800;"><i class="fas fa-bell" style="color:#DC2626;"></i> Centro de Alertas</h3>
    <span style="background:#FEF2F2; color:#DC2626; padding:0.3rem 0.8rem; border-radius:20px; font-size:0.8rem; font-weight:700;">{{ $alerts->where('is_read', false)->count() }} sin leer</span>
</div>

@if($alerts->isEmpty())
<div style="background:#F0FDF4; padding:3rem; border-radius:12px; text-align:center; border:2px solid #BBF7D0;">
    <i class="fas fa-check-circle" style="font-size:3rem; color:#F97316; margin-bottom:1rem;"></i>
    <h3 style="font-weight:800; color:#9A3412;">Sin alertas pendientes</h3>
    <p style="color:#4ADE80;">Todos los pacientes estan estables.</p>
</div>
@else
@foreach($alerts as $alert)
<div style="background:white; padding:1.25rem; border-radius:12px; box-shadow:0 4px 6px rgba(0,0,0,0.04); margin-bottom:1rem; border-left:5px solid {{ $alert->is_read ? '#94A3B8' : ($alert->severity === 'Crítica' ? '#DC2626' : '#F97316') }}; {{ !$alert->is_read ? 'animation: fadeIn 0.3s;' : 'opacity:0.7;' }}">
    <div style="display:flex; justify-content:space-between; align-items:flex-start;">
        <div style="flex:1;">
            <div style="display:flex; align-items:center; gap:0.5rem; margin-bottom:0.5rem;">
                @if(!$alert->is_read)<i class="fas fa-exclamation-circle" style="color:{{ $alert->severity === 'Crítica' ? '#DC2626' : '#F97316' }};"></i>@endif
                <span style="font-weight:800; color:{{ $alert->severity === 'Crítica' ? '#DC2626' : '#F97316' }}; font-size:0.9rem;">{{ $alert->type }}</span>
                <span style="background:{{ $alert->severity === 'Crítica' ? '#FEF2F2' : '#FFFBEB' }}; color:{{ $alert->severity === 'Crítica' ? '#DC2626' : '#D97706' }}; padding:0.1rem 0.5rem; border-radius:10px; font-size:0.7rem; font-weight:700;">{{ $alert->severity }}</span>
            </div>
            <p style="font-size:0.9rem; color:#7C2D12; margin-bottom:0.5rem;">{{ $alert->message }}</p>
            @if($alert->triage)
            <p style="font-size:0.8rem; color:#64748B;">Paciente: <strong>{{ $alert->triage->patient_name }}</strong></p>
            @endif
            <p style="font-size:0.7rem; color:#94A3B8; margin-top:0.25rem;">{{ $alert->created_at->format('d/m/Y H:i:s') }}</p>
        </div>
        @if(!$alert->is_read)
        <form method="POST" action="{{ route('enfermeria.markAlertRead', $alert->id) }}">
            @csrf
            <button type="submit" style="background:#DC2626; color:white; border:none; padding:0.5rem 1rem; border-radius:8px; font-weight:700; cursor:pointer; font-size:0.8rem;"><i class="fas fa-check"></i> Leido</button>
        </form>
        @endif
    </div>
</div>
@endforeach
@endif
{{ $alerts->withQueryString()->links() }}
@endsection
