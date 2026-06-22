@extends('enfermeria.layout')
@section('title', 'Mapa de Camas')
@section('nav-camas', 'active')

@section('content')
<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:1rem;margin-bottom:2rem">
    <div style="background:#FFF7ED;border:2px solid #FDBA74;border-radius:14px;padding:1.2rem;text-align:center">
        <div style="font-size:2rem;font-weight:900;color:#EA580C">{{ $stats['total'] }}</div>
        <div style="font-size:0.8rem;font-weight:700;color:#9A3412">Total Camas</div>
    </div>
    <div style="background:#FFEDD5;border:2px solid #F97316;border-radius:14px;padding:1.2rem;text-align:center">
        <div style="font-size:2rem;font-weight:900;color:#F97316">{{ $stats['disponibles'] }}</div>
        <div style="font-size:0.8rem;font-weight:700;color:#9A3412">Disponibles</div>
    </div>
    <div style="background:#FEE2E2;border:2px solid #EF4444;border-radius:14px;padding:1.2rem;text-align:center">
        <div style="font-size:2rem;font-weight:900;color:#DC2626">{{ $stats['ocupadas'] }}</div>
        <div style="font-size:0.8rem;font-weight:700;color:#991B1B">Ocupadas</div>
    </div>
    <div style="background:#FEF2F2;border:2px solid #FCA5A5;border-radius:14px;padding:1.2rem;text-align:center">
        <div style="font-size:2rem;font-weight:900;color:#B91C1C">{{ $stats['limpieza'] + $stats['mantenimiento'] }}</div>
        <div style="font-size:0.8rem;font-weight:700;color:#991B1B">Limpieza/Mant.</div>
    </div>
</div>

<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(320px,1fr));gap:1.5rem">
    @foreach($grouped as $area => $beds)
    <div style="background:white;border-radius:16px;box-shadow:0 4px 10px rgba(249,115,22,0.08);overflow:hidden;border:1px solid #FED7AA">
        <div style="background:linear-gradient(135deg,#FFF7ED,#FFEDD5);padding:1rem;border-bottom:2px solid #FDBA74">
            <h3 style="font-size:1rem;font-weight:900;color:#9A3412;text-transform:uppercase"><i class="fas fa-hospital" style="color:#EA580C"></i> {{ $area }}</h3>
            <span style="font-size:0.7rem;color:#EA580C;font-weight:700">{{ $beds->count() }} camas | Piso {{ $beds->first()->floor }}</span>
        </div>
        <div style="padding:1rem;display:grid;grid-template-columns:repeat(auto-fill,minmax(85px,1fr));gap:0.7rem">
            @foreach($beds as $bed)
                @php
                    $bg = '#FFF7ED'; $border = '#FDBA74'; $color = '#9A3412'; $icon = 'fa-bed';
                    if($bed->status == 'Ocupada') {
                        $bg = $bed->triage_level == 'Rojo' ? '#FEE2E2' : ($bed->triage_level == 'Naranja' ? '#FFEDD5' : '#FFF7ED');
                        $border = $bed->triage_level == 'Rojo' ? '#DC2626' : ($bed->triage_level == 'Naranja' ? '#EA580C' : '#F97316');
                        $color = $bed->triage_level == 'Rojo' ? '#991B1B' : '#7C2D12';
                        $icon = 'fa-user-injured';
                    } elseif($bed->status == 'Limpieza') {
                        $bg = '#FEF3C7'; $border = '#F59E0B'; $color = '#92400E'; $icon = 'fa-broom';
                    } elseif($bed->status == 'Mantenimiento') {
                        $bg = '#E5E7EB'; $border = '#6B7280'; $color = '#374151'; $icon = 'fa-wrench';
                    }
                    $bedLabel = 'H' . $bed->room_number . '-C' . $bed->bed_number;
                @endphp
                <div onclick="abrirCama({{ $bed->id }}, '{{ $bed->status }}', '{{ addslashes($bed->patient_name ?? '') }}', '{{ $bedLabel }}')" style="background:{{ $bg }};border:2px solid {{ $border }};border-radius:10px;padding:0.5rem;text-align:center;transition:0.2s;cursor:pointer;@if($bed->status == 'Ocupada' && $bed->triage_level == 'Rojo')animation:pulse 2s infinite;@endif">
                    <i class="fas {{ $icon }}" style="font-size:1.1rem;color:{{ $border }};margin-bottom:0.2rem"></i>
                    <div style="font-size:0.65rem;font-weight:900;color:{{ $color }}">{{ $bedLabel }}</div>
                    @if($bed->status == 'Ocupada')
                        <div style="font-size:0.5rem;font-weight:700;color:{{ $border }};margin-top:0.1rem">{{ Str::limit($bed->patient_name, 12) }}</div>
                        <div style="font-size:0.45rem;background:{{ $border }};color:white;border-radius:3px;padding:0.1rem 0.2rem;margin-top:0.2rem;font-weight:800">{{ $bed->triage_level }}</div>
                    @else
                        <div style="font-size:0.5rem;font-weight:700;color:{{ $color }};margin-top:0.1rem">{{ $bed->status }}</div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
    @endforeach
