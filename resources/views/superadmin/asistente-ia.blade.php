@extends('superadmin.layout')
@section('title', 'IA de Gestion Hospitalaria')

@section('content')
<div style="display:grid;grid-template-columns:1fr 280px;gap:1.5rem">
    <div>
        <div style="background:white;border-radius:12px;padding:2rem;box-shadow:0 2px 8px rgba(0,0,0,0.04);margin-bottom:1.5rem">
            <h3 style="font-weight:800;color:#1E1A17;margin-bottom:1rem"><i class="fas fa-brain" style="color:#F05A4E"></i> IA Gestion Hospitalaria</h3>
            <p style="font-size:0.85rem;color:#736860;margin-bottom:1rem">Asistente especializado en gestion administrativa, optimizacion de recursos y analisis financiero hospitalario.</p>
            
            <div id="chat-area" style="height:380px;overflow-y:auto;border:1px solid #E5E7EB;border-radius:10px;padding:1rem;background:#F9FAFB;margin-bottom:1rem">
                <div style="background:#FFF1EE;border-radius:10px;padding:0.8rem;margin-bottom:0.5rem;max-width:80%">
                    <div style="font-size:0.7rem;font-weight:700;color:#F05A4E;margin-bottom:0.3rem">IA Admin</div>
                    <div style="font-size:0.85rem;color:#1E1A17">Bienvenido al sistema de IA de gestion. Puedo ayudar con: optimizacion de camas, analisis financiero, prediccion de demanda y gestion de personal.</div>
                </div>
            </div>

            <div style="display:flex;gap:0.5rem">
                <input type="text" id="msg-input" placeholder="Consultar sobre gestion hospitalaria..." style="flex:1;padding:0.7rem 1rem;border:2px solid #E5E7EB;border-radius:10px;font-size:0.85rem;font-family:inherit;outline:none" onfocus="this.style.borderColor='#F05A4E'" onblur="this.style.borderColor='#E5E7EB'">
                <button onclick="sendMsg()" style="background:linear-gradient(135deg,#F05A4E,#FF8C42);color:white;border:none;padding:0.7rem 1.3rem;border-radius:10px;font-weight:700;cursor:pointer;font-size:0.85rem"><i class="fas fa-paper-plane"></i></button>
            </div>
        </div>

        <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:0.8rem">
            <button onclick="quickQ('Analisis de ocupacion hospitalaria actual')" style="background:white;border:1px solid #E5E7EB;border-radius:8px;padding:0.6rem;cursor:pointer;text-align:left;font-size:0.72rem;color:#736860;font-weight:600"><i class="fas fa-bed" style="color:#F05A4E;margin-right:4px"></i> Ocupacion</button>
            <button onclick="quickQ('Prediccion de demanda para las proximas 24hs')" style="background:white;border:1px solid #E5E7EB;border-radius:8px;padding:0.6rem;cursor:pointer;text-align:left;font-size:0.72rem;color:#736860;font-weight:600"><i class="fas fa-chart-line" style="color:#7C3AED;margin-right:4px"></i> Prediccion</button>
            <button onclick="quickQ('Optimizacion de recursos disponibles')" style="background:white;border:1px solid #E5E7EB;border-radius:8px;padding:0.6rem;cursor:pointer;text-align:left;font-size:0.72rem;color:#736860;font-weight:600"><i class="fas fa-cogs" style="color:#16A34A;margin-right:4px"></i> Recursos</button>
            <button onclick="quickQ('Reporte financiero del dia')" style="background:white;border:1px solid #E5E7EB;border-radius:8px;padding:0.6rem;cursor:pointer;text-align:left;font-size:0.72rem;color:#736860;font-weight:600"><i class="fas fa-dollar-sign" style="color:#F59E0B;margin-right:4px"></i> Finanzas</button>
        </div>
    </div>

    <div>
        <div style="background:white;border-radius:12px;padding:1rem;box-shadow:0 2px 8px rgba(0,0,0,0.04)">
            <h4 style="font-weight:800;color:#1E1A17;font-size:0.85rem;margin-bottom:0.8rem"><i class="fas fa-lightbulb" style="color:#F59E0B"></i> Sugerencias IA</h4>
            <div style="font-size:0.75rem;color:#736860;line-height:1.6">
                <div style="border-left:3px solid #16A34A;padding:0.4rem 0.6rem;margin-bottom:0.5rem;background:#F0FDF4;border-radius:0 6px 6px 0">
                    <strong style="color:#16A34A">Eficiencia:</strong> Reasignar 2 camas de UCI a hospitalizacion
                </div>
                <div style="border-left:3px solid #F59E0B;padding:0.4rem 0.6rem;margin-bottom:0.5rem;background:#FFFBEB;border-radius:0 6px 6px 0">
                    <strong style="color:#F59E0B">Alerta:</strong> Stock de insumos bajo 20%
                </div>
                <div style="border-left:3px solid #7C3AED;padding:0.4rem 0.6rem;margin-bottom:0.5rem;background:#F5F3FF;border-radius:0 6px 6px 0">
                    <strong style="color:#7C3AED">Demanda:</strong> Pico esperado 14-18hs
                </div>
                <div style="border-left:3px solid #DC2626;padding:0.4rem 0.6rem;background:#FEF2F2;border-radius:0 6px 6px 0">
                    <strong style="color:#DC2626">Critico:</strong> Personal insuficiente turno noche
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function sendMsg(){
    const input=document.getElementById('msg-input');
    const msg=input.value.trim();
    if(!msg)return;
    addMsg('Admin',msg,'#1E1A17');
    input.value='';
    setTimeout(()=>addMsg('IA Admin','Analizando datos del sistema... Basado en metricas actuales, se recomienda optimizar la distribucion de recursos. La ocupacion promedio es del 72% con tendencia ascendente.','#F05A4E'),800);
}
function quickQ(q){document.getElementById('msg-input').value=q;sendMsg();}
function addMsg(who,text,color){
    const area=document.getElementById('chat-area');
    const div=document.createElement('div');
    div.style.cssText='background:#FFF1EE;border-radius:10px;padding:0.8rem;margin-bottom:0.5rem;max-width:80%';
    div.innerHTML='<div style="font-size:0.7rem;font-weight:700;color:'+color+';margin-bottom:0.3rem">'+who+'</div><div style="font-size:0.85rem;color:#1E1A17">'+text+'</div>';
    area.appendChild(div);
    area.scrollTop=area.scrollHeight;
}
document.getElementById('msg-input').addEventListener('keydown',function(e){if(e.key==='Enter')sendMsg();});
</script>
@endsection
