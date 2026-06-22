@extends('enfermeria.layout')
@section('title', 'Pacientes')
@section('nav-pacientes', 'active')

@section('content')
@if(session('success'))
<div style="background:#F0FDF4; border:2px solid #86EFAC; border-radius:12px; padding:1rem 1.25rem; margin-bottom:1.5rem; display:flex; align-items:center; gap:0.75rem;">
    <i class="fas fa-check-circle" style="color:#F97316; font-size:1.2rem;"></i>
    <span style="font-weight:700; color:#9A3412;">{{ session('success') }}</span>
</div>
@endif

@if(session('error'))
<div style="background:#FEF2F2; border:2px solid #FCA5A5; border-radius:12px; padding:1rem 1.25rem; margin-bottom:1.5rem; display:flex; align-items:center; gap:0.75rem;">
    <i class="fas fa-times-circle" style="color:#DC2626; font-size:1.2rem;"></i>
    <span style="font-weight:700; color:#991B1B;">{{ session('error') }}</span>
</div>
@endif

<div style="background:white; padding:1.5rem; border-radius:12px; box-shadow:0 4px 6px rgba(0,0,0,0.04); margin-bottom:1.5rem; display:flex; justify-content:space-between; align-items:center;">
    <h3 style="font-weight:800;"><i class="fas fa-users" style="color:#DC2626;"></i> Todos los Pacientes</h3>
    <span style="background:#FEF2F2; color:#991B1B; padding:0.3rem 0.8rem; border-radius:20px; font-size:0.8rem; font-weight:700;">{{ $patients->count() }} registrados</span>
</div>

