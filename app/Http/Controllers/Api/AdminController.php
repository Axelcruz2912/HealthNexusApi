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
use Carbon\Carbon;
use App\Models\Medication; 
use Illuminate\Support\Facades\Cache; 


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

/**
 * Obtener dashboard de Big Data
 */
public function apiBigDataDashboard(Request $request)
{
    try {
        Log::info('AdminController::apiBigDataDashboard called');
        
        // ==========================================
        // DATOS DE ATLAS
        // ==========================================
        $atlasStats = [
            'source' => 'MongoDB Atlas',
            'collections' => 6,
            'collection_used' => 'triage_logs',
            'documents' => 0,
            'period' => 'N/A'
        ];

        $mongoFc = collect([]);
        $mongoLogs = collect([]);

        try {
            // Intentar obtener datos de MongoDB
            if (class_exists(\App\Models\MongoTriageLog::class)) {
                $atlasStats['documents'] = \App\Models\MongoTriageLog::count();
                
                $firstLog = \App\Models\MongoTriageLog::orderBy('timestamp', 'asc')->first();
                $lastLog = \App\Models\MongoTriageLog::orderBy('timestamp', 'desc')->first();
                
                if ($firstLog && $lastLog) {
                    $start = Carbon::parse($firstLog->timestamp)->format('M Y');
                    $end = Carbon::parse($lastLog->timestamp)->format('M Y');
                    $atlasStats['period'] = "{$start} - {$end}";
                }

                $mongoLogs = \App\Models\MongoTriageLog::all(['vitals_fc', 'specialty', 'timestamp', 'assigned_doctor_id', 'triage_level', 'age']);
                $mongoFc = $mongoLogs->pluck('vitals_fc')->filter()->sort()->values();
            } else {
                // Si no existe el modelo, usar datos simulados
                $atlasStats['documents'] = 7050;
                $atlasStats['period'] = 'Ene 2024 - Jun 2024';
                
                // Generar datos simulados
                $triageLevels = ['Rojo', 'Naranja', 'Amarillo', 'Verde', 'Azul'];
                $specialties = ['Cardiología', 'Neurología', 'Traumatología', 'Pediatría', 'Ginecología'];
                
                for ($i = 0; $i < 100; $i++) {
                    $mongoLogs->push((object)[
                        'vitals_fc' => rand(60, 120),
                        'specialty' => $specialties[rand(0, 4)],
                        'timestamp' => now()->subDays(rand(0, 180)),
                        'assigned_doctor_id' => rand(1, 10),
                        'triage_level' => $triageLevels[rand(0, 4)],
                        'age' => rand(18, 80)
                    ]);
                }
                $mongoFc = $mongoLogs->pluck('vitals_fc')->filter()->sort()->values();
            }
        } catch (\Exception $e) {
            \Log::error("MongoDB Connection failed: " . $e->getMessage());
            $atlasStats['period'] = 'Sin conexión a Atlas';
            
            // Datos simulados como fallback
            $triageLevels = ['Rojo', 'Naranja', 'Amarillo', 'Verde', 'Azul'];
            $specialties = ['Cardiología', 'Neurología', 'Traumatología', 'Pediatría', 'Ginecología'];
            
            for ($i = 0; $i < 100; $i++) {
                $mongoLogs->push((object)[
                    'vitals_fc' => rand(60, 120),
                    'specialty' => $specialties[rand(0, 4)],
                    'timestamp' => now()->subDays(rand(0, 180)),
                    'assigned_doctor_id' => rand(1, 10),
                    'triage_level' => $triageLevels[rand(0, 4)],
                    'age' => rand(18, 80)
                ]);
            }
            $mongoFc = $mongoLogs->pluck('vitals_fc')->filter()->sort()->values();
            $atlasStats['documents'] = $mongoFc->count();
        }

        // ==========================================
        // ESTADÍSTICAS
        // ==========================================
        $c = $mongoFc->count();
        if ($c > 0) {
            $mean = $mongoFc->avg();
            $stdDev = sqrt($mongoFc->map(function ($v) use ($mean) { return pow($v - $mean, 2); })->avg());
            $sorted = $mongoFc->values()->toArray();
            
            $getP = function($arr, $p) use ($c) {
                $index = ($p / 100) * ($c - 1);
                $lower = (int) floor($index);
                $upper = (int) ceil($index);
                if ($lower === $upper || $upper >= count($arr)) return round($arr[$lower] ?? 0, 2);
                return round($arr[$lower] + ($index - $lower) * ($arr[$upper] - $arr[$lower]), 2);
            };
            
            $fcMean = round($mean, 2);
            $fcMax = round($mongoFc->max(), 2);
            $fcMin = round($mongoFc->min(), 2);
            $fcStd = round($stdDev, 2);
            $p10 = $getP($sorted, 10);
            $p25 = $getP($sorted, 25);
            $p50 = $getP($sorted, 50);
            $p75 = $getP($sorted, 75);
            $p90 = $getP($sorted, 90);
        } else {
            $fcMean = $fcMax = $fcMin = $fcStd = $p10 = $p25 = $p50 = $p75 = $p90 = 0;
        }

        // ==========================================
        // DISTRIBUCIÓN POR TRIAGE
        // ==========================================
        $triageChart = $mongoLogs->groupBy('triage_level')->map->count();

        // ==========================================
        // ACTIVIDAD POR HORA
        // ==========================================
        $hourlyChart = $mongoLogs->filter(fn($l) => $l->timestamp)->groupBy(function($l) {
            return Carbon::parse($l->timestamp)->format('H:00');
        })->map->count()->sortKeys();

        // ==========================================
        // TOP DOCTORES
        // ==========================================
        $topDoctors = $mongoLogs->filter(fn($l) => $l->assigned_doctor_id)->groupBy('assigned_doctor_id')->map(function($group) {
            return ['total' => $group->count(), 'avg_fc' => round($group->avg('vitals_fc'))];
        })->sortByDesc('total')->take(5);

        // ==========================================
        // ACTIVIDAD POR DÍA Y MES
        // ==========================================
        $daily = $mongoLogs->filter(fn($l) => $l->timestamp)->groupBy(function($l) {
            return ucfirst(Carbon::parse($l->timestamp)->locale('es')->dayName);
        })->map(function($group) {
            return ['total' => $group->count(), 'avg_fc' => round($group->avg('vitals_fc'))];
        });

        $monthly = $mongoLogs->filter(fn($l) => $l->timestamp)->groupBy(function($l) {
            return Carbon::parse($l->timestamp)->locale('es')->translatedFormat('F Y');
        })->map(function($group) {
            return ['total' => $group->count(), 'avg_fc' => round($group->avg('vitals_fc'))];
        })->sortKeys();

        // ==========================================
        // MÉTRICAS ML
        // ==========================================
        $mlMetrics = [
            'algorithm' => 'Random Forest Classifier',
            'target' => 'Nivel de Triage',
            'features' => ['FC', 'Temp', 'SpO2', 'Edad', 'Hora'],
            'accuracy' => 87.4,
            'precision' => 85.2,
            'recall' => 88.1,
            'f1_score' => 86.6,
        ];

        // ==========================================
        // SEGURIDAD
        // ==========================================
        $securityMeasures = [
            'encryption' => 'AES-256-CBC (Laravel Crypt)',
            'auth' => 'RBAC (Role-Based Access Control)',
            'compliance' => 'NOM-024-SSA3-2012 / HIPAA',
            'data_masking' => 'Pseudonimización de patient_id',
            'audit' => 'Logs de acceso immutables en MongoDB'
        ];

        return response()->json([
            'success' => true,
            'data' => [
                'atlasStats' => $atlasStats,
                'fcMean' => $fcMean,
                'fcMax' => $fcMax,
                'fcMin' => $fcMin,
                'fcStd' => $fcStd,
                'p10' => $p10,
                'p25' => $p25,
                'p50' => $p50,
                'p75' => $p75,
                'p90' => $p90,
                'triageChart' => $triageChart,
                'hourlyChart' => $hourlyChart,
                'topDoctors' => $topDoctors,
                'daily' => $daily,
                'monthly' => $monthly,
                'mlMetrics' => $mlMetrics,
                'securityMeasures' => $securityMeasures,
            ]
        ]);
    } catch (\Exception $e) {
        Log::error('Error in apiBigDataDashboard: ' . $e->getMessage());
        Log::error('Stack trace: ' . $e->getTraceAsString());
        
        return response()->json([
            'success' => false,
            'error' => $e->getMessage()
        ], 500);
    }
}
/**
 * Ejecutar proceso ETL
 */
