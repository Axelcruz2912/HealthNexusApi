<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckDoctorPin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $role = auth()->user()->role;

        if ($role === 'Médico A') {
            if (!session('doctor_pin_verified')) {
                return redirect()->route('medico.pin.auth');
            }
        }

        return $next($request);
    }
}