</div>

<!-- MODAL PARA ASIGNAR/LIBERAR CAMA -->
<div id="modalCama" style="display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.5);z-index:1000;justify-content:center;align-items:center">
    <div style="background:white;border-radius:16px;padding:2rem;width:480px;max-height:85vh;overflow-y:auto;border-top:5px solid #EA580C">
        <h3 id="modalTitle" style="font-weight:900;color:#9A3412;margin-bottom:1.5rem"></h3>
        
        <!-- CAMA DISPONIBLE -->
        <div id="formAsignar" style="display:none">
            <!-- Tabs -->
            <div style="display:flex;gap:0.5rem;margin-bottom:1.2rem">
                <button type="button" onclick="mostrarTab('existente')" id="tabExistente" style="flex:1;padding:0.6rem;background:#F97316;color:white;border:none;border-radius:8px;font-weight:800;font-size:0.8rem;cursor:pointer">Paciente Existente</button>
                <button type="button" onclick="mostrarTab('nuevo')" id="tabNuevo" style="flex:1;padding:0.6rem;background:#FFF7ED;color:#9A3412;border:2px solid #FDBA74;border-radius:8px;font-weight:800;font-size:0.8rem;cursor:pointer">Nuevo Paciente</button>
            </div>

            <!-- Paciente Existente -->
            <form action="" method="POST" id="formAsignarCama">
                @csrf
                <div id="tabExistenteContent">
                    <label style="font-weight:800;font-size:0.85rem;color:#7C2D12">Paciente (En Espera / En Atención)</label>
                    <select name="triage_id" id="selectPaciente" style="width:100%;padding:0.7rem;border:2px solid #FDBA74;border-radius:8px;font-size:0.85rem;margin-top:0.5rem;background:#FFF7ED">
                        <option value="">Seleccionar paciente...</option>
                    </select>
                </div>
                <div id="tabNuevoContent" style="display:none">
                    <label style="font-weight:800;font-size:0.85rem;color:#7C2D12">Nombre del Paciente</label>
                    <input type="text" name="new_patient_name" id="newPatientName" placeholder="Nombre completo" style="width:100%;padding:0.7rem;border:2px solid #FDBA74;border-radius:8px;font-size:0.85rem;margin-top:0.5rem;background:#FFF7ED">
                    <label style="font-weight:800;font-size:0.85rem;color:#7C2D12;margin-top:0.8rem;display:block">Nivel de Triage</label>
                    <select name="new_triage_level" style="width:100%;padding:0.7rem;border:2px solid #FDBA74;border-radius:8px;font-size:0.85rem;margin-top:0.5rem;background:#FFF7ED">
                        <option value="Verde">Verde - No urgente</option>
                        <option value="Amarillo">Amarillo - Urgente</option>
                        <option value="Naranja">Naranja - Emergencia</option>
                        <option value="Rojo">Rojo - Rescate</option>
                    </select>
                    <label style="font-weight:800;font-size:0.85rem;color:#7C2D12;margin-top:0.8rem;display:block">Edad</label>
                    <input type="number" name="new_age" value="30" min="0" max="120" style="width:100%;padding:0.7rem;border:2px solid #FDBA74;border-radius:8px;font-size:0.85rem;margin-top:0.5rem;background:#FFF7ED">
                    </select>
                </div>
                <button type="submit" style="width:100%;margin-top:1.2rem;padding:0.8rem;background:linear-gradient(135deg,#F97316,#EA580C);color:white;border:none;border-radius:10px;font-weight:800;font-size:0.9rem;cursor:pointer">
                    <i class="fas fa-bed"></i> Asignar Cama
                </button>
            </form>
        </div>

        <!-- CAMA OCUPADA -->
        <div id="infoOcupada" style="display:none">
            <div style="background:#FEE2E2;padding:1rem;border-radius:10px;margin-bottom:1.2rem">
                <p style="font-weight:700;color:#991B1B;font-size:0.9rem"><i class="fas fa-user-injured"></i> Paciente: <span id="nombrePaciente"></span></p>
            </div>
            <form action="" method="POST" id="formLiberarCama">
                @csrf
                <button type="submit" style="width:100%;padding:0.8rem;background:#DC2626;color:white;border:none;border-radius:10px;font-weight:800;font-size:0.9rem;cursor:pointer">
                    <i class="fas fa-door-open"></i> Liberar Cama (Enviar a Limpieza)
                </button>
            </form>
        </div>

        <button onclick="cerrarModal()" style="width:100%;margin-top:0.8rem;padding:0.6rem;background:#FEF2F2;color:#991B1B;border:1px solid #FECACA;border-radius:8px;font-weight:700;cursor:pointer">Cancelar</button>
    </div>
