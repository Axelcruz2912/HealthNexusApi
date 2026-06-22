<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SeedAlternatives extends Command
{
    protected $signature = 'pharmacy:alternatives';
    protected $description = 'Seed medication alternatives with real IDs';

    public function handle()
    {
        // Limpiar anteriores
        DB::table('medication_alternatives')->truncate();

        // Buscar medicamentos por nombre
        $morfina = DB::table('medications')->where('name', 'LIKE', '%Morfina%')->first();
        $paracetamol = DB::table('medications')->where('name', 'LIKE', '%Paracetamol%')->first();
        $ketorolaco = DB::table('medications')->where('name', 'LIKE', '%Ketorolaco%')->first();
        $ibuprofeno = DB::table('medications')->where('name', 'LIKE', '%Ibuprofeno%')->first();
        $amoxicilina = DB::table('medications')->where('name', 'LIKE', '%Amoxicilina%')->first();
        $ceftriaxona = DB::table('medications')->where('name', 'LIKE', '%Ceftriaxona%')->first();
        $omeprazol = DB::table('medications')->where('name', 'LIKE', '%Omeprazol%')->first();
        $fentanilo = DB::table('medications')->where('name', 'LIKE', '%Fentanilo%')->first();
        $midazolam = DB::table('medications')->where('name', 'LIKE', '%Midazolam%')->first();
        $diazepam = DB::table('medications')->where('name', 'LIKE', '%Diazepam%')->first();
        $adrenalina = DB::table('medications')->where('name', 'LIKE', '%Adrenalina%')->first();

        $inserted = 0;

        // Morfina -> Paracetamol
        if ($morfina && $paracetamol) {
            DB::table('medication_alternatives')->insert(['medication_id' => $morfina->id, 'alternative_id' => $paracetamol->id, 'notes' => 'Solo para dolor leve-moderado. No equivalente para dolor severo.', 'created_at' => now(), 'updated_at' => now()]);
            $inserted++;
        }
        // Morfina -> Ketorolaco
        if ($morfina && $ketorolaco) {
            DB::table('medication_alternatives')->insert(['medication_id' => $morfina->id, 'alternative_id' => $ketorolaco->id, 'notes' => 'AINE potente. Menor riesgo respiratorio pero irritacion gastrica.', 'created_at' => now(), 'updated_at' => now()]);
            $inserted++;
        }
        // Morfina -> Fentanilo
        if ($morfina && $fentanilo) {
            DB::table('medication_alternatives')->insert(['medication_id' => $morfina->id, 'alternative_id' => $fentanilo->id, 'notes' => 'Opiode alternativo. Mayor potencia. Ajustar dosis.', 'created_at' => now(), 'updated_at' => now()]);
            $inserted++;
        }
        // Ketorolaco -> Ibuprofeno
        if ($ketorolaco && $ibuprofeno) {
            DB::table('medication_alternatives')->insert(['medication_id' => $ketorolaco->id, 'alternative_id' => $ibuprofeno->id, 'notes' => 'Menor potencia pero disponible. Dolor moderado.', 'created_at' => now(), 'updated_at' => now()]);
            $inserted++;
        }
        // Ketorolaco -> Paracetamol
        if ($ketorolaco && $paracetamol) {
            DB::table('medication_alternatives')->insert(['medication_id' => $ketorolaco->id, 'alternative_id' => $paracetamol->id, 'notes' => 'Para fiebre y dolor leve. Sin efecto antiinflamatorio.', 'created_at' => now(), 'updated_at' => now()]);
            $inserted++;
        }
        // Amoxicilina -> Ceftriaxona
        if ($amoxicilina && $ceftriaxona) {
            DB::table('medication_alternatives')->insert(['medication_id' => $amoxicilina->id, 'alternative_id' => $ceftriaxona->id, 'notes' => 'Cefalosporina de mayor espectro. Via IV.', 'created_at' => now(), 'updated_at' => now()]);
            $inserted++;
        }
        // Fentanilo -> Morfina
        if ($fentanilo && $morfina) {
            DB::table('medication_alternatives')->insert(['medication_id' => $fentanilo->id, 'alternative_id' => $morfina->id, 'notes' => 'Opiode alternativo. Menor potencia que fentanilo.', 'created_at' => now(), 'updated_at' => now()]);
            $inserted++;
        }
        // Midazolam -> Diazepam
        if ($midazolam && $diazepam) {
            DB::table('medication_alternatives')->insert(['medication_id' => $midazolam->id, 'alternative_id' => $diazepam->id, 'notes' => 'Benzodiacepina alternativa. Mayor duracion de accion.', 'created_at' => now(), 'updated_at' => now()]);
            $inserted++;
        }

        $this->info("{$inserted} alternativas insertadas correctamente!");
    }
}
