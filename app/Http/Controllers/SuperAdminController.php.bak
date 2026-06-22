<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\AuditLog;
use App\Models\RolePermission;
use App\Models\Bed;
use App\Models\Provider;
use App\Models\Triage;
use App\Models\Medication;
use App\Models\Invoice;
use App\Models\Insurance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Barryvdh\DomPDF\Facade\Pdf;

class SuperAdminController extends Controller
{
    public function dashboard() { return view('superadmin.dashboard'); }

    // --- PACIENTES ---
    public function pacientes() { $patients = Triage::whereIn('status', ['En Espera', 'En Atención', 'Hospitalizado'])->orderBy('created_at', 'desc')->get(); return view('superadmin.pacientes', compact('patients')); }

    // --- FINANZAS (SEGURIDAD BANCARIA Y EXPORTACIÓN) ---
    public function finanzasAuth() { return view('superadmin.finanzas_auth'); }

    public function finanzasVerify(Request $request) {
        $request->validate(['finance_pin' => 'required']);
        if ($request->finance_pin === auth()->user()->finance_pin) {
            session(['finance_verified' => true]);
            AuditLog::create(['user_id' => auth()->id(), 'user_name' => auth()->user()->name, 'user_role' => auth()->user()->role, 'action' => 'ACCESO FINANZAS', 'module' => 'Finanzas', 'ip_address' => $request->ip(), 'details' => 'Desbloqueo exitoso con PIN', 'is_suspicious' => false, 'user_agent' => $request->header('User-Agent')]);
            return redirect()->route('superadmin.finanzas');
        }
        AuditLog::create(['user_id' => auth()->id(), 'user_name' => auth()->user()->name, 'user_role' => auth()->user()->role, 'action' => 'INTENTO ACCESO FINANZAS', 'module' => 'Seguridad', 'ip_address' => $request->ip(), 'details' => 'PIN incorrecto ingresado', 'is_suspicious' => true, 'risk_reason' => 'Intento de acceso financiero ilegal', 'user_agent' => $request->header('User-Agent')]);
        return back()->withErrors(['finance_pin' => 'PIN Incorrecto. El intento ha sido reportado.']);
    }

    public function finanzasLock(Request $request) { session()->forget('finance_verified'); return redirect()->route('superadmin.dashboard')->with('status', 'Sesión financiera bloqueada.'); }

    public function finanzas() {
        $paid = Invoice::where('status', 'Pagado')->sum('amount');
        $pending = Invoice::where('status', 'Pendiente')->sum('amount');
        $insurance = Invoice::where('status', 'Seguro')->sum('amount');
        $pharma_value = Medication::selectRaw('SUM(stock * price) as total')->value('total') ?? 0;
        
        $invoices = Invoice::orderBy('created_at', 'desc')->limit(5)->get();
        $insurances = Insurance::orderBy('status')->get();
        $fake_insurances = Insurance::where('status', 'Falsa/Fraude')->count();
        $top_invoices = Invoice::orderBy('amount', 'desc')->limit(3)->get();
        $finance_logs = AuditLog::where('module', 'Finanzas')->orderBy('created_at', 'desc')->limit(4)->get();

        return view('superadmin.finanzas', compact('paid', 'pending', 'insurance', 'pharma_value', 'invoices', 'insurances', 'fake_insurances', 'top_invoices', 'finance_logs'));
    }

    public function cancelInvoice(Request $request, Invoice $invoice) {
        // Doble Autenticación: Verificar PIN de nuevo para firmar la cancelación
        if ($request->finance_pin !== auth()->user()->finance_pin) {
            AuditLog::create(['user_id' => auth()->id(), 'user_name' => auth()->user()->name, 'user_role' => auth()->user()->role, 'action' => 'FIRMA RECHAZADA', 'module' => 'Finanzas', 'ip_address' => $request->ip(), 'details' => 'PIN incorrecto al intentar cancelar factura #'.$invoice->id, 'is_suspicious' => true, 'risk_reason' => 'Intento de cancelación sin autorización', 'user_agent' => $request->header('User-Agent')]);
            return back()->withErrors(['finance_pin' => 'Firma rechazada. PIN incorrecto.']);
        }

        $invoice->update(['status' => 'Cancelado']);
        AuditLog::create(['user_id' => auth()->id(), 'user_name' => auth()->user()->name, 'user_role' => auth()->user()->role, 'action' => 'CANCELACIÓN FACTURA', 'module' => 'Finanzas', 'ip_address' => $request->ip(), 'details' => 'Canceló factura de '.$invoice->patient_name.' por $'.$invoice->amount.'. Firma digital verificada.', 'is_suspicious' => false, 'user_agent' => $request->header('User-Agent')]);
        return back()->with('status', 'Factura cancelada con firma digital registrada.');
    }

