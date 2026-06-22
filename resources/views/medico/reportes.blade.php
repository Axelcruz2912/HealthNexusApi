@extends('medico.layout')
@section('title', 'Reportes')
@section('nav-reportes', 'active')
@section('content')
<style>
.stat-card { border-radius:16px; padding:1.5rem; color:white; text-align:center; position:relative; overflow:hidden; }
.stat-card::before { content:''; position:absolute; top:-50%; right:-50%; width:100%; height:100%; background:rgba(255,255,255,0.1); border-radius:50%; }
.stat-number { font-size:3rem; font-weight:900; position:relative; }
.stat-label { font-size:0.85rem; font-weight:600; opacity:0.9; position:relative; }
.report-section { background:white; border-radius:16px; padding:1.5rem; box-shadow:0 2px 10px rgba(0,0,0,0.04); margin-bottom:1.5rem; }
</style>

<div style="background:linear-gradient(135deg,#1E293B,#334155); padding:2rem; border-radius:16px; margin-bottom:1.5rem; color:white;">
    <div style="display:flex; justify-content:space-between; align-items:center;">
        <div>
            <h3 style="font-weight:900; margin:0;"><i class="fas fa-chart-line"></i> Reportes Médicos</h3>
            <p style="opacity:0.7; margin:0.5rem 0 0;">Resumen de actividad clínica</p>
        </div>
        <a href="{{ route('medico.reportes.pdf') }}" class="btn btn-light" style="font-weight:800; border-radius:10px;">
            <i class="fas fa-file-pdf" style="color:#EF4444;"></i> Exportar PDF
        </a>
    </div>
</div>

<div style="display:grid; grid-template-columns:repeat(4,1fr); gap:1rem; margin-bottom:1.5rem;">
    <div class="stat-card" style="background:linear-gradient(135deg,#3B82F6,#2563EB);">
        <div class="stat-number">{{ $pacientesAtendidos }}</div>
        <div class="stat-label"><i class="fas fa-users"></i> Pacientes Atendidos</div>
    </div>
    <div class="stat-card" style="background:linear-gradient(135deg,#10B981,#059669);">
        <div class="stat-number">{{ $altasDadas }}</div>
        <div class="stat-label"><i class="fas fa-check-circle"></i> Altas Dadas</div>
    </div>
    <div class="stat-card" style="background:linear-gradient(135deg,#F59E0B,#D97706);">
        <div class="stat-number">{{ $recetasEmitidas }}</div>
        <div class="stat-label"><i class="fas fa-prescription"></i> Recetas Emitidas</div>
    </div>
    <div class="stat-card" style="background:linear-gradient(135deg,#8B5CF6,#7C3AED);">
        <div class="stat-number">{{ $estudiosSolicitados }}</div>
        <div class="stat-label"><i class="fas fa-microscope"></i> Estudios Solicitados</div>
    </div>
</div>

<div style="display:grid; grid-template-columns:1fr 1fr; gap:1.5rem;">
    <div class="report-section">
        <h5 style="font-weight:800; margin-bottom:1rem;"><i class="fas fa-chart-bar" style="color:#3B82F6;"></i> Actividad Reciente</h5>
        <div style="display:flex; align-items:end; gap:0.5rem; height:150px; padding-top:1rem;">
            @php $bars = [$pacientesAtendidos, $altasDadas, $recetasEmitidas, $estudiosSolicitados]; $max = max($bars) ?: 1; @endphp
            <div style="flex:1; text-align:center;">
                <div style="background:linear-gradient(to top,#3B82F6,#60A5FA); height:{{ ($pacientesAtendidos/$max)*120 }}px; border-radius:6px 6px 0 0; min-height:10px;"></div>
                <small style="font-weight:700; font-size:0.65rem;">Pacientes</small>
            </div>
            <div style="flex:1; text-align:center;">
                <div style="background:linear-gradient(to top,#10B981,#34D399); height:{{ ($altasDadas/$max)*120 }}px; border-radius:6px 6px 0 0; min-height:10px;"></div>
                <small style="font-weight:700; font-size:0.65rem;">Altas</small>
            </div>
            <div style="flex:1; text-align:center;">
                <div style="background:linear-gradient(to top,#F59E0B,#FBBF24); height:{{ ($recetasEmitidas/$max)*120 }}px; border-radius:6px 6px 0 0; min-height:10px;"></div>
                <small style="font-weight:700; font-size:0.65rem;">Recetas</small>
            </div>
            <div style="flex:1; text-align:center;">
                <div style="background:linear-gradient(to top,#8B5CF6,#A78BFA); height:{{ ($estudiosSolicitados/$max)*120 }}px; border-radius:6px 6px 0 0; min-height:10px;"></div>
                <small style="font-weight:700; font-size:0.65rem;">Estudios</small>
            </div>
        </div>
    </div>
    <div class="report-section">
        <h5 style="font-weight:800; margin-bottom:1rem;"><i class="fas fa-file-export" style="color:#10B981;"></i> Exportar Reportes</h5>
        <div style="display:grid; gap:0.75rem;">
            <a href="{{ route('medico.reportes.pdf') }}" style="display:flex; align-items:center; gap:1rem; padding:1rem; background:#FEE2E2; border-radius:10px; text-decoration:none; color:#991B1B; font-weight:700;">
                <i class="fas fa-file-pdf" style="font-size:1.5rem;"></i>
                <div><div>Reporte PDF</div><small style="opacity:0.7;">Resumen completo de pacientes</small></div>
            </a>
            <a href="{{ route('medico.reportes.pdf') }}" style="display:flex; align-items:center; gap:1rem; padding:1rem; background:#D1FAE5; border-radius:10px; text-decoration:none; color:#065F46; font-weight:700;">
                <i class="fas fa-file-excel" style="font-size:1.5rem;"></i>
                <div><div>Reporte Excel</div><small style="opacity:0.7;">Datos tabulados para análisis</small></div>
            </a>
            <a href="{{ route('medico.reportes.pdf') }}" style="display:flex; align-items:center; gap:1rem; padding:1rem; background:#E0E7FF; border-radius:10px; text-decoration:none; color:#3730A3; font-weight:700;">
                <i class="fas fa-print" style="font-size:1.5rem;"></i>
                <div><div>Imprimir</div><small style="opacity:0.7;">Vista para impresión</small></div>
            </a>
        </div>
    </div>
</div>
@endsection
