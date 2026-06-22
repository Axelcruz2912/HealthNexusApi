@extends('especialidades.layout')

@section('content')
<div style="padding:1.5rem">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem">
        <h2 style="font-weight:900;color:#9A3412"><i class="fas fa-bed-pulse" style="color:#EA580C"></i> Hospitalizados</h2>
    </div>

    @if(session('success'))
    <div style="background:#DCFCE7;color:#16A34A;padding:0.8rem;border-radius:8px;margin-bottom:1rem;font-weight:700">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
    @endif

    <!-- MIS HOSPITALIZADOS -->
    <div style="background:white;border-radius:16px;padding:1.5rem;box-shadow:0 2px 8px rgba(0,0,0,0.05);margin-bottom:1.5rem;border-top:4px solid #DC2626">
        <h3 style="font-weight:900;color:#9A3412;margin-bottom:1rem"><i class="fas fa-procedures" style="color:#DC2626"></i> Mis Pacientes Hospitalizados</h3>
        @if($misHospitalizados->count() > 0)
        <table style="width:100%;border-collapse:collapse;font-size:0.85rem">
            <thead><tr style="background:#FEF2F2"><th style="padding:0.6rem;text-align:left;color:#991B1B">Paciente</th><th style="padding:0.6rem;color:#991B1B">Triage</th><th style="padding:0.6rem;color:#991B1B">Sintomas</th><th style="padding:0.6rem;color:#991B1B">Acciones</th></tr></thead>
            <tbody>
            @foreach($misHospitalizados as $p)
            <tr style="border-bottom:1px solid #FEE2E2">
                <td style="padding:0.5rem;font-weight:700">{{ $p->patient_name }}</td>
                <td style="padding:0.5rem"><span style="background:#FEE2E2;color:#DC2626;padding:0.15rem 0.5rem;border-radius:4px;font-size:0.75rem;font-weight:800">{{ $p->triage_level }}</span></td>
                <td style="padding:0.5rem;color:#78716C">{{ Str::limit($p->symptoms, 40) }}</td>
                <td style="padding:0.5rem">
                    <form method="POST" action="{{ url('/medico/derivar/' . $p->id) }}" style="display:inline-flex;gap:0.3rem;align-items:center">
                        @csrf
                        <select name="doctor_id" style="padding:0.2rem;border:1px solid #FCA5A5;border-radius:4px;font-size:0.7rem">
                            <option value="">Derivar a...</option>
                            @foreach($todosMedicos as $doc)
                            <option value="{{ $doc->id }}">{{ $doc->name }}</option>
                            @endforeach
                        </select>
                        <button type="submit" style="padding:0.2rem 0.5rem;background:#DC2626;color:white;border:none;border-radius:4px;font-size:0.7rem;cursor:pointer"><i class="fas fa-share"></i></button>
                    </form>
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>
        {{ $misHospitalizados->withQueryString()->links() }}
        @else
        <p style="text-align:center;color:#D97706;padding:2rem;font-weight:700"><i class="fas fa-check-circle" style="color:#16A34A"></i> Sin pacientes hospitalizados asignados</p>
        @endif
    </div>

    <!-- PACIENTES PENDIENTES DE ASIGNACION -->
    <div style="background:white;border-radius:16px;padding:1.5rem;box-shadow:0 2px 8px rgba(0,0,0,0.05);margin-bottom:1.5rem;border-top:4px solid #F97316">
        <h3 style="font-weight:900;color:#9A3412;margin-bottom:1rem"><i class="fas fa-user-clock" style="color:#F97316"></i> Pacientes Sin Medico Asignado</h3>
        @if($pendientes->count() > 0)
        <table style="width:100%;border-collapse:collapse;font-size:0.85rem">
            <thead><tr style="background:#FFEDD5"><th style="padding:0.6rem;text-align:left;color:#9A3412">Paciente</th><th style="padding:0.6rem;color:#9A3412">Triage</th><th style="padding:0.6rem;color:#9A3412">Estado</th><th style="padding:0.6rem;color:#9A3412">Acciones</th></tr></thead>
            <tbody>
            @foreach($pendientes as $p)
            <tr style="border-bottom:1px solid #FFEDD5">
                <td style="padding:0.5rem;font-weight:700">{{ $p->patient_name }}</td>
                <td style="padding:0.5rem">
                    @php $colors = ['Rojo' => ['#FEE2E2','#DC2626'], 'Naranja' => ['#FFEDD5','#EA580C'], 'Amarillo' => ['#FEF9C3','#CA8A04'], 'Verde' => ['#DCFCE7','#16A34A']]; $c = isset($colors[$p->triage_level]) ? $colors[$p->triage_level] : ['#F3F4F6','#6B7280']; @endphp
                    <span style="background:{{ $c[0] }};color:{{ $c[1] }};padding:0.15rem 0.5rem;border-radius:4px;font-size:0.75rem;font-weight:800">{{ $p->triage_level }}</span>
                </td>
                <td style="padding:0.5rem;color:#9A3412;font-size:0.8rem">{{ $p->status }}</td>
                <td style="padding:0.5rem">
                    <form method="POST" action="{{ url('/medico/aceptar/' . $p->id) }}" style="display:inline">
                        @csrf
                        <button type="submit" style="padding:0.3rem 0.6rem;background:#16A34A;color:white;border:none;border-radius:6px;font-weight:800;font-size:0.75rem;cursor:pointer"><i class="fas fa-check"></i> Aceptar</button>
                    </form>
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>
        @else
        <p style="text-align:center;color:#16A34A;padding:2rem;font-weight:700"><i class="fas fa-check-circle"></i> Todos los pacientes tienen medico asignado</p>
        @endif
    </div>

    <!-- CAMAS -->
    <div style="background:white;border-radius:16px;padding:1.5rem;box-shadow:0 2px 8px rgba(0,0,0,0.05);border-top:4px solid #2563EB">
        <h3 style="font-weight:900;color:#1E40AF;margin-bottom:1rem"><i class="fas fa-bed" style="color:#2563EB"></i> Estado de Camas</h3>
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(120px,1fr));gap:0.5rem">
            @foreach($camas as $cama)
            <div style="border:2px solid {{ $cama->status == 'Disponible' ? '#16A34A' : '#DC2626' }};border-radius:8px;padding:0.6rem;text-align:center;background:{{ $cama->status == 'Disponible' ? '#F0FDF4' : '#FEF2F2' }}">
                <i class="fas fa-bed" style="color:{{ $cama->status == 'Disponible' ? '#16A34A' : '#DC2626' }}"></i>
                <div style="font-weight:800;font-size:0.75rem;color:{{ $cama->status == 'Disponible' ? '#16A34A' : '#DC2626' }}">{{ $cama->bed_number }}</div>
                <div style="font-size:0.6rem;color:#78716C">{{ $cama->status }}</div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