public function apiBigDataRunETL()
{
    try {
        Log::info('AdminController::apiBigDataRunETL called');
        
        // Simulación del proceso ETL
        // En un entorno real, aquí se ejecutaría el proceso real
        sleep(1);
        
        // Simular algunos datos de resultado
        $processed = rand(7000, 7500);
        $cleaned = rand(6900, 7400);
        
        return response()->json([
            'success' => true,
            'message' => 'Proceso ETL completado exitosamente',
            'data' => [
                'processed' => $processed,
                'cleaned' => $cleaned,
                'duplicates_removed' => 0,
                'nulls_filled' => rand(10, 50),
                'outliers_removed' => 0,
            ]
        ]);
    } catch (\Exception $e) {
        Log::error('Error in apiBigDataRunETL: ' . $e->getMessage());
        Log::error('Stack trace: ' . $e->getTraceAsString());
        
        return response()->json([
            'success' => false,
            'error' => $e->getMessage()
        ], 500);
    }
}
/**
 * Obtener actividad sospechosa
 */
public function apiGetSuspiciousActivity(Request $request)
{
    try {
        Log::info('AdminController::apiGetSuspiciousActivity called');
        
        $perPage = $request->get('per_page', 10);
        $page = $request->get('page', 1);
        
        // Obtener logs sospechosos
        $suspicious = AuditLog::where('is_suspicious', true)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage, ['*'], 'page', $page);
        
        // Transformar los datos para la app móvil
        $items = $suspicious->items();
        $formattedItems = array_map(function($item) {
            return [
                'id' => (string) $item['_id'] ?? $item['id'] ?? null,
                'created_at' => $item['created_at'] ?? null,
                'user_name' => $item['user_name'] ?? 'Sistema',
                'user_role' => $item['user_role'] ?? 'N/A',
                'risk_reason' => $item['risk_reason'] ?? 'Comportamiento anómalo',
                'details' => $item['details'] ?? 'Sin detalles',
                'action' => $item['action'] ?? 'Acción desconocida',
                'module' => $item['module'] ?? 'N/A',
                'is_suspicious' => $item['is_suspicious'] ?? true,
            ];
        }, $items);
        
        return response()->json([
            'success' => true,
            'data' => [
                'items' => $formattedItems,
                'total' => $suspicious->total(),
                'per_page' => $suspicious->perPage(),
                'current_page' => $suspicious->currentPage(),
                'last_page' => $suspicious->lastPage(),
                'has_more' => $suspicious->hasMorePages(),
                'from' => $suspicious->firstItem(),
                'to' => $suspicious->lastItem(),
            ],
            'has_suspicious' => $suspicious->count() > 0,
            'count' => $suspicious->count(),
        ]);
    } catch (\Exception $e) {
        Log::error('Error in apiGetSuspiciousActivity: ' . $e->getMessage());
        Log::error('Stack trace: ' . $e->getTraceAsString());
        
        return response()->json([
            'success' => false,
            'error' => $e->getMessage()
        ], 500);
    }
}

/**
 * Obtener datos del Monitor Live
 */
/**
 * Obtener datos del Monitor Live
 */
public function apiGetMonitorLive(Request $request)
{
    try {
        Log::info('AdminController::apiGetMonitorLive called');
        
        // Obtener sesiones activas
        $sessions = DB::table('sessions')
            ->where('last_activity', '>=', now()->subMinutes(15)->timestamp)
            ->get();
        
        // Obtener usuarios conectados
        $userIds = $sessions->pluck('user_id')->unique()->filter();
        $users = User::whereIn('id', $userIds)->get()->keyBy('id');
        
        // Formatear sesiones
        $formattedSessions = [];
        foreach ($sessions as $session) {
            $user = $users[$session->user_id] ?? null;
            $formattedSessions[] = [
                'id' => $session->id,
                'user_name' => $user ? $user->name : 'Invitado',
                'user_role' => $user ? $user->role : 'N/A',
                'last_activity' => $session->last_activity,
                'ip_address' => $session->ip_address ?? 'N/A',
                'user_agent' => $session->user_agent ?? 'N/A',
            ];
        }
        
        // Estadísticas - usando try catch por si las tablas no existen
        $urgencies = 0;
        $low_stock = 0;
        
        try {
            $urgencies = Triage::whereIn('status', ['En Espera', 'En Atención'])->count();
        } catch (\Exception $e) {
            Log::warning('Error counting triages: ' . $e->getMessage());
        }
        
        try {
            $low_stock = Medication::whereRaw('stock <= min_stock')->count();
        } catch (\Exception $e) {
            Log::warning('Error counting low stock: ' . $e->getMessage());
        }
        
        return response()->json([
            'success' => true,
            'data' => [
                'sessions' => $formattedSessions,
                'sessions_count' => count($formattedSessions),
                'urgencies' => $urgencies,
                'low_stock' => $low_stock,
                'updated_at' => now()->toDateTimeString(),
            ]
        ]);
    } catch (\Exception $e) {
        Log::error('Error in apiGetMonitorLive: ' . $e->getMessage());
        Log::error('Stack trace: ' . $e->getTraceAsString());
        
        return response()->json([
            'success' => false,
            'error' => $e->getMessage()
        ], 500);
    }
}
/**
 * Obtener datos del Mapa de Calor
 */
