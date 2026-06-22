@extends('especialidades.layout')

@section('content')
<div style="padding:1.5rem">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem">
        <h2 style="font-weight:900;color:#9A3412"><i class="fas fa-pills" style="color:#EA580C"></i> Medicamentos</h2>
        @if($mySpecialty)
        <span style="background:#FFEDD5;color:#EA580C;padding:0.3rem 0.8rem;border-radius:20px;font-weight:800;font-size:0.8rem">{{ $mySpecialty->name }}</span>
        @endif
    </div>

    <!-- MEDICAMENTOS RESTRINGIDOS -->
    @if(count($restringidos) > 0)
    <div style="background:#FEF2F2;border-radius:16px;padding:1.5rem;box-shadow:0 2px 8px rgba(0,0,0,0.05);margin-bottom:1.5rem;border-top:4px solid #DC2626">
        <h3 style="font-weight:900;color:#DC2626;margin-bottom:1rem"><i class="fas fa-ban"></i> Medicamentos Restringidos para {{ $mySpecialty ? $mySpecialty->name : 'Su Especialidad' }}</h3>
        <div style="display:flex;flex-wrap:wrap;gap:0.5rem">
            @foreach($restringidos as $r)
            <span style="background:#FEE2E2;color:#DC2626;padding:0.3rem 0.8rem;border-radius:20px;font-weight:700;font-size:0.8rem"><i class="fas fa-times-circle"></i> {{ $r }}</span>
            @endforeach
        </div>
    </div>
    @endif

    <!-- CATALOGO -->
    <div style="background:white;border-radius:16px;padding:1.5rem;box-shadow:0 2px 8px rgba(0,0,0,0.05)">
        <h3 style="font-weight:900;color:#9A3412;margin-bottom:1rem"><i class="fas fa-capsules" style="color:#F97316"></i> Catalogo de Medicamentos</h3>
        @if($medicamentos->count() > 0)
        <div style="overflow-x:auto">
            <table style="width:100%;border-collapse:collapse;font-size:0.85rem">
                <thead><tr style="background:#FFF7ED"><th style="padding:0.6rem;text-align:left;color:#9A3412">Nombre</th><th style="padding:0.6rem;color:#9A3412">Categoria</th><th style="padding:0.6rem;color:#9A3412">Dosis</th><th style="padding:0.6rem;color:#9A3412">Stock</th><th style="padding:0.6rem;color:#9A3412">Estado</th></tr></thead>
                <tbody>
                @foreach($medicamentos as $m)
                <tr style="border-bottom:1px solid #FFF0E0">
                    <td style="padding:0.5rem;font-weight:700">{{ $m->name }}</td>
                    <td style="padding:0.5rem;color:#78716C">{{ $m->category }}</td>
                    <td style="padding:0.5rem;color:#78716C">{{ $m->dosage }}</td>
                    <td style="padding:0.5rem">
                        @php $stock = isset($m->stock) ? $m->stock : 0; @endphp
                        <span style="color:{{ $stock < 10 ? '#DC2626' : '#16A34A' }};font-weight:800">{{ $stock }}</span>
                    </td>
                    <td style="padding:0.5rem">
                        @if(in_array($m->name, $restringidos))
                        <span style="background:#FEE2E2;color:#DC2626;padding:0.15rem 0.5rem;border-radius:4px;font-size:0.75rem;font-weight:800">Restringido</span>
                        @else
                        <span style="background:#DCFCE7;color:#16A34A;padding:0.15rem 0.5rem;border-radius:4px;font-size:0.75rem;font-weight:800">Disponible</span>
                        @endif
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        {{ $medicamentos->withQueryString()->links() }}
        @else
        <p style="text-align:center;color:#D97706;padding:2rem;font-weight:700"><i class="fas fa-pills"></i> Sin medicamentos registrados</p>
        @endif
    </div>
</div>
@endsection
