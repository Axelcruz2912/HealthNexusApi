@extends('superadmin.layout')
@section('title', 'Validación y Gestión de Personal')
@section('nav-personal', 'active')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
    <div>
        <h3 style="font-weight: 800; color: #1E1A17;">Credencialización Hospitalaria</h3>
        <p style="color: #736860; font-size: 0.85rem;">Validación de documentos, CURP, RFC y permisos del personal.</p>
    </div>
    <button onclick="document.getElementById('modal-add-user').style.display='flex'" style="background: #F05A4E; color: white; border: none; padding: 0.6rem 1.2rem; border-radius: 8px; font-weight: 700; cursor: pointer;">
        <i class="fas fa-user-plus"></i> Nuevo Empleado
    </button>
</div>

<div style="background: white; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.04); overflow: hidden;">
    <table style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="background: #1E1A17; color: white; text-align: left;">
                <th style="padding: 1rem; font-size: 0.8rem;">Empleado</th>
                <th style="padding: 1rem; font-size: 0.8rem;">Rol Asignado</th>
                <th style="padding: 1rem; font-size: 0.8rem;">CURP / RFC</th>
                <th style="padding: 1rem; font-size: 0.8rem;">Documentos</th>
                <th style="padding: 1rem; font-size: 0.8rem;">Validación</th>
                <th style="padding: 1rem; font-size: 0.8rem;">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr style="border-bottom: 1px solid #E5E7EB;" onmouseover="this.style.background='#FFF1EE'" onmouseout="this.style.background='white'">
                <td style="padding: 1rem;">
                    <div style="font-weight: 700; color: #1E1A17;">{{ $user->name }}</div>
                    <div style="font-size: 0.8rem; color: #736860;">{{ $user->email }}</div>
                </td>
                <td style="padding: 1rem;">
                    <form action="{{ route('superadmin.updateRole', $user->id) }}" method="POST">
                        @csrf @method('PUT')
                        <select name="role" onchange="this.form.submit()" style="padding: 0.3rem; border:1px solid #E5E7EB; border-radius:4px; font-size:0.8rem; font-weight:600; background:white;">
                            @foreach(['SuperAdmin', 'Administrador Hospitalario', 'Médico A', 'Médico B', 'Médico C', 'Enfermera A', 'Enfermera B', 'Enfermera C', 'Recepcionista', 'Farmacéutico', 'Admin Farmacia', 'Finanzas', 'Laboratorista', 'Urgenciólogo'] as $r)
                                <option value="{{ $r }}" {{ $user->role === $r ? 'selected' : '' }}>{{ $r }}</option>
                            @endforeach
                        </select>
                    </form>
                </td>
                <td style="padding: 1rem; font-size: 0.8rem; font-family: monospace;">
                    @if($user->curp)
                        <span style="color:#2D9E6A;"><i class="fas fa-check-circle"></i> CURP</span><br>
                    @else
                        <span style="color:#C7291C;"><i class="fas fa-times-circle"></i> CURP</span><br>
                    @endif
                    @if($user->rfc)
                        <span style="color:#2D9E6A;"><i class="fas fa-check-circle"></i> RFC</span>
                    @else
                        <span style="color:#C7291C;"><i class="fas fa-times-circle"></i> RFC</span>
                    @endif
                </td>
                <td style="padding: 1rem; font-size: 0.8rem;">
                    @if($user->ine_path)<a href="{{ asset($user->ine_path) }}" target="_blank" style="color:#F05A4E; text-decoration:underline;">INE</a> | @endif
                    @if($user->cedula_path)<a href="{{ asset($user->cedula_path) }}" target="_blank" style="color:#F05A4E; text-decoration:underline;">Cédula</a> | @endif
                    @if($user->certifications_path)<a href="{{ asset($user->certifications_path) }}" target="_blank" style="color:#F05A4E; text-decoration:underline;">Cert</a>@endif
                    @if(!$user->ine_path && !$user->cedula_path)<span style="color:#736860;">Sin docs</span>@endif
                </td>
                <td style="padding: 1rem;">
                    @if($user->validation_status == 'Aprobado')
                        <span style="background:#EBF9F2; color:#065F46; padding:0.3rem 0.6rem; border-radius:10px; font-size:0.75rem; font-weight:700;">APROBADO</span>
                    @elseif($user->validation_status == 'Rechazado')
                        <span style="background:#FFF1F0; color:#C7291C; padding:0.3rem 0.6rem; border-radius:10px; font-size:0.75rem; font-weight:700;" title="{{ $user->rejection_reason }}">RECHAZADO</span>
                    @else
                        <span style="background:#FFF5EB; color:#9a3412; padding:0.3rem 0.6rem; border-radius:10px; font-size:0.75rem; font-weight:700;">PENDIENTE</span>
                    @endif
                </td>
                <td style="padding: 1rem; display: flex; gap: 5px;">
                    @if($user->validation_status != 'Aprobado')
                    <form action="{{ route('superadmin.approveUser', $user->id) }}" method="POST">
                        @csrf
                        <button type="submit" style="background:#2D9E6A; color:white; border:none; padding:0.3rem 0.5rem; border-radius:4px; cursor:pointer; font-size:0.75rem;" title="Aprobar"><i class="fas fa-check"></i></button>
                    </form>
                    @endif
                    @if($user->validation_status != 'Rechazado')
                    <button onclick="openRejectModal({{ $user->id }})" style="background:#C7291C; color:white; border:none; padding:0.3rem 0.5rem; border-radius:4px; cursor:pointer; font-size:0.75rem;" title="Rechazar"><i class="fas fa-times"></i></button>
                    @endif
                    <form action="{{ route('superadmin.deleteUser', $user->id) }}" method="POST" onsubmit="return confirm('¿Dar de baja definitivamente?');">
                        @csrf @method('DELETE')
                        <button type="submit" style="background:#8C1A11; color:white; border:none; padding:0.3rem 0.5rem; border-radius:4px; cursor:pointer; font-size:0.75rem;" title="Eliminar"><i class="fas fa-trash"></i></button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Modal Agregar Empleado con Subida de Archivos -->
