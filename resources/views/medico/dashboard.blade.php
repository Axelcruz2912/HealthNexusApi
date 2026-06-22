@extends('medico.layout')
@section('title', 'Dashboard Médico')
@section('nav-dashboard', 'active')

@section('content')
@php
 $isA = $role === 'Médico A';
 $isB = $role === 'Médico B';
 $isC = $role === 'Médico C';
 $color = $isA ? '#DC2626' : ($isB ? '#EA580C' : '#F97316');
 $colorLight = $isA ? '#FEE2E2' : ($isB ? '#FFEDD5' : '#FED7AA');
 $bgLight = $isA ? '#FEF2F2' : ($isB ? '#FFF7ED' : '#FFF7ED');
 $grad = $isA ? 'linear-gradient(135deg,#DC2626,#B91C1C)' : ($isB ? 'linear-gradient(135deg,#EA580C,#C2410C)' : 'linear-gradient(135deg,#F97316,#EA580C)');
@endphp

{{-- BANNER DE BIENVENIDA --}}
<div style="background:{{$grad}};border-radius:14px;padding:1.5rem 2rem;margin-bottom:1.5rem;color:#fff;display:flex;justify-content:space-between;align-items:center">
    <div>
        <h2 style="font-weight:900;font-size:1.3rem;letter-spacing:-0.5px">Bienvenido, {{$doctorName}}</h2>
        <p style="font-size:0.85rem;opacity:0.9;margin-top:0.2rem">{{$role}} — {{now()->format('l d \\d\\e F, Y')}}</p>
    </div>
    <div style="text-align:right">
        <div style="font-size:2.5rem;font-weight:900">{{$misPacientes}}</div>
        <div style="font-size:0.7rem;opacity:0.85;font-weight:600">PACIENTES ASIGNADOS</div>
    </div>
</div>

{{-- STATS --}}
<div style="display:grid;grid-template-columns:repeat({{$isC?'2':'4'}},1fr);gap:0.75rem;margin-bottom:1.5rem">
    <div class="card" style="padding:1rem;border-top:3px solid {{$color}}">
        <div style="font-size:0.65rem;color:#78716C;font-weight:700;letter-spacing:0.5px">MIS PACIENTES</div>
        <div style="font-size:1.8rem;font-weight:900;color:{{$color}};margin-top:0.15rem">{{$misPacientes}}</div>
    </div>
    @if(!$isC)
    <div class="card" style="padding:1rem;border-top:3px solid #DC2626">
        <div style="font-size:0.65rem;color:#78716C;font-weight:700;letter-spacing:0.5px">CRÍTICOS</div>
        <div style="font-size:1.8rem;font-weight:900;color:#DC2626;margin-top:0.15rem">{{$criticos}}</div>
    </div>
    <div class="card" style="padding:1rem;border-top:3px solid #EA580C">
        <div style="font-size:0.65rem;color:#78716C;font-weight:700;letter-spacing:0.5px">CAMAS LIBRES</div>
        <div style="font-size:1.8rem;font-weight:900;color:#EA580C;margin-top:0.15rem">{{$camasDisponibles}}</div>
    </div>
    <div class="card" style="padding:1rem;border-top:3px solid {{$stockBajo>0?'#DC2626':'#2D9E6A'}}">
        <div style="font-size:0.65rem;color:#78716C;font-weight:700;letter-spacing:0.5px">STOCK BAJO</div>
        <div style="font-size:1.8rem;font-weight:900;color:{{$stockBajo>0?'#DC2626':'#2D9E6A'}};margin-top:0.15rem">{{$stockBajo}}</div>
    </div>
    @endif
</div>

{{-- ============ MÉDICO C ============ --}}
@if($isC)
<div style="background:{{$bgLight}};border:1px solid {{$colorLight}};border-radius:12px;padding:1rem 1.25rem;margin-bottom:1.5rem">
    <h4 style="font-weight:800;color:#9A3412;font-size:0.85rem"><i class="fas fa-info-circle"></i> Médico C — Pasante</h4>
    <p style="font-size:0.78rem;color:#78716C;margin-top:0.2rem">Atiendes consultas básicas. Los pacientes te son asignados por Médico A o B.</p>
