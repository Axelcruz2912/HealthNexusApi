@extends('superadmin.layout')
@section('title', 'Proveedores Hospitalarios')
@section('nav-proveedores', 'active')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
    <h3 style="font-weight: 800; color: #1E1A17;">Catálogo de Proveedores</h3>
    <button onclick="document.getElementById('modal-add-prov').style.display='flex'" style="background: #F05A4E; color: white; border: none; padding: 0.6rem 1.2rem; border-radius: 8px; font-weight: 700; cursor: pointer;">
        <i class="fas fa-plus"></i> Nuevo Proveedor
    </button>
</div>

<div style="background: white; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.04); overflow: hidden;">
    <table style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="background: #F9FAFB; text-align: left; border-bottom: 2px solid #E5E7EB;">
                <th style="padding: 1rem; font-size: 0.8rem; color: #736860;">Razón Social</th>
                <th style="padding: 1rem; font-size: 0.8rem; color: #736860;">RFC</th>
                <th style="padding: 1rem; font-size: 0.8rem; color: #736860;">Contacto</th>
                <th style="padding: 1rem; font-size: 0.8rem; color: #736860;">Tipo de Suministro</th>
                <th style="padding: 1rem; font-size: 0.8rem; color: #736860;">Estatus</th>
                <th style="padding: 1rem; font-size: 0.8rem; color: #736860;">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($providers as $provider)
            <tr style="border-bottom: 1px solid #E5E7EB;" onmouseover="this.style.background='#FFF1EE'" onmouseout="this.style.background='white'">
                <td style="padding: 1rem; font-weight: 600;">{{ $provider->name }}</td>
                <td style="padding: 1rem; font-family: monospace; color: #736860;">{{ $provider->rfc }}</td>
                <td style="padding: 1rem; font-size: 0.85rem;">{{ $provider->contact_name }}<br><span style="color:#736860;">{{ $provider->email }}</span></td>
                <td style="padding: 1rem;"><span style="background: #E5E7EB; color: #1E1A17; padding: 0.2rem 0.6rem; border-radius: 10px; font-size: 0.75rem; font-weight: 700;">{{ $provider->type }}</span></td>
                <td style="padding: 1rem;">
                    <form action="{{ route('superadmin.toggleProviderStatus', $provider->id) }}" method="POST">
                        @csrf @method('PUT')
                        <button type="submit" style="background: {{ $provider->status == 'Activo' ? '#EBF9F2' : '#FFF1F0' }}; color: {{ $provider->status == 'Activo' ? '#065F46' : '#C7291C' }}; border: none; padding: 0.3rem 0.6rem; border-radius: 6px; font-weight: 700; cursor: pointer; font-size:0.75rem;">
                            {{ $provider->status }}
                        </button>
                    </form>
                </td>
                <td style="padding: 1rem;">
                    <form action="{{ route('superadmin.deleteProvider', $provider->id) }}" method="POST" onsubmit="return confirm('¿Eliminar proveedor?');">
                        @csrf @method('DELETE')
                        <button type="submit" style="background: #8C1A11; color: white; border: none; padding: 0.4rem; border-radius: 6px; cursor: pointer;"><i class="fas fa-trash"></i></button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Modal Agregar Proveedor -->
<div id="modal-add-prov" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:100; align-items:center; justify-content:center;">
    <div style="background:white; padding:2rem; border-radius:12px; width:450px;">
        <h3 style="font-weight:800; margin-bottom:1rem;">Registrar Nuevo Proveedor</h3>
        <form action="{{ route('superadmin.storeProvider') }}" method="POST">
            @csrf
            <div style="margin-bottom:1rem;"><label style="font-size:0.8rem; font-weight:700; color:#736860;">Razón Social</label><input type="text" name="name" required style="width:100%; padding:0.6rem; border:1px solid #E5E7EB; border-radius:6px; margin-top:0.3rem;"></div>
            <div style="margin-bottom:1rem;"><label style="font-size:0.8rem; font-weight:700; color:#736860;">RFC</label><input type="text" name="rfc" maxlength="13" required style="width:100%; padding:0.6rem; border:1px solid #E5E7EB; border-radius:6px; margin-top:0.3rem;"></div>
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px; margin-bottom:1rem;">
                <div><label style="font-size:0.8rem; font-weight:700; color:#736860;">Contacto</label><input type="text" name="contact_name" required style="width:100%; padding:0.6rem; border:1px solid #E5E7EB; border-radius:6px; margin-top:0.3rem;"></div>
                <div><label style="font-size:0.8rem; font-weight:700; color:#736860;">Teléfono</label><input type="text" name="phone" required style="width:100%; padding:0.6rem; border:1px solid #E5E7EB; border-radius:6px; margin-top:0.3rem;"></div>
            </div>
            <div style="margin-bottom:1rem;"><label style="font-size:0.8rem; font-weight:700; color:#736860;">Correo</label><input type="email" name="email" required style="width:100%; padding:0.6rem; border:1px solid #E5E7EB; border-radius:6px; margin-top:0.3rem;"></div>
            <div style="margin-bottom:1rem;"><label style="font-size:0.8rem; font-weight:700; color:#736860;">Tipo de Suministro</label>
                <select name="type" style="width:100%; padding:0.6rem; border:1px solid #E5E7EB; border-radius:6px; margin-top:0.3rem;"><option>Medicamentos</option><option>Equipos Médicos</option><option>Insumos</option><option>Servicios</option><option>Alimentos</option></select>
            </div>
            <div style="display:flex; gap:10px; margin-top:1.5rem;">
                <button type="button" onclick="document.getElementById('modal-add-prov').style.display='none'" style="flex:1; padding:0.6rem; border:1px solid #E5E7EB; background:white; border-radius:6px; cursor:pointer;">Cancelar</button>
                <button type="submit" style="flex:1; padding:0.6rem; background:#F05A4E; color:white; border:none; border-radius:6px; cursor:pointer; font-weight:700;">Guardar</button>
            </div>
        </form>
    </div>
</div>
{{ $providers->withQueryString()->links() }}
@endsection