<div id="modal-add-user" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.6); z-index:100; align-items:center; justify-content:center; overflow-y:auto;">
    <div style="background:white; padding:2rem; border-radius:12px; width:500px; margin-top:2rem;">
        <h3 style="font-weight:800; margin-bottom:1rem;">Registro Nuevo Empleado</h3>
        <form action="{{ route('superadmin.storeUser') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px; margin-bottom:1rem;">
                <div><label style="font-size:0.8rem; font-weight:700; color:#736860;">Nombre Completo</label><input type="text" name="name" required style="width:100%; padding:0.5rem; border:1px solid #E5E7EB; border-radius:6px;"></div>
                <div><label style="font-size:0.8rem; font-weight:700; color:#736860;">Correo</label><input type="email" name="email" required style="width:100%; padding:0.5rem; border:1px solid #E5E7EB; border-radius:6px;"></div>
            </div>
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px; margin-bottom:1rem;">
                <div><label style="font-size:0.8rem; font-weight:700; color:#736860;">CURP (18 caracteres)</label><input type="text" name="curp" maxlength="18" style="width:100%; padding:0.5rem; border:1px solid #E5E7EB; border-radius:6px; text-transform:uppercase;"></div>
                <div><label style="font-size:0.8rem; font-weight:700; color:#736860;">RFC (12-13 caracteres)</label><input type="text" name="rfc" maxlength="13" style="width:100%; padding:0.5rem; border:1px solid #E5E7EB; border-radius:6px; text-transform:uppercase;"></div>
            </div>
            <div style="margin-bottom:1rem;"><label style="font-size:0.8rem; font-weight:700; color:#736860;">Contraseña Temporal</label><input type="password" name="password" required style="width:100%; padding:0.5rem; border:1px solid #E5E7EB; border-radius:6px;"></div>
            <div style="margin-bottom:1rem;"><label style="font-size:0.8rem; font-weight:700; color:#736860;">Rol Asignado</label>
                <select name="role" required style="width:100%; padding:0.5rem; border:1px solid #E5E7EB; border-radius:6px;">
                    @foreach(['Médico A', 'Médico B', 'Médico C', 'Enfermera A', 'Enfermera B', 'Enfermera C', 'Recepcionista', 'Farmacéutico', 'Admin Farmacia', 'Finanzas', 'Laboratorista', 'Urgenciólogo'] as $r)
                    <option value="{{ $r }}">{{ $r }}</option>
                    @endforeach
                </select>
            </div>
            <hr style="margin: 1.5rem 0; border:1px solid #E5E7EB;">
            <h4 style="font-size:0.9rem; font-weight:700; margin-bottom:0.5rem; color:#1E1A17;">Documentación Oficial</h4>
            <div style="margin-bottom:1rem;"><label style="font-size:0.8rem; font-weight:700; color:#736860;">INE / Identificación</label><input type="file" name="ine" accept="image/*,.pdf" style="width:100%; padding:0.5rem; border:1px solid #E5E7EB; border-radius:6px;"></div>
            <div style="margin-bottom:1rem;"><label style="font-size:0.8rem; font-weight:700; color:#736860;">Cédula Profesional (Médicos/Enf)</label><input type="file" name="cedula" accept="image/*,.pdf" style="width:100%; padding:0.5rem; border:1px solid #E5E7EB; border-radius:6px;"></div>
            <div style="margin-bottom:1rem;"><label style="font-size:0.8rem; font-weight:700; color:#736860;">Certificaciones (ACLS/BLS)</label><input type="file" name="certifications" accept="image/*,.pdf" style="width:100%; padding:0.5rem; border:1px solid #E5E7EB; border-radius:6px;"></div>
            
            <div style="display:flex; gap:10px; margin-top:1.5rem;">
                <button type="button" onclick="document.getElementById('modal-add-user').style.display='none'" style="flex:1; padding:0.6rem; border:1px solid #E5E7EB; background:white; border-radius:6px; cursor:pointer;">Cancelar</button>
                <button type="submit" style="flex:1; padding:0.6rem; background:#F05A4E; color:white; border:none; border-radius:6px; cursor:pointer; font-weight:700;">Registrar y Validar</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Rechazar Solicitud -->