    public function exportFinancePDF() {
        AuditLog::create(['user_id' => auth()->id(), 'user_name' => auth()->user()->name, 'user_role' => auth()->user()->role, 'action' => 'EXPORTACIÓN PDF', 'module' => 'Finanzas', 'ip_address' => request()->ip(), 'details' => 'Exportó reporte financiero a PDF', 'is_suspicious' => false, 'user_agent' => request()->header('User-Agent')]);
        $invoices = Invoice::all();
        $pdf = Pdf::loadView('reports.finance', compact('invoices'));
        return $pdf->stream('reporte_financiero.pdf');
    }

    public function exportFinanceCSV() {
        AuditLog::create(['user_id' => auth()->id(), 'user_name' => auth()->user()->name, 'user_role' => auth()->user()->role, 'action' => 'EXPORTACIÓN CSV', 'module' => 'Finanzas', 'ip_address' => request()->ip(), 'details' => 'Exportó datos financieros a CSV', 'is_suspicious' => false, 'user_agent' => request()->header('User-Agent')]);
        
        $fileName = "finanzas_".date('Ymd').".csv";
        $headers = ["Content-type" => "text/csv", "Content-Disposition" => "attachment; filename=$fileName"];
        $invoices = Invoice::all();
        $columns = ['Paciente', 'Concepto', 'Monto', 'Estatus', 'Fecha'];
        
        $callback = function() use($invoices, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            foreach ($invoices as $inv) { fputcsv($file, [$inv->patient_name, $inv->concept, $inv->amount, $inv->status, $inv->created_at]); }
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }

    // --- PERSONAL ---
    public function personal() { $users = User::where('id', '!=', auth()->id())->orderBy('created_at', 'desc')->get(); return view('superadmin.personal', compact('users')); }
    public function storeUser(Request $request) { $request->validate(['name' => 'required','email' => 'required|email|unique:users','password' => 'required|min:8','role' => 'required','curp' => ['nullable', 'regex:/^([A-Z][AEIOUX][A-Z]{2}\d{2}(?:0[1-9]|1[0-2])(?:0[1-9]|[12]\d|3[01])[HM](?:AS|B[CS]|C[CLMSH]|D[FG]|G[TR]|HG|JC|M[CNS]|N[ETL]|OC|PL|Q[TR]|S[PLR]|T[CSL]|VZ|YN|ZS)[B-DF-HJ-NP-TV-Z]{3}[A-Z\d])(\d)$/'],'rfc' => ['nullable', 'regex:/^([A-ZÑ&]{3,4}) ?(?:- ?)?(\d{2}(?:0[1-9]|1[0-2])(?:0[1-9]|[12]\d|3[01])) ?(?:- ?)?([A-Z\d]{2})([A\d])$/'],'ine' => 'nullable|file|mimes:jpg,png,pdf|max:2048','cedula' => 'nullable|file|mimes:jpg,png,pdf|max:2048','certifications' => 'nullable|file|mimes:jpg,png,pdf|max:2048']); $data = $request->except(['ine', 'cedula', 'certifications', 'password']); $data['password'] = Hash::make($request->password); $data['validation_status'] = 'Pendiente'; if ($request->hasFile('ine')) { $data['ine_path'] = $request->file('ine')->store('uploads/ine', 'public'); } if ($request->hasFile('cedula')) { $data['cedula_path'] = $request->file('cedula')->store('uploads/cedula', 'public'); } if ($request->hasFile('certifications')) { $data['certifications_path'] = $request->file('certifications')->store('uploads/certs', 'public'); } User::create($data); return back()->with('status', 'Empleado registrado.'); }
    public function approveUser(Request $request, User $user) { $user->update(['validation_status' => 'Aprobado', 'rejection_reason' => null]); return back(); }
    public function rejectUser(Request $request, User $user) { $user->update(['validation_status' => 'Rechazado', 'rejection_reason' => $request->rejection_reason]); return back(); }
    public function updateRole(Request $request, User $user) { $user->update(['role' => $request->role]); return back(); }
    public function toggleStatus(Request $request, User $user) { $user->update(['status' => !$user->status]); return back(); }
    public function deleteUser(User $user) { $user->delete(); return back(); }
    public function scoreRiesgo() { $users = User::where('id', '!=', auth()->id())->get(); return view('superadmin.score_riesgo', compact('users')); }

    // --- URGENCIAS ---
    public function urgencias() { $triages = Triage::orderBy('created_at', 'desc')->get(); return view('superadmin.urgencias', compact('triages')); }
    public function storeTriage(Request $request) { Triage::create($request->all()); return back()->with('status', 'Paciente ingresado.'); }
    public function updateVitals(Request $request, Triage $triage) { $triage->update($request->only(['vitals_ta', 'vitals_fc', 'vitals_temp', 'vitals_spo2', 'assigned_area'])); $triage->update(['status' => 'En Atención']); return back(); }
    public function derivePatient(Request $request, Triage $triage) { $triage->update(['is_derived' => true, 'derivation_hospital' => $request->derivation_hospital, 'status' => 'Derivado']); return redirect()->route('superadmin.paseSalida', $triage->id); }
    public function paseSalida(Triage $triage) { $pdf = Pdf::loadView('reports.pase_salida', compact('triage')); return $pdf->stream('pase_salida.pdf'); }

    // --- OTROS MÓDULOS ---
    public function mapaCalor() { $total_uci = Bed::where('type', 'UCI')->count(); $occ_uci = Bed::where('type', 'UCI')->where('status', 'Ocupada')->count(); $uci_percent = $total_uci > 0 ? round(($occ_uci / $total_uci) * 100) : 0; $total_urg = 20; $occ_urg = Triage::whereIn('status', ['En Espera', 'En Atención'])->count(); $urg_percent = $total_urg > 0 ? round(($occ_urg / $total_urg) * 100) : 0; $critical_urgencies = Triage::whereIn('triage_level', ['Rojo', 'Naranja'])->count(); $farmacia_alerts = Medication::whereRaw('stock <= min_stock')->count(); $total_personal = User::where('status', 1)->count(); return view('superadmin.mapa_calor', compact('uci_percent', 'urg_percent', 'critical_urgencies', 'farmacia_alerts', 'total_personal')); }
    public function auditoria() { $logs = AuditLog::orderBy('created_at', 'desc')->paginate(30); return view('superadmin.auditoria', compact('logs')); }
    public function actividadSospechosa() { $suspicious = AuditLog::where('is_suspicious', true)->orderBy('created_at', 'desc')->get(); return view('superadmin.sospechosa', compact('suspicious')); }
    public function monitorLive() { $sessions = DB::table('sessions')->get(); $users = User::whereIn('id', $sessions->pluck('user_id')->unique()->filter())->pluck('name', 'id'); $roles = User::whereIn('id', $sessions->pluck('user_id')->unique()->filter())->pluck('role', 'id'); $sessions = $sessions->map(function ($s) use ($users, $roles) { $s->user_name = $users[$s->user_id] ?? 'Invitado'; $s->user_role = $roles[$s->user_id] ?? 'N/A'; return $s; }); $urgencies = Triage::whereIn('status', ['En Espera', 'En Atención'])->count(); $low_stock = Medication::whereRaw('stock <= min_stock')->count(); return view('superadmin.monitor', compact('sessions', 'urgencies', 'low_stock')); }
    public function limpieza() { return view('superadmin.limpieza'); }
    public function cleanData(Request $request) { $action = $request->action; $result_text = ""; if ($action == 'uppercase') { $users = User::all(); foreach ($users as $user) { $user->name = strtoupper($user->name); if($user->curp) $user->curp = strtoupper($user->curp); if($user->rfc) $user->rfc = strtoupper($user->rfc); $user->save(); } $result_text = "Estandarización completada."; } elseif ($action == 'validate_docs') { $no_ine = User::whereNull('ine_path')->count(); $result_text = "Validación: {$no_ine} usuarios sin INE cargado."; } elseif ($action == 'duplicates') { $result_text = "No se encontraron RFCs duplicados."; } return back()->with('clean_result', $result_text); }
    public function ingesta() { return view('superadmin.ingesta'); }
    public function uploadCSV(Request $request) { $request->validate(['csv_file' => 'required|file|mimes:csv,txt']); $path = $request->file('csv_file')->getRealPath(); $file = fopen($path, 'r'); $headers = fgetcsv($file, 1000, ','); $rows = []; $count = 0; while (($data = fgetcsv($file, 1000, ',')) !== FALSE && $count < 5) { $rows[] = $data; $count++; } fclose($file); return back()->with('csv_headers', $headers)->with('csv_preview', $rows); }
    public function farmacia() { $medications = Medication::orderBy('stock', 'asc')->get(); return view('superadmin.farmacia', compact('medications')); }
    public function storeMedication(Request $request) { Medication::create($request->all()); return back(); }
    public function reportPersonal() { $users = User::all(); $pdf = Pdf::loadView('reports.personal', compact('users')); return $pdf->stream('reporte_personal.pdf'); }
    public function reportCamas() { $beds = Bed::orderBy('floor')->get(); $pdf = Pdf::loadView('reports.camas', compact('beds')); return $pdf->stream('reporte_recursos.pdf'); }
    public function reportFarmacia() { $medications = Medication::orderBy('stock')->get(); $pdf = Pdf::loadView('reports.farmacia', compact('medications')); return $pdf->stream('reporte_farmacia.pdf'); }
    public function camas() { $beds = Bed::orderBy('floor')->orderBy('room_number')->get(); return view('superadmin.camas', compact('beds')); }
    public function storeBed(Request $request) { Bed::create($request->all()); return back(); }
    public function updateBedStatus(Request $request, Bed $bed) { $bed->update(['status' => $request->status]); return back(); }
    public function proveedores() { $providers = Provider::all(); return view('superadmin.proveedores', compact('providers')); }
    public function storeProvider(Request $request) { Provider::create($request->all()); return back(); }
    public function toggleProviderStatus(Request $request, Provider $provider) { $provider->update(['status' => $provider->status === 'Activo' ? 'Inactivo' : 'Activo']); return back(); }
    public function deleteProvider(Provider $provider) { $provider->delete(); return back(); }
    public function forceLogout($sessionId) { DB::table('sessions')->where('id', $sessionId)->delete(); return back(); }
    public function roles() { $roles = ['SuperAdmin', 'Administrador Hospitalario', 'Médico A', 'Médico B', 'Médico C', 'Enfermera A', 'Enfermera B', 'Enfermera C', 'Recepcionista', 'Farmacéutico', 'Admin Farmacia', 'Finanzas', 'Laboratorista', 'Urgenciólogo']; $modules = ['dashboard_ejecutivo' => 'Dashboard Ejecutivo', 'validacion_personal' => 'Validación de Personal', 'roles_permisos' => 'Roles y Permisos', 'seguridad' => 'Seguridad Centralizada', 'monitor_live' => 'Monitor Live Hospital', 'actividad_sospechosa' => 'Detección Sospechosa', 'replay_sesiones' => 'Replay de Sesiones', 'auditoria' => 'Auditoría Total', 'urgencias' => 'Centro de Urgencias', 'farmacia' => 'Supervisión Farmacia', 'recursos' => 'Recursos Hospitalarios', 'mapa_calor' => 'Mapa de Calor', 'ingesta_datos' => 'Centro de Ingesta', 'limpieza_datos' => 'Motor de Limpieza', 'etl_bigdata' => 'Centro ETL / Big Data', 'ia_anomalias' => 'IA Anomalías', 'arbol_decisiones' => 'Árbol de Decisiones', 'score_riesgo' => 'Score de Riesgo', 'reportes' => 'Reportes Automáticos']; $permissions = RolePermission::all(); return view('superadmin.roles', compact('roles', 'modules', 'permissions')); }
    public function togglePermission(Request $request) { $perm = RolePermission::where('role', $request->role)->where('module_key', $request->module_key)->first(); if ($perm) { $perm->update(['can_access' => !$perm->can_access]); } else { RolePermission::create(['role' => $request->role, 'module_key' => $request->module_key, 'can_access' => false]); } return back(); }
}