<div style="background:white; border-radius:12px; box-shadow:0 4px 6px rgba(0,0,0,0.04); overflow:hidden;">
    <table style="width:100%; border-collapse:collapse;">
        <thead><tr style="background:#7C2D12; color:white; text-align:left;">
            <th style="padding:0.75rem; font-size:0.8rem;">Paciente</th>
            <th style="padding:0.75rem; font-size:0.8rem;">Triage</th>
            <th style="padding:0.75rem; font-size:0.8rem;">Sintomas</th>
            <th style="padding:0.75rem; font-size:0.8rem;">Signos Vitales</th>
            <th style="padding:0.75rem; font-size:0.8rem;">Area Actual</th>
            <th style="padding:0.75rem; font-size:0.8rem;">Estatus</th>
            <th style="padding:0.75rem; font-size:0.8rem;">Acciones</th>
        </tr></thead>
        <tbody>
        @php $colors = ['Rojo'=>'#DC2626','Naranja'=>'#FF8C42','Amarillo'=>'#D97706','Verde'=>'#F97316','Azul'=>'#DC2626']; @endphp
        @foreach($patients as $p)
        <tr style="border-bottom:1px solid #E2E8F0; @if($p->triage_level === 'Rojo') background:#FEF2F2; @endif">
            <td style="padding:0.75rem; font-weight:700;">{{ $p->patient_name }} <span style="font-size:0.7rem; color:#64748B;">({{ $p->age }}a)</span></td>
            <td style="padding:0.75rem;"><span style="background:{{ $colors[$p->triage_level] ?? '#64748B' }}; color:white; padding:0.15rem 0.5rem; border-radius:10px; font-size:0.75rem; font-weight:700;">{{ $p->triage_level }}</span></td>
            <td style="padding:0.75rem; font-size:0.8rem; color:#475569; max-width:200px;">{{ $p->symptoms }}</td>
            <td style="padding:0.75rem; font-size:0.7rem; font-family:monospace;">{{ $p->vitals_ta ? "TA:{$p->vitals_ta} FC:{$p->vitals_fc} T:{$p->vitals_temp} SpO2:{$p->vitals_spo2}%" : 'Sin registrar' }}</td>
            <td style="padding:0.75rem;">
                @if($p->assigned_area)
                <span style="background:#FEF2F2; color:#991B1B; padding:0.15rem 0.5rem; border-radius:8px; font-size:0.75rem; font-weight:700;">{{ $p->assigned_area }}</span>
                @else
                <span style="color:#94A3B8; font-size:0.75rem;">Sin asignar</span>
                @endif
            </td>
            <td style="padding:0.75rem;">
                @php $statusColors = ['En Espera'=>'#F97316','En Atención'=>'#DC2626','Hospitalizado'=>'#EF4444','Dado de Alta'=>'#F97316','Derivado'=>'#64748B']; @endphp
                <span style="background:{{ ($statusColors[$p->status] ?? '#64748B') }}22; color:{{ $statusColors[$p->status] ?? '#64748B' }}; padding:0.15rem 0.5rem; border-radius:10px; font-size:0.75rem; font-weight:700;">{{ $p->status }}</span>
            </td>
            <td style="padding:0.75rem;">
                @if($p->status !== 'Dado de Alta' && $p->status !== 'Derivado')
                <div style="display:flex; gap:4px; flex-wrap:wrap;">
                    @if(!$p->assigned_area)
                    <form method="POST" action="{{ route('enfermeria.enviarA', $p->id) }}" style="display:flex; gap:3px;">
                        @csrf
                        <select name="destino" style="padding:0.25rem; border:1px solid #E2E8F0; border-radius:4px; font-size:0.7rem;">
                            <option value="Urgencias">Urgencias</option>
                            <option value="Consulta">Consulta</option>
                            <option value="Hospitalizacion">Hospitalizacion</option>
                            <option value="UCI">UCI</option>
                            <option value="Quirofano">Quirofano</option>
                        </select>
                        <button type="submit" style="background:#DC2626; color:white; border:none; padding:0.25rem 0.5rem; border-radius:4px; font-size:0.65rem; cursor:pointer; font-weight:700;"><i class="fas fa-paper-plane"></i> Enviar</button>
                    </form>
                    @else
                    <form method="POST" action="{{ route('enfermeria.reasignar', $p->id) }}" style="display:flex; gap:3px;">
                        @csrf @method('PUT')
                        <select name="destino" style="padding:0.25rem; border:1px solid #E2E8F0; border-radius:4px; font-size:0.7rem;">
                            <option value="Urgencias" {{ $p->assigned_area === 'Urgencias' ? 'selected' : '' }}>Urgencias</option>
                            <option value="Consulta" {{ $p->assigned_area === 'Consulta' ? 'selected' : '' }}>Consulta</option>
                            <option value="Hospitalizacion" {{ $p->assigned_area === 'Hospitalizacion' ? 'selected' : '' }}>Hospitalizacion</option>
                            <option value="UCI" {{ $p->assigned_area === 'UCI' ? 'selected' : '' }}>UCI</option>
                            <option value="Quirofano" {{ $p->assigned_area === 'Quirofano' ? 'selected' : '' }}>Quirofano</option>
                        </select>
                        <button type="submit" style="background:#F97316; color:white; border:none; padding:0.25rem 0.5rem; border-radius:4px; font-size:0.65rem; cursor:pointer; font-weight:700;"><i class="fas fa-exchange-alt"></i> Cambiar</button>
                    </form>
                    @endif
                    <form method="POST" action="{{ route('enfermeria.darAlta', $p->id) }}">
                        @csrf @method('PUT')
                        <button type="submit" style="background:#F97316; color:white; border:none; padding:0.25rem 0.5rem; border-radius:4px; font-size:0.65rem; cursor:pointer; font-weight:700;" onclick="return confirm('Dar de alta a {{ $p->patient_name }}?')"><i class="fas fa-check"></i> Alta</button>
                    </form>
                </div>
                @else
                <span style="color:#94A3B8; font-size:0.7rem;">Completado</span>
                @endif
            </td>
        </tr>
        @endforeach
        </tbody>
    </table>
</div>
{{ $patients->withQueryString()->links() }}
@endsection
