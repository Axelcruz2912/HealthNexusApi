<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Triage;
use App\Models\User;
use App\Models\Specialty;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    // Agenda Principal - Cada quien ve lo suyo
    public function index()
    {
        $user = auth()->user();
        $today = today();

        // SuperAdmin y Recepcionista ven todo
        if (in_array($user->role, ['SuperAdmin', 'Recepcionista'])) {
            $appointments = Appointment::with('patient', 'doctor', 'specialty')
                ->whereDate('scheduled_at', $today)
                ->orderBy('scheduled_at')
                ->get();
            $pending = Appointment::where('status', 'Programada')->whereDate('scheduled_at', $today)->count();
        } 
        // Médicos y Especialistas ven SUS citas
        elseif (in_array($user->role, ['Médico A', 'Médico B', 'Médico C', 'Especialista', 'Urgenciólogo'])) {
            $appointments = Appointment::with('patient', 'doctor', 'specialty')
                ->where('doctor_id', $user->id)
                ->whereDate('scheduled_at', $today)
                ->orderBy('scheduled_at')
                ->get();
            $pending = $appointments->where('status', 'Programada')->count();
        }
        // Enfermería ve monitoreos y hospitalización
        elseif (in_array($user->role, ['Enfermera A', 'Enfermera B', 'Enfermera C'])) {
            $appointments = Appointment::with('patient', 'doctor', 'specialty')
                ->whereIn('type', ['Monitoreo', 'Hospitalización', 'Curación'])
                ->whereDate('scheduled_at', $today)
                ->orderBy('scheduled_at')
                ->get();
            $pending = $appointments->where('status', 'Programada')->count();
        }
        // Farmacia ve surtidos programados
        elseif (in_array($user->role, ['Farmacéutico', 'Admin Farmacia'])) {
            $appointments = Appointment::with('patient', 'doctor', 'specialty')
                ->where('type', 'Surtido')
                ->whereDate('scheduled_at', '>=', $today)
                ->orderBy('scheduled_at')
                ->take(20)
                ->get();
            $pending = $appointments->where('status', 'Programada')->count();
        }
        else {
            $appointments = collect([]);
            $pending = 0;
        }

        // Datos para crear citas
        $patients = Triage::whereIn('status', ['En Espera', 'En Atención', 'Hospitalizado'])->orderBy('patient_name')->get();
        $doctors = User::whereIn('role', ['Médico A', 'Médico B', 'Médico C', 'Especialista', 'Urgenciólogo'])->where('status', 1)->get();
        $specialties = Specialty::where('is_active', true)->orderBy('name')->get();

        return view('agenda.index', compact('appointments', 'pending', 'patients', 'doctors', 'specialties'));
    }

    // Crear Cita
    public function store(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:triages,id',
            'doctor_id' => 'required|exists:users,id',
            'scheduled_at' => 'required|date',
            'type' => 'required',
        ]);

        $appointment = Appointment::create([
            'patient_id' => $request->patient_id,
            'doctor_id' => $request->doctor_id,
            'specialty_id' => $request->specialty_id ?? null,
            'created_by' => auth()->id(),
            'scheduled_at' => $request->scheduled_at,
            'estimated_end' => $request->estimated_end ?? null,
            'type' => $request->type,
            'status' => 'Programada',
            'priority' => $request->priority ?? 'Normal',
            'location' => $request->location ?? null,
            'notes' => $request->notes ?? null,
        ]);

        AuditLog::create([
            'user_id' => auth()->id(), 'user_name' => auth()->user()->name,
            'user_role' => auth()->user()->role, 'action' => 'Cita Agendada',
            'module' => 'Agenda Central', 'ip_address' => $request->ip(),
            'details' => "Paciente: {$appointment->patient->patient_name} | Dr: {$appointment->doctor->name} | Tipo: {$request->type}"
        ]);

        return back()->with('success', "Cita programada para {$appointment->scheduled_at->format('H:i')}");
    }

    // Cambiar estado de la cita
    public function updateStatus(Request $request, $id)
    {
        $appointment = Appointment::findOrFail($id);
        $appointment->update(['status' => $request->status]);
        
        return back()->with('success', "Cita marcada como {$request->status}");
    }
}