</div>

<div class="card" style="padding:1.5rem;margin-bottom:1.5rem">
    <h4 style="font-weight:800;margin-bottom:1rem;color:{{$color}};font-size:0.9rem"><i class="fas fa-clipboard-list"></i> Mis Acciones</h4>
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:0.6rem">
        <a href="{{route('medico.consulta')}}" style="padding:1rem;background:{{$bgLight}};border-radius:10px;text-decoration:none;color:#1C1917;font-weight:700;font-size:0.82rem;border:1px solid {{$colorLight}};text-align:center;transition:all 0.15s" onmouseover="this.style.borderColor='{{$color}}'" onmouseout="this.style.borderColor='{{$colorLight}}'"><i class="fas fa-user-md" style="color:{{$color}};font-size:1.2rem;display:block;margin-bottom:0.3rem"></i>Consulta</a>
        <a href="{{route('medico.recetas')}}" style="padding:1rem;background:{{$bgLight}};border-radius:10px;text-decoration:none;color:#1C1917;font-weight:700;font-size:0.82rem;border:1px solid {{$colorLight}};text-align:center;transition:all 0.15s" onmouseover="this.style.borderColor='{{$color}}'" onmouseout="this.style.borderColor='{{$colorLight}}'"><i class="fas fa-prescription" style="color:{{$color}};font-size:1.2rem;display:block;margin-bottom:0.3rem"></i>Recetas</a>
        <a href="{{route('medico.diagnosticos')}}" style="padding:1rem;background:{{$bgLight}};border-radius:10px;text-decoration:none;color:#1C1917;font-weight:700;font-size:0.82rem;border:1px solid {{$colorLight}};text-align:center;transition:all 0.15s" onmouseover="this.style.borderColor='{{$color}}'" onmouseout="this.style.borderColor='{{$colorLight}}'"><i class="fas fa-file-medical" style="color:{{$color}};font-size:1.2rem;display:block;margin-bottom:0.3rem"></i>Diagnósticos</a>
        <a href="{{route('medico.estudios')}}" style="padding:1rem;background:{{$bgLight}};border-radius:10px;text-decoration:none;color:#1C1917;font-weight:700;font-size:0.82rem;border:1px solid {{$colorLight}};text-align:center;transition:all 0.15s" onmouseover="this.style.borderColor='{{$color}}'" onmouseout="this.style.borderColor='{{$colorLight}}'"><i class="fas fa-microscope" style="color:{{$color}};font-size:1.2rem;display:block;margin-bottom:0.3rem"></i>Estudios</a>
    </div>
</div>
@endif

