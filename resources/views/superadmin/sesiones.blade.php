@extends('superadmin.layout')
@section('title', 'Monitor Live - Sesiones Activas')
@section('nav-sesiones', 'active')

@section('content')
<div style="background: white; padding: 1.5rem; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.04); margin-bottom: 1.5rem; display: flex; justify-content: space-between; align-items: center;">
    <h3 style="font-weight: 800; color: #1E1A17;">Usuarios Conectados en Tiempo Real</h3>
    <span style="background: #EBF9F2; color: #065F46; padding: 0.4rem 0.8rem; border-radius: 20px; font-size: 0.8rem; font-weight: 700;"><i class="fas fa-circle" style="font-size: 0.5rem;"></i> {{ $sessions->count() }} Activos</span>
</div>

<div style="background: white; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.04); overflow: hidden;">
    <table style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="background: #F9FAFB; text-align: left; border-bottom: 2px solid #E5E7EB;">
                <th style="padding: 1rem; font-size: 0.8rem; color: #736860; text-transform: uppercase;">Usuario</th>
                <th style="padding: 1rem; font-size: 0.8rem; color: #736860; text-transform: uppercase;">Rol</th>
                <th style="padding: 1rem; font-size: 0.8rem; color: #736860; text-transform: uppercase;">Dirección IP</th>
                <th style="padding: 1rem; font-size: 0.8rem; color: #736860; text-transform: uppercase;">Última Actividad</th>
                <th style="padding: 1rem; font-size: 0.8rem; color: #736860; text-transform: uppercase;">Acción de Seguridad</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sessions as $session)
            <tr style="border-bottom: 1px solid #E5E7EB;" onmouseover="this.style.background='#FFF1EE'" onmouseout="this.style.background='white'">
                <td style="padding: 1rem; font-weight: 600;">{{ $session->user_name ?? 'Invitado' }}</td>
                <td style="padding: 1rem;"><span style="background: #E5E7EB; color: #1E1A17; padding: 0.2rem 0.6rem; border-radius: 10px; font-size: 0.75rem; font-weight: 700;">{{ $session->user_role ?? 'N/A' }}</span></td>
                <td style="padding: 1rem; font-family: monospace; color: #736860;">{{ $session->ip_address }}</td>
                <td style="padding: 1rem; font-size: 0.85rem; color: #736860;">{{ \Carbon\Carbon::createFromTimestamp($session->last_activity)->diffForHumans() }}</td>
                <td style="padding: 1rem;">
                    @if($session->user_id != auth()->id())
                    <form action="{{ route('superadmin.forceLogout', $session->id) }}" method="POST">
                        @csrf @method('DELETE')
                        <button type="submit" style="background: #C7291C; color: white; border: none; padding: 0.4rem 0.8rem; border-radius: 6px; font-weight: 700; cursor: pointer; font-size: 0.8rem; transition: 0.2s;" onmouseover="this.style.background='#8C1A11'" onmouseout="this.style.background='#C7291C'">
                            <i class="fas fa-power-off"></i> Forzar Cierre
                        </button>
                    </form>
                    @else
                    <span style="color: #736860; font-size: 0.8rem; font-style: italic;">Sesión Actual</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
