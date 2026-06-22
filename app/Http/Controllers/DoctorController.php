<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Triage;
use App\Models\Medication;
use App\Models\Bed;
use App\Models\Hospitalization;
use App\Models\NurseEvolution;
use App\Models\MedicalAlert;
use App\Models\VitalSign;
use App\Models\User;

class DoctorController extends Controller
{
    public function dashboard()
    {
        if (request()->has('cambiar')) {
            session()->forget(['doctor_profile', 'doctor_name']);
            return view('medico.seleccion');
        }
        $role = session('doctor_profile', null);
        if (!$role) return view('medico.seleccion');
        return $this->dashboardForRole($role);
    }

    private function dashboardForRole($role)
    {
        $isA = $role === 'Médico A';
        $isB = $role === 'Médico B';
        $isC = $role === 'Médico C';
        $doctorName = session('doctor_name', 'Médico');
        $uid = Auth::id();

        $misPacientes = Triage::where('assigned_doctor', $uid)->whereIn('status', ['En Atención', 'Hospitalizado'])->count();
        $criticos = Triage::where('triage_level', 'Rojo')->whereIn('status', ['En Espera', 'En Atención'])->count();
        $hospitalizados = Triage::where('status', 'Hospitalizado')->count();
        $recetasPendientes = \DB::table('prescriptions')->where('doctor_id', $uid)->where('status', 'Pendiente')->count();
        $camasDisponibles = Bed::where('status', 'Disponible')->count();
        $camasOcupadas = Bed::where('status', 'Ocupada')->count();
        $stockBajo = Medication::where('stock', '<', 10)->count();
        $serviciosPendientes = \DB::table('service_requests')->where('doctor_id', $uid)->where('status', 'Pendiente')->count();

        $alerts = MedicalAlert::where('is_read', 0)->orderBy('created_at', 'desc')->limit(5)->get();
        $recentVitals = VitalSign::orderBy('created_at', 'desc')->limit(6)->get();
        $misPacientesLista = Triage::where('assigned_doctor', $uid)->whereIn('status', ['En Atención', 'Hospitalizado'])->orderBy('triage_level', 'asc')->limit(8)->get();

        return view('medico.dashboard', compact(
            'role', 'isA', 'isB', 'isC', 'doctorName',
            'misPacientes', 'criticos', 'hospitalizados', 'recetasPendientes',
            'camasDisponibles', 'camasOcupadas', 'stockBajo', 'serviciosPendientes',
            'alerts', 'recentVitals', 'misPacientesLista'
        ));
    }

    public function seleccionar()
    {
        session()->forget(['doctor_profile', 'doctor_name']);
        return view('medico.seleccion');
    }

    public function seleccionarPerfil(Request $request)
    {
        $pin = $request->pin;
        $profileMap = ['1111' => 'Médico A', '2222' => 'Médico B', '3333' => 'Médico C'];
        $nameMap = ['1111' => 'Dr. Kenia Medina', '2222' => 'Dr. SF Gilkey', '3333' => 'Dr. KM Azuara'];

        if (isset($profileMap[$pin])) {
            session(['doctor_profile' => $profileMap[$pin], 'doctor_name' => $nameMap[$pin]]);
            return redirect()->route('medico.dashboard');
        }
        return back()->with('error', 'PIN incorrecto.');
    }

    public function validarPin(Request $request)
    {
        return response()->json(['success' => in_array($request->pin, ['1111', '2222', '3333'])]);
    }

    // PACIENTES
    public function pacientes()
    {
        $role = session('doctor_profile', 'Médico C');
        $uid = Auth::id();
        $isA = $role === 'Médico A';
        $medicos = $isA ? User::whereIn('role', ['Médico A', 'Médico B', 'Médico C'])->where('status', 1)->get() : collect();

        if ($isA) {
            $pacientes = Triage::whereIn('status', ['En Espera', 'En Atención', 'Hospitalizado'])->orderBy('triage_level', 'asc')->paginate(25);
        } else {
            $pacientes = Triage::where('assigned_doctor', $uid)->whereIn('status', ['En Atención', 'Hospitalizado'])->orderBy('triage_level', 'asc')->paginate(25);
        }
        return view('medico.pacientes', compact('pacientes', 'role', 'isA', 'medicos'));
    }

    public function registrarPaciente()
    {
        $role = session('doctor_profile');
        if ($role === 'Médico C') return redirect()->route('medico.dashboard')->with('error', 'Sin acceso');
        $medicos = User::whereIn('role', ['Médico A', 'Médico B', 'Médico C'])->where('status', 1)->get();
        return view('medico.registrar-paciente', compact('role', 'medicos'));
    }

