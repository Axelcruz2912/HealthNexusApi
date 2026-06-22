<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ambulance;
use App\Models\Triage;
use App\Models\User;
use App\Models\AuditLog;

class AmbulanceController extends Controller
{
    public function index()
    {
        $ambulancias = Ambulance::orderBy('status', 'asc')->orderBy('priority', 'desc')->get();
        $activas = $ambulancias->where('status', 'En Ruta')->count();
        $disponibles = $ambulancias->where('status', 'Disponible')->count();
        $criticosEnRuta = $ambulancias->where('status', 'En Ruta')->where('priority', 'Critica')->count();

        $pacientesCriticos = Triage::where('triage_level', 'Rojo')
            ->whereIn('status', ['En Espera', 'En Atencion'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        $conductores = User::where('role', 'like', '%Ambulancia%')
            ->orWhere('role', 'Conductor')
            ->get();

        if ($conductores->count() === 0) {
            $conductores = User::whereIn('role', ['Medico A', 'Medico B', 'Especialista'])
                ->limit(5)->get();
        }

        return view('especialidades.ambulancias', compact(
            'ambulancias', 'activas', 'disponibles', 'criticosEnRuta',
            'pacientesCriticos', 'conductores'
        ));
    }

    public function despachar(Request $request)
    {
        $request->validate([
            'ambulance_id' => 'required|exists:ambulances,id',
            'origin' => 'required|string',
            'destination' => 'required|string',
            'priority' => 'required|in:Normal,Urgente,Critica',
        ]);

        $ambulancia = Ambulance::findOrFail($request->ambulance_id);
        $ambulancia->update([
            'status' => 'En Ruta',
            'origin' => $request->origin,
            'destination' => $request->destination,
            'priority' => $request->priority,
            'patient_id' => $request->patient_id,
            'driver_id' => $request->driver_id,
            'dispatched_at' => now(),
            'notes' => $request->notes,
            'iot_data' => [
                'speed' => 0,
                'fuel' => rand(60, 100),
                'heart_rate' => rand(60, 120),
                'oxygen' => rand(85, 100),
                'temperature' => rand(36, 38),
            ],
        ]);

        if ($request->patient_id) {
            $triage = Triage::find($request->patient_id);
            if ($triage) {
                $triage->update(['status' => 'En Traslado']);
            }
        }

        AuditLog::create([
            'user_id' => auth()->id(),
            'user_name' => auth()->user()->name,
            'user_role' => auth()->user()->role,
            'action' => 'Ambulancia Despachada',
            'module' => 'Ambulancias',
            'ip_address' => $request->ip(),
            'details' => $ambulancia->code . ' hacia ' . $request->destination,
        ]);

        return back()->with('success', 'Ambulancia ' . $ambulancia->code . ' despachada.');
    }

    public function actualizarIot(Request $request, $id)
    {
        $ambulancia = Ambulance::findOrFail($id);
        $current = $ambulancia->iot_data ?? [];

        $ambulancia->update([
            'iot_data' => [
                'speed' => rand(20, 90),
                'fuel' => isset($current['fuel']) ? max(10, $current['fuel'] - rand(0, 3)) : rand(40, 100),
                'heart_rate' => rand(55, 130),
                'oxygen' => rand(80, 100),
                'temperature' => round(rand(350, 390) / 10, 1),
            ],
            'latitude' => 19.4326 + (rand(-200, 200) / 10000),
            'longitude' => -99.1332 + (rand(-200, 200) / 10000),
        ]);

        return response()->json($ambulancia->iot_data);
    }

    public function llegada($id)
    {
        $ambulancia = Ambulance::findOrFail($id);
        $ambulancia->update([
            'status' => 'Disponible',
            'arrived_at' => now(),
            'patient_id' => null,
            'driver_id' => null,
            'origin' => null,
            'destination' => null,
            'priority' => 'Normal',
            'notes' => null,
            'dispatched_at' => null,
        ]);

        return back()->with('success', 'Ambulancia ' . $ambulancia->code . ' registrada como disponible.');
    }

    public function hospitalLive()
    {
        $hospitalizados = Triage::where('status', 'Hospitalizado')->count();
        $enAtencion = Triage::where('status', 'En Atencion')->count();
        $enEspera = Triage::where('status', 'En Espera')->count();
        $criticos = Triage::where('triage_level', 'Rojo')->count();
        $ambulanciasActivas = Ambulance::where('status', 'En Ruta')->count();

        $areas = collect([
            ['name' => 'Urgencias', 'patients' => $enEspera, 'capacity' => 30, 'color' => '#DC2626'],
            ['name' => 'Hospitalizacion', 'patients' => $hospitalizados, 'capacity' => 50, 'color' => '#2563EB'],
            ['name' => 'Consultas', 'patients' => $enAtencion, 'capacity' => 20, 'color' => '#16A34A'],
            ['name' => 'UCI', 'patients' => $criticos, 'capacity' => 10, 'color' => '#7C3AED'],
            ['name' => 'Ambulancias', 'patients' => $ambulanciasActivas, 'capacity' => 8, 'color' => '#EA580C'],
        ]);

        $saturacion = $areas->map(function($a) {
            $a['pct'] = round(($a['patients'] / max($a['capacity'], 1)) * 100);
            $a['status'] = $a['pct'] > 90 ? 'CRITICO' : ($a['pct'] > 70 ? 'ALERTA' : 'NORMAL');
            return $a;
        });

        $modoCrisis = $saturacion->where('status', 'CRITICO')->count() >= 2;

        return view('especialidades.hospital-live', compact(
            'saturacion', 'modoCrisis', 'hospitalizados',
            'enAtencion', 'enEspera', 'criticos', 'ambulanciasActivas'
        ));
    }
}
