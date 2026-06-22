@extends('superadmin.layout')
@section('title', 'Verificación de Seguridad Requerida')

@section('content')
<div style="max-width: 500px; margin: 2rem auto; background: white; padding: 3rem; border-radius: 16px; box-shadow: 0 10px 25px rgba(0,0,0,0.2); border-top: 6px solid #C7291C;">
    <div style="text-align: center; margin-bottom: 2rem;">
        <i class="fas fa-lock" style="font-size: 3rem; color: #C7291C; margin-bottom: 1rem;"></i>
        <h2 style="font-weight: 800; color: #1E1A17;">Acceso Restringido</h2>
        <p style="color: #736860;">Módulo de Información Financiera Sensible</p>
    </div>
    
    <div style="background: #FFF1F0; border: 1px solid #FFE0DC; border-radius: 8px; padding: 1.5rem; margin-bottom: 2rem; text-align: center;">
        <h4 style="color: #8C1A11; margin-bottom: 0.5rem;">⚠️ Advertencia de Seguridad</h4>
        <p style="color: #C7291C; font-size: 0.9rem;">El acceso no autorizado a este módulo es delito federal. Todos los intentos de acceso son registrados y auditados en tiempo real.</p>
    </div>

    @if($errors->any())<div style="background: #8C1A11; color: white; padding: 1rem; border-radius: 6px; text-align: center; margin-bottom: 1.5rem;">PIN Incorrecto. Intento registrado.</div>@endif

    <form action="{{ route('superadmin.finanzas.verify') }}" method="POST">
        @csrf
        <div style="margin-bottom: 1.5rem;">
            <label style="font-size: 0.9rem; font-weight: 700; color: #1E1A17;">Ingresa tu PIN de Seguridad Financiera</label>
            <input type="password" name="finance_pin" maxlength="6" required style="width: 100%; padding: 1rem; border: 2px solid #1E1A17; border-radius: 8px; font-size: 1.5rem; text-align: center; letter-spacing: 10px; margin-top: 0.5rem;" placeholder="••••••" autofocus>
        </div>
        <button type="submit" style="width: 100%; padding: 1rem; background: #1E1A17; color: white; border: none; border-radius: 8px; font-weight: 700; font-size: 1.1rem; cursor: pointer;">
            <i class="fas fa-unlock-alt"></i> Verificar e Ingresar
        </button>
    </form>
</div>
@endsection
