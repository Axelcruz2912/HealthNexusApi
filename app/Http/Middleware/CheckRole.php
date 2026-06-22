<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!auth()->check()) {
            return redirect('/login');
        }

        $userRole = auth()->user()->role;

        // Super Admin tiene acceso a TODO
        if ($userRole === 'Super Admin') {
            return $next($request);
        }

        // Verificar si el rol del usuario esta en los roles permitidos
        if (in_array($userRole, $roles)) {
            return $next($request);
        }

        abort(403, 'No tienes permiso para acceder a esta pagina.');
    }
}