    public function storeNuevoPaciente(Request $request)
    {
        if (session('doctor_profile') === 'Médico C') return redirect()->route('medico.dashboard')->with('error', 'Sin acceso');
        $request->validate([
            'patient_name' => 'required', 'age' => 'required|numeric',
            'gender' => 'required', 'chief_complaint' => 'required', 'triage_level' => 'required',
        ]);
        $triage = Triage::create([
            'patient_name' => $request->patient_name, 'age' => $request->age,
            'gender' => $request->gender, 'chief_complaint' => $request->chief_complaint,
            'symptoms' => $request->symptoms, 'allergies' => $request->allergies,
            'triage_level' => $request->triage_level, 'status' => 'En Espera',
            'assigned_doctor' => $request->assigned_doctor ?? Auth::id(),
            'blood_type' => $request->blood_type, 'insurance' => $request->insurance,
            'emergency_contact' => $request->emergency_contact,
            'diagnostico' => $request->diagnostico, 'cie10' => $request->cie10,
            'doctor_notes' => $request->doctor_notes,
        ]);
        if ($request->triage_level === 'Rojo') {
            MedicalAlert::create([
                'type' => 'Paciente Crítico',
                'message' => 'TRIAGE ROJO: ' . $request->patient_name . ' - ' . $request->chief_complaint,
                'severity' => 'Crítica', 'triage_id' => $triage->id,
                'nurse_id' => Auth::id(), 'is_read' => false,
            ]);
        }
        return redirect()->route('medico.pacientes')->with('success', 'Paciente registrado');
    }

    public function editarPaciente($id)
    {
        $role = session('doctor_profile');
        $paciente = Triage::findOrFail($id);
        $medicos = User::whereIn('role', ['Médico A', 'Médico B', 'Médico C'])->where('status', 1)->get();
        $hosp = Hospitalization::where('triage_id', $id)->where('status', 'Activa')->first();
        return view('medico.editar-paciente', compact('paciente', 'medicos', 'hosp', 'role'));
    }

    public function actualizarPaciente(Request $request, $id)
    {
        Triage::findOrFail($id)->update($request->only([
            'patient_name', 'age', 'gender', 'chief_complaint',
            'diagnostico', 'cie10', 'tratamiento', 'doctor_notes', 'status'
        ]));
        return redirect()->route('medico.pacientes')->with('success', 'Actualizado');
    }

    public function asignarPaciente(Request $request, $id)
    {
        Triage::findOrFail($id)->update(['assigned_doctor' => $request->doctor_id, 'status' => 'En Atención']);
        return back()->with('success', 'Asignado');
    }

    public function darAlta(Request $request, $id)
    {
        $p = Triage::findOrFail($id);
        $p->update([
            'status' => 'Alta', 'discharge_date' => now(),
            'discharge_type' => 'Alta Hospitalaria',
            'discharge_doctor_id' => Auth::id(),
            'discharge_notes' => $request->discharge_notes ?? 'Alta médica',
        ]);
        $hosp = Hospitalization::where('triage_id', $id)->where('status', 'Activa')->first();
        if ($hosp) {
            $hosp->update(['status' => 'Alta', 'discharge_date' => now()]);
            $bed = Bed::find($hosp->bed_id);
            if ($bed) $bed->update(['status' => 'Disponible']);
        }
        return back()->with('success', 'Paciente dado de alta');
    }

    // CONSULTA
    public function storeConsulta(Request $request)
    {
        $request->validate(["paciente_id" => "required", "notas" => "required"]);
        $p = Triage::findOrFail($request->paciente_id);
        $p->update(["doctor_notes" => $request->notas, "status" => "En Atención"]);
        return back()->with("success", "Consulta registrada");
    }

    public function consulta()
    {
        $role = session('doctor_profile');
        $pacientes = Triage::where('assigned_doctor', Auth::id())
            ->whereIn('status', ['En Atención', 'En Espera'])
            ->orderBy('created_at', 'desc')->paginate(25);
        return view('medico.consulta', compact('pacientes', 'role'));
    }

    // DIAGNÓSTICOS
    public function diagnosticos()
    {
        $role = session('doctor_profile');
        $pacientes = Triage::where('assigned_doctor', Auth::id())
            ->whereIn('status', ['En Atención', 'Hospitalizado'])
            ->orderBy('created_at', 'desc')->paginate(25);
        return view('medico.diagnosticos', compact('pacientes', 'role'));
    }

