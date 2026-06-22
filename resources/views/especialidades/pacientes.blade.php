@extends('especialidades.layout')

@section('content')
<div style="padding:1.5rem">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem">
        <h2 style="font-weight:900;color:#9A3412"><i class="fas fa-user-injured" style="color:#EA580C"></i> Mis Pacientes</h2>
        <span style="background:#FFEDD5;color:#EA580C;padding:0.3rem 0.8rem;border-radius:20px;font-weight:800;font-size:0.8rem">{{ $myPatients->total() }} pacientes</span>
    </div>

    @if(session('success'))
    <div style="background:#DCFCE7;color:#16A34A;padding:0.8rem;border-radius:8px;margin-bottom:1rem;font-weight:700">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
    @endif

    <!-- MIS PACIENTES ASIGNADOS -->
    <div style="background:white;border-radius:16px;padding:1.5rem;box-shadow:0 2px 8px rgba(0,0,0,0.05);margin-bottom:1.5rem">
        <h3 style="font-weight:900;color:#9A3412;margin-bottom:1rem"><i class="fas fa-clipboard-list" style="color:#F97316"></i> Pacientes Asignados a Mi</h3>
        @if($myPatients->count() > 0)
        <div style="overflow-x:auto">
            <table style="width:100%;border-collapse:collapse;font-size:0.85rem">
                <thead>
                    <tr style="background:#FFF7ED">
                        <th style="padding:0.6rem;text-align:left;color:#9A3412">Paciente</th>
                        <th style="padding:0.6rem;color:#9A3412">Triage</th>
                        <th style="padding:0.6rem;color:#9A3412">Estado</th>
                        <th style="padding:0.6rem;color:#9A3412">Sintomas</th>
                        <th style="padding:0.6rem;color:#9A3412">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($myPatients as $p)
                <tr style="border-bottom:1px solid #FFF0E0">
                    <td style="padding:0.5rem;font-weight:700">{{ $p->patient_name }}</td>
                    <td style="padding:0.5rem">
                        @php
                            $colors = ['Rojo' => ['#FEE2E2','#DC2626'], 'Naranja' => ['#FFEDD5','#EA580C'], 'Amarillo' => ['#FEF9C3','#CA8A04'], 'Verde' => ['#DCFCE7','#16A34A'], 'Azul' => ['#DBEAFE','#2563EB']];
                            $c = isset($colors[$p->triage_level]) ? $colors[$p->triage_level] : ['#F3F4F6','#6B7280'];
                        @endphp
                        <span style="background:{{ $c[0] }};color:{{ $c[1] }};padding:0.15rem 0.5rem;border-radius:4px;font-size:0.75rem;font-weight:800">{{ $p->triage_level }}</span>
                    </td>
                    <td style="padding:0.5rem;color:#9A3412;font-size:0.8rem">{{ $p->status }}</td>
                    <td style="padding:0.5rem;color:#78716C;max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">{{ Str::limit($p->symptoms, 40) }}</td>
                    <td style="padding:0.5rem">
                        <form method="POST" action="{{ url('/medico/derivar/' . $p->id) }}" style="display:inline-flex;gap:0.3rem;align-items:center">
                            @csrf
                            <select name="doctor_id" style="padding:0.2rem;border:1px solid #FDBA74;border-radius:4px;font-size:0.7rem">
                                <option value="">Derivar a...</option>
                                @foreach($todosMedicos as $doc)
                                <option value="{{ $doc->id }}">{{ $doc->name }} ({{ $doc->role }})</option>
                                @endforeach
                            </select>
                            <button type="submit" style="padding:0.2rem 0.5rem;background:#EA580C;color:white;border:none;border-radius:4px;font-size:0.7rem;cursor:pointer"><i class="fas fa-share"></i></button>
                        </form>
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        {{ $myPatients->withQueryString()->links() }}
        @else
        <p style="text-align:center;color:#D97706;padding:2rem;font-weight:700"><i class="fas fa-inbox"></i> Sin pacientes asignados</p>
        @endif
    </div>

    <!-- COLEGAS DE ESPECIALIDAD -->
    @if($colegas->count() > 0)
    <div style="background:white;border-radius:16px;padding:1.5rem;box-shadow:0 2px 8px rgba(0,0,0,0.05)">
        <h3 style="font-weight:900;color:#9A3412;margin-bottom:1rem"><i class="fas fa-users" style="color:#F97316"></i> Colegas de Especialidad</h3>
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:0.8rem">
            @foreach($colegas as $col)
            <div style="border:2px solid #FFEDD5;border-radius:12px;padding:1rem;text-align:center">
                <i class="fas fa-user-md" style="font-size:1.5rem;color:#EA580C"></i>
                <div style="font-weight:800;color:#9A3412;font-size:0.85rem;margin-top:0.3rem">{{ $col->name }}</div>
                <div style="font-size:0.7rem;color:#78716C">{{ $col->role }}</div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection
