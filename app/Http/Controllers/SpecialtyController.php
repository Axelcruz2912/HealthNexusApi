<?php

namespace App\Http\Controllers;

use App\Models\Triage;
use App\Models\Specialty;
use App\Models\User;
use App\Models\Appointment;
use App\Models\Bed;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class SpecialtyController extends Controller
{
    public function index()
    {
        $specialties = Specialty::where('is_active', true)->withCount('doctors')->orderBy('name')->get();
        $criticalPatients = Triage::where('triage_level', 'Rojo')
            ->whereIn('status', ['En Espera', 'En Atención'])
            ->whereNull('assigned_doctor_id')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();
        $hospitalized = Triage::where('status', 'Hospitalizado')->count();
        $bedsAvailable = Bed::where('status', 'Disponible')->count();

        return view('especialidades.index', compact('specialties', 'criticalPatients', 'hospitalized', 'bedsAvailable'));
    }

    public function show($id)
    {
        $specialty = Specialty::findOrFail($id);
        $patients = Triage::where('assigned_doctor_id', auth()->id())
            ->whereIn('status', ['En Atención', 'Hospitalizado'])
            ->orderBy('triage_level', 'asc')
            ->paginate(30);
        $doctors = User::where('specialty_id', $id)->where('status', 1)->get();
        $criticalPatients = Triage::where('triage_level', 'Rojo')
            ->whereIn('status', ['En Espera', 'En Atención'])
            ->whereNull('assigned_doctor_id')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        return view('especialidades.show', compact('specialty', 'patients', 'doctors', 'criticalPatients'));
    }

    public function derivar(Request $request, $patientId)
    {
        $request->validate(['specialty_id' => 'required|exists:specialties,id']);
        $triage = Triage::findOrFail($patientId);
        $specialty = Specialty::findOrFail($request->specialty_id);

        // Buscar médico disponible de esa especialidad
        $doctor = User::where('specialty_id', $request->specialty_id)
            ->where('status', 1)
            ->inRandomOrder()
            ->first();

        if ($doctor) {
            $triage->update(['assigned_doctor_id' => $doctor->id]);
            $msg = "Paciente {$triage->patient_name} derivado a {$specialty->name} - Dr. {$doctor->name}";
        } else {
            $msg = "Paciente {$triage->patient_name} asignado a {$specialty->name} (sin médico disponible aún)";
        }

        AuditLog::create([
            'user_id' => auth()->id(),
            'user_name' => auth()->user()->name,
            'user_role' => auth()->user()->role,
            'action' => 'Derivación a Especialidad',
            'module' => 'Especialidades',
            'ip_address' => $request->ip(),
            'details' => $msg
        ]);

        return back()->with('success', $msg);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'icon' => 'required',
            'color' => 'required',
        ]);

        Specialty::create([
            'name' => $request->name,
            'description' => $request->description,
            'icon' => $request->icon,
            'color' => $request->color,
            'ia_config' => $request->ia_config,
            'restricted_medications' => $request->restricted_medications,
            'permissions' => $request->permissions,
            'is_active' => true,
        ]);

        return back()->with('success', "Especialidad {$request->name} creada.");
    }

    public function asignarMedico(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'specialty_id' => 'required|exists:specialties,id',
        ]);

        $user = User::findOrFail($request->user_id);
        $user->update(['specialty_id' => $request->specialty_id]);

        return back()->with('success', "Dr. {$user->name} asignado a especialidad.");
    }
}