    public function storeDiagnostico(Request $request)
    {
        Triage::findOrFail($request->triage_id)->update([
            'diagnostico' => $request->diagnostico,
            'cie10' => $request->cie10,
            'doctor_notes' => $request->doctor_notes,
        ]);
        return back()->with('success', 'Guardado');
    }

    // RECETAS
    public function recetas()
    {
        $role = session('doctor_profile', 'Médico C');
        $uid = Auth::id();
        $isC = $role === 'Médico C';
        $isA = $role === 'Médico A';

        $medQuery = Medication::orderBy('name');
        if (!$isA && $isC) $medQuery->where('required_level', 'C');
        elseif (!$isA) $medQuery->where('required_level', '!=', 'A');
        // Médico A ve TODOS los medicamentos sin restricción
        $medicamentos = $medQuery->paginate(30);

        $pacientes = Triage::where('assigned_doctor', $uid)
            ->whereIn('status', ['En Atención', 'Hospitalizado'])->paginate(25);

        $misRecetas = \DB::table('prescriptions')
            ->leftJoin('triages', 'prescriptions.triage_id', '=', 'triages.id')
            ->leftJoin('medications', 'prescriptions.medication_id', '=', 'medications.id')
            ->where('prescriptions.doctor_id', $uid)
            ->select('prescriptions.*', 'triages.patient_name', 'medications.name as medication_name')
            ->orderBy('prescriptions.created_at', 'desc')->paginate(20);

        return view('medico.recetas', compact('medicamentos', 'pacientes', 'misRecetas', 'role', 'isC', 'isA'));
    }

