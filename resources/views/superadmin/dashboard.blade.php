@extends('superadmin.layout')
@section('title', 'Dashboard Ejecutivo Inteligente')
@section('nav-dashboard', 'active')

@section('content')
<div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 1.5rem; margin-bottom: 2rem;">
    <div style="background: white; padding: 1.5rem; border-radius: 12px; border-top: 4px solid #F05A4E; box-shadow: 0 4px 6px rgba(0,0,0,0.04);">
        <div style="font-size: 0.8rem; color: #736860; font-weight: 700; text-transform: uppercase;">Ocupacion Hospitalaria</div>
        <div style="font-size: 2rem; font-weight: 800; color: #1E1A17; margin-top: 0.5rem;">78%</div>
        <div style="font-size: 0.8rem; color: #F05A4E; margin-top: 0.5rem;"><i class="fas fa-arrow-up"></i> 5% vs ayer</div>
    </div>
    <div style="background: white; padding: 1.5rem; border-radius: 12px; border-top: 4px solid #FF8C42; box-shadow: 0 4px 6px rgba(0,0,0,0.04);">
        <div style="font-size: 0.8rem; color: #736860; font-weight: 700; text-transform: uppercase;">Urgencias Criticas</div>
        <div style="font-size: 2rem; font-weight: 800; color: #C7291C; margin-top: 0.5rem;">4</div>
        <div style="font-size: 0.8rem; color: #C7291C; margin-top: 0.5rem;"><i class="fas fa-exclamation-circle"></i> Triage Rojo activo</div>
    </div>
    <div style="background: white; padding: 1.5rem; border-radius: 12px; border-top: 4px solid #2D9E6A; box-shadow: 0 4px 6px rgba(0,0,0,0.04);">
        <div style="font-size: 0.8rem; color: #736860; font-weight: 700; text-transform: uppercase;">Stock Farmacia</div>
        <div style="font-size: 2rem; font-weight: 800; color: #1E1A17; margin-top: 0.5rem;">92%</div>
        <div style="font-size: 0.8rem; color: #FF8C42; margin-top: 0.5rem;"><i class="fas fa-exclamation-triangle"></i> 3 medicamentos bajo</div>
    </div>
    <div style="background: white; padding: 1.5rem; border-radius: 12px; border-top: 4px solid #1E1A17; box-shadow: 0 4px 6px rgba(0,0,0,0.04);">
        <div style="font-size: 0.8rem; color: #736860; font-weight: 700; text-transform: uppercase;">Anomalias IA Hoy</div>
        <div style="font-size: 2rem; font-weight: 800; color: #1E1A17; margin-top: 0.5rem;">0</div>
        <div style="font-size: 0.8rem; color: #2D9E6A; margin-top: 0.5rem;"><i class="fas fa-shield-alt"></i> Sin actividad sospechosa</div>
    </div>
</div>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem;">
    <div style="background: white; padding: 2rem; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.04);">
        <h3 style="font-weight: 800; margin-bottom: 1rem; color: #1E1A17;">Centro de Inteligencia Hospitalaria</h3>
        <div style="background: #F9FAFB; border-radius: 8px; padding: 2rem; text-align: center; color: #736860;">
            <i class="fas fa-brain" style="font-size: 3rem; color: #FF8C42; margin-bottom: 1rem;"></i>
            <p>Motor de Analitica Avanzada e IA en tiempo real.</p>
            <p style="font-size: 0.8rem; margin-top: 0.5rem;">Conectando con FastAPI y PySpark...</p>
        </div>
    </div>
    <div style="background: white; padding: 2rem; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.04);">
        <h3 style="font-weight: 800; margin-bottom: 1rem; color: #1E1A17;">Actividad Reciente</h3>
        <div style="border-left: 2px solid #E5E7EB; margin-left: 0.5rem; padding-left: 1rem;">
            <div style="margin-bottom: 1rem;"><span style="font-weight: 600; font-size: 0.85rem;">Dr. Perez</span><br><span style="font-size: 0.75rem; color: #736860;">Inicio sesion - Medico A</span></div>
            <div style="margin-bottom: 1rem;"><span style="font-weight: 600; font-size: 0.85rem;">Farmacia Central</span><br><span style="font-size: 0.75rem; color: #736860;">Stock actualizado</span></div>
            <div style="margin-bottom: 1rem;"><span style="font-weight: 600; font-size: 0.85rem; color: #C7291C;">Alerta Urgencias</span><br><span style="font-size: 0.75rem; color: #736860;">Triage Rojo ingresado</span></div>
        </div>
    </div>
</div>
@endsection
