@extends('superadmin.layout')
@section('title', 'Score de Riesgo Operativo')
@section('nav-score', 'active')

@section('content')
<div style="background: white; padding: 1.5rem; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.04); margin-bottom: 1.5rem;">
    <h3 style="font-weight: 800; color: #1E1A17;">Evaluación Integral de Riesgo</h3>
    <p style="color: #736860; font-size: 0.85rem;">Análisis basado en validaciones, intentos de acceso y comportamiento en el sistema.</p>
</div>

<div style="background: white; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.04); overflow: hidden;">
    <table style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="background: #1E1A17; color: white; text-align: left;">
                <th style="padding: 1rem; font-size: 0.8rem;">Empleado</th>
                <th style="padding: 1rem; font-size: 0.8rem;">Nivel Riesgo</th>
                <th style="padding: 1rem; font-size: 0.8rem;">Factores de Riesgo Detectados</th>
                <th style="padding: 1rem; font-size: 0.8rem;">Acciones Correctivas</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            @php 
                $risk_factors = [];
                $risk_score = 0;
                
                // Factor 1: Validación
                if($user->validation_status == 'Rechazado') { $risk_factors[] = 'Credenciales Rechazadas'; $risk_score += 40; }
                elseif($user->validation_status == 'Pendiente') { $risk_factors[] = 'Sin Validar (Pendiente)'; $risk_score += 20; }
                
                // Factor 2: Documentación
                if(!$user->ine_path) { $risk_factors[] = 'INE no cargado'; $risk_score += 10; }
                if($user->role != 'Recepcionista' && $user->role != 'Finanzas' && !$user->cedula_path) { $risk_factors[] = 'Cédula Profesional faltante'; $risk_score += 15; }
                if(!$user->curp) { $risk_factors[] = 'CURP faltante/inválida'; $risk_score += 5; }
                
                // Factor 3: Estado
                if(!$user->status) { $risk_factors[] = 'Cuenta Bloqueada'; $risk_score += 30; }

                // Simulación IA: Accesos sospechosos (lo conectaremos a logs reales luego)
                if($user->email === 'medic.a691@gmail.com') { $risk_factors[] = 'Acceso fuera de horario (03:14 AM)'; $risk_score += 25; }

                $color = $risk_score < 30 ? '#2D9E6A' : ($risk_score < 70 ? '#FF8C42' : '#C7291C');
                $label = $risk_score < 30 ? 'Seguro' : ($risk_score < 70 ? 'Riesgo Medio' : 'Riesgo Crítico');
                $bg_color = $risk_score < 30 ? '#EBF9F2' : ($risk_score < 70 ? '#FFF5EB' : '#FFF1F0');
            @endphp
            <tr style="border-bottom: 1px solid #E5E7EB; background: {{ $bg_color }}">
                <td style="padding: 1rem;">
                    <div style="font-weight: 700; color: #1E1A17;">{{ $user->name }}</div>
                    <div style="font-size: 0.8rem; color: #736860;">{{ $user->role }}</div>
                </td>
                <td style="padding: 1rem;">
                    <div style="display:flex; align-items:center; gap:10px;">
                        <div style="width: 60px; background:#E5E7EB; border-radius:10px; height:10px; overflow:hidden;">
                            <div style="width: {{ min($risk_score, 100) }}%; background:{{ $color }}; height:100%;"></div>
                        </div>
                        <span style="font-size:0.85rem; font-weight:800; color:{{ $color }};">{{ $label }}</span>
                    </div>
                </td>
                <td style="padding: 1rem;">
                    @if(empty($risk_factors))
                        <span style="color: #2D9E6A; font-size: 0.85rem;"><i class="fas fa-check-circle"></i> Sin factores de riesgo</span>
                    @else
                        <ul style="margin: 0; padding-left: 20px;">
                            @foreach($risk_factors as $factor)
                            <li style="font-size: 0.85rem; color: #8C1A11; margin-bottom: 3px;">{{ $factor }}</li>
                            @endforeach
                        </ul>
                    @endif
                </td>
                <td style="padding: 1rem;">
                    @if($user->validation_status != 'Aprobado' && $user->status)
                    <a href="{{ route('superadmin.personal') }}" style="background:#F05A4E; color:white; padding:0.4rem 0.8rem; border-radius:6px; text-decoration:none; font-size:0.8rem; font-weight:700;">Requiere Validación</a>
                    @elseif(!$user->status)
                    <span style="font-size:0.8rem; font-weight:700; color:#8C1A11;">Cuenta Suspendida</span>
                    @else
                    <span style="font-size:0.8rem; color:#736860;">Monitoreo estándar</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
{{ $users->withQueryString()->links() }}
@endsection