{{-- ============ MÉDICO B ============ --}}
@if($isB)
<div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1.5rem">
    <div class="card" style="padding:1.25rem">
        <h4 style="font-weight:800;margin-bottom:0.75rem;color:#EA580C;font-size:0.85rem"><i class="fas fa-clipboard-list"></i> Acciones Rápidas</h4>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:0.4rem">
            <a href="{{route('medico.consulta')}}" style="padding:0.6rem;background:#FFF7ED;border-radius:8px;text-decoration:none;color:#1C1917;font-weight:700;font-size:0.75rem;text-align:center"><i class="fas fa-user-md" style="color:#EA580C"></i> Consulta</a>
            <a href="{{route('medico.hospitalizacion')}}" style="padding:0.6rem;background:#FFF7ED;border-radius:8px;text-decoration:none;color:#1C1917;font-weight:700;font-size:0.75rem;text-align:center"><i class="fas fa-bed" style="color:#EA580C"></i> Hospitalizar</a>
            <a href="{{route('medico.recetas')}}" style="padding:0.6rem;background:#FFF7ED;border-radius:8px;text-decoration:none;color:#1C1917;font-weight:700;font-size:0.75rem;text-align:center"><i class="fas fa-prescription" style="color:#EA580C"></i> Recetas</a>
            <a href="{{route('medico.servicios')}}" style="padding:0.6rem;background:#FFF7ED;border-radius:8px;text-decoration:none;color:#1C1917;font-weight:700;font-size:0.75rem;text-align:center"><i class="fas fa-concierge-bell" style="color:#EA580C"></i> Servicios</a>
        </div>
    </div>
    <div class="card" style="padding:1.25rem">
        <h4 style="font-weight:800;margin-bottom:0.75rem;color:#9A3412;font-size:0.85rem"><i class="fas fa-exclamation-triangle"></i> Mis Restricciones</h4>
        <div style="display:grid;gap:0.25rem">
            <div style="padding:0.3rem 0.6rem;background:#FEF2F2;border-radius:6px;font-size:0.72rem;color:#991B1B;font-weight:600"><i class="fas fa-times-circle"></i> Sin opioides críticos</div>
            <div style="padding:0.3rem 0.6rem;background:#FEF2F2;border-radius:6px;font-size:0.72rem;color:#991B1B;font-weight:600"><i class="fas fa-times-circle"></i> Sin UCI / Quirófano</div>
            <div style="padding:0.3rem 0.6rem;background:#FEF2F2;border-radius:6px;font-size:0.72rem;color:#991B1B;font-weight:600"><i class="fas fa-times-circle"></i> Sin derivaciones</div>
            <div style="padding:0.3rem 0.6rem;background:#F0FDF4;border-radius:6px;font-size:0.72rem;color:#166534;font-weight:600"><i class="fas fa-check-circle"></i> Solicitar a Médico A</div>
        </div>
    </div>
</div>
@endif

{{-- ============ MÉDICO A ============ --}}
@if($isA)
<div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:1rem;margin-bottom:1.5rem">
    <div class="card" style="padding:1.25rem">
        <h4 style="font-weight:800;margin-bottom:0.75rem;color:#DC2626;font-size:0.85rem"><i class="fas fa-fire"></i> Acceso Crítico</h4>
        <div style="display:grid;gap:0.3rem">
            <a href="{{route('medico.uci')}}" style="padding:0.45rem 0.6rem;background:#FEF2F2;border-radius:6px;text-decoration:none;color:#991B1B;font-weight:700;font-size:0.72rem"><i class="fas fa-procedures"></i> UCI — {{$criticos}} pacientes</a>
            <a href="{{route('medico.quirofano')}}" style="padding:0.45rem 0.6rem;background:#FEF2F2;border-radius:6px;text-decoration:none;color:#991B1B;font-weight:700;font-size:0.72rem"><i class="fas fa-cut"></i> Quirófano</a>
            <a href="{{route('medico.controlados')}}" style="padding:0.45rem 0.6rem;background:#FEF2F2;border-radius:6px;text-decoration:none;color:#991B1B;font-weight:700;font-size:0.72rem"><i class="fas fa-lock"></i> Medicamentos Controlados</a>
            <a href="{{route('medico.derivaciones')}}" style="padding:0.45rem 0.6rem;background:#FEF2F2;border-radius:6px;text-decoration:none;color:#991B1B;font-weight:700;font-size:0.72rem"><i class="fas fa-ambulance"></i> Derivaciones</a>
        </div>
    </div>
    <div class="card" style="padding:1.25rem">
        <h4 style="font-weight:800;margin-bottom:0.75rem;color:#DC2626;font-size:0.85rem"><i class="fas fa-shield-alt"></i> Permisos Totales</h4>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:0.3rem">
            <span style="background:#F0FDF4;color:#166534;padding:0.3rem;border-radius:6px;font-size:0.68rem;text-align:center;font-weight:700">✓ Opioides</span>
            <span style="background:#F0FDF4;color:#166534;padding:0.3rem;border-radius:6px;font-size:0.68rem;text-align:center;font-weight:700">✓ Quirófano</span>
            <span style="background:#F0FDF4;color:#166534;padding:0.3rem;border-radius:6px;font-size:0.68rem;text-align:center;font-weight:700">✓ UCI</span>
            <span style="background:#F0FDF4;color:#166534;padding:0.3rem;border-radius:6px;font-size:0.68rem;text-align:center;font-weight:700">✓ Firma Digital</span>
            <span style="background:#F0FDF4;color:#166534;padding:0.3rem;border-radius:6px;font-size:0.68rem;text-align:center;font-weight:700">✓ IA Médica</span>
            <span style="background:#F0FDF4;color:#166534;padding:0.3rem;border-radius:6px;font-size:0.68rem;text-align:center;font-weight:700">✓ Auditoría</span>
        </div>
    </div>
    <div class="card" style="padding:1.25rem">
        <h4 style="font-weight:800;margin-bottom:0.75rem;color:#DC2626;font-size:0.85rem"><i class="fas fa-exchange-alt"></i> Asignar Pacientes</h4>
        <p style="font-size:0.72rem;color:#78716C;margin-bottom:0.5rem">Transfiere pacientes a Médico B o C</p>
        <a href="{{route('medico.pacientes')}}" style="display:block;padding:0.6rem;background:linear-gradient(135deg,#DC2626,#B91C1C);border-radius:8px;text-decoration:none;color:#fff;font-weight:700;font-size:0.78rem;text-align:center"><i class="fas fa-users"></i> Gestionar</a>
    </div>
