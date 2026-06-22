@extends('especialidades.layout')

@section('content')
<div style="padding:1.5rem">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem">
        <h2 style="font-weight:900;color:#7C3AED"><i class="fas fa-brain" style="color:#7C3AED"></i> IA Medica</h2>
        <span style="background:#EDE9FE;color:#7C3AED;padding:0.3rem 0.8rem;border-radius:20px;font-weight:800;font-size:0.8rem"><i class="fas fa-robot"></i> Analisis en Tiempo Real</span>
    </div>

    <!-- ALERTAS IA -->
    <div style="background:linear-gradient(135deg,#7C3AED,#6D28D9);border-radius:16px;padding:1.5rem;margin-bottom:1.5rem;color:white">
        <h3 style="font-weight:900;margin-bottom:1rem"><i class="fas fa-bell"></i> Alertas Inteligentes</h3>
        @if($alertas->count() > 0)
        <div style="display:grid;gap:0.8rem">
            @foreach($alertas as $a)
            <div style="background:rgba(255,255,255,0.15);border-radius:12px;padding:1rem;display:flex;align-items:center;gap:1rem;border-left:4px solid {{ $a['color'] }}">
                <i class="fas {{ $a['icono'] }}" style="font-size:1.5rem;color:{{ $a['color'] }};background:rgba(255,255,255,0.9);border-radius:50%;width:40px;height:40px;display:flex;align-items:center;justify-content:center"></i>
                <div style="flex:1">
                    <div style="font-weight:900;font-size:0.9rem">{{ $a['titulo'] }}</div>
                    <div style="font-size:0.8rem;opacity:0.9">{{ $a['detalle'] }}</div>
                </div>
                <div style="font-size:0.7rem;opacity:0.7">{{ $a['tiempo'] }}</div>
            </div>
            @endforeach
        </div>
        @else
        <p style="text-align:center;padding:1.5rem;opacity:0.8;font-weight:700"><i class="fas fa-check-circle"></i> Sin alertas activas - Todo estable</p>
        @endif
    </div>

    <!-- ANALISIS DE PACIENTES -->
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;margin-bottom:1.5rem">
        <div style="background:white;border-radius:16px;padding:1.5rem;box-shadow:0 2px 8px rgba(0,0,0,0.05);border-top:4px solid #DC2626">
            <h3 style="font-weight:900;color:#DC2626;margin-bottom:1rem"><i class="fas fa-heart-pulse"></i> Pacientes Criticos</h3>
            @php $criticos = $misPacientes->where('triage_level', 'Rojo'); @endphp
            @if($criticos->count() > 0)
            @foreach($criticos as $p)
            <div style="background:#FEF2F2;border-radius:8px;padding:0.8rem;margin-bottom:0.5rem;border-left:4px solid #DC2626">
                <div style="font-weight:800;color:#991B1B">{{ $p->patient_name }}</div>
                <div style="font-size:0.75rem;color:#78716C">{{ Str::limit($p->symptoms, 50) }}</div>
                <div style="font-size:0.7rem;color:#DC2626;margin-top:0.3rem"><i class="fas fa-clock"></i> {{ $p->created_at->diffForHumans() }}</div>
            </div>
            @endforeach
            @else
            <p style="text-align:center;color:#16A34A;padding:1rem;font-weight:700"><i class="fas fa-shield-heart"></i> Sin pacientes criticos</p>
            @endif
        </div>

        <div style="background:white;border-radius:16px;padding:1.5rem;box-shadow:0 2px 8px rgba(0,0,0,0.05);border-top:4px solid #2563EB">
            <h3 style="font-weight:900;color:#1E40AF;margin-bottom:1rem"><i class="fas fa-chart-line"></i> Metricas en Tiempo Real</h3>
            <div style="display:grid;gap:0.8rem">
                <div style="background:#EFF6FF;border-radius:8px;padding:0.8rem;display:flex;justify-content:space-between;align-items:center">
                    <span style="font-weight:700;color:#1E40AF;font-size:0.85rem">Mis Pacientes Activos</span>
                    <span style="font-weight:900;font-size:1.2rem;color:#1E40AF">{{ $misPacientes->count() }}</span>
                </div>
                <div style="background:#FEF2F2;border-radius:8px;padding:0.8rem;display:flex;justify-content:space-between;align-items:center">
                    <span style="font-weight:700;color:#DC2626;font-size:0.85rem">Criticos Sin Asignar</span>
                    <span style="font-weight:900;font-size:1.2rem;color:#DC2626">{{ $criticosSinAsignar }}</span>
                </div>
                <div style="background:#F0FDF4;border-radius:8px;padding:0.8rem;display:flex;justify-content:space-between;align-items:center">
                    <span style="font-weight:700;color:#16A34A;font-size:0.85rem">Nivel de Riesgo</span>
                    @php $riesgo = $criticosSinAsignar > 3 ? 'ALTO' : ($criticosSinAsignar > 0 ? 'MEDIO' : 'BAJO'); $rColor = $riesgo == 'ALTO' ? '#DC2626' : ($riesgo == 'MEDIO' ? '#EA580C' : '#16A34A'); @endphp
                    <span style="font-weight:900;font-size:1rem;color:{{ $rColor }}">{{ $riesgo }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- RECOMENDACIONES IA -->
    <div style="background:white;border-radius:16px;padding:1.5rem;box-shadow:0 2px 8px rgba(0,0,0,0.05);border-top:4px solid #7C3AED">
        <h3 style="font-weight:900;color:#6D28D9;margin-bottom:1rem"><i class="fas fa-lightbulb"></i> Recomendaciones de IA</h3>
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:1rem">
            @if($criticosSinAsignar > 0)
            <div style="background:#FEF2F2;border-radius:12px;padding:1rem;border-left:4px solid #DC2626">
                <div style="font-weight:800;color:#DC2626;font-size:0.85rem"><i class="fas fa-exclamation-triangle"></i> Asignacion Urgente</div>
                <div style="font-size:0.8rem;color:#78716C;margin-top:0.3rem">Hay {{ $criticosSinAsignar }} pacientes criticos sin medico. Se recomienda asignar inmediatamente.</div>
            </div>
            @endif
            @if($misPacientes->where('triage_level', 'Naranja')->count() > 2)
            <div style="background:#FFEDD5;border-radius:12px;padding:1rem;border-left:4px solid #EA580C">
                <div style="font-weight:800;color:#EA580C;font-size:0.85rem"><i class="fas fa-user-md"></i> Carga Elevada</div>
                <div style="font-size:0.8rem;color:#78716C;margin-top:0.3rem">Tiene multiples pacientes urgentes. Considere derivar algunos casos.</div>
            </div>
            @endif
            <div style="background:#EDE9FE;border-radius:12px;padding:1rem;border-left:4px solid #7C3AED">
                <div style="font-weight:800;color:#7C3AED;font-size:0.85rem"><i class="fas fa-notes-medical"></i> Seguimiento</div>
                <div style="font-size:0.8rem;color:#78716C;margin-top:0.3rem">Revise los signos vitales de pacientes hospitalizados con mas de 24h.</div>
            </div>
        </div>
    </div>
</div>
@endsection
