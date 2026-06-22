<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AdminController; 
// use App\Http\Controllers\Api\DoctorController;
// use App\Http\Controllers\Api\NurseController;
// use App\Http\Controllers\Api\PharmacyController;

// Rutas públicas
Route::post('/login', [AuthController::class, 'login']);

// Rutas protegidas por token
Route::middleware(['auth:sanctum'])->group(function () {
    
    // Rutas de autenticación
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'me']);
    Route::post('/verify-token', [AuthController::class, 'verifyToken']);
    
    // ==========================================
    // RUTAS PARA MÉDICOS - COMENTADAS
    // ==========================================
    // Route::middleware(['role:Médico A,Médico B,Médico C,Especialista,Urgenciólogo'])->prefix('doctor')->group(function () {
    //     Route::get('/patients', [DoctorController::class, 'getPatients']);
    //     Route::get('/appointments', [DoctorController::class, 'getAppointments']);
    //     Route::post('/prescriptions', [DoctorController::class, 'createPrescription']);
    // });
    
    // ==========================================
    // RUTAS PARA ENFERMERAS - COMENTADAS
    // ==========================================
    // Route::middleware(['role:Enfermera A,Enfermera B,Enfermera C'])->prefix('nurse')->group(function () {
    //     Route::post('/vital-signs', [NurseController::class, 'storeVitalSigns']);
    //     Route::get('/triage-list', [NurseController::class, 'getTriageList']);
    // });
    
    // ==========================================
    // RUTAS PARA FARMACIA - COMENTADAS
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
        
        // ==========================================
        // 🏥 URGENCIAS
        // ==========================================
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

// RUTAS PARA FINANZAS
Route::middleware(['role:SuperAdmin,Administrador Hospitalario'])->prefix('admin')->group(function () {
    Route::get('/finanzas/dashboard', [AdminController::class, 'apiFinanzasDashboard']);
    Route::post('/finanzas/verify-pin', [AdminController::class, 'apiFinanzasVerifyPin']);
    Route::post('/finanzas/invoice', [AdminController::class, 'apiFinanzasStoreInvoice']);
    Route::put('/finanzas/invoice/{id}/status', [AdminController::class, 'apiFinanzasUpdateInvoiceStatus']);
    Route::delete('/finanzas/invoice/{id}', [AdminController::class, 'apiFinanzasDeleteInvoice']);
    Route::post('/finanzas/insurance', [AdminController::class, 'apiFinanzasStoreInsurance']);
    Route::put('/finanzas/insurance/{id}/status', [AdminController::class, 'apiFinanzasUpdateInsuranceStatus']);
    Route::delete('/finanzas/insurance/{id}', [AdminController::class, 'apiFinanzasDeleteInsurance']);
});

// RUTAS PARA AUDITORIA
Route::middleware(['role:SuperAdmin,Administrador Hospitalario'])->prefix('admin')->group(function () {
    Route::get('/auditoria/dashboard', [AdminController::class, 'apiAuditoriaDashboard']);
});

});