<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Triage;
use App\Models\Bed;

class PatientController extends Controller
{
    public function pacientesParaCamas()
    {
        $pacientes = Triage::whereIn('status', ['En Atención', 'Hospitalizado', 'En Espera'])
            ->select('id', 'patient_name', 'triage_level', 'status', 'bed_id')
            ->orderBy('triage_level')
            ->get();
            
        return response()->json($pacientes);
    }
}
