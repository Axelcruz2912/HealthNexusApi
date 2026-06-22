<!DOCTYPE html><html lang="es"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Login | HealthNexus</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet"><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>*{margin:0;padding:0;box-sizing:border-box;font-family:'Inter',sans-serif}body{background:url('https://z-cdn-media.chatglm.cn/files/80c61c1e-d72e-4aec-8a08-af6eb66aace2.png?auth_key=1880130553-922cb1e48de4401cb9a3226a29954818-0-7c32a8d724f054cb81d74f8cbd64ce93') no-repeat center center fixed;background-size:cover;height:100vh;display:flex;align-items:center;justify-content:center}
.overlay{position:absolute;top:0;left:0;width:100%;height:100%;background:linear-gradient(135deg, rgba(30,26,23,0.85), rgba(240,90,78,0.5));z-index:0}
.login-card{position:relative;z-index:1;background:rgba(255,255,255,0.97);padding:3rem;border-radius:20px;box-shadow:0 20px 50px rgba(0,0,0,0.4);width:100%;max-width:460px;backdrop-filter:blur(10px)}
.logo{text-align:center;margin-bottom:2rem}.logo img{max-width:240px;height:auto}
h1{font-size:1.5rem;font-weight:800;color:#1E1A17;text-align:center;margin-bottom:0.5rem}p.subtitle{color:#736860;font-size:0.9rem;margin-bottom:2rem;text-align:center}
.form-group{margin-bottom:1.25rem}label{display:block;font-size:0.82rem;font-weight:700;color:#736860;text-transform:uppercase;margin-bottom:0.375rem}
input{width:100%;padding:0.75rem 1rem;border:1.5px solid #E2E8F0;border-radius:10px;font-size:0.95rem;outline:none;background:#FAFAF8;transition:0.2s}input:focus{border-color:#F05A4E;box-shadow:0 0 0 3px rgba(240,90,78,0.14)}
button{width:100%;padding:0.95rem;background:#1E1A17;color:white;border:none;border-radius:10px;font-size:1rem;font-weight:700;cursor:pointer;transition:0.2s;display:flex;align-items:center;justify-content:center;gap:0.5rem}button:hover{background:#333;transform:translateY(-1px);box-shadow:0 4px 12px rgba(0,0,0,0.2)}
.links{margin-top:1.5rem;text-align:center}a{color:#F05A4E;text-decoration:none;font-weight:600;font-size:0.85rem;transition:0.2s}a:hover{color:#d9483c}
.error{background:#FFF1F0;color:#8C1A11;padding:1rem;border-radius:8px;font-size:0.9rem;margin-bottom:1.5rem;border-left:4px solid #C7291C}
</style></head><body>
<div class="overlay"></div>
<div class="login-card">
    <div class="logo">
        <img src="https://z-cdn-media.chatglm.cn/files/e422f718-2f1b-43b1-8d33-abab22ae033a.png?auth_key=1880130553-c96ef22a7ec1475a8024ee420ae894cb-0-01473062212a4e246495206bff72dde3" alt="HealthNexus Logo">
    </div>
    <div style="text-align:center; margin-bottom:1rem;"><img src="http://localhost/images/logo.png" alt="HealthNexus" style="height:60px;"></div><h1>Iniciar Sesión</h1><p class="subtitle">Ingresa tus credenciales para acceder al sistema hospitalario.</p>
    @if($errors->any())<div class="error"><i class="fas fa-exclamation-circle"></i> {{ $errors->first() }}</div>@endif
    <form method="POST" action="{{ route('login') }}">@csrf
        <div class="form-group"><label>Correo Electrónico</label><input type="email" name="email" value="{{ old('email') }}" required autofocus></div>
        <div class="form-group"><label>Contraseña</label><input type="password" name="password" required></div>
        <button type="submit"><i class="fas fa-sign-in-alt"></i> Entrar al Sistema</button>
    </form>
    <div class="links"><a href="{{ route('password.request') }}">¿Olvidaste tu contraseña?</a></div>
</div></body></html>
