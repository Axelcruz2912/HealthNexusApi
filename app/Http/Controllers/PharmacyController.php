<?php

namespace App\Http\Controllers;

use App\Models\Medication;
use App\Models\AuditLog;
use App\Models\Provider;
use App\Models\Triage;
use App\Models\User;
use App\Models\PurchaseOrder;
use App\Models\OrderItem;
use App\Models\PatientMedication;
use App\Models\CrashCart;
use App\Models\RestockRequest;
use App\Models\MedicationAlternative;
use Illuminate\Http\Request;

class PharmacyController extends Controller
{
    // ===== DASHBOARD MEJORADO =====
    public function dashboard() {
        // CENTRAL
        $centralStock = Medication::where('origin', 'Central')->sum('stock');
        $centralValue = Medication::where('origin', 'Central')->selectRaw('SUM(stock * price) as total')->value('total') ?? 0;
        $centralLow = Medication::where('origin', 'Central')->whereRaw('stock <= min_stock')->where('stock', '>', 0)->count();
        $centralOut = Medication::where('origin', 'Central')->where('stock', 0)->count();
        
        // HOSPITALARIA
        $hospStock = Medication::whereIn('origin', ['Hospitalaria', 'Urgencias', 'Quirurgico'])->sum('stock');
        $hospValue = Medication::whereIn('origin', ['Hospitalaria', 'Urgencias', 'Quirurgico'])->selectRaw('SUM(stock * price) as total')->value('total') ?? 0;
        
        // GENERAL
        $total = Medication::count();
        $low_stock = Medication::whereRaw('stock <= min_stock')->where('stock', '>', 0)->orderBy('stock', 'asc')->get();
        $expiring_soon = Medication::whereDate('expiry_date', '<=', now()->addDays(30))->orderBy('expiry_date', 'asc')->get();
        $out_of_stock = Medication::where('stock', 0)->count();
        $stock_value = Medication::selectRaw('SUM(stock * price) as total')->value('total') ?? 0;
        $controlled = Medication::where('required_level', 'A')->count();
        $expiring_critical = Medication::whereDate('expiry_date', '<=', now()->addDays(7))->count();
        
        // OPERACIONES
        $pending_orders = PurchaseOrder::whereIn('status', ['Borrador', 'Enviada', 'En Transito'])->count();
        $cart_alerts = CrashCart::where('status', '!=', 'Completo')->count();
        $dispensed_today = PatientMedication::whereDate('created_at', today())->count();
        $controladosUsados = PatientMedication::whereDate('created_at', today())->whereHas('medication', function($q) { $q->where('required_level', 'A'); })->count();
        $pending_restock = RestockRequest::whereIn('status', ['Solicitada', 'Aprobada'])->count();
        
        // TOP MEDICAMENTOS DISPENSADOS HOY
        $topDispensed = PatientMedication::whereDate('created_at', today())
            ->selectRaw('medication_name, SUM(quantity) as total')
            ->groupBy('medication_name')
            ->orderByDesc('total')
            ->take(5)
            ->get();
            
        // ULTIMAS DISPENSACIONES
        $recentDispensed = PatientMedication::orderBy('created_at', 'desc')->take(8)->get();

        return view('farmacia.dashboard', compact(
            'centralStock', 'centralValue', 'centralLow', 'centralOut',
            'hospStock', 'hospValue',
            'total', 'low_stock', 'expiring_soon', 'out_of_stock', 'stock_value', 'controlled', 'expiring_critical',
            'pending_orders', 'cart_alerts', 'dispensed_today', 'controladosUsados', 'pending_restock',
            'topDispensed', 'recentDispensed'
        ));
    }

    // ===== INVENTARIO =====
    public function inventory() {
        $medications = Medication::orderBy('origin')->orderBy('required_level')->get();
        return view('farmacia.inventory', compact('medications'));
    }

    public function controlled() {
        $controlled_meds = Medication::where('required_level', 'A')->get();
        return view('farmacia.controlled', compact('controlled_meds'));
    }

    public function enfermeraMeds() {
        $meds = Medication::where('enfermera_can_administer', true)->get();
        return view('farmacia.enfermera', compact('meds'));
    }

    public function storeMedication(Request $request) {
        $request->validate([
            'name' => 'required', 'active_ingredient' => 'required', 'stock' => 'required|integer',
            'min_stock' => 'required|integer', 'price' => 'required|numeric',
            'required_level' => 'required|in:A,B,C,Enfermera', 'origin' => 'required',
            'lot_number' => 'required', 'expiry_date' => 'required|date',
        ]);
        $data = $request->all();
        $data['enfermera_can_administer'] = $request->has('enfermera_can_administer') || $request->required_level === 'Enfermera';
        $med = Medication::create($data);
        AuditLog::create(['user_id' => auth()->id(), 'user_name' => auth()->user()->name, 'user_role' => auth()->user()->role, 'action' => 'Medicamento Registrado', 'module' => 'Farmacia - Inventario', 'ip_address' => $request->ip(), 'details' => $med->name . ' | Nivel: ' . $med->required_level]);
        return back()->with('success', 'Medicamento registrado.');
    }

    // ===== DISPENSACION =====
    public function dispensacion() {
        $medications = Medication::where('stock', '>', 0)->orderBy('name')->get();
        $patients = Triage::whereIn('status', ['En Espera', 'En Atención', 'Hospitalizado'])->get();
        $doctors = User::whereIn('role', ['Médico A', 'Médico B', 'Médico C', 'Enfermera A', 'Enfermera B', 'Enfermera C', 'Urgenciólogo'])->where('status', 'Activo')->get();
        $recent = PatientMedication::orderBy('created_at', 'desc')->take(10)->get();
        return view('farmacia.dispensacion', compact('medications', 'patients', 'doctors', 'recent'));
    }

    public function dispenseMedication(Request $request) {
        $request->validate(['medication_id' => 'required|exists:medications,id', 'patient_id' => 'required|exists:triages,id', 'doctor_id' => 'required|exists:users,id', 'quantity' => 'required|integer|min:1']);
        $med = Medication::findOrFail($request->medication_id);
        $doctor = User::findOrFail($request->doctor_id);
        $patient = Triage::findOrFail($request->patient_id);

        $denied = false; $reason = '';
        if ($med->required_level == 'A' && !in_array($doctor->role, ['Médico A', 'Urgenciólogo'])) { $denied = true; $reason = 'Solo Médico A o Urgenciólogo puede recetar Nivel A.'; }
        elseif ($med->required_level == 'B' && !in_array($doctor->role, ['Médico A', 'Médico B', 'Urgenciólogo'])) { $denied = true; $reason = 'Se requiere al menos Médico B para Nivel B.'; }
        elseif (!$med->enfermera_can_administer && in_array($doctor->role, ['Enfermera A', 'Enfermera B', 'Enfermera C'])) { $denied = true; $reason = 'Enfermería solo puede dispensar medicamentos autorizados.'; }

        if ($denied) {
            AuditLog::create(['user_id' => auth()->id(), 'user_name' => auth()->user()->name, 'user_role' => auth()->user()->role, 'action' => 'Dispensacion DENEGADA', 'module' => 'Farmacia - Recetas', 'ip_address' => $request->ip(), 'details' => "Dr. {$doctor->name} ({$doctor->role}) intento recetar {$med->name}. Motivo: {$reason}"]);
            return back()->with('error', $reason)->withInput();
        }
        if ($request->quantity > $med->stock) { return back()->with('error', "Stock insuficiente. Solo hay {$med->stock} unidades.")->withInput(); }

        $interactionAlert = false; $interactionDetails = null;
        $interactions = $this->checkInteractions($med, $patient);
        if ($interactions) { $interactionAlert = true; $interactionDetails = $interactions; }

        $med->decrement('stock', $request->quantity);

        PatientMedication::create([
            'triage_id' => $patient->id, 'patient_name' => $patient->patient_name,
            'medication_id' => $med->id, 'medication_name' => $med->name,
            'quantity' => $request->quantity, 'dispensed_by' => auth()->id(),
            'prescribed_by' => $doctor->id, 'interaction_alert' => $interactionAlert,
            'interaction_details' => $interactionDetails,
        ]);

        AuditLog::create(['user_id' => auth()->id(), 'user_name' => auth()->user()->name, 'user_role' => auth()->user()->role, 'action' => 'Medicamento Dispensado', 'module' => 'Farmacia - Recetas', 'ip_address' => $request->ip(), 'details' => "{$med->name} x{$request->quantity} para {$patient->patient_name}" . ($interactionAlert ? ' | ALERTA' : '')]);

        if ($med->fresh()->stock <= $med->min_stock && $med->fresh()->stock > 0) {
            $existing = RestockRequest::where('medication_id', $med->id)->whereIn('status', ['Solicitada', 'Aprobada'])->first();
            if (!$existing) { $this->autoRequestRestock($med); }
        }

        $msg = "Receta dispensada: {$med->name} x{$request->quantity}. Stock actual: {$med->fresh()->stock}";
        if ($interactionAlert) { $msg .= " | ALERTA: {$interactionDetails}"; }
        return back()->with($interactionAlert ? 'warning' : 'success', $msg);
    }

    private function checkInteractions($newMed, $patient) {
        $patientMeds = PatientMedication::where('triage_id', $patient->id)->whereDate('created_at', '>=', now()->subDays(7))->pluck('medication_name')->toArray();
        if (empty($patientMeds)) return null;
        $criticalInteractions = ['Morfina' => ['Midazolam', 'Fentanilo', 'Diazepam'], 'Midazolam' => ['Morfina', 'Fentanilo'], 'Fentanilo' => ['Morfina', 'Midazolam', 'Diazepam'], 'Diazepam' => ['Morfina', 'Fentanilo', 'Midazolam']];
        $interactions = $criticalInteractions[$newMed->name] ?? [];
        $found = [];
        foreach ($patientMeds as $prevMed) { if (in_array($prevMed, $interactions)) { $found[] = $prevMed; } }
        if (!empty($found)) { return "INTERACCION con: " . implode(', ', $found) . ". Depresion respiratoria potencial."; }
        return null;
    }

    private function autoRequestRestock($med) {
        $priority = $med->stock == 0 ? 'Critica' : ($med->stock <= 3 ? 'Alta' : 'Media');
        $qty = $med->min_stock * 3;
        $reqNum = 'REQ-' . date('Ymd') . '-' . str_pad(RestockRequest::count() + 1, 4, '0', STR_PAD_LEFT);
        RestockRequest::create([
            'request_number' => $reqNum, 'medication_id' => $med->id,
            'quantity_requested' => $qty, 'priority' => $priority,
            'status' => 'Solicitada', 'requested_by' => auth()->id(),
            'reason' => "Stock bajo automatico: {$med->stock} unidades (Minimo: {$med->min_stock})",
            'required_by' => now()->addDays($priority == 'Critica' ? 1 : ($priority == 'Alta' ? 3 : 7)),
        ]);
        AuditLog::create(['user_id' => auth()->id(), 'user_name' => auth()->user()->name, 'user_role' => auth()->user()->role, 'action' => 'Solicitud Auto Reabastecimiento', 'module' => 'Farmacia - Desabasto', 'ip_address' => request()->ip(), 'details' => "{$reqNum}: {$med->name} x{$qty} - Prioridad: {$priority}"]);
    }

    // ===== DESABASTO MEJORADO =====
    public function desabasto() {
        $low = Medication::whereRaw('stock <= min_stock')->where('stock', '>', 0)->orderBy('stock', 'asc')->get();
        $out = Medication::where('stock', 0)->orderBy('name')->get();
        $requests = RestockRequest::with('medication', 'requester')->orderBy('created_at', 'desc')->take(20)->get();
        return view('farmacia.desabasto', compact('low', 'out', 'requests'));
    }

    public function requestRestock(Request $request) {
        $request->validate(['medication_id' => 'required|exists:medications,id', 'quantity' => 'required|integer|min:1', 'priority' => 'required|in:Baja,Media,Alta,Critica', 'reason' => 'nullable|string']);
        $med = Medication::findOrFail($request->medication_id);
        $reqNum = 'REQ-' . date('Ymd') . '-' . str_pad(RestockRequest::count() + 1, 4, '0', STR_PAD_LEFT);
        RestockRequest::create([
            'request_number' => $reqNum, 'medication_id' => $med->id,
            'quantity_requested' => $request->quantity, 'priority' => $request->priority,
            'status' => 'Solicitada', 'requested_by' => auth()->id(),
            'reason' => $request->reason,
            'required_by' => now()->addDays($request->priority == 'Critica' ? 1 : ($request->priority == 'Alta' ? 3 : 7)),
        ]);
        AuditLog::create(['user_id' => auth()->id(), 'user_name' => auth()->user()->name, 'user_role' => auth()->user()->role, 'action' => 'Solicitud de Reabastecimiento', 'module' => 'Farmacia - Desabasto', 'ip_address' => $request->ip(), 'details' => "{$reqNum}: {$med->name} x{$request->quantity} - {$request->priority}"]);
        return back()->with('success', "Solicitud {$reqNum} creada para {$med->name}.");
    }

    public function approveRestock($id) {
        $req = RestockRequest::findOrFail($id);
        $req->update(['status' => 'Aprobada', 'approved_by' => auth()->id(), 'quantity_approved' => $req->quantity_requested]);
        AuditLog::create(['user_id' => auth()->id(), 'user_name' => auth()->user()->name, 'user_role' => auth()->user()->role, 'action' => 'Solicitud Aprobada', 'module' => 'Farmacia - Desabasto', 'ip_address' => request()->ip(), 'details' => $req->request_number . ' aprobada']);
        return back()->with('success', "Solicitud {$req->request_number} aprobada.");
    }

    // ===== HISTORIAL PACIENTE =====
    public function pacienteHistorial($id) {
        $patient = Triage::findOrFail($id);
        $medications = PatientMedication::where('triage_id', $id)->orderBy('created_at', 'desc')->get();
        return view('farmacia.paciente_historial', compact('patient', 'medications'));
    }

    // ===== ORDENES DE COMPRA =====
    public function ordenes() {
        $orders = PurchaseOrder::with('provider', 'items.medication')->orderBy('created_at', 'desc')->get();
        return view('farmacia.ordenes', compact('orders'));
    }

    public function crearOrden() {
        $providers = Provider::where('status', 'Activo')->orderBy('name')->get();
        $lowStock = Medication::whereRaw('stock <= min_stock')->orderBy('name')->get();
        return view('farmacia.orden_crear', compact('providers', 'lowStock'));
    }

    public function storeOrden(Request $request) {
        $request->validate(['provider_id' => 'required|exists:providers,id', 'items' => 'required|array|min:1', 'items.*.medication_id' => 'required|exists:medications,id', 'items.*.quantity' => 'required|integer|min:1']);
        $po_number = 'PO-' . date('Ymd') . '-' . str_pad(PurchaseOrder::count() + 1, 4, '0', STR_PAD_LEFT);
        $total = 0; $items = [];
        foreach ($request->items as $item) {
            $med = Medication::find($item['medication_id']);
            $subtotal = $med->price * $item['quantity']; $total += $subtotal;
            $items[] = new OrderItem(['medication_id' => $med->id, 'quantity' => $item['quantity'], 'unit_price' => $med->price, 'subtotal' => $subtotal]);
        }
        $order = PurchaseOrder::create(['po_number' => $po_number, 'provider_id' => $request->provider_id, 'status' => 'Borrador', 'total_amount' => $total, 'expected_delivery' => now()->addDays(3)]);
        $order->items()->saveMany($items);
        AuditLog::create(['user_id' => auth()->id(), 'user_name' => auth()->user()->name, 'user_role' => auth()->user()->role, 'action' => 'Orden de Compra Creada', 'module' => 'Farmacia - Compras', 'ip_address' => $request->ip(), 'details' => "{$po_number} por \${$total}"]);
        return redirect()->route('farmacia.ordenes')->with('success', "Orden {$po_number} creada.");
    }

    public function recibirOrden($id) {
        $order = PurchaseOrder::findOrFail($id);
        $order->update(['status' => 'Recibida', 'received_date' => now()]);
        foreach ($order->items as $item) { $item->medication->increment('stock', $item->quantity); }
        $provider = $order->provider;
        $provider->increment('total_orders');
        if ($order->received_date > $order->expected_delivery) { $provider->increment('late_deliveries'); }
        AuditLog::create(['user_id' => auth()->id(), 'user_name' => auth()->user()->name, 'user_role' => auth()->user()->role, 'action' => 'Orden Recibida', 'module' => 'Farmacia - Compras', 'ip_address' => request()->ip(), 'details' => $order->po_number]);
        return back()->with('success', "Orden recibida. Stock actualizado.");
    }

    // ===== PROVEEDORES =====
    public function proveedores() {
        $providers = Provider::orderBy('name')->get();
        return view('farmacia.proveedores', compact('providers'));
    }

    // ===== CRASH CARTS =====
    public function crashCarts() {
        $carts = CrashCart::orderBy('name')->get();
        return view('farmacia.crash_carts', compact('carts'));
    }

    public function checkCart($id) {
        $cart = CrashCart::findOrFail($id);
        $cart->update(['last_checked' => now(), 'checked_by' => auth()->user()->name, 'status' => 'Completo']);
        AuditLog::create(['user_id' => auth()->id(), 'user_name' => auth()->user()->name, 'user_role' => auth()->user()->role, 'action' => 'Carro Verificado', 'module' => 'Farmacia - Crash Cart', 'ip_address' => request()->ip(), 'details' => $cart->name]);
        return back()->with('success', "{$cart->name} verificado.");
    }

    // ===== OTRAS VISTAS =====
    public function movimientos() {
        $logs = AuditLog::where('module', 'LIKE', 'Farmacia%')->orderBy('created_at', 'desc')->take(50)->get();
        return view('farmacia.movimientos', compact('logs'));
    }

    public function anomalias() {
        $denied = AuditLog::where('module', 'LIKE', 'Farmacia%')->where('action', 'Dispensacion DENEGADA')->orderBy('created_at', 'desc')->take(20)->get();
        $critical = Medication::where('stock', '<=', 5)->get();
        $expired = Medication::whereDate('expiry_date', '<', now())->get();
        $interactions = PatientMedication::where('interaction_alert', true)->orderBy('created_at', 'desc')->take(20)->get();
        return view('farmacia.anomalias', compact('denied', 'critical', 'expired', 'interactions'));
    }

    public function consumo() {
        $origins = Medication::selectRaw('origin, SUM(stock) as total_stock, COUNT(*) as total_meds, SUM(stock * price) as total_value')->groupBy('origin')->get();
        $levels = Medication::selectRaw('required_level, SUM(stock) as total_stock, COUNT(*) as total_meds')->groupBy('required_level')->get();
        return view('farmacia.consumo', compact('origins', 'levels'));
    }

    public function exportar() { return view('farmacia.exportar'); }
    public function carga() { return view('farmacia.carga'); }

    public function traspasos() {
        $medications = Medication::where('stock', '>', 0)->orderBy('name')->get();
        $origins = ['Central', 'Hospitalaria', 'Quirurgico', 'Urgencias'];
        return view('farmacia.traspasos', compact('medications', 'origins'));
    }

    public function storeTraspaso(Request $request) {
        $request->validate(['medication_id' => 'required|exists:medications,id', 'origin' => 'required', 'destination' => 'required|different:origin', 'quantity' => 'required|integer|min:1']);
        $med = Medication::findOrFail($request->medication_id);
        if ($request->quantity > $med->stock) return back()->with('error', 'Stock insuficiente.');
        $med->decrement('stock', $request->quantity);
        $newMed = $med->replicate(); $newMed->origin = $request->destination; $newMed->stock = $request->quantity; $newMed->save();
        AuditLog::create(['user_id' => auth()->id(), 'user_name' => auth()->user()->name, 'user_role' => auth()->user()->role, 'action' => 'Traspaso', 'module' => 'Farmacia', 'ip_address' => $request->ip(), 'details' => "{$med->name} x{$request->quantity} de {$request->origin} a {$request->destination}"]);
        return back()->with('success', "Traspaso realizado.");
    }

    public function exportInventoryPDF() {
        $medications = Medication::orderBy('origin')->orderBy('required_level')->get();
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('farmacia.pdf.inventory', compact('medications'));
        return $pdf->download('inventario_' . date('Y-m-d') . '.pdf');
    }

    public function uploadCSV(Request $request) {
        $request->validate(['csv_file' => 'required|mimes:csv,txt|max:10240']);
        $path = $request->file('csv_file')->getRealPath();
        $file = fopen($path, 'r'); $header = fgetcsv($file); $imported = 0;
        while (($row = fgetcsv($file)) !== false) {
            if (count($row) >= 6) {
                try { Medication::create(['name' => $row[0], 'active_ingredient' => $row[1] ?? 'N/A', 'stock' => (int)($row[2] ?? 0), 'min_stock' => (int)($row[3] ?? 10), 'price' => (float)($row[4] ?? 0), 'required_level' => in_array($row[5] ?? 'C', ['A', 'B', 'C', 'Enfermera']) ? $row[5] : 'C', 'enfermera_can_administer' => ($row[5] ?? '') === 'Enfermera', 'origin' => $row[6] ?? 'Central', 'lot_number' => $row[7] ?? 'SIN-LOTE', 'expiry_date' => $row[8] ?? now()->addYear()->format('Y-m-d'), 'location' => $row[9] ?? 'N/A', 'provider_name' => $row[10] ?? 'N/A']); $imported++; } catch (\Exception $e) {}
            }
        }
        fclose($file);
        return back()->with('success', "Importados: {$imported}");
    }
}
