@extends('medico.layout')
@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card shadow mb-4">
            <div class="card-header bg-primary text-white"><h6 class="mb-0">Solicitar Servicio</h6></div>
            <div class="card-body">
                <form action="{{ route('medico.storeServicio') }}" method="POST">@csrf
                    <div class="mb-2"><label>Paciente</label><select name="triage_id" class="form-select form-select-sm" required><option value="">Sel...</option>@foreach($pacientes as $p)<option value="{{ $p->id }}">{{ $p->patient_name }}</option>@endforeach</select></div>
                    <div class="mb-2"><label>Tipo</label><select name="tipo" class="form-select form-select-sm"><option>Laboratorio</option><option>Imagenología</option><option>Terapia</option><option>Otro</option></select></div>
                    <div class="mb-2"><label>Descripción</label><textarea name="descripcion" class="form-control form-control-sm" rows="2" required></textarea></div>
                    <div class="mb-2"><label>Prioridad</label><select name="prioridad" class="form-select form-select-sm"><option>Normal</option><option>Urgente</option></select></div>
                    <button class="btn btn-primary btn-sm w-100">Solicitar</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card shadow">
            <div class="card-header bg-dark text-white"><h6 class="mb-0">Mis Solicitudes</h6></div>
            <div class="card-body p-0">
                <table class="table table-sm table-hover mb-0"><thead class="table-light"><tr><th>Fecha</th><th>Tipo</th><th>Descripción</th><th>Estado</th><th></th></tr></thead><tbody>
                @foreach($solicitudes as $s)
                <tr><td>{{ $s->created_at }}</td><td>{{ $s->tipo }}</td><td>{{ Str::limit($s->descripcion, 30) }}</td><td><span class="badge bg-{{ $s->status == 'Pendiente' ? 'warning' : ($s->status == 'Completado' ? 'success' : 'danger') }}">{{ $s->status }}</span></td><td>@if($s->status=='Pendiente')<form action="{{ route('medico.cancelarServicio', $s->id) }}" method="POST" style="display:inline">@csrf @method('DELETE')<button class="btn btn-danger btn-xs">X</button></form>@endif</td></tr>
                @endforeach
                </tbody></table>
                <div class="p-2">{{ $solicitudes->withQueryString()->links() }}</div>
            </div>
        </div>
    </div>
</div>
@endsection
