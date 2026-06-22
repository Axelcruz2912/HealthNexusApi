@extends('superadmin.layout')
@section('title', 'Monitor Live del Hospital')
@section('nav-monitor', 'active')

@section('content')
<div style="background: #2D9E6A; color: white; padding: 2rem; border-radius: 12px; margin-bottom: 2rem; display: flex; align-items: center; gap: 1.5rem; animation: pulse 2s infinite;">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>@keyframes pulse { 0% { opacity: 1; } 50% { opacity: 0.8; } 100% { opacity: 1; } }</style>
    <i class="fas fa-broadcast-tower" style="font-size: 3rem;"></i>
    <div>
        <h3 style="font-weight: 800; margin-bottom: 0.5rem;">Transmisión en Vivo</h3>
        <p style="opacity: 0.9;">Monitoreo de actividad en este preciso instante.</p>
    </div>
</div>

<div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem; margin-bottom: 2rem;">
    <div style="background: white; padding: 2rem; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.04); text-align: center; border-top: 4px solid #2D9E6A;">
        <h2 style="font-size: 3rem; font-weight: 800; color: #2D9E6A;">{{ $sessions->count() }}</h2>
        <p style="color: #736860; font-weight: 600;">Sesiones Activas Ahora</p>
    </div>
    <div style="background: white; padding: 2rem; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.04); text-align: center; border-top: 4px solid #F05A4E;">
        <h2 style="font-size: 3rem; font-weight: 800; color: #F05A4E;">{{ $urgencies }}</h2>
        <p style="color: #736860; font-weight: 600;">Pacientes en Urgencias</p>
    </div>
    <div style="background: white; padding: 2rem; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.04); text-align: center; border-top: 4px solid #FF8C42;">
        <h2 style="font-size: 3rem; font-weight: 800; color: #FF8C42;">{{ $low_stock }}</h2>
        <p style="color: #736860; font-weight: 600;">Medicamentos Baja Exist.</p>
    </div>
</div>

<h3 style="font-weight: 800; margin-bottom: 1rem;">Usuarios Conectados</h3>
<div style="background: white; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.04); overflow: hidden;">
    <table style="width: 100%; border-collapse: collapse;">
        <thead><tr style="background: #1E1A17; color: white; text-align: left;"><th style="padding:1rem;">Nombre</th><th style="padding:1rem;">Rol</th><th style="padding:1rem;">Última Actividad</th></tr></thead>
        <tbody>
            @foreach($sessions as $session)
            <tr style="border-bottom: 1px solid #E5E7EB;">
                <td style="padding: 1rem; font-weight: 700;">{{ $session->user_name }}</td>
                <td style="padding: 1rem;"><span style="background:#E5E7EB; padding:0.2rem 0.5rem; border-radius:10px; font-size:0.75rem; font-weight:700;">{{ $session->user_role }}</span></td>
                <td style="padding: 1rem; color: #2D9E6A; font-weight: 600;">Hace {{ \Carbon\Carbon::createFromTimestamp($session->last_activity)->diffForHumans() }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