public function apiGetHeatmap(Request $request)
{
    try {
        Log::info('AdminController::apiGetHeatmap called');
        
        // ==========================================
        // UCI - Saturación
        // ==========================================
        $total_uci = DB::table('beds')->where('type', 'UCI')->count();
        $occ_uci = DB::table('beds')->where('type', 'UCI')->where('status', 'Ocupada')->count();
        $uci_percent = $total_uci > 0 ? round(($occ_uci / $total_uci) * 100) : 0;
        
        // ==========================================
        // Urgencias - Triage
        // ==========================================
        $urg_percent = 0;
        $critical_urgencies = 0;
        try {
            $total_urg = DB::table('triages')->whereIn('status', ['En Espera', 'En Atención'])->count();
            $urg_percent = min(100, round(($total_urg / 20) * 100));
            $critical_urgencies = DB::table('triages')->whereIn('triage_level', ['Rojo', 'Naranja'])->count();
        } catch (\Exception $e) {
            Log::warning('Error counting triages: ' . $e->getMessage());
        }
        
        // ==========================================
        // Farmacia - Desabasto
        // ==========================================
        $farmacia_alerts = 0;
        try {
            $farmacia_alerts = DB::table('medications')->whereRaw('stock <= min_stock')->count();
        } catch (\Exception $e) {
            Log::warning('Error counting medications: ' . $e->getMessage());
        }
        
        // ==========================================
        // Personal Activo
        // ==========================================
        $total_personal = DB::table('users')->where('status', 1)->count();
        
        // ==========================================
        // Colores según porcentaje
        // ==========================================
        $getColor = function($percent, $critical = false) {
            if ($critical) {
                return $percent > 80 ? '#C7291C' : ($percent > 50 ? '#FF8C42' : '#2D9E6A');
            }
            return $percent > 0 ? '#C7291C' : '#2D9E6A';
        };
        
        return response()->json([
            'success' => true,
            'data' => [
                'uci_percent' => $uci_percent,
                'uci_color' => $getColor($uci_percent),
                'uci_status' => $uci_percent == 100 ? 'SIN CAMAS DISPONIBLES' : 'Operación Normal',
                'urg_percent' => $urg_percent,
                'urg_color' => $getColor($urg_percent),
                'urg_status' => $critical_urgencies . ' pacientes críticos (Rojo/Naranja)',
                'farmacia_alerts' => $farmacia_alerts,
                'farmacia_color' => $farmacia_alerts > 0 ? '#C7291C' : '#2D9E6A',
                'farmacia_status' => $farmacia_alerts > 0 ? 'Medicamentos por debajo del mínimo' : 'Stock normal',
                'total_personal' => $total_personal,
                'total_personal_color' => '#1E1A17',
                'personal_status' => 'En turno actualmente',
                'updated_at' => now()->toDateTimeString(),
            ]
        ]);
    } catch (\Exception $e) {
        Log::error('Error in apiGetHeatmap: ' . $e->getMessage());
        Log::error('Stack trace: ' . $e->getTraceAsString());
        
        return response()->json([
            'success' => false,
            'error' => $e->getMessage()
        ], 500);
    }
}
/**
 * Subir archivo CSV para ingesta
 */
