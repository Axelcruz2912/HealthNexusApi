<?php
namespace App\Http\Controllers;
use App\Models\Triage;
use App\Models\Specialty;
use App\Models\Appointment;
use App\Models\Bed;
use Illuminate\Http\Request;
class SpecialistController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user()->load('specialty');
        $mySpecialty = $user->specialty;
        
        $myPatients = Triage::where('assigned_doctor_id', $user->id)
            ->whereIn('status', ['En Atención', 'Hospitalizado'])
            ->orderBy('triage_level', 'asc')->take(15)->get();
            
        $criticalPatients = Triage::where('triage_level', 'Rojo')
            ->whereIn('status', ['En Espera', 'En Atención'])
            ->whereNull('assigned_doctor_id')
            ->orderBy('created_at', 'desc')->take(10)->get();
            
        $specialties = Specialty::where('is_active', true)->withCount('doctors')->orderBy('name')->get();
        
        $todayAppointments = Appointment::where('doctor_id', $user->id)
            ->whereDate('scheduled_at', today())->orderBy('scheduled_at')->get();
            
        $hospitalized = Triage::where('status', 'Hospitalizado')->count();
        $bedsAvailable = Bed::where('status', 'Disponible')->count();

        return view('especialidades.dashboard', compact(
            'user', 'mySpecialty', 'myPatients', 'criticalPatients', 
            'specialties', 'todayAppointments', 'hospitalized', 'bedsAvailable'
        ));
    }

    public function agenda() { return view('especialidades.agenda'); }
    public function misPacientes() { return view('especialidades.pacientes'); }
    public function hospitalizados() { $misHospitalizados = \App\Models\Triage::where('status', 'Hospitalizado')->orderBy('created_at', 'desc')->paginate(30); return view('especialidades.hospitalizados', compact('misHospitalizados')); }
    public function derivaciones() { return view('especialidades.derivaciones'); }
    public function reportes() { return view('especialidades.reportes'); }
    public function iaMedica() { return view('especialidades.ia-medica'); }
    public function medicamentos() { return view('especialidades.medicamentos'); }
    public function crearDerivacion(Request $request) { return redirect()->route('especialista.derivaciones')->with('status', 'Derivación creada'); }
    public function aceptarDerivacion($id) { return redirect()->route('especialista.derivaciones')->with('status', 'Derivación aceptada'); }
    public function rechazarDerivacion($id) { return redirect()->route('especialista.derivaciones')->with('status', 'Derivación rechazada'); }
    public function reagendarDerivacion($id) { return redirect()->route('especialista.derivaciones')->with('status', 'Derivación reagendada'); }
    public function aceptarPaciente($id) { return redirect()->back()->with('status', 'Paciente aceptado'); }
    public function derivarPaciente($id) { return redirect()->back()->with('status', 'Paciente derivado'); }
}
