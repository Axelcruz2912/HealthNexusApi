@php
    $user = auth()->user();
    $role = $user->role;
    $isSuperAdmin = $role === 'Super Admin' || $role === 'SuperAdmin';
    $prefix = $isSuperAdmin ? '/admin' : '/medico';
@endphp

<div style="width:260px;background:white;border-left:1px solid #FDBA74;padding:1rem 0;position:fixed;right:0;top:56px;bottom:0;overflow-y:auto;box-shadow:-2px 0 8px rgba(0,0,0,0.05)">
    <div style="padding:0.5rem 1rem;margin-bottom:0.3rem">
        <div style="font-size:0.65rem;font-weight:900;color:#9A3412;text-transform:uppercase;letter-spacing:1px;padding:0.3rem 0;margin-bottom:0.3rem">Navegacion</div>
        <a href="{{ url($prefix . '/especialista') }}" style="display:flex;align-items:center;gap:0.6rem;padding:0.5rem 0.8rem;color:#78716C;text-decoration:none;font-size:0.82rem;font-weight:600;border-radius:8px;transition:0.15s">
            <i class="fas fa-home" style="width:18px;text-align:center"></i> Dashboard
        </a>
        <a href="{{ url($prefix . '/especialidades') }}" style="display:flex;align-items:center;gap:0.6rem;padding:0.5rem 0.8rem;color:#78716C;text-decoration:none;font-size:0.82rem;font-weight:600;border-radius:8px;transition:0.15s">
            <i class="fas fa-hospital" style="width:18px;text-align:center"></i> Especialidades
        </a>
        <a href="{{ url($prefix . '/agenda') }}" style="display:flex;align-items:center;gap:0.6rem;padding:0.5rem 0.8rem;color:#78716C;text-decoration:none;font-size:0.82rem;font-weight:600;border-radius:8px;transition:0.15s">
            <i class="fas fa-calendar-alt" style="width:18px;text-align:center"></i> Agenda
        </a>
    </div>

    <div style="height:1px;background:#FFF0E0;margin:0.5rem 1rem"></div>

    <div style="padding:0.5rem 1rem;margin-bottom:0.3rem">
        <div style="font-size:0.65rem;font-weight:900;color:#9A3412;text-transform:uppercase;letter-spacing:1px;padding:0.3rem 0;margin-bottom:0.3rem">Pacientes</div>
        <a href="{{ url($prefix . '/pacientes') }}" style="display:flex;align-items:center;gap:0.6rem;padding:0.5rem 0.8rem;color:#78716C;text-decoration:none;font-size:0.82rem;font-weight:600;border-radius:8px;transition:0.15s">
            <i class="fas fa-user-injured" style="width:18px;text-align:center"></i> Mis Pacientes
        </a>
        <a href="{{ url($prefix . '/hospitalizados') }}" style="display:flex;align-items:center;gap:0.6rem;padding:0.5rem 0.8rem;color:#78716C;text-decoration:none;font-size:0.82rem;font-weight:600;border-radius:8px;transition:0.15s">
            <i class="fas fa-bed-pulse" style="width:18px;text-align:center"></i> Hospitalizados
        </a>
        <a href="{{ url($prefix . '/derivaciones') }}" style="display:flex;align-items:center;gap:0.6rem;padding:0.5rem 0.8rem;color:#78716C;text-decoration:none;font-size:0.82rem;font-weight:600;border-radius:8px;transition:0.15s">
            <i class="fas fa-share-nodes" style="width:18px;text-align:center"></i> Derivaciones
        </a>
    </div>

    <div style="height:1px;background:#FFF0E0;margin:0.5rem 1rem"></div>

    <div style="padding:0.5rem 1rem;margin-bottom:0.3rem">
        <div style="font-size:0.65rem;font-weight:900;color:#9A3412;text-transform:uppercase;letter-spacing:1px;padding:0.3rem 0;margin-bottom:0.3rem"><i class="fas fa-truck-medical" style="color:#EA580C"></i> Ambulancia / Traslados</div>
        <a href="{{ url($prefix . '/ambulancias') }}" style="display:flex;align-items:center;gap:0.6rem;padding:0.5rem 0.8rem;color:#78716C;text-decoration:none;font-size:0.82rem;font-weight:600;border-radius:8px;transition:0.15s">
            <i class="fas fa-truck-medical" style="width:18px;text-align:center"></i> Ambulancias
        </a>
        <a href="{{ url($prefix . '/hospital-live') }}" style="display:flex;align-items:center;gap:0.6rem;padding:0.5rem 0.8rem;color:#78716C;text-decoration:none;font-size:0.82rem;font-weight:600;border-radius:8px;transition:0.15s">
            <i class="fas fa-tower-broadcast" style="width:18px;text-align:center"></i> Hospital Live
        </a>
    </div>

    <div style="height:1px;background:#FFF0E0;margin:0.5rem 1rem"></div>

    <div style="padding:0.5rem 1rem;margin-bottom:0.3rem">
        <div style="font-size:0.65rem;font-weight:900;color:#9A3412;text-transform:uppercase;letter-spacing:1px;padding:0.3rem 0;margin-bottom:0.3rem"><i class="fas fa-brain" style="color:#EA580C"></i> IA Medica</div>
        <a href="{{ url($prefix . '/asistente-ia') }}" style="display:flex;align-items:center;gap:0.6rem;padding:0.5rem 0.8rem;color:#78716C;text-decoration:none;font-size:0.82rem;font-weight:600;border-radius:8px;transition:0.15s">
            <i class="fas fa-robot" style="width:18px;text-align:center"></i> Asistente IA
        </a>
    </div>

    @if($isSuperAdmin)
    <div style="height:1px;background:#FFF0E0;margin:0.5rem 1rem"></div>
    <div style="padding:0.5rem 1rem;margin-bottom:0.3rem">
        <div style="font-size:0.65rem;font-weight:900;color:#DC2626;text-transform:uppercase;letter-spacing:1px;padding:0.3rem 0;margin-bottom:0.3rem"><i class="fas fa-crown" style="color:#DC2626"></i> Super Admin</div>
        <a href="{{ url('/superadmin/dashboard') }}" style="display:flex;align-items:center;gap:0.6rem;padding:0.5rem 0.8rem;color:#78716C;text-decoration:none;font-size:0.82rem;font-weight:600;border-radius:8px;transition:0.15s">
            <i class="fas fa-cog" style="width:18px;text-align:center"></i> Panel Admin
        </a>
    </div>
    @endif

    <div style="height:1px;background:#FFF0E0;margin:0.5rem 1rem"></div>

    <div style="padding:0.5rem 1rem">
        <div style="font-size:0.65rem;font-weight:900;color:#9A3412;text-transform:uppercase;letter-spacing:1px;padding:0.3rem 0;margin-bottom:0.3rem">Rapido</div>
        <div style="display:flex;justify-content:space-between;padding:0.4rem 0.8rem;font-size:0.75rem">
            <span>Hospitalizados</span><span style="font-weight:900;color:#DC2626">{{ App\Models\Triage::where('status', 'Hospitalizado')->count() }}</span>
        </div>
        <div style="display:flex;justify-content:space-between;padding:0.4rem 0.8rem;font-size:0.75rem">
            <span>Camas Libres</span><span style="font-weight:900;color:#EA580C">{{ App\Models\Bed::where('status', 'Disponible')->count() }}</span>
        </div>
        <div style="display:flex;justify-content:space-between;padding:0.4rem 0.8rem;font-size:0.75rem">
            <span>Criticos</span><span style="font-weight:900;color:#DC2626">{{ App\Models\Triage::where('triage_level', 'Rojo')->whereIn('status', ['En Espera', 'En Atencion'])->count() }}</span>
        </div>
        <div style="display:flex;justify-content:space-between;padding:0.4rem 0.8rem;font-size:0.75rem">
            <span>Ambulancias</span><span style="font-weight:900;color:#EA580C">{{ App\Models\Ambulance::where('status', 'En Ruta')->count() }}/{{ App\Models\Ambulance::count() }}</span>
        </div>
    </div>

    <div style="height:1px;background:#FFF0E0;margin:0.5rem 1rem"></div>

    <form method="POST" action="{{ route('logout') }}" style="padding:0 1rem">
        @csrf
        <button type="submit" style="display:flex;align-items:center;gap:0.6rem;padding:0.5rem 0.8rem;color:#DC2626;font-size:0.82rem;font-weight:700;border-radius:8px;cursor:pointer;background:none;border:none;width:100%">
            <i class="fas fa-sign-out-alt" style="width:18px;text-align:center"></i> Cerrar Sesion
        </button>
    </form>
</div>
