<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AdminController; 
// use App\Http\Controllers\Api\DoctorController;
// use App\Http\Controllers\Api\NurseController;
// use App\Http\Controllers\Api\PharmacyController;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware(['auth:sanctum'])->group(function () {
    
    // Rutas de autenticación
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'me']);
    Route::post('/verify-token', [AuthController::class, 'verifyToken']);
    
    // ==========================================
    // RUTAS PARA MÉDICOS 
    // ==========================================
    // Route::middleware(['role:Médico A,Médico B,Médico C,Especialista,Urgenciólogo'])->prefix('doctor')->group(function () {
    //     Route::get('/patients', [DoctorController::class, 'getPatients']);
    //     Route::get('/appointments', [DoctorController::class, 'getAppointments']);
    //     Route::post('/prescriptions', [DoctorController::class, 'createPrescription']);
    // });
    
    // ==========================================
    // RUTAS PARA ENFERMERAS 
    // ==========================================
    // Route::middleware(['role:Enfermera A,Enfermera B,Enfermera C'])->prefix('nurse')->group(function () {
    //     Route::post('/vital-signs', [NurseController::class, 'storeVitalSigns']);
    //     Route::get('/triage-list', [NurseController::class, 'getTriageList']);
    // });
    
    // ==========================================
    // RUTAS PARA FARMACIA 
    // ==========================================
    // Route::middleware(['role:Farmacéutico,Admin Farmacia'])->prefix('pharmacy')->group(function () {
    //     Route::get('/inventory', [PharmacyController::class, 'getInventory']);
    //     Route::post('/dispense', [PharmacyController::class, 'dispense']);
    // });
    
    // ==========================================
    // RUTAS PARA ADMIN (SuperAdmin y Administrador Hospitalario)
    // ==========================================
    Route::middleware(['role:SuperAdmin,Administrador Hospitalario'])->prefix('admin')->group(function () {
        
        // ---- USUARIOS ----
        Route::get('/users', [AdminController::class, 'getUsers']);
        Route::post('/users', [AdminController::class, 'storeUser']);
        Route::put('/users/{id}/approve', [AdminController::class, 'approveUser']);
        Route::put('/users/{id}/reject', [AdminController::class, 'rejectUser']);
        Route::put('/users/{id}/role', [AdminController::class, 'updateRole']);
        Route::put('/users/{id}/status', [AdminController::class, 'toggleStatus']);
        Route::delete('/users/{id}', [AdminController::class, 'deleteUser']);
        
        // ---- RISK SCORE ----
        Route::get('/risk-score', [AdminController::class, 'getRiskScore']); 
        
        // ---- ROLES Y PERMISOS ----
        Route::get('/roles-permissions', [AdminController::class, 'getRolesPermissions']);
        Route::post('/toggle-permission', [AdminController::class, 'togglePermission']); 
        
        // ---- PACIENTES ----
        Route::get('/patients', [AdminController::class, 'getPatients']); 
        Route::put('/patients/{id}/status', [AdminController::class, 'updatePatientStatus']);
        
        //  URGENCIAS
        Route::prefix('emergency')->group(function () {
            Route::get('/dashboard', [AdminController::class, 'apiEmergencyDashboard']);
            Route::post('/patient', [AdminController::class, 'apiStoreTriage']);
            Route::put('/patient/{id}/vitals', [AdminController::class, 'apiUpdateVitals']);
            Route::put('/patient/{id}/derive', [AdminController::class, 'apiDerivePatient']);
            Route::put('/patient/{id}/discharge', [AdminController::class, 'apiDischargePatient']);
        });
        
    });
    // RUTAS PARA FARMACIA (SuperAdmin también puede ver)
Route::middleware(['role:SuperAdmin,Administrador Hospitalario,Farmacéutico,Admin Farmacia'])->prefix('pharmacy')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'apiPharmacyDashboard']);
    Route::get('/inventory', [AdminController::class, 'apiPharmacyInventory']);
    Route::post('/prescribe', [AdminController::class, 'apiPharmacyPrescribe']);
});

// RUTAS PARA CAMAS
Route::middleware(['role:SuperAdmin,Administrador Hospitalario'])->prefix('admin')->group(function () {
    Route::get('/beds', [AdminController::class, 'apiGetBeds']);
    Route::post('/beds', [AdminController::class, 'apiStoreBed']);
    Route::put('/beds/{id}/status', [AdminController::class, 'apiUpdateBedStatus']);
    Route::delete('/beds/{id}', [AdminController::class, 'apiDeleteBed']);
});

// RUTAS PARA AMBULANCIAS
Route::middleware(['role:SuperAdmin,Administrador Hospitalario'])->prefix('admin')->group(function () {
    Route::get('/ambulances', [AdminController::class, 'apiGetAmbulances']);
    Route::post('/ambulances', [AdminController::class, 'apiStoreAmbulance']);
    Route::put('/ambulances/{id}/status', [AdminController::class, 'apiUpdateAmbulanceStatus']);
    Route::delete('/ambulances/{id}', [AdminController::class, 'apiDeleteAmbulance']);
});
// RUTAS PARA HOSPITAL LIVE
Route::middleware(['role:SuperAdmin,Administrador Hospitalario'])->prefix('admin')->group(function () {
    Route::get('/hospital-live', [AdminController::class, 'apiHospitalLive']);
});



// RUTAS PARA AUDITORIA
Route::middleware(['role:SuperAdmin,Administrador Hospitalario'])->prefix('admin')->group(function () {
    Route::get('/auditoria/dashboard', [AdminController::class, 'apiAuditoriaDashboard']);
});
Route::middleware(['role:SuperAdmin,Administrador Hospitalario'])->prefix('admin')->group(function () {
    Route::get('/bigdata/dashboard', [AdminController::class, 'apiBigDataDashboard']);
    Route::post('/bigdata/run-etl', [AdminController::class, 'apiBigDataRunETL']);
});
// RUTAS PARA ACTIVIDAD SOSPECHOSA
Route::middleware(['role:SuperAdmin,Administrador Hospitalario'])->prefix('admin')->group(function () {
    Route::get('/suspicious-activity', [AdminController::class, 'apiGetSuspiciousActivity']);
});

// RUTAS PARA MONITOR LIVE
Route::middleware(['role:SuperAdmin,Administrador Hospitalario'])->prefix('admin')->group(function () {
    Route::get('/monitor-live', [AdminController::class, 'apiGetMonitorLive']);
});
// RUTAS PARA MAPA DE CALOR
Route::middleware(['role:SuperAdmin,Administrador Hospitalario'])->prefix('admin')->group(function () {
    Route::get('/heatmap', [AdminController::class, 'apiGetHeatmap']);
});
// RUTAS PARA INGESTA DE DATOS
Route::middleware(['role:SuperAdmin,Administrador Hospitalario'])->prefix('admin')->group(function () {
    Route::post('/ingesta/upload', [AdminController::class, 'apiUploadCSV']);
    Route::get('/ingesta/preview', [AdminController::class, 'apiGetCSVPreview']);
});
// RUTAS PARA LIMPIEZA DE DATOS
Route::middleware(['role:SuperAdmin,Administrador Hospitalario'])->prefix('admin')->group(function () {
    Route::post('/clean-data', [AdminController::class, 'apiCleanData']);
    Route::get('/clean-result', [AdminController::class, 'apiGetCleanResult']);
});
// RUTAS PARA FINANZAS
Route::middleware(['role:SuperAdmin,Administrador Hospitalario'])->prefix('admin')->group(function () {
    Route::get('/finanzas/dashboard', [AdminController::class, 'apiFinanzasDashboard']);
    Route::post('/finanzas/verify-pin', [AdminController::class, 'apiFinanzasVerifyPin']);
});
});