</div>
@endif

{{-- MIS PACIENTES --}}
@if($misPacientesLista->count() > 0)
<div class="card" style="padding:1.25rem;margin-bottom:1.5rem">
    <h4 style="font-weight:800;margin-bottom:0.75rem;font-size:0.85rem"><i class="fas fa-users" style="color:{{$color}}"></i> Mis Pacientes</h4>
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(185px,1fr));gap:0.5rem">
    @foreach($misPacientesLista as $p)
    @php $tc=$p->triage_level==='Rojo'?'#DC2626':($p->triage_level==='Amarillo'?'#F59E0B':($p->triage_level==='Naranja'?'#EA580C':'#F97316')); @endphp
    <div style="padding:0.65rem;border-radius:8px;border:1px solid #E7E5E4;border-left:3px solid {{$tc}};transition:all 0.15s" onmouseover="this.style.boxShadow='0 2px 8px rgba(0,0,0,0.08)'" onmouseout="this.style.boxShadow='none'">
        <div style="font-weight:700;font-size:0.8rem;color:#1C1917">{{$p->patient_name}}</div>
        <div style="display:flex;justify-content:space-between;align-items:center;margin-top:0.2rem">
            <span style="background:{{$tc}}18;color:{{$tc}};padding:0.05rem 0.35rem;border-radius:6px;font-size:0.6rem;font-weight:800">{{$p->triage_level}}</span>
            <span style="font-size:0.6rem;color:#A8A29E;font-weight:600">{{$p->status}}</span>
        </div>
        @if($p->diagnostico)<div style="font-size:0.65rem;color:#78716C;margin-top:0.15rem;font-weight:500">{{Str::limit($p->diagnostico,28)}}</div>@endif
    </div>
    @endforeach
    </div>
</div>
@endif

{{-- ALERTAS --}}
@if($alerts->count() > 0)
<div class="card" style="padding:1.25rem">
    <h4 style="font-weight:800;margin-bottom:0.75rem;font-size:0.85rem"><i class="fas fa-bell" style="color:#EA580C"></i> Alertas Recientes</h4>
    @foreach($alerts as $a)
    <div style="padding:0.5rem 0.7rem;border-radius:8px;margin-bottom:0.35rem;border-left:3px solid {{$a->severity==='Crítica'?'#DC2626':'#F97316'}};background:{{$a->severity==='Crítica'?'#FEF2F2':'#FFF7ED'}}">
        <span style="font-weight:700;font-size:0.75rem;color:{{$a->severity==='Crítica'?'#DC2626':'#EA580C'}}">{{$a->type}}</span>
        <span style="font-size:0.7rem;color:#57534E"> — {{$a->message}}</span>
    </div>
    @endforeach
</div>
@endif
@endsection