<div id="modal-reject" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.6); z-index:200; align-items:center; justify-content:center;">
    <div style="background:white; padding:2rem; border-radius:12px; width:400px;">
        <h3 style="font-weight:800; margin-bottom:1rem; color:#C7291C;">Rechazar Solicitud</h3>
        <form id="reject-form" action="" method="POST">
            @csrf @method('PUT')
            <div style="margin-bottom:1rem;"><label style="font-size:0.8rem; font-weight:700; color:#736860;">Motivo del Rechazo</label><textarea name="rejection_reason" required rows="3" style="width:100%; padding:0.5rem; border:1px solid #E5E7EB; border-radius:6px;" placeholder="Ej: Cédula profesional no válida, RFC incorrecto..."></textarea></div>
            <div style="display:flex; gap:10px;">
                <button type="button" onclick="document.getElementById('modal-reject').style.display='none'" style="flex:1; padding:0.6rem; border:1px solid #E5E7EB; background:white; border-radius:6px; cursor:pointer;">Cancelar</button>
                <button type="submit" style="flex:1; padding:0.6rem; background:#C7291C; color:white; border:none; border-radius:6px; cursor:pointer; font-weight:700;">Confirmar Rechazo</button>
            </div>
        </form>
    </div>
</div>

<script>
function openRejectModal(userId) {
    document.getElementById('reject-form').action = '/superadmin/personal/' + userId + '/reject';
    document.getElementById('modal-reject').style.display = 'flex';
}
</script>
{{ $users->withQueryString()->links() }}
@endsection
