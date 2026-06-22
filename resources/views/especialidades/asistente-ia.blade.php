@extends('especialidades.layout')

@section('content')
<div style="padding:1.5rem">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem">
        <h2 style="font-weight:900;color:#9A3412"><i class="fas fa-brain" style="color:#EA580C"></i> Asistente IA Medico</h2>
        <div style="display:flex;gap:0.8rem">
            <span id="ai-status" style="background:#FFEDD5;color:#EA580C;padding:0.3rem 0.8rem;border-radius:20px;font-weight:800;font-size:0.75rem"><i class="fas fa-circle" style="font-size:0.4rem"></i> Conectado</span>
        </div>
    </div>

    <!-- ALERTAS AUTOMATICAS -->
    @if($alertas->count() > 0)
    <div style="background:linear-gradient(135deg,#7F1D1D,#DC2626);border-radius:16px;padding:1.5rem;margin-bottom:1.5rem;color:white">
        <h3 style="font-weight:900;margin-bottom:1rem"><i class="fas fa-bell"></i> Alertas Inteligentes ({{ $alertas->count() }})</h3>
        <div style="display:grid;gap:0.8rem">
            @foreach($alertas as $a)
            <div style="background:rgba(255,255,255,0.12);border-radius:12px;padding:1rem;border-left:4px solid {{ $a['color'] }}">
                <div style="display:flex;justify-content:space-between;align-items:start">
                    <div>
                        <div style="font-weight:900;font-size:0.9rem"><i class="fas {{ $a['icono'] }}"></i> {{ $a['titulo'] }}</div>
                        <div style="font-size:0.8rem;opacity:0.9;margin-top:0.2rem">{{ $a['detalle'] }}</div>
                    </div>
                </div>
                @if(isset($a['recomendacion']))
                <div style="background:rgba(255,255,255,0.1);border-radius:8px;padding:0.5rem;margin-top:0.5rem;font-size:0.8rem">
                    <strong>💡 Recomendacion:</strong> {{ $a['recomendacion'] }}
                </div>
                @endif
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- CHAT IA -->
    <div style="background:white;border-radius:16px;box-shadow:0 2px 8px rgba(0,0,0,0.05);margin-bottom:1.5rem;border-top:4px solid #EA580C;overflow:hidden">
        <div style="padding:1rem;background:#FFF7ED;border-bottom:1px solid #FDBA74">
            <h3 style="font-weight:900;color:#9A3412"><i class="fas fa-robot" style="color:#EA580C"></i> Consulta con IA</h3>
            <p style="font-size:0.75rem;color:#A8A29E;margin-top:0.2rem">Escribe sintomas, preguntas clinicas o situaciones de emergencia</p>
        </div>

        <!-- CHAT AREA -->
        <div id="chat-area" style="padding:1.5rem;min-height:300px;max-height:500px;overflow-y:auto;background:#FFFBEB">
            <!-- Mensaje de bienvenida -->
            <div style="display:flex;gap:0.8rem;margin-bottom:1rem">
                <div style="width:36px;height:36px;border-radius:50%;background:#EA580C;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                    <i class="fas fa-robot" style="color:white;font-size:0.9rem"></i>
                </div>
                <div style="background:white;border-radius:12px;padding:1rem;max-width:80%;border:1px solid #FDBA74">
                    <div style="font-weight:800;color:#9A3412;font-size:0.85rem;margin-bottom:0.3rem">HealthNexus IA</div>
                    <div style="color:#57534E;font-size:0.85rem;line-height:1.5">
                        Hola Dr. {{ $user->name }}. Soy tu asistente medico IA.<br><br>
                        Puedo ayudarte con:<br>
                        🫀 Analisis de sintomas<br>
                        🧠 Evaluaciones clinicas<br>
                        🚨 Protocolos de emergencia<br>
                        💊 Interacciones medicamentosas<br>
                        📊 Priorizacion de pacientes<br><br>
                        ¿En que puedo ayudarte?
                    </div>
                </div>
            </div>
        </div>

        <!-- INPUT -->
        <div style="padding:1rem;background:#FFF7ED;border-top:1px solid #FDBA74">
            <div style="display:flex;gap:0.8rem">
                <input type="text" id="ai-input" placeholder="Escribe tu consulta medica..." style="flex:1;padding:0.7rem 1rem;border:2px solid #FDBA74;border-radius:10px;font-size:0.9rem;background:white;outline:none" onkeydown="if(event.key==='Enter')enviarConsulta()">
                <button onclick="enviarConsulta()" id="btn-enviar" style="padding:0.7rem 1.5rem;background:#EA580C;color:white;border:none;border-radius:10px;font-weight:800;cursor:pointer;font-size:0.9rem;white-space:nowrap">
                    <i class="fas fa-paper-plane"></i> Consultar
                </button>
            </div>
            <div style="display:flex;gap:0.5rem;margin-top:0.8rem;flex-wrap:wrap">
                <button onclick="consultaRapida('Paciente con dolor toracico agudo y diaforesis')" style="padding:0.3rem 0.7rem;background:#FEF2F2;border:1px solid #FCA5A5;border-radius:20px;color:#DC2626;font-size:0.7rem;font-weight:700;cursor:pointer">🫀 Dolor toracico</button>
                <button onclick="consultaRapida('Evaluacion neurologica paciente con alteracion de conciencia')" style="padding:0.3rem 0.7rem;background:#FFEDD5;border:1px solid #FDBA74;border-radius:20px;color:#EA580C;font-size:0.7rem;font-weight:700;cursor:pointer">🧠 Neurologico</button>
                <button onclick="consultaRapida('Protocolo de crisis hospitalaria por saturacion')" style="padding:0.3rem 0.7rem;background:#FEF2F2;border:1px solid #FCA5A5;border-radius:20px;color:#DC2626;font-size:0.7rem;font-weight:700;cursor:pointer">🚨 Protocolo crisis</button>
                <button onclick="consultaRapida('Paciente pediatrico con fiebre alta y convulsiones')" style="padding:0.3rem 0.7rem;background:#FFEDD5;border:1px solid #FDBA74;border-radius:20px;color:#EA580C;font-size:0.7rem;font-weight:700;cursor:pointer">👶 Pediatrico</button>
            </div>
        </div>
    </div>

    <!-- STATS DEL SISTEMA -->
    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:1rem">
        <div style="background:white;border-radius:14px;padding:1rem;text-align:center;box-shadow:0 2px 8px rgba(0,0,0,0.05);border-top:3px solid #DC2626">
            <div style="font-size:1.8rem;font-weight:900;color:#DC2626">{{ $misPacientes->count() }}</div>
            <div style="font-size:0.7rem;font-weight:800;color:#991B1B">Mis Pacientes</div>
        </div>
        <div style="background:white;border-radius:14px;padding:1rem;text-align:center;box-shadow:0 2px 8px rgba(0,0,0,0.05);border-top:3px solid #EA580C">
            <div style="font-size:1.8rem;font-weight:900;color:#EA580C">{{ $criticosSinDoctor }}</div>
            <div style="font-size:0.7rem;font-weight:800;color:#9A3412">Criticos Sin Doctor</div>
        </div>
        <div style="background:white;border-radius:14px;padding:1rem;text-align:center;box-shadow:0 2px 8px rgba(0,0,0,0.05);border-top:3px solid #F97316">
            <div style="font-size:1.8rem;font-weight:900;color:#F97316">{{ $hospitalizados }}</div>
            <div style="font-size:0.7rem;font-weight:800;color:#9A3412">Hospitalizados</div>
        </div>
        <div style="background:white;border-radius:14px;padding:1rem;text-align:center;box-shadow:0 2px 8px rgba(0,0,0,0.05);border-top:3px solid #EA580C">
            <div style="font-size:1.8rem;font-weight:900;color:#EA580C">{{ $ambulancias }}</div>
            <div style="font-size:0.7rem;font-weight:800;color:#9A3412">Ambulancias Activas</div>
        </div>
    </div>
