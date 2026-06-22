<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();

        $role = $request->user()->role;

        if (in_array($role, ['SuperAdmin', 'Administrador Hospitalario'])) {
            return redirect()->route('superadmin.dashboard');
        } elseif (in_array($role, ['Farmacéutico', 'Admin Farmacia'])) {
            return redirect()->route('farmacia.dashboard');
        } elseif (in_array($role, ['Enfermera A', 'Enfermera B', 'Enfermera C'])) {
            return redirect()->route('enfermeria.dashboard');
        } elseif ($role === 'Especialista') {
            return redirect()->route('medico.especialista.dashboard');
        } elseif (in_array($role, ['Médico A', 'Médico B', 'Médico C', 'Urgenciólogo'])) {
            return redirect()->route('medico.dashboard');
        }

        return redirect()->route('superadmin.dashboard');
    }

    public function destroy(): RedirectResponse
    {
        Auth::guard('web')->logout();
        session()->invalidate();
        session()->regenerateToken();
        return redirect('/');
    }
}