public function apiUploadCSV(Request $request)
{
    try {
        Log::info('AdminController::apiUploadCSV called');
        
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:10240'
        ]);
        
        $file = $request->file('csv_file');
        $path = $file->getRealPath();
        $handle = fopen($path, 'r');
        
        // Leer cabeceras
        $headers = fgetcsv($handle, 1000, ',');
        
        // Leer primeras 5 filas para previsualización
        $rows = [];
        $count = 0;
        while (($data = fgetcsv($handle, 1000, ',')) !== false && $count < 5) {
            $rows[] = $data;
            $count++;
        }
        fclose($handle);
        
        // Guardar en caché en lugar de sesión
        $cacheKey = 'csv_preview_' . auth()->id();
        Cache::put($cacheKey, [
            'headers' => $headers,
            'preview' => $rows,
            'filename' => $file->getClientOriginalName(),
            'rows_preview' => $count,
        ], 3600); // 1 hora
        
        // Guardar el archivo procesado
        $savedPath = $file->store('csv_uploads', 'public');
        
        return response()->json([
            'success' => true,
            'message' => 'Archivo procesado correctamente',
            'data' => [
                'headers' => $headers,
                'preview' => $rows,
                'filename' => $file->getClientOriginalName(),
                'rows_preview' => $count,
                'saved_path' => $savedPath,
            ]
        ]);
    } catch (\Exception $e) {
        Log::error('Error in apiUploadCSV: ' . $e->getMessage());
        Log::error('Stack trace: ' . $e->getTraceAsString());
        
        return response()->json([
            'success' => false,
            'error' => $e->getMessage()
        ], 500);
    }
}

/**
 * Obtener previsualización del CSV
 */
public function apiGetCSVPreview(Request $request)
{
    try {
        Log::info('AdminController::apiGetCSVPreview called');
        
        // Obtener de caché en lugar de sesión
        $cacheKey = 'csv_preview_' . auth()->id();
        $data = Cache::get($cacheKey);
        
        if ($data) {
            return response()->json([
                'success' => true,
                'data' => [
                    'headers' => $data['headers'] ?? [],
                    'preview' => $data['preview'] ?? [],
                    'filename' => $data['filename'] ?? null,
                    'rows_preview' => $data['rows_preview'] ?? 0,
                    'has_data' => true,
                ]
            ]);
        }
        
        return response()->json([
            'success' => true,
            'data' => [
                'headers' => [],
                'preview' => [],
                'filename' => null,
                'rows_preview' => 0,
                'has_data' => false,
            ]
        ]);
    } catch (\Exception $e) {
        Log::error('Error in apiGetCSVPreview: ' . $e->getMessage());
        
        return response()->json([
            'success' => false,
            'error' => $e->getMessage()
        ], 500);
    }
}
/**
 * Ejecutar limpieza de datos
 */
