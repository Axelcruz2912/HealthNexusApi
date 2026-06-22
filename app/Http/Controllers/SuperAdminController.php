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
    public function pacientes() {
        $patients = Triage::whereIn('status', ['En Espera', 'En Atención', 'Hospitalizado'])->orderBy('created_at', 'desc')->paginate(30);
        return view('superadmin.pacientes', compact('patients'));
    }

    // --- FINANZAS ---
    public function finanzasAuth() { return view('superadmin.finanzas_auth'); }

    public function finanzasVerify(Request $request) {
        $request->validate(['finance_pin' => 'required']);
        if ($request->finance_pin === auth()->user()->finance_pin) {
            session(['finance_verified' => true]);
            return redirect()->route('superadmin.finanzas');
        }
        return back()->withErrors(['finance_pin' => 'PIN Incorrecto. El intento ha sido reportado.']);
    }

    public function finanzasLock(Request $request) { session()->forget('finance_verified'); return redirect()->route('superadmin.dashboard')->with('status', 'Sesión financiera bloqueada.'); }

    public function finanzas() {
        if (!session('finance_verified')) {
            return redirect()->route('superadmin.finanzas.auth');
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
        $pharma_value = Medication::selectRaw('SUM(stock * price) as total')->value('total') ?? 0;

        // === INGRESOS DIARIOS (últimos 7 días) ===
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
        $invoices = DB::table('invoices')->orderBy('created_at', 'desc')->paginate(20);

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

        // === COSTO POR PACIENTE (hospitalizados) ===
        $costoPorPaciente = DB::table('hospitalizations')
            ->join('triages', 'hospitalizations.triage_id', '=', 'triages.id')
            ->select('triages.patient_name', 'hospitalizations.admission_date', 'hospitalizations.discharge_date',
                DB::raw('DATEDIFF(COALESCE(hospitalizations.discharge_date, NOW()), hospitalizations.admission_date) as dias'))
            ->orderByDesc('dias')
            ->limit(15)->get();

        // === APROBACIONES PENDIENTES ===
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

        $top_invoices = $topInvoices;
        return view('superadmin.finanzas', compact(
            'paid', 'pending', 'insurance', 'vencido', 'total', 'pharma_value',
            'ingresosUrgencias', 'ingresosCirugia', 'ingresosHospitalizacion', 'ingresosFarmacia', 'ingresosEstudios', 'ingresosUCI',
            'ingresosDiarios', 'segurosPorProveedor', 'polizasFalsas', 'sinCobertura', 'segurosVencidos',
            'cobrosDuplicados', 'gastosSospechosos', 'invoices', 'topInvoices',
            'pacientesDeuda', 'costoPorPaciente', 'cirugiasCostosas', 'medsCaros',
            'farmaciaCostosa', 'riskScore', 'top_invoices'
        ));
    }


    public function cancelInvoice(Request $request, Invoice $invoice) {
        if ($request->finance_pin !== auth()->user()->finance_pin) {
            return back()->withErrors(['finance_pin' => 'Firma rechazada. PIN incorrecto.']);
        }
        $invoice->update(['status' => 'Cancelado']);
        // ETL: CARGA EN MONGODB ATLAS (DATA WAREHOUSE) EN TIEMPO REAL
        \App\Models\MongoTriageLog::create([
            'patient_id' => $request->patient_name,
            'triage_level' => $request->triage_level,
            'age' => $request->age,
            'specialty' => 'Urgencias',
            'vitals_fc' => $request->vitals_fc ?? 80,
            'vitals_temp' => $request->vitals_temp ?? 36.5,
            'vitals_spo2' => $request->vitals_spo2 ?? 98,
            'timestamp' => now()
        ]);

        return back()->with('status', 'Factura cancelada con firma digital registrada.');
    }

    public function exportFinancePDF() {
        $paid = DB::table('prescriptions')
            ->join('medications', 'prescriptions.medication_id', '=', 'medications.id')
            ->whereIn('prescriptions.status', ['Surtida', 'Autorizada'])
            ->selectRaw('SUM(medications.price * prescriptions.quantity) as total')
            ->value('total') ?? 0;
        $pending = DB::table('prescriptions')
            ->join('medications', 'prescriptions.medication_id', '=', 'medications.id')
            ->where('prescriptions.status', 'Pendiente')
            ->selectRaw('SUM(medications.price * prescriptions.quantity) as total')
            ->value('total') ?? 0;
        $pharma_value = Medication::selectRaw('SUM(stock * price) as total')->value('total') ?? 0;
        $top = DB::table('prescriptions')
            ->join('medications', 'prescriptions.medication_id', '=', 'medications.id')
            ->select('medications.name', DB::raw('SUM(medications.price * prescriptions.quantity) as total'), DB::raw('COUNT(*) as qty'))
            ->groupBy('medications.name')->orderByDesc('total')->limit(20)->get();
        $pdf = Pdf::loadView('reports.finance', compact('paid', 'pending', 'pharma_value', 'top'));
        return $pdf->stream('reporte_financiero_'.date('Ymd').'.pdf');
    }

    public function exportFinanceCSV() {
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


    // --- FINANZAS CRUD ---
    public function storeInvoice(Request $request) {
        $request->validate([
            'patient_name' => 'required',
            'concept' => 'required',
            'amount' => 'required|numeric|min:0',
            'status' => 'required',
        ]);
        DB::table('invoices')->insert([
            'patient_name' => $request->patient_name,
            'concept' => $request->concept,
            'amount' => $request->amount,
            'status' => $request->status,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        AuditLog::create([
            'user_id' => auth()->id(),
            'user_name' => auth()->user()->name,
            'user_role' => auth()->user()->role,
            'action' => 'FACTURA CREADA',
            'module' => 'Finanzas',
            'ip_address' => $request->ip(),
            'details' => "Paciente: {$request->patient_name} | Concepto: {$request->concept} | Monto: ${$request->amount} | Estado: {$request->status}",
            'is_suspicious' => $request->amount > 50000,
            'risk_reason' => $request->amount > 50000 ? 'Factura de alto valor' : null,
            'user_agent' => $request->header('User-Agent'),
        ]);
        return back()->with('status', 'Factura creada correctamente.');
    }

    public function updateInvoice(Request $request, $id) {
        $inv = DB::table('invoices')->where('id', $id)->first();
        if (!$inv) return back()->withErrors(['error' => 'Factura no encontrada.']);
        $old_status = $inv->status;
        DB::table('invoices')->where('id', $id)->update([
            'status' => $request->status,
            'updated_at' => now(),
        ]);
        AuditLog::create([
            'user_id' => auth()->id(),
            'user_name' => auth()->user()->name,
            'user_role' => auth()->user()->role,
            'action' => 'FACTURA ACTUALIZADA',
            'module' => 'Finanzas',
            'ip_address' => $request->ip(),
            'details' => "Factura #{$id}: {$inv->patient_name} | Estado: {$old_status} -> {$request->status} | Monto: ${$inv->amount}",
            'is_suspicious' => $old_status === 'Pagado' && $request->status !== 'Pagado',
            'risk_reason' => $old_status === 'Pagado' && $request->status !== 'Pagado' ? 'Factura pagada cambiada de estado' : null,
            'user_agent' => $request->header('User-Agent'),
        ]);
        return back()->with('status', "Factura #{$id} actualizada a {$request->status}.");
    }

    public function deleteInvoice(Request $request, $id) {
        $inv = DB::table('invoices')->where('id', $id)->first();
        if (!$inv) return back()->withErrors(['error' => 'Factura no encontrada.']);
        AuditLog::create([
            'user_id' => auth()->id(),
            'user_name' => auth()->user()->name,
            'user_role' => auth()->user()->role,
            'action' => 'FACTURA ELIMINADA',
            'module' => 'Finanzas',
            'ip_address' => $request->ip(),
            'details' => "Factura #{$id} eliminada: {$inv->patient_name} | {$inv->concept} | ${$inv->amount} | {$inv->status}",
            'is_suspicious' => true,
            'risk_reason' => 'Eliminacion de factura',
            'user_agent' => $request->header('User-Agent'),
        ]);
        DB::table('invoices')->where('id', $id)->delete();
        return back()->with('status', "Factura #{$id} eliminada.");
    }

    public function storeInsurance(Request $request) {
        $request->validate([
            'patient_name' => 'required',
            'policy_number' => 'required',
            'provider' => 'required',
            'status' => 'required',
        ]);
        DB::table('insurances')->insert([
            'patient_name' => $request->patient_name,
            'policy_number' => $request->policy_number,
            'provider' => $request->provider,
            'status' => $request->status,
            'created_at' => now(),
            'updated_at' => now(),
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
            'risk_reason' => $request->status === 'Falsa/Fraude' ? 'Poliza marcada como fraude al registro' : null,
            'user_agent' => $request->header('User-Agent'),
        ]);
        return back()->with('status', 'Seguro registrado correctamente.');
    }

    public function updateInsurance(Request $request, $id) {
        $ins = DB::table('insurances')->where('id', $id)->first();
        if (!$ins) return back()->withErrors(['error' => 'Seguro no encontrado.']);
        $old_status = $ins->status;
        DB::table('insurances')->where('id', $id)->update([
            'status' => $request->status,
            'updated_at' => now(),
        ]);
        AuditLog::create([
            'user_id' => auth()->id(),
            'user_name' => auth()->user()->name,
            'user_role' => auth()->user()->role,
            'action' => 'SEGURO ACTUALIZADO',
            'module' => 'Finanzas',
            'ip_address' => $request->ip(),
            'details' => "Seguro #{$id}: {$ins->patient_name} | Poliza: {$ins->policy_number} | Estado: {$old_status} -> {$request->status}",
            'is_suspicious' => $request->status === 'Falsa/Fraude',
            'risk_reason' => $request->status === 'Falsa/Fraude' ? 'Poliza marcada como fraude' : null,
            'user_agent' => $request->header('User-Agent'),
        ]);
        return back()->with('status', "Seguro #{$id} actualizado a {$request->status}.");
    }

    public function deleteInsurance(Request $request, $id) {
        $ins = DB::table('insurances')->where('id', $id)->first();
        if (!$ins) return back()->withErrors(['error' => 'Seguro no encontrado.']);
        AuditLog::create([
            'user_id' => auth()->id(),
            'user_name' => auth()->user()->name,
            'user_role' => auth()->user()->role,
            'action' => 'SEGURO ELIMINADO',
            'module' => 'Finanzas',
            'ip_address' => $request->ip(),
            'details' => "Seguro #{$id} eliminado: {$ins->patient_name} | {$ins->policy_number} | {$ins->provider} | {$ins->status}",
            'is_suspicious' => true,
            'risk_reason' => 'Eliminacion de registro de seguro',
            'user_agent' => $request->header('User-Agent'),
        ]);
        DB::table('insurances')->where('id', $id)->delete();
        return back()->with('status', "Seguro #{$id} eliminado.");
    }

    // --- PERSONAL ---
    public function personal() {
        $users = User::where('id', '!=', auth()->id())->orderBy('created_at', 'desc')->paginate(30);
        return view('superadmin.personal', compact('users'));
    }

    public function storeUser(Request $request) {
        $request->validate(['name' => 'required','email' => 'required|email|unique:users','password' => 'required|min:8','role' => 'required']);
        $data = $request->except(['password']);
        $data['password'] = Hash::make($request->password);
        $data['validation_status'] = 'Pendiente';
        User::create($data);
        return back()->with('status', 'Empleado registrado.');
    }

    public function approveUser(Request $request, User $user) { $user->update(['validation_status' => 'Aprobado']); return back(); }
    public function rejectUser(Request $request, User $user) { $user->update(['validation_status' => 'Rechazado', 'rejection_reason' => $request->rejection_reason]); return back(); }
    public function updateRole(Request $request, User $user) { $user->update(['role' => $request->role]); return back(); }
    public function toggleStatus(Request $request, User $user) { $user->update(['status' => !$user->status]); return back(); }
    public function deleteUser(User $user) { $user->delete(); return back(); }

    public function scoreRiesgo() {
        $users = User::where('id', '!=', auth()->id())->paginate(30);
        return view('superadmin.score_riesgo', compact('users'));
    }

    // --- URGENCIAS ---
    public function urgencias() {
        $triages = Triage::orderBy('created_at', 'desc')->paginate(30);
        $colors = ['Rojo' => '#DC2626', 'Naranja' => '#EA580C', 'Amarillo' => '#F59E0B', 'Verde' => '#F97316', 'Azul' => '#3B82F6'];
        $bdTotalDocs = \App\Models\MongoTriageLog::where('timestamp', '>=', now()->startOfDay())->count();
        $bdTodayPatients = \App\Models\MongoTriageLog::where('timestamp', '>=', now()->startOfDay())->count();
        $bdAvgFc = round(\App\Models\MongoTriageLog::where('timestamp', '>=', now()->startOfDay())->avg('vitals_fc') ?? 0);
        $bdRojoHoy = \App\Models\MongoTriageLog::where('timestamp', '>=', now()->startOfDay())->where('triage_level', 'Rojo')->count();
        return view('superadmin.urgencias', compact('triages', 'colors', 'bdTotalDocs', 'bdTodayPatients', 'bdAvgFc', 'bdRojoHoy'));
    }

    

    public function updateVitals(Request $request, Triage $triage) { $triage->update($request->only(['vitals_ta', 'vitals_fc', 'vitals_temp', 'vitals_spo2', 'assigned_area'])); $triage->update(['status' => 'En Atención']); return back(); }
    public function derivePatient(Request $request, Triage $triage) { $triage->update(['is_derived' => true, 'derivation_hospital' => $request->derivation_hospital, 'status' => 'Derivado']); return redirect()->route('superadmin.paseSalida', $triage->id); }
    public function paseSalida(Triage $triage) { $pdf = Pdf::loadView('reports.pase_salida', compact('triage')); return $pdf->stream('pase_salida.pdf'); }

    // --- MAPA DE CALOR ---
    public function mapaCalor() {
        $total_uci = Bed::where('type', 'UCI')->count();
        $occ_uci = Bed::where('type', 'UCI')->where('status', 'Ocupada')->count();
        $uci_percent = $total_uci > 0 ? round(($occ_uci / $total_uci) * 100) : 0;
        $urg_percent = min(100, round((Triage::whereIn('status', ['En Espera', 'En Atención'])->count() / 20) * 100));
        $critical_urgencies = Triage::whereIn('triage_level', ['Rojo', 'Naranja'])->count();
        $farmacia_alerts = Medication::whereRaw('stock <= min_stock')->count();
        $total_personal = User::where('status', 1)->count();
        return view('superadmin.mapa_calor', compact('uci_percent', 'urg_percent', 'critical_urgencies', 'farmacia_alerts', 'total_personal'));
    }

    // --- AUDITORÍA ---
    public function auditoria() {
        $query = AuditLog::query();
        
        if(request('module')) $query->where('module', request('module'));
        if(request('user')) $query->where('user_name', 'like', '%'.request('user').'%');
        if(request('risk')) $query->where('risk_level', request('risk'));
        if(request('suspicious')) $query->where('is_suspicious', true);
        if(request('patient')) $query->where('patient_name', 'like', '%'.request('patient').'%');
        
        $logs = $query->orderBy('created_at', 'desc')->paginate(40);
        
        $stats = [
            'total' => AuditLog::count(),
            'today' => AuditLog::where('created_at', '>=', now()->startOfDay())->count(),
            'suspicious' => AuditLog::where('is_suspicious', true)->count(),
            'critical' => AuditLog::where('risk_level', 'critico')->count(),
            'high' => AuditLog::where('risk_level', 'alto')->count(),
            'modules' => AuditLog::distinct('module')->count(),
            'users_active' => AuditLog::where('created_at', '>=', now()->startOfDay())->distinct('user_id')->count(),
        ];
        
        $hourlyData = collect(range(0,23))->mapWithKeys(function($h) { return [$h => 0]; });
        $hourlyRaw = AuditLog::where('created_at', '>=', now()->subHours(24))->get()
            ->groupBy(function($item) { return (int)date('G', strtotime($item->created_at)); })
            ->map(function($items) { return $items->count(); });
        foreach($hourlyRaw as $h => $cnt) { $hourlyData[$h] = $cnt; }
        
        $topActions = AuditLog::where('created_at', '>=', now()->subDays(7))->get()
            ->groupBy('action')->map(function($items, $key) { return (object)['action' => $key, 'total' => $items->count()]; })
            ->sortByDesc('total')->take(10)->values();
        
        $byModule = AuditLog::where('created_at', '>=', now()->subDays(7))->get()
            ->groupBy('module')->map(function($items, $key) { 
                return (object)['module' => $key, 'total' => $items->count(), 'suspicious' => $items->where('is_suspicious', true)->count()]; 
            })->sortByDesc('total')->values();
        
        $topUsers = AuditLog::where('created_at', '>=', now()->subDays(7))->get()
            ->groupBy(function($item) { return $item->user_name.'|'.$item->user_role; })
            ->map(function($items, $key) {
                $parts = explode('|', $key);
                return (object)['user_name' => $parts[0], 'user_role' => $parts[1], 'total' => $items->count(), 'suspicious' => $items->where('is_suspicious', true)->count()];
            })->sortByDesc('total')->take(10)->values();
        
        $riskUsers = AuditLog::where('created_at', '>=', now()->subDays(30))
            ->where(function($q) { $q->where('is_suspicious', true)->orWhereIn('risk_level', ['alto','critico']); })
            ->get()
            ->groupBy(function($item) { return $item->user_name.'|'.$item->user_role; })
            ->map(function($items, $key) {
                $parts = explode('|', $key);
                return (object)[
                    'user_name' => $parts[0], 'user_role' => $parts[1],
                    'total_actions' => $items->count(),
                    'suspicious' => $items->where('is_suspicious', true)->count(),
                    'critical' => $items->where('risk_level', 'critico')->count(),
                    'high' => $items->where('risk_level', 'alto')->count(),
                    'active_days' => $items->groupBy(function($i) { return date('Y-m-d', strtotime($i->created_at)); })->count(),
                ];
            })->sortByDesc('critical')->take(15)->values();
        
        $alerts = AuditLog::where('is_suspicious', true)->orderBy('created_at','desc')->limit(10)->get();
        
        $negligencia = DB::table('triages')
            ->whereIn('status', ['En Espera','En Atencion'])
            ->where('created_at', '<', now()->subHours(2))
            ->orderBy('triage_level')->limit(10)->get();
        
        // Variables para tabs adicionales
        $accesos = AuditLog::whereIn('action', ['LOGIN','LOGIN FALLIDO','LOGOUT','Sesion Bloqueada','PIN Incorrecto','Acceso No Autorizado','Intento Fuerza Bruta'])->orderBy('created_at','desc')->limit(50)->get();
        $loginExitoso = AuditLog::where('action','LOGIN')->where('created_at','>=',now()->subDays(7))->count();
        $loginFallido = AuditLog::where('action','LOGIN FALLIDO')->where('created_at','>=',now()->subDays(7))->count();
        $bloqueos = AuditLog::whereIn('action',['Sesion Bloqueada','Intento Fuerza Bruta'])->where('created_at','>=',now()->subDays(7))->count();
        
        $medicaLogs = AuditLog::where('module','Medico')->orderBy('created_at','desc')->limit(40)->get();
        $recetas = AuditLog::where('action','Receta Medica')->where('created_at','>=',now()->subDays(7))->count();
        $cirugias = AuditLog::where('action','Cirugia Programada')->where('created_at','>=',now()->subDays(7))->count();
        $defunciones = AuditLog::where('action','Certificado Defuncion')->count();
        
        $hospLogs = AuditLog::where('module','Hospitalizacion')->orderBy('created_at','desc')->limit(40)->get();
        $ingresos = AuditLog::where('action','Paciente Hospitalizado')->where('created_at','>=',now()->subDays(7))->count();
        $altas = AuditLog::where('action','Alta Medica')->where('created_at','>=',now()->subDays(7))->count();
        $traslados = AuditLog::where('action','Traslado')->where('created_at','>=',now()->subDays(7))->count();
        
        $finLogs = AuditLog::where('module','Finanzas')->orderBy('created_at','desc')->limit(40)->get();
        
        $pharmaLogs = AuditLog::where('module','Farmacia')->orderBy('created_at','desc')->limit(40)->get();
        $controlados = AuditLog::where('module','Farmacia')->where('action','like','%Controlado%')->orderBy('created_at','desc')->limit(20)->get();
        
        $topUsersAll = AuditLog::where('created_at','>=',now()->subDays(30))->get()->groupBy('user_name');
        
        // IA - Deteccion de anomalias
        $anomalias = collect();
        $ipsFallidas = AuditLog::where('action','LOGIN FALLIDO')->where('created_at','>=',now()->subHours(24))->get()->groupBy('ip_address')->filter(function($g){ return $g->count() >= 3; });
        foreach($ipsFallidas as $ip => $items) {
            $anomalias->push((object)['tipo'=>'Fuerza Bruta','severity'=>'critico','icon'=>'fa-hammer','desc'=>"IP $ip con ".$items->count()." logins fallidos en 24h","module"=>'Seguridad']);
        }
        $fueraHorario = AuditLog::where('action','LOGIN')->where('created_at','>=',now()->subDays(7))->get()->filter(function($l){ $h = (int)date('G', strtotime($l->created_at)); return $h < 6 || $h > 22; })->groupBy('user_name');
        foreach($fueraHorario as $user => $items) {
            $anomalias->push((object)['tipo'=>'Acceso Nocturno','severity'=>'alto','icon'=>'fa-moon','desc'=>"$user accedio fuera de horario ".$items->count()." veces","module"=>'Seguridad']);
        }
        $rapidEdits = AuditLog::where('created_at','>=',now()->subHours(1))->get()->groupBy('user_name')->filter(function($g){ return $g->count() > 20; });
        foreach($rapidEdits as $user => $items) {
            $anomalias->push((object)['tipo'=>'Modificacion Masiva','severity'=>'alto','icon'=>'fa-bolt','desc'=>"$user hizo ".$items->count()." acciones en 1 hora","module"=>'Sistema']);
        }
        
        $riskDist = AuditLog::where('created_at','>=',now()->subDays(30))->get()->groupBy('risk_level')->map(function($g){ return $g->count(); });
        $riskAreas = AuditLog::where('created_at','>=',now()->subDays(30))->get()->groupBy('module')->map(function($items,$key){ return (object)['module'=>$key,'total'=>$items->count(),'suspicious'=>$items->where('is_suspicious',true)->count(),'critical'=>$items->where('risk_level','critico')->count()]; })->sortByDesc('suspicious')->take(10);
        
        return view('superadmin.auditoria', compact(
            'logs','stats','hourlyData','topActions','byModule','topUsers','riskUsers','alerts','negligencia',
            'accesos','loginExitoso','loginFallido','bloqueos',
            'medicaLogs','recetas','cirugias','defunciones',
            'hospLogs','ingresos','altas','traslados',
            'finLogs','pharmaLogs','controlados',
            'topUsersAll','anomalias','riskDist','riskAreas'
        ));
    }

    public function actividadSospechosa() {
        $suspicious = AuditLog::where('is_suspicious', true)->orderBy('created_at', 'desc')->paginate(30);
        return view('superadmin.sospechosa', compact('suspicious'));
    }

    // --- MONITOR LIVE ---
    public function monitorLive() {
        $sessions = DB::table('sessions')->get();
        $users = User::whereIn('id', $sessions->pluck('user_id')->unique()->filter())->pluck('name', 'id');
        $roles = User::whereIn('id', $sessions->pluck('user_id')->unique()->filter())->pluck('role', 'id');
        $sessions = $sessions->map(function ($s) use ($users, $roles) { $s->user_name = $users[$s->user_id] ?? 'Invitado'; $s->user_role = $roles[$s->user_id] ?? 'N/A'; return $s; });
        $urgencies = Triage::whereIn('status', ['En Espera', 'En Atención'])->count();
        $low_stock = Medication::whereRaw('stock <= min_stock')->count();
        return view('superadmin.monitor', compact('sessions', 'urgencies', 'low_stock'));
    }

    // --- LIMPIEZA ---
    public function limpieza() { return view('superadmin.limpieza'); }
    public function cleanData(Request $request) {
        $action = $request->action;
        $result_text = "";
        if ($action == 'uppercase') {
            $users = User::all();
            foreach ($users as $user) { $user->name = strtoupper($user->name); if($user->curp) $user->curp = strtoupper($user->curp); if($user->rfc) $user->rfc = strtoupper($user->rfc); $user->save(); }
            $result_text = "Estandarización completada.";
        } elseif ($action == 'validate_docs') {
            $no_ine = User::whereNull('ine_path')->count();
            $result_text = "Validación: {$no_ine} usuarios sin INE cargado.";
        } elseif ($action == 'duplicates') {
            $result_text = "No se encontraron RFCs duplicados.";
        }
        return back()->with('clean_result', $result_text);
    }

    // --- INGESTA ---
    public function ingesta() { return view('superadmin.ingesta'); }
    public function uploadCSV(Request $request) {
        $request->validate(['csv_file' => 'required|file|mimes:csv,txt']);
        $path = $request->file('csv_file')->getRealPath();
        $file = fopen($path, 'r');
        $headers = fgetcsv($file, 1000, ',');
        $rows = []; $count = 0;
        while (($data = fgetcsv($file, 1000, ',')) !== FALSE && $count < 5) { $rows[] = $data; $count++; }
        fclose($file);
        return back()->with('csv_headers', $headers)->with('csv_preview', $rows);
    }

    // --- FARMACIA ---
    public function farmacia() {
        $medications = Medication::orderBy('stock', 'asc')->get();
        $triages = Triage::whereIn('status', ['En Espera', 'En Atención'])->take(50)->get();
        $critical_patients = Triage::where('triage_level', 'Rojo')->whereIn('status', ['En Espera', 'En Atención'])->take(20)->get();
        $normal_patients = Triage::whereIn('triage_level', ['Verde', 'Azul'])->whereIn('status', ['En Espera', 'En Atención'])->take(20)->get();
        $prescriptions = collect();
        return view('superadmin.farmacia', compact('medications', 'triages', 'critical_patients', 'normal_patients', 'prescriptions'));
    }

    public function storeMedication(Request $request) { Medication::create($request->all()); return back(); }
    public function prescribe(Request $request) { return back()->with('status', 'Prescripción registrada.'); }

    // --- REPORTES ---
    public function reportPersonal() { $users = User::all(); $pdf = Pdf::loadView('reports.personal', compact('users')); return $pdf->stream('reporte_personal.pdf'); }
    public function reportCamas() { $beds = Bed::orderBy('floor')->get(); $pdf = Pdf::loadView('reports.camas', compact('beds')); return $pdf->stream('reporte_recursos.pdf'); }
    public function reportFarmacia() { $medications = Medication::orderBy('stock')->get(); $pdf = Pdf::loadView('reports.farmacia', compact('medications')); return $pdf->stream('reporte_farmacia.pdf'); }

    // --- CAMAS ---
    public function camas() { $beds = Bed::orderBy('floor')->orderBy('room_number')->paginate(40); return view('superadmin.camas', compact('beds')); }
    public function storeBed(Request $request) { Bed::create($request->all()); return back(); }
    public function updateBedStatus(Request $request, Bed $bed) { $bed->update(['status' => $request->status]); return back(); }

    // --- PROVEEDORES ---
    public function proveedores() { $providers = Provider::paginate(30); return view('superadmin.proveedores', compact('providers')); }
    public function storeProvider(Request $request) { Provider::create($request->all()); return back(); }
    public function toggleProviderStatus(Request $request, Provider $provider) { $provider->update(['status' => $provider->status === 'Activo' ? 'Inactivo' : 'Activo']); return back(); }
    public function deleteProvider(Provider $provider) { $provider->delete(); return back(); }

    public function forceLogout($sessionId) { DB::table('sessions')->where('id', $sessionId)->delete(); return back(); }

    // --- ROLES ---
    public function roles() {
        $roles = ['SuperAdmin', 'Administrador Hospitalario', 'Médico A', 'Médico B', 'Médico C', 'Enfermera A', 'Enfermera B', 'Enfermera C', 'Recepcionista', 'Farmacéutico', 'Admin Farmacia', 'Finanzas', 'Laboratorista', 'Urgenciólogo'];
        $modules = ['dashboard_ejecutivo' => 'Dashboard Ejecutivo', 'validacion_personal' => 'Validación de Personal', 'roles_permisos' => 'Roles y Permisos', 'seguridad' => 'Seguridad Centralizada', 'monitor_live' => 'Monitor Live Hospital', 'actividad_sospechosa' => 'Detección Sospechosa', 'replay_sesiones' => 'Replay de Sesiones', 'auditoria' => 'Auditoría Total', 'urgencias' => 'Centro de Urgencias', 'farmacia' => 'Supervisión Farmacia', 'recursos' => 'Recursos Hospitalarios', 'mapa_calor' => 'Mapa de Calor', 'ingesta_datos' => 'Centro de Ingesta', 'limpieza_datos' => 'Motor de Limpieza', 'etl_bigdata' => 'Centro ETL / Big Data', 'ia_anomalias' => 'IA Anomalías', 'arbol_decisiones' => 'Árbol de Decisiones', 'score_riesgo' => 'Score de Riesgo', 'reportes' => 'Reportes Automáticos'];
        $permissions = RolePermission::all();
        return view('superadmin.roles', compact('roles', 'modules', 'permissions'));
    }

    public function togglePermission(Request $request) {
        $perm = RolePermission::where('role', $request->role)->where('module_key', $request->module_key)->first();
        if ($perm) { $perm->update(['can_access' => !$perm->can_access]); }
        else { RolePermission::create(['role' => $request->role, 'module_key' => $request->module_key, 'can_access' => false]); }
        return back();
    }
    public function exportAuditPDF(Request $request)
    {
        $logs = AuditLog::orderBy('created_at','desc')->limit(500)->get();
        $pdf = Pdf::loadView('reports.audit', compact('logs'));
        return $pdf->stream('auditoria_'.date('Ymd').'.pdf');
    }

    public function exportAuditCSV(Request $request)
    {
        $fileName = "auditoria_".date('Ymd').".csv";
        $logs = AuditLog::orderBy('created_at','desc')->limit(2000)->get();
        $csv = "\xEF\xBB\xBF";
        $csv .= "Fecha,Usuario,Rol,Accion,Modulo,Detalles,IP,Riesgo,Sospechoso\n";
        foreach($logs as $l) {
            $csv .= date('d/m/Y H:i', strtotime($l->created_at)).","
                .$l->user_name.",".$l->user_role.","
                .$l->action.",".$l->module.","
                .'"'.str_replace('"','""',$l->details ?? '').'",'
                .$l->ip_address.",".($l->risk_level ?? 'bajo').","
                .($l->is_suspicious ? 'SI' : 'NO')."\n";
        }
        return response($csv)->header('Content-Type','text/csv; charset=UTF-8')
            ->header('Content-Disposition','attachment; filename="'.$fileName.'"');
    }

    public function exportAuditJSON(Request $request)
    {
        $logs = AuditLog::orderBy('created_at','desc')->limit(2000)->get();
        return response()->json($logs, 200, [], JSON_PRETTY_PRINT);
    }

    public function storeTriage(Request $request)
    {
        // ==========================================
        // ETL: DROPLICATES EN TIEMPO REAL
        // ==========================================
        $existingPatient = Triage::where('patient_name', $request->patient_name)
            ->where('age', $request->age)
            ->whereIn('status', ['En Espera', 'En Atención'])
            ->whereDate('created_at', today())
            ->first();

        if ($existingPatient) {
            return back()->with('etl_error', 'ETL Big Data: Registro bloqueado. El paciente ya esta activo en Urgencias hoy (Duplicado evitado).');
        }

        // Fix para campo symptoms y creación en MySQL
        $data = $request->all();
        $data['symptoms'] = $data['symptoms'] ?? $data['chief_complaint'] ?? 'Pendiente';
        $data['status'] = 'En Espera';
        $triage = Triage::create($data);

        // ==========================================
        // ETL: CARGA EN MONGODB ATLAS (REAL TIME)
        // ==========================================
        \App\Models\MongoTriageLog::create([
            'patient_id' => $triage->patient_name,
            'triage_level' => $triage->triage_level,
            'age' => $triage->age,
            'specialty' => 'Urgencias',
            'vitals_fc' => $triage->vitals_fc ?? 80,
            'vitals_temp' => $triage->vitals_temp ?? 36.5,
            'vitals_spo2' => $triage->vitals_spo2 ?? 98,
            'timestamp' => now()
        ]);

        // ==========================================
        // ETL: CARGA EN MONGODB ATLAS (REAL TIME)
        // ==========================================
        \App\Models\MongoTriageLog::create([
            'patient_id' => $request->patient_name,
            'triage_level' => $request->triage_level,
            'age' => $request->age,
            'specialty' => 'Urgencias',
            'vitals_fc' => $request->vitals_fc ?? 80,
            'vitals_temp' => $request->vitals_temp ?? 36.5,
            'vitals_spo2' => $request->vitals_spo2 ?? 98,
            'timestamp' => now()
        ]);

        return back()->with('status', 'Paciente ingresado correctamente.');
    }

 }