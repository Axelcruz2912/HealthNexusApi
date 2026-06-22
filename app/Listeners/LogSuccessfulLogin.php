<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class LogSuccessfulLogin
{
    public function handle(Login $event): void
    {
        $request = request();
        
        // Detectar si es móvil o escritorio de forma sencilla
        $userAgent = $request->header('User-Agent');
        $device = str_contains($userAgent, 'Mobile') ? 'Móvil' : 'Escritorio';
        $browser = str_contains($userAgent, 'Chrome') ? 'Chrome' : (str_contains($userAgent, 'Firefox') ? 'Firefox' : (str_contains($userAgent, 'Edge') ? 'Edge' : 'Otro'));

        AuditLog::create([
            'user_id' => $event->user->id,
            'user_name' => $event->user->name,
            'user_role' => $event->user->role,
            'action' => 'LOGIN',
            'module' => 'Sistema Core',
            'ip_address' => $request->ip() ?? '127.0.0.1',
            'details' => 'Inicio de sesión exitoso',
            'is_suspicious' => false,
            'user_agent' => $browser . ' / ' . $device
        ]);
    }
}
