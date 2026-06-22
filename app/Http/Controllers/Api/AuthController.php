<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Models\User;

class AuthController extends Controller
{
    // Login para móvil
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
            'device_name' => 'sometimes|string',
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            throw ValidationException::withMessages([
                'email' => ['Las credenciales son incorrectas.'],
            ]);
        }

        $user = User::where('email', $request->email)->first();
        
        // Revocar tokens anteriores (opcional)
        $user->tokens()->delete();

        // Crear token con habilidades según el rol
        $abilities = $this->getAbilitiesForRole($user->role);
        
        $token = $user->createToken($request->device_name ?? 'mobile_app', $abilities)->plainTextToken;

        return response()->json([
            'success' => true,
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'avatar' => $user->avatar ?? null,
            ],
            'redirect_route' => $this->getRedirectRoute($user->role),
        ]);
    }

    // Logout
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Sesión cerrada correctamente'
        ]);
    }

    // Obtener usuario actual
    public function me(Request $request)
    {
        $user = $request->user();
        
        return response()->json([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'avatar' => $user->avatar,
                'email_verified_at' => $user->email_verified_at,
            ]
        ]);
    }

    // Verificar token (para mantener sesión)
    public function verifyToken(Request $request)
    {
        $user = $request->user();
        
        if (!$user) {
            return response()->json(['valid' => false], 401);
        }

        return response()->json([
            'valid' => true,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'role' => $user->role,
            ]
        ]);
    }

    // Obtener permisos según rol
    private function getAbilitiesForRole($role)
    {
        $abilities = ['basic-access'];
        
        if (in_array($role, ['Médico A', 'Médico B', 'Médico C', 'Especialista', 'Urgenciólogo'])) {
            $abilities = array_merge($abilities, [
                'view-patients', 'create-consultations', 'create-prescriptions',
                'view-medical-records', 'create-diagnoses', 'view-hospitalizations'
            ]);
        }
        
        if (in_array($role, ['Enfermera A', 'Enfermera B', 'Enfermera C'])) {
            $abilities = array_merge($abilities, [
                'view-patients', 'create-vitals', 'view-triage', 'create-evolutions'
            ]);
        }
        
        if (in_array($role, ['Farmacéutico', 'Admin Farmacia'])) {
            $abilities = array_merge($abilities, [
                'view-inventory', 'dispense-medications', 'view-prescriptions'
            ]);
        }
        
        if (in_array($role, ['SuperAdmin', 'Administrador Hospitalario'])) {
            $abilities = array_merge($abilities, [
                'admin-access', 'view-all', 'manage-users', 'view-finances', 'view-audit'
            ]);
        }
        
        return $abilities;
    }

    // Obtener ruta de redirección según rol
    private function getRedirectRoute($role)
    {
        if (in_array($role, ['SuperAdmin', 'Administrador Hospitalario'])) {
            return 'superadmin.dashboard';
        } elseif (in_array($role, ['Farmacéutico', 'Admin Farmacia'])) {
            return 'farmacia.dashboard';
        } elseif (in_array($role, ['Enfermera A', 'Enfermera B', 'Enfermera C'])) {
            return 'enfermeria.dashboard';
        } elseif ($role === 'Especialista') {
            return 'medico.especialista.dashboard';
        } elseif (in_array($role, ['Médico A', 'Médico B', 'Médico C', 'Urgenciólogo'])) {
            return 'medico.dashboard';
        }
        
        return 'dashboard';
    }
}