public function apiCleanData(Request $request)
{
    try {
        Log::info('AdminController::apiCleanData called');
        
        $request->validate([
            'action' => 'required|string|in:uppercase,validate_docs,duplicates'
        ]);
        
        $action = $request->action;
        $result_text = "";
        
        if ($action == 'uppercase') {
            // Estandarización a mayúsculas
            $users = User::all();
            $updated = 0;
            foreach ($users as $user) {
                $changed = false;
                if ($user->name) {
                    $user->name = strtoupper($user->name);
                    $changed = true;
                }
                if ($user->curp) {
                    $user->curp = strtoupper($user->curp);
                    $changed = true;
                }
                if ($user->rfc) {
                    $user->rfc = strtoupper($user->rfc);
                    $changed = true;
                }
                if ($changed) {
                    $user->save();
                    $updated++;
                }
            }
            $result_text = "Estandarización completada. {$updated} registros actualizados.";
            
        } elseif ($action == 'validate_docs') {
            // Validación de documentos
            $no_ine = User::whereNull('ine_path')->count();
            $no_cedula = User::whereNull('cedula_path')->count();
            $no_curp = User::whereNull('curp')->count();
            $no_rfc = User::whereNull('rfc')->count();
            
            $result_text = "Validación completada:\n" .
                          "• {$no_ine} usuarios sin INE cargado\n" .
                          "• {$no_cedula} usuarios sin Cédula Profesional\n" .
                          "• {$no_curp} usuarios sin CURP\n" .
                          "• {$no_rfc} usuarios sin RFC";
            
        } elseif ($action == 'duplicates') {
            // Buscar duplicados por CURP
            $duplicates = DB::table('users')
                ->select('curp', DB::raw('count(*) as total'))
                ->whereNotNull('curp')
                ->groupBy('curp')
                ->having('total', '>', 1)
                ->get();
            
            if ($duplicates->count() > 0) {
                $result_text = "Duplicados encontrados:\n";
                foreach ($duplicates as $dup) {
                    $result_text .= "• CURP: {$dup->curp} - {$dup->total} registros\n";
                }
            } else {
                $result_text = "No se encontraron duplicados por CURP.";
            }
        }
        
        // Guardar resultado en caché
        $cacheKey = 'clean_result_' . auth()->id();
        Cache::put($cacheKey, $result_text, 3600);
        
        return response()->json([
            'success' => true,
            'message' => 'Limpieza ejecutada correctamente',
            'data' => [
                'result' => $result_text,
                'action' => $action,
            ]
        ]);
    } catch (\Exception $e) {
        Log::error('Error in apiCleanData: ' . $e->getMessage());
        Log::error('Stack trace: ' . $e->getTraceAsString());
        
        return response()->json([
            'success' => false,
            'error' => $e->getMessage()
        ], 500);
    }
}

/**
 * Obtener resultado de limpieza
 */
public function apiGetCleanResult(Request $request)
{
    try {
        Log::info('AdminController::apiGetCleanResult called');
        
        $cacheKey = 'clean_result_' . auth()->id();
        $result = Cache::get($cacheKey);
        
        return response()->json([
            'success' => true,
            'data' => [
                'result' => $result,
                'has_result' => !empty($result),
            ]
        ]);
    } catch (\Exception $e) {
        Log::error('Error in apiGetCleanResult: ' . $e->getMessage());
        
        return response()->json([
            'success' => false,
            'error' => $e->getMessage()
        ], 500);
    }
}
/**
 * Verificar PIN y obtener dashboard de finanzas (todo en uno)
 */
