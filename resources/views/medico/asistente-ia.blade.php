@extends('medico.layout')
@section('title', 'Asistente IA Medica')

@section('content')
<div style="display:grid;grid-template-columns:1fr 300px;gap:1.5rem">
    <div>
        <div style="background:white;border-radius:12px;padding:1.5rem;box-shadow:0 2px 8px rgba(0,0,0,0.04);margin-bottom:1.5rem">
            <h3 style="font-weight:800;color:#1C1917;margin-bottom:1rem"><i class="fas fa-robot" style="color:#7C3AED"></i> Dr. IA - Asistente Clinico</h3>
            <p style="font-size:0.82rem;color:#736860;margin-bottom:1rem">Hola <strong>{{ $doctorName }}</strong>, soy tu asistente de IA. Puedo ayudarte con diagnosticos diferenciales, interacciones medicamentosas y protocolos clinicos.</p>
            
            <div id="chat-area" style="height:350px;overflow-y:auto;border:1px solid #E7E5E4;border-radius:10px;padding:1rem;background:#FAFAF9;margin-bottom:1rem">
                <div style="background:#F3F0FF;border-radius:10px;padding:0.8rem;margin-bottom:0.5rem;max-width:80%">
                    <div style="font-size:0.7rem;font-weight:700;color:#7C3AED;margin-bottom:0.3rem">Dr. IA</div>
                    <div style="font-size:0.82rem;color:#1C1917">Bienvenido doctor. Estoy listo para asistirle. Escriba su consulta clinica.</div>
                </div>
            </div>

            <div style="display:flex;gap:0.5rem">
                <input type="text" id="msg-input" placeholder="Escriba su consulta clinica..." style="flex:1;padding:0.6rem 1rem;border:2px solid #E7E5E4;border-radius:10px;font-size:0.82rem;font-family:inherit;outline:none" onfocus="this.style.borderColor='#7C3AED'" onblur="this.style.borderColor='#E7E5E4'">
                <button onclick="sendMsg()" style="background:linear-gradient(135deg,#7C3AED,#6D28D9);color:white;border:none;padding:0.6rem 1.2rem;border-radius:10px;font-weight:700;cursor:pointer;font-size:0.82rem"><i class="fas fa-paper-plane"></i></button>
            </div>
        </div>

        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:0.8rem">
            <button onclick="quickQ('Diagnosticos diferenciales para dolor toracico agudo')" style="background:white;border:1px solid #E7E5E4;border-radius:8px;padding:0.6rem;cursor:pointer;text-align:left;font-size:0.72rem;color:#736860;font-weight:600;transition:0.15s" onmouseover="this.style.borderColor='#7C3AED'" onmouseout="this.style.borderColor='#E7E5E4'"><i class="fas fa-heart" style="color:#DC2626;margin-right:4px"></i> Dolor Toracico</button>
            <button onclick="quickQ('Interacciones de warfarina con AINEs')" style="background:white;border:1px solid #E7E5E4;border-radius:8px;padding:0.6rem;cursor:pointer;text-align:left;font-size:0.72rem;color:#736860;font-weight:600;transition:0.15s" onmouseover="this.style.borderColor='#7C3AED'" onmouseout="this.style.borderColor='#E7E5E4'"><i class="fas fa-pills" style="color:#F59E0B;margin-right:4px"></i> Interacciones</button>
            <button onclick="quickQ('Protocolo de manejo de sepsis')" style="background:white;border:1px solid #E7E5E4;border-radius:8px;padding:0.6rem;cursor:pointer;text-align:left;font-size:0.72rem;color:#736860;font-weight:600;transition:0.15s" onmouseover="this.style.borderColor='#7C3AED'" onmouseout="this.style.borderColor='#E7E5E4'"><i class="fas fa-clipboard-list" style="color:#16A34A;margin-right:4px"></i> Protocolos</button>
        </div>
    </div>

    <div>
        <div style="background:white;border-radius:12px;padding:1rem;box-shadow:0 2px 8px rgba(0,0,0,0.04);margin-bottom:1rem">
            <h4 style="font-weight:800;color:#1C1917;font-size:0.85rem;margin-bottom:0.8rem"><i class="fas fa-users" style="color:#EA580C"></i> Mis Pacientes</h4>
            @if($misPacientes->count() > 0)
            @foreach($misPacientes as $p)
            <div style="border-left:3px solid {{ $p->triage_level === 'Rojo' ? '#DC2626' : ($p->triage_level === 'Amarillo' ? '#F59E0B' : '#16A34A') }};padding:0.4rem 0.6rem;margin-bottom:0.4rem;font-size:0.72rem;cursor:pointer" onclick="askAbout({{ $p->id }})">
                <div style="font-weight:700;color:#1C1917">{{ $p->patient_name or 'Paciente #'.$p->id }}</div>
                <div style="color:#736860">{{ $p->triage_level }} | {{ $p->status }}</div>
            </div>
            @endforeach
            @else
            <p style="font-size:0.72rem;color:#A8A29E;text-align:center;padding:0.5rem">Sin pacientes asignados</p>
            @endif
        </div>
    </div>
</div>

<script>
function sendMsg(){
    const input = document.getElementById('msg-input');
    const msg = input.value.trim();
    if(!msg) return;
    addMsg('Dr. {{ $doctorName }}', msg, '#EA580C');
    input.value = '';
    setTimeout(()=>addMsg('Dr. IA','Procesando consulta... Analizando datos clinicos disponibles. Recomendacion basada en evidencia: Se sugiere evaluar signos vitales y considerar estudios complementarios para confirmacion diagnostica.','#7C3AED'),800);
}
function quickQ(q){ document.getElementById('msg-input').value=q; sendMsg(); }
function askAbout(id){ document.getElementById('msg-input').value='Resumen clinico del paciente #'+id; sendMsg(); }
function addMsg(who,text,color){
    const area=document.getElementById('chat-area');
    const div=document.createElement('div');
    div.style.cssText='background:#F3F0FF;border-radius:10px;padding:0.8rem;margin-bottom:0.5rem;max-width:80%';
    div.innerHTML='<div style="font-size:0.7rem;font-weight:700;color:'+color+';margin-bottom:0.3rem">'+who+'</div><div style="font-size:0.82rem;color:#1C1917">'+text+'</div>';
    area.appendChild(div);
    area.scrollTop=area.scrollHeight;
}
document.getElementById('msg-input').addEventListener('keydown',function(e){if(e.key==='Enter')sendMsg();});
</script>
@endsection