    public function storeReceta(Request $request)
    {
        \DB::table('prescriptions')->insert([
            'triage_id' => $request->triage_id,
            'patient_id' => $request->triage_id,
            'medication_id' => $request->medication_id,
            'quantity' => $request->quantity ?? 1,
            'dosis' => $request->dosis,
            'frecuencia' => $request->frecuencia,
            'duracion' => $request->duracion,
            'indicaciones' => $request->indicaciones,
            'status' => 'Pendiente',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        return back()->with('success', 'Receta enviada a Farmacia');
    }

    public function cancelarReceta($id)
    {
        \DB::table('prescriptions')->where('id', $id)->update(['status' => 'Cancelada', 'updated_at' => now()]);
        return back()->with('success', 'Cancelada');
    }

    // SIGNOS VITALES
    public function signosVitales()
    {
        $role = session('doctor_profile');
        $vitals = VitalSign::orderBy('created_at', 'desc')->paginate(25);
        return view('medico.signos', compact('vitals', 'role'));
    }

    // ESTUDIOS
    public function estudios()
    {
        $role = session('doctor_profile');
        $pacientes = Triage::where('assigned_doctor', Auth::id())
            ->whereIn('status', ['En Atención', 'Hospitalizado'])->paginate(25);
        $estudios = \DB::table('medical_studies')->where('doctor_id', Auth::id())
            ->orderBy('created_at', 'desc')->paginate(20);
        return view('medico.estudios', compact('pacientes', 'estudios', 'role'));
    }

    public function storeEstudio(Request $request)
    {
        \DB::table('medical_studies')->insert([
            'triage_id' => $request->triage_id,
            'patient_id' => $request->triage_id,
            'tipo' => $request->tipo, 'prioridad' => $request->prioridad,
            'notas' => $request->notas, 'status' => 'Solicitado',
            'created_at' => now(), 'updated_at' => now(),
        ]);
        return back()->with('success', 'Estudio solicitado');
    }

    public function resultadoEstudio(Request $request, $id)
    {
        \DB::table('medical_studies')->where('id', $id)->update([
            'status' => 'Completado', 'notas' => $request->resultado, 'updated_at' => now(),
        ]);
        return back()->with('success', 'Resultado registrado');
    }

    public function eliminarEstudio($id)
    {
        \DB::table('medical_studies')->where('id', $id)->delete();
        return back()->with('success', 'Eliminado');
    }

    // TRATAMIENTOS
    
    public function storeTratamiento(Request $request)
    {
        $request->validate(['triage_id' => 'required', 'tratamiento' => 'required']);
        $p = Triage::findOrFail($request->triage_id);
        $p->update(['tratamiento' => $request->tratamiento, 'doctor_notes' => $request->notas]);
        return back()->with('success', 'Tratamiento guardado');
    }
    public function tratamientos()
    {
        $role = session('doctor_profile');
        if ($role !== 'Médico A') return redirect()->route('medico.dashboard')->with('error', 'Solo Médico A');
        $pacientes = Triage::where('assigned_doctor', Auth::id())
            ->whereIn('status', ['En Atención', 'Hospitalizado'])->paginate(25);
        return view('medico.tratamientos', compact('pacientes', 'role'));
    }

    // HOSPITALIZACIÓN
    public function hospitalizacion()
    {
        $role = session('doctor_profile');
        if ($role === 'Médico C') return redirect()->route('medico.dashboard')->with('error', 'Sin acceso');
        $hospitalizados = Hospitalization::where('status', 'Activa')
            ->orderBy('admission_date', 'desc')->paginate(20);
        $camas = Bed::orderBy('floor', 'asc')->get();
        $pacientes = Triage::whereIn('status', ['En Atención', 'En Espera'])->paginate(25);
        return view('medico.hospitalizacion', compact('hospitalizados', 'camas', 'pacientes', 'role'));
    }

    public function storeHospitalizacion(Request $request)
    {
        $triage = Triage::find($request->triage_id);
        Hospitalization::create([
            'triage_id' => $request->triage_id,
            'patient_name' => $triage->patient_name ?? 'Paciente',
            'bed_id' => $request->bed_id,
            'admission_date' => now(),
            'diagnosis' => $request->diagnostico,
            'status' => 'Ingresado',
        ]);
        $bed = Bed::find($request->bed_id);
        if ($bed) $bed->update(['status' => 'Ocupada', 'patient_name' => $triage->patient_name ?? null, 'triage_level' => $triage->triage_level ?? null]);
        if ($triage) $triage->update(['status' => 'Hospitalizado']);
        return back()->with('success', 'Paciente hospitalizado');
    }

    public function altaHospitalizacion($id)
    {
        $hosp = Hospitalization::findOrFail($id);
        $hosp->update(['status' => 'Alta', 'discharge_date' => now()]);
        $bed = Bed::find($hosp->bed_id);
        if ($bed) $bed->update(['status' => 'Disponible']);
        $triage = Triage::find($hosp->triage_id);
        if ($triage) {
            $triage->update([
                'status' => 'Alta', 'discharge_date' => now(),
                'discharge_type' => 'Alta', 'discharge_doctor_id' => Auth::id(),
            ]);
        }
        return back()->with('success', 'Paciente dado de alta');
    }

    // CAMAS
    public function camas()
    {
        $role = session('doctor_profile');
        if ($role === 'Médico C') return redirect()->route('medico.dashboard')->with('error', 'Sin acceso');
        $camas = Bed::orderBy('floor', 'asc')->orderBy('room_number', 'asc')->paginate(40);
        $hospitalizaciones = Hospitalization::where('status', 'Activa')->get();
        return view('medico.camas', compact('camas', 'hospitalizaciones', 'role'));
    }

    // SERVICIOS
    public function servicios()
    {
        $role = session('doctor_profile');
        if ($role === 'Médico C') return redirect()->route('medico.dashboard')->with('error', 'Sin acceso');
        $pacientes = Triage::where('assigned_doctor', Auth::id())
            ->whereIn('status', ['En Atención', 'Hospitalizado'])->paginate(25);
        $solicitudes = \DB::table('service_requests')->where('doctor_id', Auth::id())
            ->orderBy('created_at', 'desc')->paginate(20);
        return view('medico.servicios', compact('pacientes', 'solicitudes', 'role'));
    }

    public function storeServicio(Request $request)
    {
        \DB::table('service_requests')->insert([
            'triage_id' => $request->triage_id,
            'patient_id' => $request->triage_id,
            'tipo' => $request->tipo, 'descripcion' => $request->descripcion,
            'prioridad' => $request->prioridad, 'status' => 'Pendiente',
            'created_at' => now(), 'updated_at' => now(),
        ]);
        return back()->with('success', 'Servicio solicitado');
    }

    public function cancelarServicio($id)
    {
        \DB::table('service_requests')->where('id', $id)->update(['status' => 'Cancelado', 'updated_at' => now()]);
        return back()->with('success', 'Servicio cancelado');
    }

    // FARMACIA STOCK
    public function farmaciaStock()
    {
        $role = session('doctor_profile');
        if ($role === 'Médico C') return redirect()->route('medico.dashboard')->with('error', 'Sin acceso');
        $medicamentos = Medication::orderBy('name')->paginate(30);
        return view('medico.farmacia-stock', compact('medicamentos', 'role'));
    }

    // INSUMOS
    public function insumos()
    {
        $role = session('doctor_profile');
        if ($role !== 'Médico A') return redirect()->route('medico.dashboard')->with('error', 'Solo Médico A');
        $insumos = Medication::orderBy('name')->paginate(30);
        return view('medico.insumos', compact('insumos', 'role'));
    }

    // EVOLUCIÓN
    public function evolucion()
    {
        $role = session('doctor_profile');
        $uid = Auth::id();
        $pacientes = Triage::where('assigned_doctor', $uid)
            ->whereIn('status', ['En Atención', 'Hospitalizado'])->paginate(25);
        $evolutions = NurseEvolution::orderBy('created_at', 'desc')->paginate(20);
        return view('medico.evolucion', compact('pacientes', 'evolutions', 'role'));
    }

    public function storeEvolucion(Request $request)
    {
        $triage = Triage::find($request->triage_id);
        NurseEvolution::create([
            'triage_id' => $request->triage_id,
            'patient_name' => $triage->patient_name ?? 'Paciente',
            'nurse_id' => Auth::id(),
            'observation' => $request->notes,
            'priority' => $request->priority ?? 'Normal',
        ]);
        return back()->with('success', 'Nota de evolución guardada');
    }

    public function actualizarEvolucion(Request $request, $id)
    {
        NurseEvolution::findOrFail($id)->update([ 'observation' => $request->notes, 'priority' => $request->priority]);
        return back()->with('success', 'Nota actualizada');
    }

    public function eliminarEvolucion($id)
    {
        NurseEvolution::findOrFail($id)->delete();
        return back()->with('success', 'Nota eliminada');
    }

    // DEFUNCIONES
    public function defunciones()
    {
        $role = session('doctor_profile');
        if ($role !== 'Médico A') return redirect()->route('medico.dashboard')->with('error', 'Solo Médico A');
        $defunciones = \DB::table('patient_deaths')->orderBy('death_time', 'desc')->paginate(20);
        $totalMes = \DB::table('patient_deaths')->whereMonth('death_time', now()->month)->count();
        $causas = \DB::table('patient_deaths')
            ->select('cause_of_death', \DB::raw('count(*) as total'))
            ->groupBy('cause_of_death')->orderBy('total', 'desc')->limit(5)->get();
        return view('medico.defunciones', compact('defunciones', 'totalMes', 'causas', 'role'));
    }

    public function registrarDefuncion(Request $request, $id)
    {
        if (session('doctor_profile') !== 'Médico A') return back()->with('error', 'Solo Médico A');
        $request->validate(['cause_of_death' => 'required']);
        $p = Triage::findOrFail($id);
        $certNum = 'DEF-' . date('Y') . '-' . str_pad(\DB::table('patient_deaths')->count() + 1, 4, '0', STR_PAD_LEFT);

        \DB::table('patient_deaths')->insert([
            'bed_id' => $request->bed_id ?? null,
            'death_time' => now(), 'cause_of_death' => $request->cause_of_death,
            'immediate_cause' => $request->immediate_cause,
            'clinical_summary' => $request->clinical_summary,
            'autopsy_required' => $request->has('autopsy_required'),
            'death_certificate_number' => $certNum,
            'notified_family' => $request->notified_family ?? 'No',
            'notes' => $request->notes,
            'created_at' => now(), 'updated_at' => now(),
        ]);

        $p->update([
            'status' => 'Defunción', 'discharge_date' => now(),
            'discharge_type' => 'Defunción', 'discharge_doctor_id' => Auth::id(),
        ]);

        $hosp = Hospitalization::where('triage_id', $id)->where('status', 'Activa')->first();
        if ($hosp) {
            $hosp->update(['status' => 'Defunción', 'discharge_date' => now()]);
            $bed = Bed::find($hosp->bed_id);
            if ($bed) $bed->update(['status' => 'Disponible']);
        }

        return redirect()->route('medico.defunciones')->with('success', 'Defunción registrada. Certificado: ' . $certNum);
    }

    public function verDefuncion($id)
    {
        $role = session('doctor_profile');
        if ($role !== 'Médico A') return redirect()->route('medico.dashboard')->with('error', 'Solo Médico A');
        $defuncion = \DB::table('patient_deaths')->where('id', $id)->first();
        $paciente = Triage::find($defuncion->triage_id);
        $doctor = User::find($defuncion->doctor_id);
        return view('medico.ver-defuncion', compact('defuncion', 'paciente', 'doctor', 'role'));
    }

    public function certificadoDefuncionPDF($id)
    {
        $defuncion = \DB::table('patient_deaths')->where('id', $id)->first();
        $paciente = Triage::find($defuncion->triage_id);
        $doctor = User::find($defuncion->doctor_id);
        $pdf = \PDF::loadView('medico.pdf.defuncion', compact('defuncion', 'paciente', 'doctor'));
        return $pdf->download('Acta_Defuncion_' . $paciente->patient_name . '.pdf');
    }

    public function derivarPaciente(Request $request, $id)
    {
        if (session('doctor_profile') !== 'Médico A') return back()->with('error', 'Solo Médico A');
        $request->validate(['hospital_destino' => 'required', 'motivo' => 'required']);
        \DB::table('derivations')->insert([
            'hospital_destino' => $request->hospital_destino, 'motivo' => $request->motivo,
            'status' => 'Pendiente', 'created_at' => now(), 'updated_at' => now(),
        ]);
        $triage = Triage::find($id);
        if ($triage) {
            $triage->update([
                'status' => 'Derivado', 'discharge_date' => now(),
                'discharge_type' => 'Derivación', 'discharge_doctor_id' => Auth::id(),
            ]);
        }
        return redirect()->route('medico.derivaciones')->with('success', 'Paciente derivado exitosamente. Puede generar el Pase de Salida en el historial.');
    }

    // UCI
    public function uci()
    {
        $role = session('doctor_profile');
        if ($role !== 'Médico A') return redirect()->route('medico.dashboard')->with('error', 'Solo Médico A');
        $criticalPatients = Triage::where('triage_level', 'Rojo')
            ->whereIn('status', ['En Atención', 'Hospitalizado'])->paginate(25);
        $uciBeds = Bed::where('type', 'UCI')->get();
        return view('medico.uci', compact('criticalPatients', 'uciBeds', 'role'));
    }

    public function quirofano()
    {
        $role = session('doctor_profile');
        if ($role !== 'Médico A') return redirect()->route('medico.dashboard')->with('error', 'Solo Médico A');
        $scheduled = Hospitalization::where('status', 'Activa')
            ->whereHas('bed', function($q) { $q->where('type', 'Quirófano'); })
            ->paginate(20);
        return view('medico.quirofano', compact('scheduled', 'role'));
    }

    public function controlados()
    {
        $role = session('doctor_profile');
        if ($role !== 'Médico A') return redirect()->route('medico.dashboard')->with('error', 'Solo Médico A');
        $meds = Medication::where('required_level', 'A')->orderBy('name')->paginate(30);
        return view('medico.controlados', compact('meds', 'role'));
    }

    public function iaMedica()
    {
        return view('medico.ia-medica', ['role' => 'Médico A']);
    }

    public function derivaciones()
    {
        $role = session('doctor_profile');
        if ($role !== 'Médico A') return redirect()->route('medico.dashboard')->with('error', 'Solo Médico A');
        $pacientes = Triage::whereIn('status', ['En Atención', 'Hospitalizado'])->paginate(25);
        $derivaciones = \DB::table('derivations')
            ->leftJoin('triages', 'derivations.triage_id', '=', 'triages.id')
            ->leftJoin('users', 'derivations.doctor_id', '=', 'users.id')
            ->select('derivations.*', 'triages.patient_name', 'triages.age', 'triages.triage_level', 'triages.diagnostico', 'users.name as doctor_name')
            ->orderBy('derivations.created_at', 'desc')
            ->paginate(20);
        $ultimaDerivacion = \DB::table('derivations')
            ->leftJoin('triages', 'derivations.triage_id', '=', 'triages.id')
            ->select('derivations.*', 'triages.patient_name', 'triages.age', 'triages.triage_level', 'triages.diagnostico')
            ->orderBy('derivations.created_at', 'desc')
            ->first();
        $hospitales = [
            'Hospital General de la Zona' => 'Hospital General',
            'Centro Médico Nacional' => 'Centro Médico',
            'Hospital Infantil Regional' => 'Hospital Infantil',
            'Hospital Regional Alta Especialidad' => 'Hospital Regional',
            'Clínica Especializada' => 'Clínica Especializada',
        ];
        return view('medico.derivaciones', compact('pacientes', 'derivaciones', 'hospitales', 'role', 'ultimaDerivacion'));
    }

    public function exportDerivacionPDF($id)
    {
        $derivacion = \DB::table('derivations')
            ->leftJoin('triages', 'derivations.triage_id', '=', 'triages.id')
            ->leftJoin('users', 'derivations.doctor_id', '=', 'users.id')
            ->select('derivations.*', 'triages.patient_name', 'triages.age', 'triages.triage_level', 'triages.diagnostico', 'triages.cie10', 'triages.tratamiento', 'triages.symptoms', 'triages.vitals_ta', 'triages.vitals_fc', 'triages.vitals_temp', 'triages.vitals_spo2', 'users.name as doctor_name')
            ->where('derivations.id', $id)
            ->first();
        $paciente = $derivacion ? (object)['patient_name' => $derivacion->patient_name, 'age' => $derivacion->age, 'triage_level' => $derivacion->triage_level, 'diagnostico' => $derivacion->diagnostico, 'cie10' => $derivacion->cie10, 'tratamiento' => $derivacion->tratamiento, 'symptoms' => $derivacion->symptoms, 'vitals_ta' => $derivacion->vitals_ta, 'vitals_fc' => $derivacion->vitals_fc, 'vitals_temp' => $derivacion->vitals_temp, 'vitals_spo2' => $derivacion->vitals_spo2, 'id' => $derivacion->triage_id] : null;
        $doctor = $derivacion ? (object)['name' => $derivacion->doctor_name, 'id' => $derivacion->doctor_id] : null;
        $pdf = \PDF::loadView('medico.pdf.derivacion', compact('derivacion', 'paciente', 'doctor'));
        return $pdf->download('Pase_Salida_' . ($paciente->patient_name ?? 'Paciente') . '.pdf');
    }

    public function alertas()
    {
        $role = session('doctor_profile');
        $alerts = MedicalAlert::with('triage')->orderBy('is_read')
            ->orderBy('created_at', 'desc')->paginate(25);
        return view('medico.alertas', compact('alerts', 'role'));
    }

    public function markAlertRead($id)
    {
        $alert = MedicalAlert::find($id);
        if ($alert) $alert->update(['is_read' => 1]);
        return back()->with('success', 'Alerta leída');
    }

    public function eliminarAlerta($id)
    {
        MedicalAlert::findOrFail($id)->delete();
        return back()->with('success', 'Alerta eliminada');
    }

    public function reportes()
    {
        $role = session('doctor_profile');
        if ($role === 'Médico C') return redirect()->route('medico.dashboard')->with('error', 'Sin acceso');
        $uid = Auth::id();
        $pacientesAtendidos = Triage::where('assigned_doctor', $uid)->count();
        $recetasEmitidas = \DB::table('prescriptions')->where('doctor_id', $uid)->count();
        $estudiosSolicitados = \DB::table('medical_studies')->where('doctor_id', $uid)->count();
        $altasDadas = Triage::where('discharge_doctor_id', $uid)->where('status', 'Alta')->count();
        return view('medico.reportes', compact('role', 'pacientesAtendidos', 'recetasEmitidas', 'estudiosSolicitados', 'altasDadas'));
    }

    public function exportReportesPDF()
    {
        $pacientes = Triage::where('assigned_doctor', Auth::id())->get();
        $pdf = \PDF::loadView('medico.pdf.reportes', compact('pacientes'));
        return $pdf->download('Reporte_Medico_' . date('Y-m-d') . '.pdf');
    }
    

    public function pacientesHospitalizados() {
        $pacientes = Triage::where('status', 'Hospitalizado')
            ->whereNotNull('assigned_doctor_id')
            ->where('assigned_doctor_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->paginate(30);
        $pendientes = Triage::where('status', 'Hospitalizado')
            ->whereNull('assigned_doctor_id')
            ->orderBy('created_at', 'desc')
            ->take(20)
            ->get();
        $medicosBC = User::whereIn('role', ['Médico B', 'Médico C'])->where('status', 'Activo')->get();
        return view('medico.hospitalizados', compact('pacientes', 'pendientes', 'medicosBC'));
    }

    public function aceptarPaciente(Request $request, $id) {
        $triage = Triage::findOrFail($id);
        $triage->update(['assigned_doctor_id' => auth()->id()]);
        AuditLog::create([
            'user_id' => auth()->id(), 'user_name' => auth()->user()->name,
            'user_role' => auth()->user()->role, 'action' => 'Paciente Aceptado',
            'module' => 'Médico - Hospitalizados', 'ip_address' => $request->ip(),
            'details' => $triage->patient_name . ' aceptado por ' . auth()->user()->name
        ]);
        return back()->with('success', "Paciente {$triage->patient_name} aceptado.");
    }


    // ==========================================
    // AMBULANCIAS Y TRASLADOS - MEDICO A/B
    // ==========================================
    public function ambulancias()
    {
        $role = session('doctor_profile', 'Médico C');
        $isA = $role === 'Médico A';
        $doctorId = auth()->id();

        $ambulancias = \App\Models\Ambulance::orderBy('status')->orderBy('priority','desc')->get();
        $disponibles = $ambulancias->where('status','Disponible')->count();
        $activas = $ambulancias->where('status','En Ruta')->count();
        $misTraslados = \App\Models\Triage::where('assigned_doctor_id', $doctorId)->where('status','En Traslado')->count();
        $criticosPendientes = \App\Models\Triage::where('triage_level','Rojo')->whereIn('status',['En Espera','En Atencion'])->whereNull('assigned_doctor_id')->count();
        $misPacientes = \App\Models\Triage::where('assigned_doctor_id', $doctorId)->whereIn('status',['En Atencion','Hospitalizado'])->limit(20)->get();
        $misPacientesTraslado = \App\Models\Triage::where('assigned_doctor_id', $doctorId)->where('status','En Traslado')->get();

        return view('medico.ambulancias', compact('role','isA','ambulancias','disponibles','activas','misTraslados','criticosPendientes','misPacientes','misPacientesTraslado'));
    }

    public function hospitalLive()
    {
        $role = session('doctor_profile', 'Médico C');
        $doctorId = auth()->id();

        $hospitalizados = \App\Models\Triage::where('status','Hospitalizado')->count();
        $enAtencion = \App\Models\Triage::where('status','En Atencion')->count();
        $enEspera = \App\Models\Triage::where('status','En Espera')->count();
        $criticos = \App\Models\Triage::where('triage_level','Rojo')->count();
        $ambActivas = \App\Models\Ambulance::where('status','En Ruta')->count();

        $modoCrisis = false;
        $areas = collect([
            ['name'=>'Urgencias','pacientes'=>$enEspera,'capacidad'=>30,'color'=>'#DC2626'],
            ['name'=>'Hospitalizacion','pacientes'=>$hospitalizados,'capacidad'=>50,'color'=>'#EA580C'],
            ['name'=>'Consultas','pacientes'=>$enAtencion,'capacidad'=>20,'color'=>'#F97316'],
            ['name'=>'UCI','pacientes'=>$criticos,'capacidad'=>10,'color'=>'#C7291C'],
            ['name'=>'Ambulancias','pacientes'=>$ambActivas,'capacidad'=>8,'color'=>'#F05A4E'],
        ])->map(function($a) use (&$modoCrisis) {
            $a['pct'] = round(($a['pacientes']/max($a['capacidad'],1))*100);
            $a['status'] = $a['pct']>90?'CRITICO':($a['pct']>70?'ALERTA':'NORMAL');
            $a['status_color'] = $a['pct']>90?'#DC2626':($a['pct']>70?'#F59E0B':'#16A34A');
            $a['border'] = $a['status_color'];
            $a['bg'] = $a['pct']>90?'#FEF2F2':($a['pct']>70?'#FFFBEB':'#F0FDF4');
            if ($a['pct']>90) $modoCrisis = true;
            return $a;
        });

        if ($areas->where('status','CRITICO')->count()>=2) $modoCrisis = true;

        $misCriticos = \App\Models\Triage::where('assigned_doctor_id',$doctorId)->where('triage_level','Rojo')->get();
        $metricas = collect([
            ['label'=>'En Espera','valor'=>$enEspera,'color'=>'#DC2626'],
            ['label'=>'En Atencion','valor'=>$enAtencion,'color'=>'#EA580C'],
            ['label'=>'Hospitalizados','valor'=>$hospitalizados,'color'=>'#F97316'],
            ['label'=>'Criticos','valor'=>$criticos,'color'=>'#C7291C'],
            ['label'=>'Ambulancias','valor'=>$ambActivas,'color'=>'#7C3AED'],
        ]);

        return view('medico.hospital-live', compact('role','modoCrisis','areas','misCriticos','metricas'));
    }

    public function asistenteIA()
    {
        $role = session('doctor_profile', 'Médico C');
        $doctorName = session('doctor_name', 'Médico');
        $doctorId = auth()->id();
        $misPacientes = \App\Models\Triage::where('assigned_doctor_id',$doctorId)->whereIn('status',['En Atencion','Hospitalizado'])->limit(15)->get();

        return view('medico.asistente-ia', compact('role','doctorName','misPacientes'));
    }
}