</div>

<script>
    let pacientes = [];
    fetch('/enfermeria/api/pacientes-camas').then(r => r.json()).then(data => { 
        pacientes = data;
        const select = document.getElementById('selectPaciente');
        data.forEach(p => {
            select.innerHTML += `<option value="${p.id}">${p.patient_name} - ${p.triage_level}</option>`;
        });
    });

    function mostrarTab(tab) {
        if (tab === 'existente') {
            document.getElementById('tabExistenteContent').style.display = 'block';
            document.getElementById('tabNuevoContent').style.display = 'none';
            document.getElementById('tabExistente').style.background = '#F97316';
            document.getElementById('tabExistente').style.color = 'white';
            document.getElementById('tabNuevo').style.background = '#FFF7ED';
            document.getElementById('tabNuevo').style.color = '#9A3412';
            document.getElementById('newPatientName').removeAttribute('required');
            document.getElementById('selectPaciente').setAttribute('required', 'required');
        } else {
            document.getElementById('tabExistenteContent').style.display = 'none';
            document.getElementById('tabNuevoContent').style.display = 'block';
            document.getElementById('tabNuevo').style.background = '#F97316';
            document.getElementById('tabNuevo').style.color = 'white';
            document.getElementById('tabExistente').style.background = '#FFF7ED';
            document.getElementById('tabExistente').style.color = '#9A3412';
            document.getElementById('selectPaciente').removeAttribute('required');
            document.getElementById('newPatientName').setAttribute('required', 'required');
        }
    }

    function abrirCama(id, status, paciente, label) {
        const modal = document.getElementById('modalCama');
        modal.style.display = 'flex';
        document.getElementById('modalTitle').innerHTML = '<i class="fas fa-bed" style="color:#EA580C"></i> Cama ' + label;

        if (status === 'Disponible') {
            document.getElementById('formAsignar').style.display = 'block';
            document.getElementById('infoOcupada').style.display = 'none';
            document.getElementById('formAsignarCama').action = '{{ url("/enfermeria/camas/") }}/' + id + '/asignar';
        } else if (status === 'Ocupada') {
            document.getElementById('formAsignar').style.display = 'none';
            document.getElementById('infoOcupada').style.display = 'block';
            document.getElementById('nombrePaciente').innerText = paciente;
            document.getElementById('formLiberarCama').action = '{{ url("/enfermeria/camas/") }}/' + id + '/liberar';
        } else {
            document.getElementById('formAsignar').style.display = 'none';
            document.getElementById('infoOcupada').style.display = 'none';
        }
    }

    function cerrarModal() {
        document.getElementById('modalCama').style.display = 'none';
    }
</script>

<style>
    @keyframes pulse { 0%, 100% { box-shadow: 0 0 0 0 rgba(220,38,38,0.4); } 50% { box-shadow: 0 0 0 8px rgba(220,38,38,0); } }
</style>
@endsection
