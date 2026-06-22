<!DOCTYPE html><html lang="es"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Nueva Contraseña | HealthNexus</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet"><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>*{margin:0;padding:0;box-sizing:border-box;font-family:'Inter',sans-serif}body{background:url('https://z-cdn-media.chatglm.cn/files/80c61c1e-d72e-4aec-8a08-af6eb66aace2.png?auth_key=1880130553-922cb1e48de4401cb9a3226a29954818-0-7c32a8d724f054cb81d74f8cbd64ce93') no-repeat center center fixed;background-size:cover;height:100vh;display:flex;align-items:center;justify-content:center}
.overlay{position:absolute;top:0;left:0;width:100%;height:100%;background:rgba(255,255,255,0.85);z-index:0}
.card{position:relative;z-index:1;background:white;padding:3rem;border-radius:20px;box-shadow:0 20px 50px rgba(0,0,0,0.15);width:100%;max-width:450px}
.logo{text-align:center;margin-bottom:2rem}
.logo img{max-width:200px;height:auto}
h1{font-size:1.5rem;font-weight:800;color:#1E1A17;text-align:center;margin-bottom:0.5rem}p{color:#736860;font-size:0.9rem;margin-bottom:2rem;text-align:center}
.form-group{margin-bottom:1.5rem}label{display:block;font-size:0.82rem;font-weight:700;color:#736860;text-transform:uppercase;margin-bottom:0.5rem}
input{width:100%;padding:0.9rem 1rem;border:1.5px solid #E2E8F0;border-radius:10px;font-size:0.95rem;outline:none;background:#FAFAF8}input:focus{border-color:#2D9E6A;box-shadow:0 0 0 3px rgba(45,158,106,0.1)}
button{width:100%;padding:1rem;background:#2D9E6A;color:white;border:none;border-radius:10px;font-size:1rem;font-weight:700;cursor:pointer;transition:0.2s}button:hover{background:#238B55}
.error{background:#FFF1F0;color:#8C1A11;padding:1rem;border-radius:8px;font-size:0.9rem;margin-bottom:1.5rem;border-left:4px solid #C7291C;text-align:center}
</style></head><body>
<div class="overlay"></div>
<div class="card">
    <div class="logo">
        <img src="https://z-cdn-media.chatglm.cn/files/e422f718-2f1b-43b1-8d33-abab22ae033a.png?auth_key=1880130553-c96ef22a7ec1475a8024ee420ae894cb-0-01473062212a4e246495206bff72dde3" alt="HealthNexus Logo">
    </div>
    <h1>Nueva Contraseña</h1><p>Ingresa y confirma tu nueva contraseña segura.</p>
    @if($errors->any())<div class="error"><i class="fas fa-exclamation-circle"></i> {{ $errors->first() }}</div>@endif
    <form method="POST" action="{{ route('password.update') }}">@csrf
        <input type="hidden" name="token" value="{{ $token }}">
        <div class="form-group"><label>Correo Electrónico</label><input type="email" name="email" value="{{ $email ?? old('email') }}" required></div>
        <div class="form-group"><label>Nueva Contraseña</label><input type="password" name="password" required placeholder="Mínimo 8 caracteres"></div>
        <div class="form-group"><label>Confirmar Contraseña</label><input type="password" name="password_confirmation" required></div>
        <button type="submit"><i class="fas fa-save"></i> Restablecer Contraseña</button>
    </form>
</div></body></html>
