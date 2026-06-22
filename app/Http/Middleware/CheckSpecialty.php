<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckSpecialty
{
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();
        
        // Solo Especialistas, Médicos A, Urgenciólogos y SuperAdmin pueden acceder
        $allowedRoles = ['SuperAdmin', 'Especialista', 'Médico A', 'Urgenciólogo'];
        
        if (!in_array($user->role, $allowedRoles) && !$user->specialty_id) {
            abort(403, 'No tienes acceso al módulo de especialidades.');
        }
        
        return $next($request);
    }
}
