@extends('superadmin.layout')
@section('title', 'Control de Roles y Permisos')
@section('nav-roles', 'active')

@section('content')
<div style="background: white; padding: 1.5rem; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.04); margin-bottom: 1.5rem;">
    <h3 style="font-weight: 800; color: #1E1A17;">Matriz de Permisos del Sistema</h3>
    <p style="color: #736860; font-size: 0.85rem;">Activa o desactiva el acceso a los módulos por rol. Los cambios se aplican inmediatamente.</p>
</div>

<div style="background: white; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.04); overflow-x: auto;">
    <table style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="background: #1E1A17; color: white; text-align: left;">
                <th style="padding: 1rem; font-size: 0.8rem; text-transform: uppercase;">Módulo del Sistema</th>
                @foreach($roles as $role)
                    <th style="padding: 1rem; font-size: 0.75rem; text-transform: uppercase; text-align: center;">{{ $role }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($modules as $key => $name)
            <tr style="border-bottom: 1px solid #E5E7EB;">
                <td style="padding: 1rem; font-weight: 700; color: #1E1A17;">{{ $name }}</td>
                @foreach($roles as $role)
                    @php
                        $perm = $permissions->where('role', $role)->where('module_key', $key)->first();
                        $checked = $perm ? $perm->can_access : true;
                    @endphp
                    <td style="padding: 1rem; text-align: center;">
                        <form action="{{ route('superadmin.togglePermission') }}" method="POST">
                            @csrf
                            <input type="hidden" name="role" value="{{ $role }}">
                            <input type="hidden" name="module_key" value="{{ $key }}">
                            <button type="submit" style="border: none; background: none; cursor: pointer; font-size: 1.2rem;">
                                @if($checked)
                                    <i class="fas fa-check-circle" style="color: #2D9E6A;"></i>
                                @else
                                    <i class="fas fa-times-circle" style="color: #C7291C;"></i>
                                @endif
                            </button>
                        </form>
                    </td>
                @endforeach
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
