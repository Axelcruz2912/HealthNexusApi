<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seleccionar Perfil | HealthNexus</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Inter', sans-serif; }
        body { background: #F0F4F8; height: 100vh; display: flex; align-items: center; justify-content: center; }
        .container { width: 900px; }
        .header { text-align: center; margin-bottom: 2rem; }
        .header img { max-width: 160px; margin-bottom: 1rem; }
        .header h1 { font-size: 1.5rem; font-weight: 800; color: #1E1A17; }
        .header p { color: #64748B; font-size: 0.95rem; margin-top: 0.5rem; }
        .cards { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem; }
        .card { background: white; border-radius: 16px; padding: 2rem; text-align: center; box-shadow: 0 4px 15px rgba(0,0,0,0.08); cursor: pointer; transition: all 0.3s; border: 3px solid transparent; position: relative; overflow: hidden; }
        .card:hover { transform: translateY(-5px); box-shadow: 0 10px 30px rgba(0,0,0,0.15); }
        .card.selected { border-color: currentColor; }
        .card-a { border-top: 5px solid #DC2626; }
        .card-a:hover, .card-a.selected { border-color: #DC2626; background: #FEF2F2; }
        .card-b { border-top: 5px solid #F59E0B; }
        .card-b:hover, .card-b.selected { border-color: #F59E0B; background: #FFFBEB; }
        .card-c { border-top: 5px solid #2D9E6A; }
        .card-c:hover, .card-c.selected { border-color: #2D9E6A; background: #F0FDF4; }
        .card i { font-size: 2.5rem; margin-bottom: 1rem; }
        .card-a i { color: #DC2626; }
        .card-b i { color: #F59E0B; }
        .card-c i { color: #2D9E6A; }
        .card h3 { font-weight: 800; font-size: 1.1rem; margin-bottom: 0.25rem; }
        .card .level { font-size: 0.8rem; font-weight: 700; text-transform: uppercase; margin-bottom: 1rem; }
        .card-a .level { color: #DC2626; }
        .card-b .level { color: #F59E0B; }
        .card-c .level { color: #2D9E6A; }
        .card .perms { text-align: left; font-size: 0.8rem; color: #475569; line-height: 1.6; }
        .card .perms i { font-size: 0.7rem; width: 16px; margin-bottom: 0; margin-right: 0.25rem; }
        .perm-ok { color: #2D9E6A; }
        .perm-no { color: #DC2626; }
        .pin-section { background: white; border-radius: 16px; padding: 2rem; margin-top: 1.5rem; box-shadow: 0 4px 15px rgba(0,0,0,0.08); text-align: center; display: none; }
        .pin-section h3 { font-weight: 800; margin-bottom: 0.5rem; }
        .pin-section p { color: #64748B; font-size: 0.85rem; margin-bottom: 1rem; }
        .pin-input { width: 200px; padding: 0.75rem; border: 2px solid #1E1A17; border-radius: 10px; font-size: 1.5rem; text-align: center; letter-spacing: 8px; margin: 0 auto 1rem; display: block; outline: none; }
        .pin-input:focus { border-color: #3B82F6; }
        .btn-enter { padding: 0.75rem 3rem; border: none; border-radius: 10px; font-weight: 700; font-size: 1rem; cursor: pointer; color: white; transition: 0.2s; }
        .btn-a { background: #DC2626; }
        .btn-b { background: #F59E0B; }
        .btn-c { background: #2D9E6A; }
        .btn-enter:hover { opacity: 0.9; }
        .error { background: #FEF2F2; color: #991B1B; padding: 0.75rem; border-radius: 8px; margin-bottom: 1rem; font-weight: 700; font-size: 0.85rem; display: none; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="https://z-cdn-media.chatglm.cn/files/e422f718-2f1b-43b1-8d33-abab22ae033a.png?auth_key=1880130553-c96ef22a7ec1475a8024ee420ae894cb-0-01473062212a4e246495206bff72dde3" alt="HealthNexus">
            <h1>Selecciona tu Perfil Medico</h1>
            <p>Elige tu nivel y confirma con tu PIN de seguridad</p>
        </div>

        @if(session('error'))
        <div class="error" style="display:block;">{{ session('error') }}</div>
        @endif

        <div class="cards">
            <!-- MÉDICO A -->
            <div class="card card-a" onclick="selectProfile('A', 'Dr. Kenia Medina')">
                <i class="fas fa-user-md"></i>
                <h3>Dr. Kenia Medina</h3>
                <div class="level">Nivel A - Especialista</div>
                <div class="perms">
                    <div><i class="fas fa-check perm-ok"></i> Opioides y Sedantes</div>
                    <div><i class="fas fa-check perm-ok"></i> Quirófano</div>
                    <div><i class="fas fa-check perm-ok"></i> UCI y Criticos</div>
                    <div><i class="fas fa-check perm-ok"></i> Controlados</div>
                    <div><i class="fas fa-check perm-ok"></i> Firma Digital</div>
                    <div><i class="fas fa-check perm-ok"></i> Derivaciones</div>
                </div>
            </div>

            <!-- MÉDICO B -->
            <div class="card card-b" onclick="selectProfile('B', 'Dr. SF Gilkey')">
                <i class="fas fa-user-md"></i>
                <h3>Dr. SF Gilkey</h3>
                <div class="level">Nivel B - Hospitalizacion</div>
                <div class="perms">
                    <div><i class="fas fa-check perm-ok"></i> Antibioticos Fuertes</div>
                    <div><i class="fas fa-check perm-ok"></i> Hospitalizacion</div>
                    <div><i class="fas fa-check perm-ok"></i> Urgencias Moderadas</div>
                    <div><i class="fas fa-times perm-no"></i> Opioides Criticos</div>
                    <div><i class="fas fa-times perm-no"></i> Quirófano Total</div>
                    <div><i class="fas fa-times perm-no"></i> UCI Directa</div>
                </div>
            </div>

            <!-- MÉDICO C -->
            <div class="card card-c" onclick="selectProfile('C', 'Dr. KM')">
                <i class="fas fa-user"></i>
                <h3>Dr. KM</h3>
                <div class="level">Nivel C - Basico / Pasante</div>
                <div class="perms">
                    <div><i class="fas fa-check perm-ok"></i> Consulta Basica</div>
                    <div><i class="fas fa-check perm-ok"></i> Medicamentos Simples</div>
                    <div><i class="fas fa-check perm-ok"></i> Diagnosticos Basicos</div>
                    <div><i class="fas fa-times perm-no"></i> Controlados</div>
                    <div><i class="fas fa-times perm-no"></i> Hospitalizacion</div>
                    <div><i class="fas fa-times perm-no"></i> Opioides</div>
                </div>
            </div>
        </div>

        <!-- PIN Section -->
        <div class="pin-section" id="pin-section">
            <h3 id="pin-title">Confirma tu Identidad</h3>
            <p>Ingresa tu PIN de seguridad para acceder como <strong id="pin-role"></strong></p>
            <div class="error" id="pin-error"></div>
            <form method="POST" action="{{ route('medico.verify.profile') }}" id="pin-form">
                @csrf
                <input type="hidden" name="selected_role" id="selected_role" value="">
                <input type="password" name="doctor_pin" class="pin-input" maxlength="4" required placeholder="PIN" autofocus>
                <button type="submit" class="btn-enter btn-a" id="btn-enter"><i class="fas fa-sign-in-alt"></i> Entrar</button>
            </form>
        </div>
    </div>

    <script>
        let selectedLevel = '';

        function selectProfile(level, name) {
            selectedLevel = level;
            document.getElementById('selected_role').value = 'Médico ' + level;
            document.getElementById('pin-role').textContent = name + ' (Médico ' + level + ')';
            document.getElementById('pin-title').textContent = 'Hola, ' + name.split(' ')[1];
            
            // Remove previous selections
            document.querySelectorAll('.card').forEach(c => c.classList.remove('selected'));
            document.querySelector('.card-' + level.toLowerCase()).classList.add('selected');
            
            // Show PIN section
            document.getElementById('pin-section').style.display = 'block';
            
            // Change button color
            const btn = document.getElementById('btn-enter');
            btn.className = 'btn-enter btn-' + level.toLowerCase();
            
            // Focus on PIN input
            document.querySelector('.pin-input').focus();
            
            // Scroll to PIN
            document.getElementById('pin-section').scrollIntoView({ behavior: 'smooth' });
        }
    </script>
</body>
</html>
