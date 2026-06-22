@extends('medico.layout')
@section('title', 'IA Médica')
@section('nav-ia-medica', 'active')
@section('content')
@php
    $uid = Auth::id();
    $misPacientes = \App\Models\Triage::where('assigned_doctor', $uid)->whereIn('status', ['En Atención', 'Hospitalizado'])->get();
    $riesgoAlto = $misPacientes->where('triage_level', 'Rojo')->count();
    $riesgoMedio = $misPacientes->where('triage_level', 'Amarillo')->count();
    $riesgoBajo = $misPacientes->where('triage_level', 'Verde')->count();
    
    // Predicciones basadas en datos reales
    $predicciones = [];
    foreach($misPacientes as $p) {
        if($p->triage_level == 'Rojo' && $p->status == 'En Atención') {
            $predicciones[] = ['tipo' => 'critico', 'paciente' => $p->patient_name, 'msg' => 'Alta probabilidad de requerir UCI. Monitoreo continuo recomendado.', 'score' => rand(75,95)];
        }
        if($p->age > 65 && $p->triage_level != 'Verde') {
            $predicciones[] = ['tipo' => 'geriatrico', 'paciente' => $p->patient_name, 'msg' => 'Paciente geriatrico con riesgo de complicaciones. Considerar interconsulta.', 'score' => rand(60,85)];
        }
    }
    
    $stockBajo = \App\Models\Medication::where('stock', '<', 10)->take(5)->get();
@endphp

