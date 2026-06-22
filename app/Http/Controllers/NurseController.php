<?php

namespace App\Http\Controllers;

use App\Models\Triage;
use App\Models\Bed;
use App\Models\NurseEvolution;
use App\Models\Hospitalization;
use App\Models\AuditLog;
use App\Models\MedicalAlert;
use App\Models\User;
use App\Models\Medication;
use App\Models\RestockRequest;
use App\Models\PatientMedication;
use Illuminate\Http\Request;

class NurseController extends Controller
{
    public function dashboard()
    {
        $criticalPatients = Triage::where('triage_level', 'Rojo')->whereIn('status', ['En Espera', 'En Atención'])->limit(20)->get();
        $hospitalized = Triage::where('status', 'Hospitalizado')->count();
        $bedsAvailable = Bed::where('status', 'Disponible')->count();
        $critical = Triage::where('triage_level', 'Rojo')->whereIn('status', ['En Espera', 'En Atención'])->count();
        $alerts = MedicalAlert::where('is_read', 0)->orderBy('created_at', 'desc')->take(5)->get();
        return view('enfermeria.dashboard', compact('criticalPatients', 'hospitalized', 'bedsAvailable', 'critical', 'alerts'))->with('active', 'dashboard');
    }

    public function triage()
    {
        $triages = Triage::orderBy('created_at', 'desc')->paginate(30);
        $colors = ['Rojo' => '#DC2626', 'Naranja' => '#EA580C', 'Amarillo' => '#F59E0B', 'Verde' => '#F97316', 'Azul' => '#3B82F6'];
        $bdTotalDocs = \App\Models\MongoTriageLog::where('timestamp', '>=', now()->startOfDay())->count();
        $bdTodayPatients = \App\Models\MongoTriageLog::where('timestamp', '>=', now()->startOfDay())->count();
        $bdAvgFc = round(\App\Models\MongoTriageLog::where('timestamp', '>=', now()->startOfDay())->avg('vitals_fc') ?? 0);
        $bdRojoHoy = \App\Models\MongoTriageLog::where('timestamp', '>=', now()->startOfDay())->where('triage_level', 'Rojo')->count();
        return view('superadmin.urgencias', compact('triages', 'colors', 'bdTotalDocs', 'bdTodayPatients', 'bdAvgFc', 'bdRojoHoy'));
    }

    public function signosVitales()
    {
        $patients = Triage::whereIn('status', ['En Atención', 'Hospitalizado'])->orderBy('created_at', 'desc')->paginate(30);
        $recentVitals = Triage::whereIn('status', ['En Atención', 'Hospitalizado'])->whereNotNull('vitals_fc')->orderBy('updated_at', 'desc')->take(10)->get();
        return view('enfermeria.signos', compact('patients', 'recentVitals'));
    }

    public function storeSignos(Request $request)
    {
        $request->validate(['triage_id' => 'required|exists:triages,id']);
        $triage = Triage::findOrFail($request->triage_id);
        $triage->update($request->only(['vitals_ta', 'vitals_fc', 'vitals_temp', 'vitals_spo2']));
        AuditLog::create(['user_id' => auth()->id(), 'user_name' => auth()->user()->name, 'user_role' => auth()->user()->role, 'action' => 'Signos Vitales', 'module' => 'Enfermería', 'ip_address' => $request->ip(), 'details' => 'Paciente: ' . $triage->patient_name]);
        return back()->with('success', 'Signos vitales registrados.');
    }

    public function hospitalizacion()
    {
        $activePatients = Triage::whereIn('status', ['En Atención', 'Hospitalizado'])->orderBy('created_at', 'desc')->paginate(30);
        $beds = Bed::where('status', 'Disponible')->orderBy('area')->orderBy('room_number')->get();
        $doctors = User::whereIn('role', ['Médico A', 'Médico B', 'Médico C'])->where('status', 'Activo')->get();
        $hospitalizations = Hospitalization::with('triage', 'bed')->orderBy('created_at', 'desc')->paginate(20);
        return view('enfermeria.hospitalizacion', compact('activePatients', 'beds', 'doctors', 'hospitalizations'));
    }

    public function storeHospitalization(Request $request)
    {
        $request->validate(['triage_id' => 'required|exists:triages,id', 'bed_id' => 'required|exists:beds,id', 'diagnosis' => 'required']);
        $triage = Triage::findOrFail($request->triage_id);
        $bed = Bed::findOrFail($request->bed_id);
        Hospitalization::create(['triage_id' => $request->triage_id, 'bed_id' => $request->bed_id, 'doctor_id' => $request->doctor_id ?? null, 'nurse_id' => auth()->id(), 'admission_date' => now(), 'diagnosis' => $request->diagnosis, 'status' => 'Ingresado']);
        $bed->update(['status' => 'Ocupada', 'patient_name' => $triage->patient_name, 'triage_level' => $triage->triage_level]);
        $triage->update(['status' => 'Hospitalizado']);
        AuditLog::create(['user_id' => auth()->id(), 'user_name' => auth()->user()->name, 'user_role' => auth()->user()->role, 'action' => 'Hospitalización', 'module' => 'Enfermería', 'ip_address' => $request->ip(), 'details' => $triage->patient_name]);
        return back()->with('success', 'Paciente hospitalizado.');
    }

    public function evolucion()
    {
        $patients = Triage::whereIn('status', ['En Atención', 'Hospitalizado'])->orderBy('created_at', 'desc')->paginate(30);
        $evolutions = NurseEvolution::orderBy('created_at', 'desc')->paginate(30);
        return view('enfermeria.evolucion', compact('patients', 'evolutions'));
    }

    public function storeEvolucion(Request $request)
    {
        $request->validate(['triage_id' => 'required|exists:triages,id', 'notes' => 'required']);
        $triage = Triage::findOrFail($request->triage_id);
        AuditLog::create(['user_id' => auth()->id(), 'user_name' => auth()->user()->name, 'user_role' => auth()->user()->role, 'action' => 'Nota de Evolución', 'module' => 'Enfermería', 'ip_address' => $request->ip(), 'details' => $triage->patient_name]);
        return back()->with('success', 'Nota de evolución registrada.');
    }

    public function documentacion()
    {
        $patients = Triage::whereIn('status', ['En Atención', 'Hospitalizado'])->orderBy('created_at', 'desc')->paginate(30);
        $evolutions = NurseEvolution::orderBy('created_at', 'desc')->take(20)->get();
        $vitals = Triage::whereIn('status', ['En Atención', 'Hospitalizado'])->whereNotNull('vitals_fc')->orderBy('updated_at', 'desc')->take(20)->get();
        $colors = ['Rojo' => '#DC2626', 'Naranja' => '#EA580C', 'Amarillo' => '#F59E0B', 'Verde' => '#F97316'];
        return view('enfermeria.documentacion', compact('patients', 'evolutions', 'vitals', 'colors'));
    }

    public function pacientes()
    {
        $patients = Triage::orderBy('created_at', 'desc')->paginate(30);
        $colors = ['Rojo' => '#DC2626', 'Naranja' => '#EA580C', 'Amarillo' => '#F59E0B', 'Verde' => '#F97316', 'Azul' => '#3B82F6'];
        $statusColors = ['En Espera' => '#F59E0B', 'En Atención' => '#EA580C', 'Hospitalizado' => '#DC2626', 'Alta' => '#F97316'];
        return view('enfermeria.pacientes', compact('patients', 'colors', 'statusColors'));
    }

    public function reasignarPaciente(Request $request, $id)
    {
        $triage = Triage::findOrFail($id);
        $triage->update($request->only(['status', 'bed_id']));
        AuditLog::create(['user_id' => auth()->id(), 'user_name' => auth()->user()->name, 'user_role' => auth()->user()->role, 'action' => 'Reasignar Paciente', 'module' => 'Enfermería', 'ip_address' => $request->ip(), 'details' => $triage->patient_name]);
        return back()->with('success', 'Paciente reasignado.');
    }

    public function alertas()
    {
        $alerts = MedicalAlert::orderBy('created_at', 'desc')->paginate(30);
        return view('enfermeria.alertas', compact('alerts'));
    }

    public function reportes()
    {
        $patients = Triage::whereIn("status", ["En Atención", "Hospitalizado"])->orderBy("created_at", "desc")->paginate(30);
        $evolutions = NurseEvolution::orderBy("created_at", "desc")->take(20)->get();
        return view("enfermeria.reportes", compact("patients", "evolutions"));
    }

    public function medicamentos()
    {
        $meds = Medication::where('enfermera_can_administer', true)->orderBy('name')->get();
        return view('enfermeria.medicamentos', compact('meds'));
    }

    public function solicitudesFarmacia()
    {
        $meds = RestockRequest::with('medication')->orderBy('created_at', 'desc')->paginate(30);
        return view('enfermeria.solicitudes', compact('meds'));
    }

    public function mapaCamas()
    {
        $beds = Bed::orderBy('area')->orderBy('floor')->orderBy('room_number')->orderBy('bed_number')->get();
        $grouped = $beds->groupBy('area');
        $stats = ['total' => $beds->count(), 'disponibles' => $beds->where('status', 'Disponible')->count(), 'ocupadas' => $beds->where('status', 'Ocupada')->count(), 'limpieza' => $beds->where('status', 'Limpieza')->count(), 'mantenimiento' => $beds->where('status', 'Mantenimiento')->count()];
        return view('enfermeria.mapa_camas', compact('grouped', 'stats'));
    }

    public function asignarCama(Request $request, $id)
    {
        $cama = Bed::findOrFail($id);
        if ($request->filled('triage_id')) {
            $triage = Triage::findOrFail($request->triage_id);
        } else {
            $request->validate(['new_patient_name' => 'required|string|max:255']);
            $triage = Triage::create(['patient_name' => $request->new_patient_name, 'triage_level' => $request->new_triage_level ?? 'Verde', 'status' => 'Hospitalizado', 'age' => $request->new_age ?? 30, 'symptoms' => 'Por determinar', 'chief_complaint' => 'Ingreso directo desde Mapa de Camas', 'created_by' => auth()->id()]);
        }
        $cama->status = 'Ocupada';
        $cama->patient_name = $triage->patient_name;
        $cama->triage_level = $triage->triage_level;
        if ($triage->triage_level == 'Rojo') { $cama->area = 'UCI'; } elseif ($triage->triage_level == 'Naranja') { $cama->area = 'Urgencias'; } else { $cama->area = 'Hospitalización'; }
        $cama->save();
        if ($triage->status != 'Hospitalizado') { $triage->update(['status' => 'Hospitalizado']); }
        AuditLog::create(['user_id' => auth()->id(), 'user_name' => auth()->user()->name, 'user_role' => auth()->user()->role, 'action' => 'Cama Asignada', 'module' => 'Enfermería - Mapa Camas', 'ip_address' => $request->ip(), 'details' => $triage->patient_name]);
        return back()->with('success', "Cama asignada a {$triage->patient_name}");
    }

    public function liberarCama($id)
    {
        $cama = Bed::findOrFail($id);
        $paciente = $cama->patient_name;
        $cama->status = 'Limpieza';
        $cama->patient_name = null;
        $cama->triage_level = null;
        $cama->save();
        AuditLog::create(['user_id' => auth()->id(), 'user_name' => auth()->user()->name, 'user_role' => auth()->user()->role, 'action' => 'Cama Liberada', 'module' => 'Enfermería - Mapa Camas', 'ip_address' => request()->ip(), 'details' => $paciente]);
        return back()->with('success', "Cama liberada.");
    }

    public function storeTriage(Request $request) 
    {
        $validated = $request->validate([
            'patient_name' => 'required|string',
            'triage_level' => 'required|string',
            'age' => 'required|integer',
            'chief_complaint' => 'nullable|string'
        ]);

        // 1. LIMPIEZA DE DATOS (Data Cleaning)
        $validated['patient_name'] = ucwords(strtolower(trim($validated['patient_name'])));
        $validated['chief_complaint'] = ucfirst(strtolower(trim($validated['chief_complaint'] ?? '')));

        // 2. DEDUPLICACIÓN (Drop Duplicates)
        $existingPatient = Triage::where('patient_name', $validated['patient_name'])
            ->where('age', $validated['age'])
            ->whereIn('status', ['En Espera', 'En Atención'])
            ->whereDate('created_at', today())
            ->first();

        if ($existingPatient) {
            return back()->with('error', '⚠️ Este paciente ya ha sido registrado hoy en Urgencias. Registro duplicado evitado.');
        }

        $validated['status'] = 'En Espera';
        $validated['symptoms'] = $validated['symptoms'] ?? $validated['chief_complaint'] ?? 'Pendiente';
        Triage::create($validated);

        \App\Models\MongoTriageLog::create([
            'patient_id' => $validated['patient_name'],
            'triage_level' => $validated['triage_level'],
            'age' => $validated['age'],
            'specialty' => 'Urgencias',
            'vitals_fc' => $request->vitals_fc ?? 80,
            'vitals_temp' => $request->vitals_temp ?? 36.5,
            'vitals_spo2' => $request->vitals_spo2 ?? 98,
            'timestamp' => now()
        ]);

        return back()->with('status', '✅ Paciente ingresado correctamente.');
    }

    public function darAlta($id)
    {
        $triage = Triage::findOrFail($id);
        $triage->update(["status" => "Alta"]);
        if ($triage->bed_id) {
            $bed = Bed::find($triage->bed_id);
            if ($bed) { $bed->update(["status" => "Limpieza", "patient_name" => null, "triage_level" => null]); }
        }
        AuditLog::create(["user_id" => auth()->id(), "user_name" => auth()->user()->name, "user_role" => auth()->user()->role, "action" => "Alta Médica", "module" => "Enfermería", "ip_address" => request()->ip(), "details" => $triage->patient_name]);
        return back()->with("success", "Paciente dado de alta.");
    }

    public function markAlertRead($id)
    {
        $alert = MedicalAlert::findOrFail($id);
        $alert->update(["is_read" => 1]);
        return back()->with("success", "Alerta marcada como leída.");
    }

    public function pacientesParaCamas()
    {
        $pacientes = Triage::whereIn('status', ['En Espera', 'En Atención'])->whereNotNull('patient_name')->select('id', 'patient_name', 'triage_level')->orderBy('created_at', 'desc')->limit(50)->get();
        return response()->json($pacientes);
    }

    public function bigdata()
    {
        $today = now()->startOfDay();
        $logs = \App\Models\MongoTriageLog::where('timestamp', '>=', $today)->get();

        $fc = $logs->pluck('vitals_fc')->sort()->values();
        $c = $fc->count();

        $getP = function($arr, $p) use ($c) {
            if ($c == 0) return 0;
            $index = ($p / 100) * ($c - 1);
            $lower = (int) floor($index);
            $upper = (int) ceil($index);
            if ($lower === $upper) return round($arr[$lower], 2);
            return round($arr[$lower] + ($index - $lower) * ($arr[$upper] - $arr[$lower]), 2);
        };

        $fcMean = $fc->avg() ?? 0;
        $fcStd = $c > 0 ? round(sqrt($fc->map(fn($v) => pow($v - $fcMean, 2))->avg()), 2) : 0;

        $stats = [
            'total' => $c,
            'avg_fc' => round($fcMean, 2),
            'max_fc' => $fc->max() ?? 0,
            'min_fc' => $fc->min() ?? 0,
            'mode_fc' => $c > 0 ? round($fc->mode()[0], 2) : 0,
            'std_fc' => $fcStd,
            'p10' => $getP($fc, 10), 'p25' => $getP($fc, 25),
            'p50' => $getP($fc, 50), 'p75' => $getP($fc, 75),
            'p90' => $getP($fc, 90), 'p95' => $getP($fc, 95), 'p99' => $getP($fc, 99),
            'iqr' => round($getP($fc, 75) - $getP($fc, 25), 2),
            'avg_temp' => round($logs->avg('vitals_temp') ?? 0, 1),
            'avg_spo2' => round($logs->avg('vitals_spo2') ?? 0, 0)
        ];

        $dist = $logs->groupBy('triage_level')->map->count();

        $train = round($c * 0.70);
        $test = round($c * 0.20);
        $val = $c - $train - $test;

        return view('enfermeria.bigdata', compact('stats', 'dist', 'train', 'test', 'val'));
    }
}