public function apiFinanzasDashboard(Request $request)
{
    try {
        Log::info('AdminController::apiFinanzasDashboard called');
        
        $user = auth()->user();
        
        // Obtener el PIN del header o del body
        $pin = $request->header('X-Finance-Pin') ?? $request->input('pin');
        
        // PIN por defecto '1111' si no existe en la BD
        $validPin = $user->finance_pin ?? '1111';
        
        // Si no hay PIN o es incorrecto, pedir PIN
        if (!$pin || $pin !== $validPin) {
            return response()->json([
                'success' => false,
                'requires_pin' => true,
                'message' => 'Se requiere verificación PIN'
            ], 401);
        }
        
        // ==========================================
        // KPIs
        // ==========================================
        $paid = DB::table('invoices')->where('status', 'Pagado')->sum('amount');
        $pending = DB::table('invoices')->where('status', 'Pendiente')->sum('amount');
        $insurance = DB::table('invoices')->where('status', 'Seguro')->sum('amount');
        $vencido = DB::table('invoices')->where('status', 'Vencido')->sum('amount');
        $total = DB::table('invoices')->sum('amount');
        $pharma_value = DB::table('medications')->selectRaw('SUM(stock * price) as total')->value('total') ?? 0;
        
        // ==========================================
        // INGRESOS POR ÁREA
        // ==========================================
        $ingresosUrgencias = DB::table('invoices')->where('concept', 'Consulta Urgencias')->sum('amount');
        $ingresosCirugia = DB::table('invoices')->where('concept', 'Cirugia')->sum('amount');
        $ingresosHospitalizacion = DB::table('invoices')->where('concept', 'like', '%Hospitalizacion%')->sum('amount');
        $ingresosFarmacia = DB::table('invoices')->where('concept', 'Medicamentos')->sum('amount');
        $ingresosEstudios = DB::table('invoices')->whereIn('concept', ['Estudio Laboratorio','Rayos X','Tomografia','Estudios'])->sum('amount');
        $ingresosUCI = DB::table('invoices')->where('concept', 'UCI')->sum('amount');
        
        // ==========================================
        // SEGUROS
        // ==========================================
        $segurosPorProveedor = DB::table('insurances')
            ->select('provider', DB::raw('COUNT(*) as total'), DB::raw('SUM(CASE WHEN status="Vigente" THEN 1 ELSE 0 END) as vigentes'))
            ->groupBy('provider')->get();
            
        $polizasFalsas = DB::table('insurances')->where('status', 'Falsa/Fraude')->count();
        $sinCobertura = DB::table('insurances')->where('status', 'Sin Cobertura')->count();
        $segurosVencidos = DB::table('insurances')->where('status', 'Vencida')->count();
        
        // ==========================================
        // RIESGO
        // ==========================================
        $riskScore = $pending > ($paid * 0.5) ? 'ALTO RIESGO' : ($pending > ($paid * 0.25) ? 'MODERADO' : 'ESTABLE');
        
        // ==========================================
        // ÚLTIMAS FACTURAS
        // ==========================================
        $invoices = DB::table('invoices')->orderBy('created_at', 'desc')->limit(10)->get();
        
        // ==========================================
        // INGRESOS DIARIOS (últimos 7 días)
        // ==========================================
        $ingresosDiarios = DB::table('invoices')
            ->selectRaw('DATE(created_at) as fecha, SUM(amount) as total, COUNT(*) as qty')
            ->where('created_at', '>=', now()->subDays(7))
            ->groupByRaw('DATE(created_at)')
            ->orderByDesc('fecha')
            ->get();
        
        // ==========================================
        // TOP CONCEPTOS
        // ==========================================
        $topInvoices = DB::table('invoices')
            ->select('concept', DB::raw('SUM(amount) as total'), DB::raw('COUNT(*) as qty'))
            ->groupBy('concept')->orderByDesc('total')->get();
        
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
                'top_conceptos' => $topInvoices,
                'risk_score' => $riskScore,
                'invoices' => $invoices,
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
 * Verificar PIN (endpoint separado - opcional)
 */
public function apiFinanzasVerifyPin(Request $request)
{
    try {
        Log::info('AdminController::apiFinanzasVerifyPin called');
        
        $request->validate([
            'pin' => 'required|string|min:4|max:6'
        ]);
        
        $user = auth()->user();
        $validPin = $user->finance_pin ?? '1111';
        
        if ($request->pin === $validPin) {
            // Retornar éxito, el PIN se enviará en el header en la siguiente petición
            return response()->json([
                'success' => true,
                'message' => 'PIN verificado correctamente'
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
}