@php $prefix = '/medico'; @endphp
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