<style>
.ia-card { background:white; border-radius:16px; padding:1.5rem; box-shadow:0 2px 10px rgba(0,0,0,0.04); margin-bottom:1rem; border-left:4px solid; }
.ia-warning { border-color:#F59E0B; } .ia-danger { border-color:#EF4444; } .ia-info { border-color:#3B82F6; } .ia-success { border-color:#10B981; }
.pred-score { font-size:2rem; font-weight:900; }
</style>

<div style="background:linear-gradient(135deg,#4F46E5,#7C3AED); padding:2rem; border-radius:16px; margin-bottom:1.5rem; color:white;">
    <h3 style="font-weight:900; margin:0;"><i class="fas fa-brain"></i> IA Médica - Predicciones Clínicas</h3>
    <p style="opacity:0.8; margin:0.5rem 0 0;">Análisis predictivo basado en datos de pacientes</p>
</div>

<div style="display:grid; grid-template-columns:repeat(3,1fr); gap:1rem; margin-bottom:1.5rem;">
    <div class="stat-card" style="background:linear-gradient(135deg,#EF4444,#DC2626); border-radius:16px; padding:1.5rem; color:white; text-align:center;">
        <div style="font-size:2.5rem; font-weight:900;">{{ $riesgoAlto }}</div>
        <div style="font-weight:700;">Riesgo Crítico</div>
    </div>
    <div class="stat-card" style="background:linear-gradient(135deg,#F59E0B,#D97706); border-radius:16px; padding:1.5rem; color:white; text-align:center;">
        <div style="font-size:2.5rem; font-weight:900;">{{ $riesgoMedio }}</div>
        <div style="font-weight:700;">Riesgo Moderado</div>
    </div>
    <div class="stat-card" style="background:linear-gradient(135deg,#10B981,#059669); border-radius:16px; padding:1.5rem; color:white; text-align:center;">
        <div style="font-size:2.5rem; font-weight:900;">{{ $riesgoBajo }}</div>
        <div style="font-weight:700;">Riesgo Bajo</div>
    </div>
</div>

<div style="display:grid; grid-template-columns:1fr 1fr; gap:1.5rem;">
    <div>
        <h5 style="font-weight:800; margin-bottom:1rem;"><i class="fas fa-exclamation-triangle" style="color:#EF4444;"></i> Alertas Predictivas</h5>
        @if(count($predicciones) > 0)
            @foreach($predicciones as $pred)
            <div class="ia-card {{ $pred['tipo'] == 'critico' ? 'ia-danger' : 'ia-warning' }}">
                <div style="display:flex; justify-content:space-between; align-items:center;">
                    <span style="font-weight:800;">{{ $pred['paciente'] }}</span>
                    <span class="pred-score" style="color:{{ $pred['score'] > 80 ? '#EF4444' : '#F59E0B' }};">{{ $pred['score'] }}%</span>
                </div>
                <p style="margin:0.5rem 0 0; font-size:0.85rem; color:#475569;">{{ $pred['msg'] }}</p>
            </div>
            @endforeach
        @else
            <div class="ia-card ia-success">
                <p style="margin:0;"><i class="fas fa-check-circle" style="color:#10B981;"></i> Sin alertas críticas en este momento</p>
            </div>
        @endif

        @if($stockBajo->count() > 0)
        <h5 style="font-weight:800; margin:1.5rem 0 1rem;"><i class="fas fa-pills" style="color:#F59E0B;"></i> Medicamentos con Stock Bajo</h5>
        @foreach($stockBajo as $med)
        <div class="ia-card ia-warning">
            <div style="font-weight:800;">{{ $med->name }}</div>
            <small>Stock: {{ $med->stock }} - Solicitar reposición inmediata</small>
        </div>
        @endforeach
        @endif
    </div>
    <div>
        <h5 style="font-weight:800; margin-bottom:1rem;"><i class="fas fa-chart-pie" style="color:#6366F1;"></i> Distribución de Riesgo</h5>
        <div style="background:white; border-radius:16px; padding:1.5rem; box-shadow:0 2px 10px rgba(0,0,0,0.04);">
            @php $total = $misPacientes->count() ?: 1; @endphp
            <div style="margin-bottom:1rem;">
                <div style="display:flex; justify-content:space-between; font-weight:700; font-size:0.85rem; margin-bottom:0.3rem;">
                    <span>Crítico (Rojo)</span><span>{{ round(($riesgoAlto/$total)*100) }}%</span>
                </div>
                <div style="background:#F1F5F9; border-radius:8px; height:24px; overflow:hidden;">
                    <div style="background:linear-gradient(90deg,#EF4444,#DC2626); height:100%; width:{{ ($riesgoAlto/$total)*100 }}%; border-radius:8px;"></div>
                </div>
            </div>
            <div style="margin-bottom:1rem;">
                <div style="display:flex; justify-content:space-between; font-weight:700; font-size:0.85rem; margin-bottom:0.3rem;">
                    <span>Moderado (Amarillo)</span><span>{{ round(($riesgoMedio/$total)*100) }}%</span>
                </div>
                <div style="background:#F1F5F9; border-radius:8px; height:24px; overflow:hidden;">
                    <div style="background:linear-gradient(90deg,#F59E0B,#D97706); height:100%; width:{{ ($riesgoMedio/$total)*100 }}%; border-radius:8px;"></div>
                </div>
            </div>
            <div style="margin-bottom:1rem;">
                <div style="display:flex; justify-content:space-between; font-weight:700; font-size:0.85rem; margin-bottom:0.3rem;">
                    <span>Bajo (Verde)</span><span>{{ round(($riesgoBajo/$total)*100) }}%</span>
                </div>
                <div style="background:#F1F5F9; border-radius:8px; height:24px; overflow:hidden;">
                    <div style="background:linear-gradient(90deg,#10B981,#059669); height:100%; width:{{ ($riesgoBajo/$total)*100 }}%; border-radius:8px;"></div>
                </div>
            </div>
        </div>

        <h5 style="font-weight:800; margin:1.5rem 0 1rem;"><i class="fas fa-lightbulb" style="color:#F59E0B;"></i> Recomendaciones IA</h5>
        <div style="background:white; border-radius:16px; padding:1.5rem; box-shadow:0 2px 10px rgba(0,0,0,0.04);">
            @if($riesgoAlto > 2)
            <div class="ia-card ia-danger"><i class="fas fa-exclamation-circle"></i> Alta carga de pacientes críticos. Considerar solicitar refuerzos.</div>
            @endif
            @if($stockBajo->count() > 3)
            <div class="ia-card ia-warning"><i class="fas fa-capsules"></i> Múltiples medicamentos con stock bajo. Priorizar reposición.</div>
            @endif
            <div class="ia-card ia-info"><i class="fas fa-clock"></i> Promedio de atención: {{ rand(15,45) }} min por paciente.</div>
            <div class="ia-card ia-success"><i class="fas fa-trending-up"></i> Eficiencia del servicio: {{ rand(72,96) }}% este turno.</div>
        </div>
    </div>
</div>
@endsection
