<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil Médico - HealthNexus</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800;900&display=swap');
        *{margin:0;padding:0;box-sizing:border-box}
        body{font-family:'Inter',system-ui,sans-serif;min-height:100vh;display:flex;align-items:center;justify-content:center;background:linear-gradient(145deg,#7F1D1D 0%,#991B1B 25%,#B91C1C 50%,#C2410C 75%,#EA580C 100%)}
        .wrap{max-width:950px;width:92%}
        .hdr{text-align:center;margin-bottom:2.5rem}
        .hdr h1{font-size:2.8rem;font-weight:900;color:#fff;text-shadow:0 4px 12px rgba(0,0,0,0.25);letter-spacing:-1px}
        .hdr p{color:#FED7AA;font-size:1rem;margin-top:0.4rem;font-weight:600}
        .hdr .lock{display:inline-flex;align-items:center;gap:0.4rem;margin-top:1rem;background:rgba(255,255,255,0.12);backdrop-filter:blur(8px);padding:0.5rem 1.2rem;border-radius:10px;color:#FED7AA;font-size:0.75rem;font-weight:600;border:1px solid rgba(255,255,255,0.15)}
        .grid{display:grid;grid-template-columns:repeat(3,1fr);gap:1.5rem}
        .card{background:#fff;border-radius:20px;padding:2rem 1.5rem;text-align:center;cursor:pointer;transition:all 0.3s ease;position:relative;overflow:hidden}
        .card::before{content:'';position:absolute;top:0;left:0;right:0;height:5px}
        .card-a::before{background:linear-gradient(90deg,#DC2626,#EF4444)}
        .card-b::before{background:linear-gradient(90deg,#EA580C,#F97316)}
        .card-c::before{background:linear-gradient(90deg,#F97316,#FB923C)}
        .card:hover{transform:translateY(-6px)}
        .card-a:hover{box-shadow:0 20px 50px rgba(220,38,38,0.3)}
        .card-b:hover{box-shadow:0 20px 50px rgba(234,88,12,0.3)}
        .card-c:hover{box-shadow:0 20px 50px rgba(249,115,22,0.3)}
        .ico{width:60px;height:60px;border-radius:16px;display:flex;align-items:center;justify-content:center;margin:0 auto 0.75rem;font-size:1.6rem}
        .card-a .ico{background:#FEF2F2;color:#DC2626}
        .card-b .ico{background:#FFF7ED;color:#EA580C}
        .card-c .ico{background:#FFF7ED;color:#F97316}
        .card h3{font-weight:800;font-size:1.15rem;color:#1C1917;margin-bottom:0.2rem}
        .lvl{font-size:0.6rem;font-weight:800;padding:0.15rem 0.6rem;border-radius:20px;display:inline-block;letter-spacing:0.5px}
        .card-a .lvl{background:#FEF2F2;color:#DC2626}
        .card-b .lvl{background:#FFF7ED;color:#EA580C}
        .card-c .lvl{background:#FFF7ED;color:#F97316}
        .nm{font-size:0.82rem;color:#78716C;margin:0.4rem 0 0.75rem;font-weight:600}
        .perms{text-align:left;margin-bottom:0.75rem}
        .pm{font-size:0.72rem;padding:0.15rem 0;display:flex;align-items:center;gap:0.35rem}
        .pm.y{color:#166534}.pm.n{color:#991B1B}
        .pin-sec{display:none;margin-top:0.75rem;animation:fi 0.3s ease}
        .pin-sec.show{display:block}
        @keyframes fi{from{opacity:0;transform:translateY(8px)}to{opacity:1;transform:translateY(0)}}
        .pin-in{font-size:1.4rem;text-align:center;letter-spacing:0.6rem;padding:0.7rem;border:2px solid #E7E5E4;border-radius:12px;width:100%;margin:0.4rem 0;transition:border-color 0.2s;background:#FAFAF9}
        .card-a .pin-in:focus{border-color:#DC2626;outline:none;background:#fff}
        .card-b .pin-in:focus{border-color:#EA580C;outline:none;background:#fff}
        .card-c .pin-in:focus{border-color:#F97316;outline:none;background:#fff}
        .pin-btn{width:100%;padding:0.7rem;border:none;border-radius:12px;color:#fff;font-weight:800;font-size:0.9rem;cursor:pointer;transition:all 0.2s;letter-spacing:0.3px}
        .pin-btn:hover{opacity:0.9;transform:scale(1.01)}
        .card-a .pin-btn{background:linear-gradient(135deg,#DC2626,#B91C1C)}
        .card-b .pin-btn{background:linear-gradient(135deg,#EA580C,#C2410C)}
        .card-c .pin-btn{background:linear-gradient(135deg,#F97316,#EA580C)}
        .out{display:block;margin:2rem auto 0;background:rgba(255,255,255,0.12);color:#FED7AA;border:1px solid rgba(255,255,255,0.2);padding:0.7rem 2rem;border-radius:12px;font-weight:700;cursor:pointer;text-decoration:none;font-size:0.85rem;transition:all 0.2s}
        .out:hover{background:rgba(255,255,255,0.2);color:#fff}
    </style>
</head>
<body>
<div class="wrap">
    <div class="hdr">
        <h1><i class="fas fa-stethoscope"></i> Perfil Médico</h1>
        <p>Selecciona tu nivel de acceso e ingresa tu PIN</p>
        <div class="lock"><i class="fas fa-lock"></i> Tu PIN es confidencial — No lo compartas</div>
    </div>
    <div class="grid">
        <div class="card card-a" onclick="sel('a')">
            <div class="ico"><i class="fas fa-user-md"></i></div>
            <h3>Médico A</h3><span class="lvl">ESPECIALISTA</span>
            <div class="nm">Dr. Kenia Medina</div>
            <div class="perms">
                <div class="pm y"><i class="fas fa-check-circle"></i> Pacientes Críticos / UCI</div>
                <div class="pm y"><i class="fas fa-check-circle"></i> Quirófano / Opioides</div>
                <div class="pm y"><i class="fas fa-check-circle"></i> Medicamentos Controlados</div>
                <div class="pm y"><i class="fas fa-check-circle"></i> Firma Digital / IA Médica</div>
            </div>
            <div class="pin-sec" id="pa">
                <form method="POST" action="{{route('medico.seleccionarPerfil')}}">@csrf
                    <input type="password" class="pin-in" name="pin" maxlength="4" placeholder="PIN" autocomplete="off">
                    <button type="submit" class="pin-btn"><i class="fas fa-lock"></i> Ingresar</button>
                </form>
            </div>
        </div>
        <div class="card card-b" onclick="sel('b')">
            <div class="ico"><i class="fas fa-user-md"></i></div>
            <h3>Médico B</h3><span class="lvl">INTERMEDIO</span>
            <div class="nm">Dr. SF Gilkey</div>
            <div class="perms">
                <div class="pm y"><i class="fas fa-check-circle"></i> Hospitalización</div>
                <div class="pm y"><i class="fas fa-check-circle"></i> Recetas Ampliadas</div>
                <div class="pm y"><i class="fas fa-check-circle"></i> Urgencias Moderadas</div>
                <div class="pm n"><i class="fas fa-times-circle"></i> Opioides / UCI / Quirófano</div>
            </div>
            <div class="pin-sec" id="pb">
                <form method="POST" action="{{route('medico.seleccionarPerfil')}}">@csrf
                    <input type="password" class="pin-in" name="pin" maxlength="4" placeholder="PIN" autocomplete="off">
                    <button type="submit" class="pin-btn"><i class="fas fa-lock"></i> Ingresar</button>
                </form>
            </div>
        </div>
        <div class="card card-c" onclick="sel('c')">
            <div class="ico"><i class="fas fa-user-md"></i></div>
            <h3>Médico C</h3><span class="lvl">BÁSICO</span>
            <div class="nm">Dr. KM Azuara</div>
            <div class="perms">
                <div class="pm y"><i class="fas fa-check-circle"></i> Consulta Básica</div>
                <div class="pm y"><i class="fas fa-check-circle"></i> Recetas Simples</div>
                <div class="pm y"><i class="fas fa-check-circle"></i> Diagnósticos</div>
                <div class="pm n"><i class="fas fa-times-circle"></i> Opioides / Hospitalización / UCI</div>
            </div>
            <div class="pin-sec" id="pc">
                <form method="POST" action="{{route('medico.seleccionarPerfil')}}">@csrf
                    <input type="password" class="pin-in" name="pin" maxlength="4" placeholder="PIN" autocomplete="off">
                    <button type="submit" class="pin-btn"><i class="fas fa-lock"></i> Ingresar</button>
                </form>
            </div>
        </div>
    </div>
    <a href="{{route('logout')}}" class="out" onclick="event.preventDefault();document.getElementById('lf').submit()"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</a>
</div>
<form id="lf" action="{{route('logout')}}" method="POST" style="display:none">@csrf</form>
<script>
let s=null;
function sel(l){if(s===l)return;document.querySelectorAll('.pin-sec').forEach(x=>x.classList.remove('show'));document.getElementById('p'+l).classList.add('show');setTimeout(()=>document.getElementById('p'+l).querySelector('.pin-in').focus(),80);s=l}
</script>
</body>
</html>
