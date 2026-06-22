@extends('medico.layout')
@section('title', 'Diagnósticos')
@section('nav-diagnosticos', 'active')
@section('content')
<div style="background:white; padding:1.5rem; border-radius:12px; box-shadow:0 4px 6px rgba(0,0,0,0.04); margin-bottom:1.5rem;">
    <h3 style="font-weight:800;"><i class="fas fa-file-medical" style="color:#3B82F6;"></i> Diagnósticos</h3>
</div>
@foreach($pacientes as $p)
<div style="background:white; padding:1rem; border-radius:8px; margin-bottom:0.75rem; border-left:3px solid #3B82F6; box-shadow:0 2px 4px rgba(0,0,0,0.04);">
    <div style="font-weight:700;">{{ $p->patient_name }} <span style="font-size:0.75rem; color:#94A3B8;">{{ $p->created_at->format('d/m/Y') }}</span></div>
    <div style="font-size:0.85rem; color:#1E293B; margin-top:0.25rem;"><strong>DX:</strong> {{ $p->diagnostico }}</div>
    @if($p->cie10)<div style="font-size:0.8rem; color:#64748B;">CIE-10: {{ $p->cie10 }}</div>@endif
</div>
@endforeach
@endsection