</div>

<script>
function enviarConsulta() {
    const input = document.getElementById('ai-input');
    const consulta = input.value.trim();
    if (!consulta) return;

    input.value = '';
    const btn = document.getElementById('btn-enviar');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Pensando...';

    // Agregar mensaje del usuario
    const chatArea = document.getElementById('chat-area');
    chatArea.innerHTML += `
        <div style="display:flex;gap:0.8rem;margin-bottom:1rem;justify-content:flex-end">
            <div style="background:#EA580C;border-radius:12px;padding:1rem;max-width:80%;color:white">
                <div style="font-weight:800;font-size:0.8rem;margin-bottom:0.2rem;opacity:0.9">Dr. {{ $user->name }}</div>
                <div style="font-size:0.85rem;line-height:1.5">${consulta}</div>
            </div>
            <div style="width:36px;height:36px;border-radius:50%;background:#9A3412;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                <i class="fas fa-user-md" style="color:white;font-size:0.9rem"></i>
            </div>
        </div>
    `;

    // Agregar indicador de escritura
    const typingId = 'typing-' + Date.now();
    chatArea.innerHTML += `
        <div id="${typingId}" style="display:flex;gap:0.8rem;margin-bottom:1rem">
            <div style="width:36px;height:36px;border-radius:50%;background:#EA580C;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                <i class="fas fa-robot" style="color:white;font-size:0.9rem"></i>
            </div>
            <div style="background:white;border-radius:12px;padding:1rem;border:1px solid #FDBA74">
                <div style="font-weight:800;color:#9A3412;font-size:0.85rem;margin-bottom:0.3rem">HealthNexus IA</div>
                <div style="color:#D97706;font-size:0.85rem"><i class="fas fa-spinner fa-spin"></i> Analizando...</div>
            </div>
        </div>
    `;
    chatArea.scrollTop = chatArea.scrollHeight;

    // Enviar consulta
    fetch('{{ url("/medico/ia/consultar") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ consulta: consulta })
    })
    .then(r => r.json())
    .then(data => {
        const typing = document.getElementById(typingId);
        if (typing) typing.remove();

        const fuente = data.fuente === 'openai' ? '🤖 OpenAI GPT-4' : '🏥 IA Local HealthNexus';
        const aviso = data.aviso ? `<div style="background:#FFEDD5;color:#EA580C;padding:0.3rem 0.6rem;border-radius:6px;font-size:0.7rem;margin-top:0.5rem">${data.aviso}</div>` : '';

        // Formatear respuesta con saltos de linea
        const respuestaFormatted = data.respuesta.replace(/\n/g, '<br>').replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');

        chatArea.innerHTML += `
            <div style="display:flex;gap:0.8rem;margin-bottom:1rem">
                <div style="width:36px;height:36px;border-radius:50%;background:#EA580C;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                    <i class="fas fa-robot" style="color:white;font-size:0.9rem"></i>
                </div>
                <div style="background:white;border-radius:12px;padding:1rem;max-width:80%;border:1px solid #FDBA74">
                    <div style="font-weight:800;color:#9A3412;font-size:0.85rem;margin-bottom:0.3rem">${fuente}</div>
                    <div style="color:#57534E;font-size:0.85rem;line-height:1.6">${respuestaFormatted}</div>
                    ${aviso}
                    <div style="font-size:0.65rem;color:#A8A29E;margin-top:0.5rem;border-top:1px solid #FFF0E0;padding-top:0.3rem">⚠️ Asistencia IA - No reemplaza criterio medico</div>
                </div>
            </div>
        `;
        chatArea.scrollTop = chatArea.scrollHeight;
    })
    .catch(e => {
        const typing = document.getElementById(typingId);
        if (typing) typing.remove();
        chatArea.innerHTML += `
            <div style="display:flex;gap:0.8rem;margin-bottom:1rem">
                <div style="width:36px;height:36px;border-radius:50%;background:#DC2626;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                    <i class="fas fa-robot" style="color:white;font-size:0.9rem"></i>
                </div>
                <div style="background:#FEF2F2;border-radius:12px;padding:1rem;max-width:80%;border:1px solid #FCA5A5">
                    <div style="color:#DC2626;font-size:0.85rem">Error de conexion. Intente nuevamente.</div>
                </div>
            </div>
        `;
        chatArea.scrollTop = chatArea.scrollHeight;
    })
    .finally(() => {
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-paper-plane"></i> Consultar';
    });
}

function consultaRapida(texto) {
    document.getElementById('ai-input').value = texto;
    enviarConsulta();
}
</script>
@endsection
