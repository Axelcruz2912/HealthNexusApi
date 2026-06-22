<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\AuditLog;  
use App\Models\Triage; 
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    /**
     * Obtener lista de usuarios
     */
    public function getUsers()
    {
        try {
            Log::info('AdminController::getUsers called');
            
            $users = DB::table('users')
                ->select('id', 'name', 'email', 'role', 'curp', 'rfc', 
                        'validation_status', 'status', 'ine_path', 'cedula_path', 
                        'certifications_path', 'rejection_reason', 'created_at')
                ->orderBy('created_at', 'desc')
                ->get();

            Log::info('Users fetched: ' . $users->count());
            
            return response()->json([
                'success' => true,
                'data' => $users,
                'count' => $users->count()
            ]);
        } catch (\Exception $e) {
            Log::error('Error in getUsers: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Crear nuevo usuario
     */
    public function storeUser(Request $request)
    {
        try {
            Log::info('AdminController::storeUser called');
            Log::info('Request files: ', $request->allFiles());
            
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users',
                'password' => 'required|min:8',
                'role' => 'required|string|max:100',
                'curp' => 'nullable|string|max:18',
                'rfc' => 'nullable|string|max:13',
                'ine' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
                'cedula' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
                'certifications' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            ]);

            $userData = [
                'name' => strtoupper($request->name),
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role,
                'validation_status' => 'Pendiente',
                'status' => 1,
                'finance_pin' => '1234'
            ];

            if ($request->curp) {
                $userData['curp'] = strtoupper($request->curp);
            }
            if ($request->rfc) {
                $userData['rfc'] = strtoupper($request->rfc);
            }

            $user = User::create($userData);

            // ==========================================
            // SUBIDA DE ARCHIVOS
            // ==========================================
            
            // INE / Identificación
            if ($request->hasFile('ine')) {
                try {
                    $file = $request->file('ine');
                    $filename = time() . '_ine_' . $user->id . '.' . $file->getClientOriginalExtension();
                    $path = $file->storeAs('users/ine', $filename, 'public');
                    $user->ine_path = $path;
                    Log::info('INE saved: ' . $path);
                } catch (\Exception $e) {
                    Log::error('Error saving INE: ' . $e->getMessage());
                }
            }

            // Cédula Profesional
            if ($request->hasFile('cedula')) {
                try {
                    $file = $request->file('cedula');
                    $filename = time() . '_cedula_' . $user->id . '.' . $file->getClientOriginalExtension();
                    $path = $file->storeAs('users/cedula', $filename, 'public');
                    $user->cedula_path = $path;
                    Log::info('Cédula saved: ' . $path);
                } catch (\Exception $e) {
                    Log::error('Error saving Cédula: ' . $e->getMessage());
                }
            }

            // Certificaciones
            if ($request->hasFile('certifications')) {
                try {
                    $file = $request->file('certifications');
                    $filename = time() . '_cert_' . $user->id . '.' . $file->getClientOriginalExtension();
                    $path = $file->storeAs('users/certifications', $filename, 'public');
                    $user->certifications_path = $path;
                    Log::info('Certification saved: ' . $path);
                } catch (\Exception $e) {
                    Log::error('Error saving Certification: ' . $e->getMessage());
                }
            }

            $user->save();

            Log::info('User created: ' . $user->id);

            return response()->json([
                'success' => true,
                'message' => 'Empleado registrado correctamente',
                'user' => $user
            ]);
        } catch (\Exception $e) {
            Log::error('Error in storeUser: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Aprobar usuario
     */
    public function approveUser($id)
    {
        try {
            Log::info('AdminController::approveUser called for id: ' . $id);
            
            $user = User::find($id);
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'error' => 'Usuario no encontrado'
                ], 404);
            }
            
            $user->validation_status = 'Aprobado';
            $user->status = 1;
            $user->save();

            Log::info('User approved: ' . $id);

            return response()->json([
                'success' => true,
                'message' => 'Usuario aprobado correctamente'
            ]);
        } catch (\Exception $e) {
            Log::error('Error in approveUser: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Rechazar usuario
     */
    public function rejectUser(Request $request, $id)
    {
        try {
            Log::info('AdminController::rejectUser called for id: ' . $id);
            
            $request->validate([
                'rejection_reason' => 'required|string'
            ]);
            
            $user = User::find($id);
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'error' => 'Usuario no encontrado'
                ], 404);
            }
            
            $user->validation_status = 'Rechazado';
            $user->rejection_reason = $request->rejection_reason;
            $user->save();

            Log::info('User rejected: ' . $id);

            return response()->json([
                'success' => true,
                'message' => 'Usuario rechazado correctamente'
            ]);
        } catch (\Exception $e) {
            Log::error('Error in rejectUser: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Actualizar rol de usuario
     */
    public function updateRole(Request $request, $id)
    {
        try {
            Log::info('AdminController::updateRole called for id: ' . $id);
            
            $request->validate([
                'role' => 'required|string|max:100'
            ]);
            
            $user = User::find($id);
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'error' => 'Usuario no encontrado'
                ], 404);
            }
            
            $user->role = $request->role;
            $user->save();

            Log::info('Role updated for user: ' . $id);

            return response()->json([
                'success' => true,
                'message' => 'Rol actualizado correctamente'
            ]);
        } catch (\Exception $e) {
            Log::error('Error in updateRole: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Activar/Desactivar usuario
     */
    public function toggleStatus($id)
    {
        try {
            Log::info('AdminController::toggleStatus called for id: ' . $id);
            
            $user = User::find($id);
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'error' => 'Usuario no encontrado'
                ], 404);
            }
            
            $user->status = $user->status == 1 ? 0 : 1;
            $user->save();

            Log::info('Status toggled for user: ' . $id . ' New status: ' . $user->status);

            return response()->json([
                'success' => true,
                'message' => 'Estado actualizado correctamente',
                'status' => $user->status
            ]);
        } catch (\Exception $e) {
            Log::error('Error in toggleStatus: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Eliminar usuario
     */
    public function deleteUser($id)
    {
        try {
            Log::info('AdminController::deleteUser called for id: ' . $id);
            
            $user = User::find($id);
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'error' => 'Usuario no encontrado'
                ], 404);
            }
            
            $userName = $user->name;
            $user->delete();

            Log::info('User deleted: ' . $id . ' - ' . $userName);

            return response()->json([
                'success' => true,
                'message' => 'Usuario eliminado correctamente'
            ]);
        } catch (\Exception $e) {
            Log::error('Error in deleteUser: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener score de riesgo de usuarios
     */
    public function getRiskScore()
    {
        try {
            Log::info('AdminController::getRiskScore called');
            
            $users = User::where('id', '!=', auth()->id())
                ->select('id', 'name', 'email', 'role', 'validation_status', 'status', 
                        'ine_path', 'cedula_path', 'curp', 'rfc')
                ->get();
            
            $riskData = $users->map(function($user) {
                $risk_factors = [];
                $risk_score = 0;
                
                // Factor 1: Validación
                if($user->validation_status == 'Rechazado') {
                    $risk_factors[] = 'Credenciales Rechazadas';
                    $risk_score += 40;
                } elseif($user->validation_status == 'Pendiente') {
                    $risk_factors[] = 'Sin Validar (Pendiente)';
                    $risk_score += 20;
                }
                
                // Factor 2: Documentación
                if(!$user->ine_path) {
                    $risk_factors[] = 'INE no cargado';
                    $risk_score += 10;
                }
                if(!in_array($user->role, ['Recepcionista', 'Finanzas']) && !$user->cedula_path) {
                    $risk_factors[] = 'Cédula Profesional faltante';
                    $risk_score += 15;
                }
                if(!$user->curp) {
                    $risk_factors[] = 'CURP faltante/inválida';
                    $risk_score += 5;
                }
                
                // Factor 3: Estado
                if(!$user->status) {
                    $risk_factors[] = 'Cuenta Bloqueada';
                    $risk_score += 30;
                }
                
                // Determinar nivel de riesgo
                $color = $risk_score < 30 ? '#2D9E6A' : ($risk_score < 70 ? '#FF8C42' : '#C7291C');
                $label = $risk_score < 30 ? 'Seguro' : ($risk_score < 70 ? 'Riesgo Medio' : 'Riesgo Crítico');
                $bg_color = $risk_score < 30 ? '#EBF9F2' : ($risk_score < 70 ? '#FFF5EB' : '#FFF1F0');
                
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                    'validation_status' => $user->validation_status,
                    'status' => $user->status,
                    'risk_score' => $risk_score,
                    'risk_label' => $label,
                    'risk_color' => $color,
                    'risk_bg_color' => $bg_color,
                    'risk_factors' => $risk_factors,
                    'needs_validation' => $user->validation_status != 'Aprobado' && $user->status,
                    'account_suspended' => !$user->status,
                ];
            });
            
            return response()->json([
                'success' => true,
                'data' => $riskData,
                'count' => $riskData->count()
            ]);
        } catch (\Exception $e) {
            Log::error('Error in getRiskScore: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
 * Obtener roles y permisos
 */
public function getRolesPermissions()
{
    try {
        Log::info('AdminController::getRolesPermissions called');
        
        $roles = ['SuperAdmin', 'Administrador Hospitalario', 'Médico A', 'Médico B', 'Médico C', 
                  'Enfermera A', 'Enfermera B', 'Enfermera C', 'Recepcionista', 'Farmacéutico', 
                  'Admin Farmacia', 'Finanzas', 'Laboratorista', 'Urgenciólogo'];
        
        $modules = [
            'dashboard_ejecutivo' => 'Dashboard Ejecutivo',
            'validacion_personal' => 'Validación de Personal',
            'roles_permisos' => 'Roles y Permisos',
            'seguridad' => 'Seguridad Centralizada',
            'monitor_live' => 'Monitor Live Hospital',
            'actividad_sospechosa' => 'Detección Sospechosa',
            'replay_sesiones' => 'Replay de Sesiones',
            'auditoria' => 'Auditoría Total',
            'urgencias' => 'Centro de Urgencias',
            'farmacia' => 'Supervisión Farmacia',
            'recursos' => 'Recursos Hospitalarios',
            'mapa_calor' => 'Mapa de Calor',
            'ingesta_datos' => 'Centro de Ingesta',
            'limpieza_datos' => 'Motor de Limpieza',
            'etl_bigdata' => 'Centro ETL / Big Data',
            'ia_anomalias' => 'IA Anomalías',
            'arbol_decisiones' => 'Árbol de Decisiones',
            'score_riesgo' => 'Score de Riesgo',
            'reportes' => 'Reportes Automáticos'
        ];
        
        $permissions = DB::table('role_permissions')->get();
        
        $data = [];
        foreach ($modules as $key => $name) {
            $row = [
                'module_key' => $key,
                'module_name' => $name,
                'permissions' => []
            ];
            foreach ($roles as $role) {
                $perm = $permissions->where('role', $role)->where('module_key', $key)->first();
                $row['permissions'][$role] = $perm ? (bool)$perm->can_access : true;
            }
            $data[] = $row;
        }
        
        return response()->json([
            'success' => true,
            'data' => [
                'roles' => $roles,
                'modules' => $data,
                'permissions' => $permissions
            ]
        ]);
    } catch (\Exception $e) {
        Log::error('Error in getRolesPermissions: ' . $e->getMessage());
        
        return response()->json([
            'success' => false,
            'error' => $e->getMessage()
        ], 500);
    }
}

/**
 * Toggle permission
 */
public function togglePermission(Request $request)
{
    try {
        Log::info('AdminController::togglePermission called');
        
        $request->validate([
            'role' => 'required|string',
            'module_key' => 'required|string'
        ]);
        
        $perm = DB::table('role_permissions')
            ->where('role', $request->role)
            ->where('module_key', $request->module_key)
            ->first();
        
        if ($perm) {
            $newStatus = !$perm->can_access;
            DB::table('role_permissions')
                ->where('role', $request->role)
                ->where('module_key', $request->module_key)
                ->update(['can_access' => $newStatus]);
            
            return response()->json([
                'success' => true,
                'message' => 'Permiso actualizado',
                'can_access' => $newStatus
            ]);
        } else {
            DB::table('role_permissions')->insert([
                'role' => $request->role,
                'module_key' => $request->module_key,
                'can_access' => false,
                'created_at' => now(),
                'updated_at' => now()
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Permiso creado',
                'can_access' => false
            ]);
        }
    } catch (\Exception $e) {
        Log::error('Error in togglePermission: ' . $e->getMessage());
        
        return response()->json([
            'success' => false,
            'error' => $e->getMessage()
        ], 500);
    }
}

/**
 * Obtener lista de pacientes
 */
public function getPatients()
{
    try {
        Log::info('AdminController::getPatients called');
        
        $patients = DB::table('triages')
            ->whereIn('status', ['En Espera', 'En Atención', 'Hospitalizado', 'Derivado'])
            ->select('id', 'patient_name', 'triage_level', 'assigned_area', 'status', 
                    'vitals_ta', 'vitals_fc', 'vitals_temp', 'vitals_spo2', 
                    'age', 'created_at')
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Mapear colores de triage
        $patients = $patients->map(function($patient) {
            $colors = [
                'Rojo' => '#C7291C',
                'Naranja' => '#EA580C', 
                'Amarillo' => '#F59E0B',
                'Verde' => '#2D9E6A',
                'Azul' => '#3B82F6'
            ];
            $patient->triage_color = $colors[$patient->triage_level] ?? '#736860';
            
            return $patient;
        });
        
        Log::info('Patients fetched: ' . $patients->count());
        
        return response()->json([
            'success' => true,
            'data' => $patients,
            'count' => $patients->count()
        ]);
    } catch (\Exception $e) {
        Log::error('Error in getPatients: ' . $e->getMessage());
        
        return response()->json([
            'success' => false,
            'error' => $e->getMessage()
        ], 500);
    }
}

/**
 * Actualizar estado de paciente
 */
public function updatePatientStatus(Request $request, $id)
{
    try {
        Log::info('AdminController::updatePatientStatus called for id: ' . $id);
        
        $request->validate([
            'status' => 'required|string|in:En Espera,En Atención,Hospitalizado,Derivado,Alta'
        ]);
        
        $updated = DB::table('triages')
            ->where('id', $id)
            ->update([
                'status' => $request->status,
                'updated_at' => now()
            ]);
        
        if ($updated) {
            return response()->json([
                'success' => true,
                'message' => 'Estado actualizado correctamente'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'error' => 'Paciente no encontrado'
            ], 404);
        }
    } catch (\Exception $e) {
        Log::error('Error in updatePatientStatus: ' . $e->getMessage());
        
        return response()->json([
            'success' => false,
            'error' => $e->getMessage()
        ], 500);
    }
}

/**
 * Obtener dashboard de urgencias
 */
public function apiEmergencyDashboard()
{
    try {
        Log::info('AdminController::apiEmergencyDashboard called');
        
        $triages = Triage::whereIn('status', ['En Espera', 'En Atención', 'Hospitalizado'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        $colors = [
            'Rojo' => ['bg' => '#FFE0DC', 'border' => '#C7291C', 'text' => '#8C1A11'],
            'Naranja' => ['bg' => '#FFF5EB', 'border' => '#FF8C42', 'text' => '#9a3412'],
            'Amarillo' => ['bg' => '#FFFCE8', 'border' => '#F59E0B', 'text' => '#92400E'],
            'Verde' => ['bg' => '#EBF9F2', 'border' => '#2D9E6A', 'text' => '#065F46'],
            'Azul' => ['bg' => '#EFF6FF', 'border' => '#3B82F6', 'text' => '#1E3A8A']
        ];
        
        $patientsByTriage = [];
        foreach ($colors as $color => $style) {
            $patientsByTriage[$color] = $triages->where('triage_level', $color)->values()->map(function($p) {
                return [
                    'id' => $p->id,
                    'patient_name' => $p->patient_name,
                    'age' => $p->age,
                    'symptoms' => $p->symptoms ?? $p->chief_complaint ?? 'Pendiente',
                    'status' => $p->status,
                    'assigned_area' => $p->assigned_area,
                    'vitals_ta' => $p->vitals_ta,
                    'vitals_fc' => $p->vitals_fc,
                    'vitals_temp' => $p->vitals_temp,
                    'vitals_spo2' => $p->vitals_spo2,
                    'is_derived' => $p->is_derived ?? false,
                    'created_at' => $p->created_at
                ];
            });
        }
        
        return response()->json([
            'success' => true,
            'data' => [
                'patients' => $patientsByTriage,
                'colors' => $colors
            ]
        ]);
    } catch (\Exception $e) {
        Log::error('Error in apiEmergencyDashboard: ' . $e->getMessage());
        return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
    }
}

/**
 * Registrar nuevo paciente en urgencias
 */
public function apiStoreTriage(Request $request)
{
    try {
        Log::info('AdminController::apiStoreTriage called');
        
        $request->validate([
            'patient_name' => 'required|string',
            'triage_level' => 'required|in:Rojo,Naranja,Amarillo,Verde,Azul',
            'age' => 'required|integer|min:0',
            'chief_complaint' => 'nullable|string'
        ]);
        
        // Verificar duplicados
        $existingPatient = DB::table('triages')
            ->where('patient_name', $request->patient_name)
            ->where('age', $request->age)
            ->whereIn('status', ['En Espera', 'En Atención'])
            ->whereDate('created_at', today())
            ->first();
        
        if ($existingPatient) {
            return response()->json([
                'success' => false,
                'error' => 'ETL Big Data: Registro bloqueado. El paciente ya está activo en Urgencias hoy (Duplicado evitado).'
            ], 400);
        }
        
        $id = DB::table('triages')->insertGetId([
            'patient_name' => $request->patient_name,
            'triage_level' => $request->triage_level,
            'age' => $request->age,
            'symptoms' => $request->chief_complaint ?? 'Pendiente',
            'chief_complaint' => $request->chief_complaint ?? 'Pendiente',
            'status' => 'En Espera',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
        Log::info('Triage created: ' . $id);
        
        return response()->json([
            'success' => true,
            'message' => 'Paciente registrado correctamente',
            'data' => ['id' => $id]
        ]);
    } catch (\Exception $e) {
        Log::error('Error in apiStoreTriage: ' . $e->getMessage());
        return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
    }
}

/**
 * Actualizar signos vitales de paciente
 */
public function apiUpdateVitals(Request $request, $id)
{
    try {
        Log::info('AdminController::apiUpdateVitals called for id: ' . $id);
        
        $request->validate([
            'vitals_ta' => 'required|string',
            'vitals_fc' => 'required|string',
            'vitals_temp' => 'required|string',
            'vitals_spo2' => 'required|string',
            'assigned_area' => 'nullable|string'
        ]);
        
        $updated = DB::table('triages')
            ->where('id', $id)
            ->update([
                'vitals_ta' => $request->vitals_ta,
                'vitals_fc' => $request->vitals_fc,
                'vitals_temp' => $request->vitals_temp,
                'vitals_spo2' => $request->vitals_spo2,
                'assigned_area' => $request->assigned_area ?? 'Urgencias',
                'status' => 'En Atención',
                'updated_at' => now()
            ]);
        
        if ($updated) {
            return response()->json([
                'success' => true,
                'message' => 'Signos vitales registrados correctamente'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'error' => 'Paciente no encontrado'
            ], 404);
        }
    } catch (\Exception $e) {
        Log::error('Error in apiUpdateVitals: ' . $e->getMessage());
        return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
    }
}

/**
 * Derivar paciente
 */
/**
 * Prescribir medicamento
 */
public function apiPharmacyPrescribe(Request $request)
{
    try {
        Log::info('AdminController::apiPharmacyPrescribe called');
        
        $request->validate([
            'medication_id' => 'required|exists:medications,id',
            'patient_id' => 'required|exists:triages,id',
            'doctor_role' => 'required|string'
        ]);
        
        $medication = DB::table('medications')->where('id', $request->medication_id)->first();
        $patient = DB::table('triages')->where('id', $request->patient_id)->first();
        
        if (!$medication || !$patient) {
            return response()->json([
                'success' => false, 
                'error' => 'Medicamento o paciente no encontrado'
            ], 404);
        }
        
        // Verificar nivel de acceso del médico
        $doctorRole = $request->doctor_role;
        $requiredLevel = $medication->required_level ?? 'C';
        $allowed = false;
        $denialReason = null;
        
        if ($requiredLevel == 'C') {
            $allowed = true;
        } elseif ($requiredLevel == 'B' && in_array($doctorRole, ['Médico A', 'Médico B', 'Urgenciólogo'])) {
            $allowed = true;
        } elseif ($requiredLevel == 'A' && in_array($doctorRole, ['Médico A', 'Urgenciólogo'])) {
            $allowed = true;
        } else {
            $allowed = false;
            $denialReason = "Nivel {$requiredLevel} requiere médico " . 
                           ($requiredLevel == 'A' ? 'A o Urgenciólogo' : 
                            ($requiredLevel == 'B' ? 'A o B' : 'Cualquier médico'));
        }
        
        // Verificar stock
        if ($medication->stock <= 0) {
            $allowed = false;
            $denialReason = "Sin stock disponible";
        }
        
        // Registrar prescripción
        $status = $allowed ? 'Autorizada' : 'Denegada';
        
        try {
            $prescriptionId = DB::table('prescriptions')->insertGetId([
                'medication_id' => $medication->id,
                'medication_name' => $medication->name,
                'patient_id' => $patient->id,
                'patient_name' => $patient->patient_name,
                'doctor_role' => $doctorRole,
                'doctor_name' => auth()->user()->name ?? 'Sistema',
                'status' => $status,
                'denial_reason' => $denialReason,
                'is_priority' => $patient->triage_level === 'Rojo',
                'created_at' => now(),
                'updated_at' => now()
            ]);
        } catch (\Exception $e) {
            Log::error('Error inserting prescription: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Error al guardar la prescripción: ' . $e->getMessage()
            ], 500);
        }
        
        // Si es autorizada, reducir stock
        if ($allowed) {
            try {
                DB::table('medications')->where('id', $medication->id)->decrement('stock', 1);
            } catch (\Exception $e) {
                Log::error('Error updating stock: ' . $e->getMessage());
            }
        }
        
        return response()->json([
            'success' => true,
            'data' => [
                'prescription_id' => $prescriptionId,
                'status' => $status,
                'message' => $allowed ? 'Receta autorizada' : "Acceso denegado: {$denialReason}",
                'is_priority' => $patient->triage_level === 'Rojo'
            ]
        ]);
    } catch (\Exception $e) {
        Log::error('Error in apiPharmacyPrescribe: ' . $e->getMessage());
        Log::error('Stack trace: ' . $e->getTraceAsString());
        return response()->json([
            'success' => false,
            'error' => $e->getMessage()
        ], 500);
    }
}
public function apiDerivePatient(Request $request, $id)
{
    try {
        Log::info('AdminController::apiDerivePatient called for id: ' . $id);
        
        $request->validate([
            'derivation_hospital' => 'required|string'
        ]);
        
        $updated = DB::table('triages')
            ->where('id', $id)
            ->update([
                'is_derived' => true,
                'derivation_hospital' => $request->derivation_hospital,
                'status' => 'Derivado',
                'updated_at' => now()
            ]);
        
        if ($updated) {
            return response()->json([
                'success' => true,
                'message' => 'Paciente derivado correctamente'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'error' => 'Paciente no encontrado'
            ], 404);
        }
    } catch (\Exception $e) {
        Log::error('Error in apiDerivePatient: ' . $e->getMessage());
        return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
    }
}

/**
 * Dar de alta paciente
 */
public function apiDischargePatient($id)
{
    try {
        Log::info('AdminController::apiDischargePatient called for id: ' . $id);
        
        $updated = DB::table('triages')
            ->where('id', $id)
            ->update([
                'status' => 'Dado de Alta',
                'updated_at' => now()
            ]);
        
        if ($updated) {
            return response()->json([
                'success' => true,
                'message' => 'Paciente dado de alta correctamente'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'error' => 'Paciente no encontrado'
            ], 404);
        }
    } catch (\Exception $e) {
        Log::error('Error in apiDischargePatient: ' . $e->getMessage());
        return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
    }
}

/**
 * Obtener dashboard de farmacia (VERSIÓN OPTIMIZADA CON PAGINACIÓN)
 */
public function apiPharmacyDashboard(Request $request)
{
    try {
        Log::info('AdminController::apiPharmacyDashboard called');
        
        // ==========================================
        // PARÁMETROS DE PAGINACIÓN
        // ==========================================
        $perPage = $request->get('per_page', 30);
        $page = $request->get('page', 1);
        $search = $request->get('search', '');
        
        // ==========================================
        // MEDICAMENTOS CON PAGINACIÓN
        // ==========================================
        $medicationsQuery = DB::table('medications');
        
        // Filtro de búsqueda
        if ($search) {
            $medicationsQuery->where('name', 'LIKE', "%{$search}%")
                           ->orWhere('origin', 'LIKE', "%{$search}%")
                           ->orWhere('active_ingredient', 'LIKE', "%{$search}%");
        }
        
        $medications = $medicationsQuery
            ->orderBy('origin')
            ->orderBy('name')
            ->paginate($perPage, ['*'], 'page', $page);
        
        // ==========================================
        // PACIENTES CRÍTICOS (LIMITADOS)
        // ==========================================
        $criticalPatients = DB::table('triages')
            ->where('triage_level', 'Rojo')
            ->whereIn('status', ['En Espera', 'En Atención'])
            ->limit(10)
            ->get();
        
        // ==========================================
        // PACIENTES NORMALES (LIMITADOS)
        // ==========================================
        $normalPatients = DB::table('triages')
            ->whereIn('triage_level', ['Verde', 'Azul', 'Amarillo'])
            ->whereIn('status', ['En Espera', 'En Atención'])
            ->limit(10)
            ->get();
        
        // ==========================================
        // ÚLTIMAS PRESCRIPCIONES (LIMITADAS)
        // ==========================================
        $prescriptions = DB::table('prescriptions')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        // ==========================================
        // CONTAR POR ORIGEN
        // ==========================================
        $origins = ['Central', 'Hospitalaria', 'Quirófano', 'Urgencias'];
        $originCounts = [];
        foreach ($origins as $origin) {
            $originCounts[$origin] = DB::table('medications')
                ->where('origin', $origin)
                ->count();
        }
        
        // ==========================================
        // TOTAL DE MEDICAMENTOS POR ORIGEN (PARA STATS)
        // ==========================================
        $totalMedications = DB::table('medications')->count();
        $lowStockCount = DB::table('medications')
            ->whereRaw('stock <= min_stock')
            ->where('stock', '>', 0)
            ->count();
        $outOfStockCount = DB::table('medications')
            ->where('stock', 0)
            ->count();
        
        return response()->json([
            'success' => true,
            'data' => [
                // Medicamentos paginados
                'medications' => $medications->items(),
                'medications_meta' => [
                    'total' => $medications->total(),
                    'per_page' => $medications->perPage(),
                    'current_page' => $medications->currentPage(),
                    'last_page' => $medications->lastPage(),
                    'from' => $medications->firstItem(),
                    'to' => $medications->lastItem(),
                ],
                // Pacientes
                'critical_patients' => $criticalPatients->map(function($p) {
                    return [
                        'id' => $p->id,
                        'patient_name' => $p->patient_name,
                        'triage_level' => $p->triage_level,
                        'status' => $p->status,
                    ];
                }),
                'normal_patients' => $normalPatients->map(function($p) {
                    return [
                        'id' => $p->id,
                        'patient_name' => $p->patient_name,
                        'triage_level' => $p->triage_level,
                        'status' => $p->status,
                    ];
                }),
                // Conteos por origen
                'origin_counts' => $originCounts,
                // Últimas prescripciones
                'prescriptions' => $prescriptions->map(function($p) {
                    return [
                        'id' => $p->id,
                        'medication_name' => $p->medication_name ?? 'N/A',
                        'patient_name' => $p->patient_name ?? 'N/A',
                        'doctor_name' => $p->doctor_name ?? 'Sistema',
                        'status' => $p->status ?? 'Pendiente',
                        'denial_reason' => $p->denial_reason,
                        'is_priority' => $p->is_priority ?? false,
                        'created_at' => $p->created_at,
                    ];
                }),
                // Stats adicionales
                'stats' => [
                    'total_medications' => $totalMedications,
                    'low_stock' => $lowStockCount,
                    'out_of_stock' => $outOfStockCount,
                ]
            ]
        ]);
    } catch (\Exception $e) {
        Log::error('Error in apiPharmacyDashboard: ' . $e->getMessage());
        return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
    }
}

/**
 * Obtener lista de camas
 */
public function apiGetBeds()
{
    try {
        Log::info('AdminController::apiGetBeds called');
        
        $beds = DB::table('beds')
            ->orderBy('floor')
            ->orderBy('room_number')
            ->orderBy('bed_number')
            ->get();
        
        // Agrupar por piso
        $floors = $beds->groupBy('floor');
        
        // Contar por estado
        $stats = [
            'total' => $beds->count(),
            'disponible' => $beds->where('status', 'Disponible')->count(),
            'ocupada' => $beds->where('status', 'Ocupada')->count(),
            'limpieza' => $beds->where('status', 'Limpieza')->count(),
            'mantenimiento' => $beds->where('status', 'Mantenimiento')->count(),
        ];
        
        return response()->json([
            'success' => true,
            'data' => [
                'beds' => $beds->map(function($bed) {
                    return [
                        'id' => $bed->id,
                        'floor' => $bed->floor,
                        'room_number' => $bed->room_number,
                        'bed_number' => $bed->bed_number,
                        'type' => $bed->type ?? 'General',
                        'status' => $bed->status,
                        'patient_name' => $bed->patient_name ?? null,
                        'status_color' => $bed->status == 'Disponible' ? '#2D9E6A' : 
                                        ($bed->status == 'Ocupada' ? '#C7291C' : 
                                        ($bed->status == 'Limpieza' ? '#FF8C42' : '#736860')),
                        'status_bg' => $bed->status == 'Disponible' ? '#EBF9F2' : 
                                      ($bed->status == 'Ocupada' ? '#FFF1F0' : 
                                      ($bed->status == 'Limpieza' ? '#FFF5EB' : '#F4F6F8')),
                    ];
                }),
                'floors' => $floors->keys()->sort()->values(),
                'stats' => $stats,
                'floor_names' => [
                    1 => 'Urgencias',
                    2 => 'UCI',
                    3 => 'Pediatría',
                    4 => 'Quirófanos',
                ]
            ]
        ]);
    } catch (\Exception $e) {
        Log::error('Error in apiGetBeds: ' . $e->getMessage());
        return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
    }
}

/**
 * Crear nueva cama
 */
public function apiStoreBed(Request $request)
{
    try {
        Log::info('AdminController::apiStoreBed called');
        
        $request->validate([
            'floor' => 'required|integer',
            'room_number' => 'required|string',
            'bed_number' => 'required|string',
            'type' => 'nullable|string'
        ]);
        
        $id = DB::table('beds')->insertGetId([
            'floor' => $request->floor,
            'room_number' => $request->room_number,
            'bed_number' => $request->bed_number,
            'type' => $request->type ?? 'General',
            'status' => 'Disponible',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
        Log::info('Bed created: ' . $id);
        
        return response()->json([
            'success' => true,
            'message' => 'Cama registrada correctamente',
            'data' => ['id' => $id]
        ]);
    } catch (\Exception $e) {
        Log::error('Error in apiStoreBed: ' . $e->getMessage());
        return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
    }
}

/**
 * Actualizar estado de cama
 */
public function apiUpdateBedStatus(Request $request, $id)
{
    try {
        Log::info('AdminController::apiUpdateBedStatus called for id: ' . $id);
        
        $request->validate([
            'status' => 'required|string|in:Disponible,Ocupada,Limpieza,Mantenimiento'
        ]);
        
        $updated = DB::table('beds')
            ->where('id', $id)
            ->update([
                'status' => $request->status,
                'updated_at' => now()
            ]);
        
        if ($updated) {
            return response()->json([
                'success' => true,
                'message' => 'Estado actualizado correctamente'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'error' => 'Cama no encontrada'
            ], 404);
        }
    } catch (\Exception $e) {
        Log::error('Error in apiUpdateBedStatus: ' . $e->getMessage());
        return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
    }
}

/**
 * Eliminar cama
 */
public function apiDeleteBed($id)
{
    try {
        Log::info('AdminController::apiDeleteBed called for id: ' . $id);
        
        $deleted = DB::table('beds')->where('id', $id)->delete();
        
        if ($deleted) {
            return response()->json([
                'success' => true,
                'message' => 'Cama eliminada correctamente'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'error' => 'Cama no encontrada'
            ], 404);
        }
    } catch (\Exception $e) {
        Log::error('Error in apiDeleteBed: ' . $e->getMessage());
        return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
    }
}

/**
 * Obtener lista de ambulancias
 */
public function apiGetAmbulances()
{
    try {
        Log::info('AdminController::apiGetAmbulances called');
        
        $ambulances = DB::table('ambulances')
            ->orderBy('status')
            ->orderBy('priority', 'desc')
            ->get();
        
        $disponibles = $ambulances->where('status', 'Disponible')->count();
        $activas = $ambulances->where('status', 'En Ruta')->count();
        $criticas = $ambulances->where('priority', 'Critica')->count();
        $total = $ambulances->count();
        $costoOperativo = $activas * 2500;
        
        return response()->json([
            'success' => true,
            'data' => [
                'ambulances' => $ambulances->map(function($amb) {
                    return [
                        'id' => $amb->id,
                        'code' => $amb->code ?? 'AMB-' . str_pad($amb->id, 3, '0', STR_PAD_LEFT),
                        'status' => $amb->status,
                        'priority' => $amb->priority ?? 'Normal',
                        'current_location' => $amb->current_location ?? 'Base',
                        'destination' => $amb->destination ?? '-',
                        'status_color' => $amb->status === 'Disponible' ? '#16A34A' : 
                                        ($amb->status === 'En Ruta' ? '#EA580C' : '#DC2626'),
                        'status_bg' => $amb->status === 'Disponible' ? '#F0FDF4' : 
                                      ($amb->status === 'En Ruta' ? '#FFF7ED' : '#FEF2F2'),
                        'cost' => $amb->status === 'En Ruta' ? 2500 : 0,
                    ];
                }),
                'stats' => [
                    'disponibles' => $disponibles,
                    'activas' => $activas,
                    'criticas' => $criticas,
                    'total' => $total,
                    'costo_operativo' => $costoOperativo,
                    'tasa_uso' => $total > 0 ? round(($activas / $total) * 100) : 0,
                ]
            ]
        ]);
    } catch (\Exception $e) {
        Log::error('Error in apiGetAmbulances: ' . $e->getMessage());
        return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
    }
}

/**
 * Crear nueva ambulancia
 */
public function apiStoreAmbulance(Request $request)
{
    try {
        Log::info('AdminController::apiStoreAmbulance called');
        
        $request->validate([
            'code' => 'nullable|string|unique:ambulances,code',
            'status' => 'required|in:Disponible,En Ruta,En Mantenimiento',
            'priority' => 'nullable|in:Normal,Critica',
            'current_location' => 'nullable|string',
            'destination' => 'nullable|string',
        ]);
        
        $id = DB::table('ambulances')->insertGetId([
            'code' => $request->code ?? 'AMB-' . str_pad(DB::table('ambulances')->count() + 1, 3, '0', STR_PAD_LEFT),
            'status' => $request->status,
            'priority' => $request->priority ?? 'Normal',
            'current_location' => $request->current_location ?? 'Base',
            'destination' => $request->destination,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
        Log::info('Ambulance created: ' . $id);
        
        return response()->json([
            'success' => true,
            'message' => 'Ambulancia registrada correctamente',
            'data' => ['id' => $id]
        ]);
    } catch (\Exception $e) {
        Log::error('Error in apiStoreAmbulance: ' . $e->getMessage());
        return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
    }
}

/**
 * Actualizar estado de ambulancia
 */
public function apiUpdateAmbulanceStatus(Request $request, $id)
{
    try {
        Log::info('AdminController::apiUpdateAmbulanceStatus called for id: ' . $id);
        
        $request->validate([
            'status' => 'required|in:Disponible,En Ruta,En Mantenimiento'
        ]);
        
        $updated = DB::table('ambulances')
            ->where('id', $id)
            ->update([
                'status' => $request->status,
                'updated_at' => now()
            ]);
        
        if ($updated) {
            return response()->json([
                'success' => true,
                'message' => 'Estado actualizado correctamente'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'error' => 'Ambulancia no encontrada'
            ], 404);
        }
    } catch (\Exception $e) {
        Log::error('Error in apiUpdateAmbulanceStatus: ' . $e->getMessage());
        return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
    }
}

/**
 * Eliminar ambulancia
 */
public function apiDeleteAmbulance($id)
{
    try {
        Log::info('AdminController::apiDeleteAmbulance called for id: ' . $id);
        
        $deleted = DB::table('ambulances')->where('id', $id)->delete();
        
        if ($deleted) {
            return response()->json([
                'success' => true,
                'message' => 'Ambulancia eliminada correctamente'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'error' => 'Ambulancia no encontrada'
            ], 404);
        }
    } catch (\Exception $e) {
        Log::error('Error in apiDeleteAmbulance: ' . $e->getMessage());
        return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
    }
}
/**
 * Obtener datos de Hospital Live
 */
public function apiHospitalLive()
{
    try {
        Log::info('AdminController::apiHospitalLive called');
        
        // Métricas principales
        $hospitalizados = DB::table('triages')->where('status', 'Hospitalizado')->count();
        $enAtencion = DB::table('triages')->where('status', 'En Atencion')->count();
        $enEspera = DB::table('triages')->where('status', 'En Espera')->count();
        $criticos = DB::table('triages')->where('triage_level', 'Rojo')->count();
        $ambulancias = DB::table('ambulances')->where('status', 'En Ruta')->count();
        $camasLibres = DB::table('beds')->where('status', 'Disponible')->count();
        
        $metricas = [
            ['label' => 'En Espera', 'valor' => $enEspera, 'color' => '#DC2626'],
            ['label' => 'En Atencion', 'valor' => $enAtencion, 'color' => '#EA580C'],
            ['label' => 'Hospitalizados', 'valor' => $hospitalizados, 'color' => '#F97316'],
            ['label' => 'Criticos', 'valor' => $criticos, 'color' => '#C7291C'],
            ['label' => 'Ambulancias', 'valor' => $ambulancias, 'color' => '#7C3AED'],
            ['label' => 'Camas Libres', 'valor' => $camasLibres, 'color' => '#16A34A'],
        ];
        
        // Áreas de saturación
        $areas = [
            [
                'name' => 'Urgencias',
                'pacientes' => $enEspera,
                'capacidad' => 30,
                'color' => '#DC2626',
                'status_color' => '#DC2626',
                'border' => '#DC2626',
                'bg' => '#FEF2F2'
            ],
            [
                'name' => 'Hospitalizacion',
                'pacientes' => $hospitalizados,
                'capacidad' => 50,
                'color' => '#EA580C',
                'status_color' => '#EA580C',
                'border' => '#EA580C',
                'bg' => '#FFF7ED'
            ],
            [
                'name' => 'Consultas',
                'pacientes' => $enAtencion,
                'capacidad' => 20,
                'color' => '#F97316',
                'status_color' => '#F97316',
                'border' => '#F97316',
                'bg' => '#FFF7ED'
            ],
            [
                'name' => 'UCI',
                'pacientes' => $criticos,
                'capacidad' => 10,
                'color' => '#C7291C',
                'status_color' => '#C7291C',
                'border' => '#C7291C',
                'bg' => '#FEF2F2'
            ],
            [
                'name' => 'Ambulancias',
                'pacientes' => $ambulancias,
                'capacidad' => 8,
                'color' => '#7C3AED',
                'status_color' => '#7C3AED',
                'border' => '#7C3AED',
                'bg' => '#F5F3FF'
            ],
        ];
        
        // Calcular porcentajes y estados
        foreach ($areas as &$area) {
            $area['pct'] = $area['capacidad'] > 0 ? round(($area['pacientes'] / $area['capacidad']) * 100) : 0;
            $area['pct'] = min($area['pct'], 100);
            $area['status'] = $area['pct'] > 90 ? 'CRITICO' : ($area['pct'] > 70 ? 'ALERTA' : 'NORMAL');
            $area['status_color'] = $area['pct'] > 90 ? '#DC2626' : ($area['pct'] > 70 ? '#F59E0B' : '#16A34A');
            $area['border'] = $area['pct'] > 90 ? '#DC2626' : ($area['pct'] > 70 ? '#F59E0B' : '#16A34A');
            $area['bg'] = $area['pct'] > 90 ? '#FEF2F2' : ($area['pct'] > 70 ? '#FFFBEB' : '#F0FDF4');
        }
        
        // Modo crisis
        $modoCrisis = false;
        $criticalAreas = array_filter($areas, function($a) { return $a['status'] === 'CRITICO'; });
        if (count($criticalAreas) >= 2) {
            $modoCrisis = true;
        }
        
        // ✅ EVENTOS DESDE MONGODB - Usando el modelo AuditLog
        $eventos = AuditLog::orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function($e) {
                return [
                    'id' => (string) $e->_id,
                    'action' => $e->action ?? 'Evento',
                    'details' => $e->details ?? '',
                    'user_name' => $e->user_name ?? 'Sistema',
                    'created_at' => $e->created_at,
                    'time' => $e->created_at ? date('H:i', strtotime($e->created_at)) : 'N/A',
                ];
            });
        
        return response()->json([
            'success' => true,
            'data' => [
                'modo_crisis' => $modoCrisis,
                'metricas' => $metricas,
                'areas' => $areas,
                'eventos' => $eventos,
            ]
        ]);
    } catch (\Exception $e) {
        Log::error('Error in apiHospitalLive: ' . $e->getMessage());
        Log::error('Stack trace: ' . $e->getTraceAsString());
        return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
    }
}
/**
 * Verificar PIN de finanzas
 */
public function apiFinanzasVerifyPin(Request $request)
{
    try {
        Log::info('AdminController::apiFinanzasVerifyPin called');
        
        $request->validate([
            'pin' => 'required|string|min:4'
        ]);
        
        $user = auth()->user();
        
        // PIN por defecto '1234'
        $validPin = $user->finance_pin ?? '1234';
        
        if ($request->pin === $validPin) {
            // Generar un token temporal para finanzas
            $financeToken = bin2hex(random_bytes(32));
            
            // Guardar en el usuario
            $user->finance_token = $financeToken;
            $user->save();
            
            return response()->json([
                'success' => true,
                'message' => 'PIN verificado correctamente',
                'finance_token' => $financeToken
            ]);
        }
        
        return response()->json([
            'success' => false,
            'error' => 'PIN incorrecto'
        ], 401);
    } catch (\Exception $e) {
        Log::error('Error in apiFinanzasVerifyPin: ' . $e->getMessage());
        return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
    }
}

/**
 * Obtener dashboard de finanzas
 */
public function apiFinanzasDashboard(Request $request)
{
    try {
        Log::info('AdminController::apiFinanzasDashboard called');
        
        $user = auth()->user();
        
        // Verificar token de finanzas en el header
        $financeToken = $request->header('X-Finance-Token');
        
        if (!$financeToken || $financeToken !== ($user->finance_token ?? null)) {
            return response()->json([
                'success' => false,
                'requires_pin' => true,
                'message' => 'Se requiere verificación PIN'
            ], 401);
        }
        
        // === INGRESOS POR ÁREA ===
        $ingresosUrgencias = DB::table('invoices')->where('concept', 'Consulta Urgencias')->sum('amount');
        $ingresosCirugia = DB::table('invoices')->where('concept', 'Cirugia')->sum('amount');
        $ingresosHospitalizacion = DB::table('invoices')->where('concept', 'like', '%Hospitalizacion%')->sum('amount');
        $ingresosFarmacia = DB::table('invoices')->where('concept', 'Medicamentos')->sum('amount');
        $ingresosEstudios = DB::table('invoices')->whereIn('concept', ['Estudio Laboratorio','Rayos X','Tomografia','Estudios'])->sum('amount');
        $ingresosUCI = DB::table('invoices')->where('concept', 'UCI')->sum('amount');

        $paid = DB::table('invoices')->where('status', 'Pagado')->sum('amount');
        $pending = DB::table('invoices')->where('status', 'Pendiente')->sum('amount');
        $insurance = DB::table('invoices')->where('status', 'Seguro')->sum('amount');
        $vencido = DB::table('invoices')->where('status', 'Vencido')->sum('amount');
        $total = DB::table('invoices')->sum('amount');
        $pharma_value = DB::table('medications')->selectRaw('SUM(stock * price) as total')->value('total') ?? 0;

        // === INGRESOS DIARIOS ===
        $ingresosDiarios = DB::table('invoices')
            ->selectRaw('DATE(created_at) as fecha, SUM(amount) as total, COUNT(*) as qty')
            ->where('created_at', '>=', now()->subDays(7))
            ->groupByRaw('DATE(created_at)')
            ->orderByDesc('fecha')
            ->get();

        // === SEGUROS ===
        $segurosPorProveedor = DB::table('insurances')
            ->select('provider', DB::raw('COUNT(*) as total'), DB::raw('SUM(CASE WHEN status="Vigente" THEN 1 ELSE 0 END) as vigentes'))
            ->groupBy('provider')->get();

        $polizasFalsas = DB::table('insurances')->where('status', 'Falsa/Fraude')->count();
        $sinCobertura = DB::table('insurances')->where('status', 'Sin Cobertura')->count();
        $segurosVencidos = DB::table('insurances')->where('status', 'Vencida')->count();

        // === DETECCIÓN DE FRAUDE ===
        $cobrosDuplicados = DB::table('invoices')
            ->select('patient_name', 'concept', 'amount', DB::raw('COUNT(*) as qty'))
            ->groupBy('patient_name', 'concept', 'amount')
            ->havingRaw('COUNT(*) > 1')
            ->orderByDesc('qty')
            ->limit(10)->get();

        $gastosSospechosos = DB::table('invoices')
            ->where('amount', '>', 50000)
            ->orderByDesc('amount')
            ->limit(10)->get();

        // === ÚLTIMAS FACTURAS ===
        $invoices = DB::table('invoices')->orderBy('created_at', 'desc')->limit(20)->get();

        // === TOP COSTOS ===
        $topInvoices = DB::table('invoices')
            ->select('concept', DB::raw('SUM(amount) as total'), DB::raw('COUNT(*) as qty'))
            ->groupBy('concept')->orderByDesc('total')->get();

        // === PACIENTES CON DEUDA ===
        $pacientesDeuda = DB::table('invoices')
            ->select('patient_name', DB::raw('SUM(amount) as deuda'), DB::raw('COUNT(*) as facturas'))
            ->where('status', 'Pendiente')
            ->groupBy('patient_name')
            ->orderByDesc('deuda')
            ->limit(15)->get();

        // === COSTO POR PACIENTE ===
        $costoPorPaciente = DB::table('hospitalizations')
            ->join('triages', 'hospitalizations.triage_id', '=', 'triages.id')
            ->select('triages.patient_name', 'hospitalizations.admission_date', 'hospitalizations.discharge_date',
                DB::raw('DATEDIFF(COALESCE(hospitalizations.discharge_date, NOW()), hospitalizations.admission_date) as dias'))
            ->orderByDesc('dias')
            ->limit(15)->get();

        // === APROBACIONES ===
        $cirugiasCostosas = DB::table('invoices')
            ->where('concept', 'Cirugia')->where('status', 'Pendiente')
            ->where('amount', '>', 20000)->count();

        $medsCaros = DB::table('prescriptions')
            ->join('medications', 'prescriptions.medication_id', '=', 'medications.id')
            ->where('prescriptions.status', 'Pendiente')
            ->where('medications.price', '>', 500)
            ->count();

        // === FARMACIA COSTOSA ===
        $farmaciaCostosa = DB::table('medications')
            ->where('price', '>', 100)
            ->orderByDesc('price')
            ->limit(10)->get();

        // Score de riesgo
        $riskScore = $pending > ($paid * 0.5) ? 'ALTO RIESGO' : ($pending > ($paid * 0.25) ? 'MODERADO' : 'ESTABLE');

        return response()->json([
            'success' => true,
            'data' => [
                'kpis' => [
                    'paid' => $paid,
                    'pending' => $pending,
                    'insurance' => $insurance,
                    'vencido' => $vencido,
                    'total' => $total,
                    'pharma_value' => $pharma_value,
                ],
                'ingresos_por_area' => [
                    'urgencias' => $ingresosUrgencias,
                    'cirugia' => $ingresosCirugia,
                    'hospitalizacion' => $ingresosHospitalizacion,
                    'farmacia' => $ingresosFarmacia,
                    'estudios' => $ingresosEstudios,
                    'uci' => $ingresosUCI,
                ],
                'ingresos_diarios' => $ingresosDiarios,
                'seguros_por_proveedor' => $segurosPorProveedor,
                'alertas_seguros' => [
                    'polizas_falsas' => $polizasFalsas,
                    'sin_cobertura' => $sinCobertura,
                    'seguros_vencidos' => $segurosVencidos,
                ],
                'fraude' => [
                    'cobros_duplicados' => $cobrosDuplicados,
                    'gastos_sospechosos' => $gastosSospechosos,
                    'pacientes_deuda' => $pacientesDeuda,
                ],
                'costos' => [
                    'costo_por_paciente' => $costoPorPaciente,
                    'farmacia_costosa' => $farmaciaCostosa,
                ],
                'aprobaciones' => [
                    'cirugias_costosas' => $cirugiasCostosas,
                    'meds_caros' => $medsCaros,
                ],
                'top_conceptos' => $topInvoices,
                'invoices' => $invoices,
                'risk_score' => $riskScore,
                'risk_color' => $riskScore === 'ESTABLE' ? '#065F46' : ($riskScore === 'MODERADO' ? '#92400E' : '#991B1B'),
                'risk_bg' => $riskScore === 'ESTABLE' ? '#D1FAE5' : ($riskScore === 'MODERADO' ? '#FEF3C7' : '#FEE2E2'),
            ]
        ]);
    } catch (\Exception $e) {
        Log::error('Error in apiFinanzasDashboard: ' . $e->getMessage());
        return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
    }
}

/**
 * Crear nueva factura
 */
public function apiFinanzasStoreInvoice(Request $request)
{
    try {
        Log::info('AdminController::apiFinanzasStoreInvoice called');
        
        $request->validate([
            'patient_name' => 'required|string',
            'concept' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'status' => 'required|in:Pagado,Pendiente,Seguro,Vencido'
        ]);
        
        $id = DB::table('invoices')->insertGetId([
            'patient_name' => $request->patient_name,
            'concept' => $request->concept,
            'amount' => $request->amount,
            'status' => $request->status,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
        // Registrar en auditoría
        AuditLog::create([
            'user_id' => auth()->id(),
            'user_name' => auth()->user()->name,
            'user_role' => auth()->user()->role,
            'action' => 'FACTURA CREADA',
            'module' => 'Finanzas',
            'ip_address' => $request->ip(),
            'details' => "Paciente: {$request->patient_name} | Concepto: {$request->concept} | Monto: \${$request->amount} | Estado: {$request->status}",
            'is_suspicious' => $request->amount > 50000,
            'risk_reason' => $request->amount > 50000 ? 'Factura de alto valor' : null,
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Factura creada correctamente',
            'data' => ['id' => $id]
        ]);
    } catch (\Exception $e) {
        Log::error('Error in apiFinanzasStoreInvoice: ' . $e->getMessage());
        return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
    }
}

/**
 * Actualizar estado de factura
 */
public function apiFinanzasUpdateInvoiceStatus(Request $request, $id)
{
    try {
        Log::info('AdminController::apiFinanzasUpdateInvoiceStatus called for id: ' . $id);
        
        $request->validate([
            'status' => 'required|in:Pagado,Pendiente,Seguro,Vencido'
        ]);
        
        $updated = DB::table('invoices')
            ->where('id', $id)
            ->update([
                'status' => $request->status,
                'updated_at' => now()
            ]);
        
        if ($updated) {
            return response()->json([
                'success' => true,
                'message' => 'Estado actualizado correctamente'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'error' => 'Factura no encontrada'
            ], 404);
        }
    } catch (\Exception $e) {
        Log::error('Error in apiFinanzasUpdateInvoiceStatus: ' . $e->getMessage());
        return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
    }
}

/**
 * Eliminar factura
 */
public function apiFinanzasDeleteInvoice($id)
{
    try {
        Log::info('AdminController::apiFinanzasDeleteInvoice called for id: ' . $id);
        
        $deleted = DB::table('invoices')->where('id', $id)->delete();
        
        if ($deleted) {
            return response()->json([
                'success' => true,
                'message' => 'Factura eliminada correctamente'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'error' => 'Factura no encontrada'
            ], 404);
        }
    } catch (\Exception $e) {
        Log::error('Error in apiFinanzasDeleteInvoice: ' . $e->getMessage());
        return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
    }
}

/**
 * Crear nuevo seguro
 */
public function apiFinanzasStoreInsurance(Request $request)
{
    try {
        Log::info('AdminController::apiFinanzasStoreInsurance called');
        
        $request->validate([
            'patient_name' => 'required|string',
            'policy_number' => 'required|string',
            'provider' => 'required|string',
            'status' => 'required|in:Vigente,Vencida,Sin Cobertura,Falsa/Fraude'
        ]);
        
        $id = DB::table('insurances')->insertGetId([
            'patient_name' => $request->patient_name,
            'policy_number' => $request->policy_number,
            'provider' => $request->provider,
            'status' => $request->status,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
        AuditLog::create([
            'user_id' => auth()->id(),
            'user_name' => auth()->user()->name,
            'user_role' => auth()->user()->role,
            'action' => 'SEGURO REGISTRADO',
            'module' => 'Finanzas',
            'ip_address' => $request->ip(),
            'details' => "Paciente: {$request->patient_name} | Poliza: {$request->policy_number} | Proveedor: {$request->provider} | Estado: {$request->status}",
            'is_suspicious' => $request->status === 'Falsa/Fraude',
            'risk_reason' => $request->status === 'Falsa/Fraude' ? 'Poliza marcada como fraude' : null,
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Seguro registrado correctamente',
            'data' => ['id' => $id]
        ]);
    } catch (\Exception $e) {
        Log::error('Error in apiFinanzasStoreInsurance: ' . $e->getMessage());
        return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
    }
}

/**
 * Actualizar estado de seguro
 */
public function apiFinanzasUpdateInsuranceStatus(Request $request, $id)
{
    try {
        Log::info('AdminController::apiFinanzasUpdateInsuranceStatus called for id: ' . $id);
        
        $request->validate([
            'status' => 'required|in:Vigente,Vencida,Sin Cobertura,Falsa/Fraude'
        ]);
        
        $updated = DB::table('insurances')
            ->where('id', $id)
            ->update([
                'status' => $request->status,
                'updated_at' => now()
            ]);
        
        if ($updated) {
            return response()->json([
                'success' => true,
                'message' => 'Estado actualizado correctamente'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'error' => 'Seguro no encontrado'
            ], 404);
        }
    } catch (\Exception $e) {
        Log::error('Error in apiFinanzasUpdateInsuranceStatus: ' . $e->getMessage());
        return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
    }
}

/**
 * Eliminar seguro
 */
public function apiFinanzasDeleteInsurance($id)
{
    try {
        Log::info('AdminController::apiFinanzasDeleteInsurance called for id: ' . $id);
        
        $deleted = DB::table('insurances')->where('id', $id)->delete();
        
        if ($deleted) {
            return response()->json([
                'success' => true,
                'message' => 'Seguro eliminado correctamente'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'error' => 'Seguro no encontrado'
            ], 404);
        }
    } catch (\Exception $e) {
        Log::error('Error in apiFinanzasDeleteInsurance: ' . $e->getMessage());
        return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
    }
}
/**
 * Obtener dashboard de auditoría
 */
public function apiAuditoriaDashboard(Request $request)
{
    try {
        Log::info('AdminController::apiAuditoriaDashboard called');
        
        // ==========================================
        // LOGS PARA TIMELINE
        // ==========================================
        $logs = AuditLog::orderBy('created_at', 'desc')
            ->limit(50)
            ->get()
            ->map(function($l) {
                return [
                    'id' => (string) $l->_id,
                    'action' => $l->action,
                    'user_name' => $l->user_name,
                    'user_role' => $l->user_role,
                    'module' => $l->module,
                    'details' => $l->details,
                    'ip_address' => $l->ip_address,
                    'is_suspicious' => $l->is_suspicious,
                    'risk_level' => $l->risk_level ?? 'bajo',
                    'risk_reason' => $l->risk_reason,
                    'created_at' => $l->created_at,
                ];
            });
        
        // ==========================================
        // STATS PRINCIPALES
        // ==========================================
        $stats = [
            'total' => AuditLog::count(),
            'today' => AuditLog::where('created_at', '>=', now()->startOfDay())->count(),
            'suspicious' => AuditLog::where('is_suspicious', true)->count(),
            'critical' => AuditLog::where('risk_level', 'critico')->count(),
            'high' => AuditLog::where('risk_level', 'alto')->count(),
            'modules' => AuditLog::distinct('module')->count(),
            'users_active' => AuditLog::where('created_at', '>=', now()->startOfDay())->distinct('user_id')->count(),
        ];
        
        // ==========================================
        // ALERTAS RECIENTES
        // ==========================================
        $alerts = AuditLog::where('is_suspicious', true)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function($a) {
                return [
                    'id' => (string) $a->_id,
                    'action' => $a->action,
                    'user_name' => $a->user_name,
                    'module' => $a->module,
                    'risk_level' => $a->risk_level ?? 'bajo',
                    'risk_reason' => $a->risk_reason,
                    'created_at' => $a->created_at,
                ];
            });
        
        // ==========================================
        // ACTIVIDAD 24H
        // ==========================================
        $hourlyData = collect(range(0,23))->mapWithKeys(function($h) { return [$h => 0]; });
        $hourlyRaw = AuditLog::where('created_at', '>=', now()->subHours(24))->get()
            ->groupBy(function($item) { return (int)date('G', strtotime($item->created_at)); })
            ->map(function($items) { return $items->count(); });
        foreach($hourlyRaw as $h => $cnt) { $hourlyData[$h] = $cnt; }
        
        // ==========================================
        // TOP ACCIONES
        // ==========================================
        $topActions = AuditLog::where('created_at', '>=', now()->subDays(7))->get()
            ->groupBy('action')->map(function($items, $key) { 
                return ['action' => $key, 'total' => $items->count()]; 
            })->sortByDesc('total')->take(10)->values();
        
        // ==========================================
        // POR MODULO
        // ==========================================
        $byModule = AuditLog::where('created_at', '>=', now()->subDays(7))->get()
            ->groupBy('module')->map(function($items, $key) { 
                return [
                    'module' => $key, 
                    'total' => $items->count(), 
                    'suspicious' => $items->where('is_suspicious', true)->count()
                ]; 
            })->sortByDesc('total')->values();
        
        // ==========================================
        // TOP USUARIOS
        // ==========================================
        $topUsers = AuditLog::where('created_at', '>=', now()->subDays(7))->get()
            ->groupBy(function($item) { return $item->user_name.'|'.$item->user_role; })
            ->map(function($items, $key) {
                $parts = explode('|', $key);
                return [
                    'user_name' => $parts[0], 
                    'user_role' => $parts[1] ?? 'N/A', 
                    'total' => $items->count(), 
                    'suspicious' => $items->where('is_suspicious', true)->count()
                ];
            })->sortByDesc('total')->take(10)->values();
        
        // ==========================================
        // ACCESOS
        // ==========================================
        $accesos = AuditLog::whereIn('action', ['LOGIN','LOGIN FALLIDO','LOGOUT','Sesion Bloqueada','PIN Incorrecto','Acceso No Autorizado','Intento Fuerza Bruta'])
            ->orderBy('created_at','desc')
            ->limit(50)
            ->get()
            ->map(function($a) {
                return [
                    'id' => (string) $a->_id,
                    'action' => $a->action,
                    'user_name' => $a->user_name,
                    'ip_address' => $a->ip_address,
                    'user_agent' => $a->user_agent,
                    'created_at' => $a->created_at,
                ];
            });
        
        $loginExitoso = AuditLog::where('action','LOGIN')->where('created_at','>=',now()->subDays(7))->count();
        $loginFallido = AuditLog::where('action','LOGIN FALLIDO')->where('created_at','>=',now()->subDays(7))->count();
        $bloqueos = AuditLog::whereIn('action',['Sesion Bloqueada','Intento Fuerza Bruta'])->where('created_at','>=',now()->subDays(7))->count();
        
        // ==========================================
        // AUDITORIA MEDICA
        // ==========================================
        $medicaLogs = AuditLog::where('module','Medico')->orderBy('created_at','desc')->limit(40)->get()
            ->map(function($l) {
                return [
                    'id' => (string) $l->_id,
                    'action' => $l->action,
                    'user_name' => $l->user_name,
                    'patient_name' => $l->patient_name,
                    'details' => $l->details,
                    'created_at' => $l->created_at,
                ];
            });
        $recetas = AuditLog::where('action','Receta Medica')->where('created_at','>=',now()->subDays(7))->count();
        $cirugias = AuditLog::where('action','Cirugia Programada')->where('created_at','>=',now()->subDays(7))->count();
        $defunciones = AuditLog::where('action','Certificado Defuncion')->count();
        
        // ==========================================
        // AUDITORIA HOSPITALIZACION
        // ==========================================
        $hospLogs = AuditLog::where('module','Hospitalizacion')->orderBy('created_at','desc')->limit(40)->get()
            ->map(function($l) {
                return [
                    'id' => (string) $l->_id,
                    'action' => $l->action,
                    'user_name' => $l->user_name,
                    'patient_name' => $l->patient_name,
                    'details' => $l->details,
                    'created_at' => $l->created_at,
                ];
            });
        $ingresos = AuditLog::where('action','Paciente Hospitalizado')->where('created_at','>=',now()->subDays(7))->count();
        $altas = AuditLog::where('action','Alta Medica')->where('created_at','>=',now()->subDays(7))->count();
        $traslados = AuditLog::where('action','Traslado')->where('created_at','>=',now()->subDays(7))->count();
        
        // ==========================================
        // AUDITORIA FINANZAS
        // ==========================================
        $finLogs = AuditLog::where('module','Finanzas')->orderBy('created_at','desc')->limit(40)->get()
            ->map(function($l) {
                return [
                    'id' => (string) $l->_id,
                    'action' => $l->action,
                    'user_name' => $l->user_name,
                    'details' => $l->details,
                    'is_suspicious' => $l->is_suspicious,
                    'risk_level' => $l->risk_level ?? 'bajo',
                    'created_at' => $l->created_at,
                ];
            });
        
        // ==========================================
        // AUDITORIA FARMACIA
        // ==========================================
        $pharmaLogs = AuditLog::where('module','Farmacia')->orderBy('created_at','desc')->limit(40)->get()
            ->map(function($l) {
                return [
                    'id' => (string) $l->_id,
                    'action' => $l->action,
                    'user_name' => $l->user_name,
                    'details' => $l->details,
                    'created_at' => $l->created_at,
                ];
            });
        $controlados = AuditLog::where('module','Farmacia')->where('action','like','%Controlado%')->orderBy('created_at','desc')->limit(20)->get()
            ->map(function($l) {
                return [
                    'id' => (string) $l->_id,
                    'action' => $l->action,
                    'user_name' => $l->user_name,
                    'details' => $l->details,
                    'created_at' => $l->created_at,
                ];
            });
        
        // ==========================================
        // USUARIOS DE RIESGO
        // ==========================================
        $riskUsers = AuditLog::where('created_at','>=',now()->subDays(30))
            ->where(function($q) { $q->where('is_suspicious', true)->orWhereIn('risk_level', ['alto','critico']); })
            ->get()
            ->groupBy(function($item) { return $item->user_name.'|'.$item->user_role; })
            ->map(function($items, $key) {
                $parts = explode('|', $key);
                return [
                    'user_name' => $parts[0],
                    'user_role' => $parts[1] ?? 'N/A',
                    'total_actions' => $items->count(),
                    'suspicious' => $items->where('is_suspicious', true)->count(),
                    'critical' => $items->where('risk_level', 'critico')->count(),
                    'high' => $items->where('risk_level', 'alto')->count(),
                    'active_days' => $items->groupBy(function($i) { return date('Y-m-d', strtotime($i->created_at)); })->count(),
                ];
            })->sortByDesc('critical')->take(15)->values();
        
        // ==========================================
        // TOP USUARIOS ALL
        // ==========================================
        $topUsersAll = AuditLog::where('created_at','>=',now()->subDays(30))->get()
            ->groupBy('user_name')
            ->map(function($items, $key) {
                $first = $items->first();
                return [
                    'user_name' => $key,
                    'user_role' => $first->user_role ?? 'N/A',
                    'items' => $items,
                ];
            })->take(10);
        
        // ==========================================
        // ANOMALIAS IA
        // ==========================================
        $anomalias = collect();
        
        // Fuerza bruta
        $ipsFallidas = AuditLog::where('action','LOGIN FALLIDO')->where('created_at','>=',now()->subHours(24))
            ->get()->groupBy('ip_address')->filter(function($g){ return $g->count() >= 3; });
        foreach($ipsFallidas as $ip => $items) {
            $anomalias->push((object)[
                'tipo'=>'Fuerza Bruta',
                'severity'=>'critico',
                'icon'=>'fa-hammer',
                'desc'=>"IP $ip con ".$items->count()." logins fallidos en 24h",
                'module'=>'Seguridad'
            ]);
        }
        
        // Acceso nocturno
        $fueraHorario = AuditLog::where('action','LOGIN')->where('created_at','>=',now()->subDays(7))
            ->get()->filter(function($l){ $h = (int)date('G', strtotime($l->created_at)); return $h < 6 || $h > 22; })
            ->groupBy('user_name');
        foreach($fueraHorario as $user => $items) {
            $anomalias->push((object)[
                'tipo'=>'Acceso Nocturno',
                'severity'=>'alto',
                'icon'=>'fa-moon',
                'desc'=>"$user accedio fuera de horario ".$items->count()." veces",
                'module'=>'Seguridad'
            ]);
        }
        
        // ==========================================
        // DISTRIBUCION DE RIESGO
        // ==========================================
        $riskDist = AuditLog::where('created_at','>=',now()->subDays(30))
            ->get()->groupBy('risk_level')->map(function($g){ return $g->count(); });
        
        $riskAreas = AuditLog::where('created_at','>=',now()->subDays(30))
            ->get()->groupBy('module')
            ->map(function($items,$key){ 
                return (object)[
                    'module'=>$key,
                    'total'=>$items->count(),
                    'suspicious'=>$items->where('is_suspicious',true)->count(),
                    'critical'=>$items->where('risk_level','critico')->count()
                ]; 
            })->sortByDesc('suspicious')->take(10)->values();
        
        // ==========================================
        // NEGLIGENCIA
        // ==========================================
        $negligencia = DB::table('triages')
            ->whereIn('status', ['En Espera','En Atencion'])
            ->where('created_at', '<', now()->subHours(2))
            ->orderBy('triage_level')
            ->limit(10)
            ->get()
            ->map(function($n) {
                return [
                    'id' => $n->id,
                    'patient_name' => $n->patient_name,
                    'triage_level' => $n->triage_level,
                    'status' => $n->status,
                    'assigned_area' => $n->assigned_area,
                    'created_at' => $n->created_at,
                ];
            });
        
        return response()->json([
            'success' => true,
            'data' => [
                'logs' => $logs, // ✅ AGREGADO: logs para timeline
                'stats' => $stats,
                'alerts' => $alerts,
                'hourlyData' => $hourlyData,
                'topActions' => $topActions,
                'byModule' => $byModule,
                'topUsers' => $topUsers,
                'accesos' => $accesos,
                'loginExitoso' => $loginExitoso,
                'loginFallido' => $loginFallido,
                'bloqueos' => $bloqueos,
                'medicaLogs' => $medicaLogs,
                'recetas' => $recetas,
                'cirugias' => $cirugias,
                'defunciones' => $defunciones,
                'hospLogs' => $hospLogs,
                'ingresos' => $ingresos,
                'altas' => $altas,
                'traslados' => $traslados,
                'finLogs' => $finLogs,
                'pharmaLogs' => $pharmaLogs,
                'controlados' => $controlados,
                'riskUsers' => $riskUsers,
                'topUsersAll' => $topUsersAll,
                'anomalias' => $anomalias,
                'riskDist' => $riskDist,
                'riskAreas' => $riskAreas,
                'negligencia' => $negligencia,
            ]
        ]);
    } catch (\Exception $e) {
        Log::error('Error in apiAuditoriaDashboard: ' . $e->getMessage());
        Log::error('Stack trace: ' . $e->getTraceAsString());
        return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
    }
}
}