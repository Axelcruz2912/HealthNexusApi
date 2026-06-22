<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\MongoTriageLog;
use Carbon\Carbon;

class SeedBigDataToday extends Command
{
    protected $signature = 'bigdata:seed-today {--count=2000}';
    protected $description = 'Inyecta registros en MongoDB Atlas con fecha de HOY para probar el dashboard en vivo';

    public function handle()
    {
        $count = $this->option('count');
        $this->info("Inyectando {$count} registros en MongoDB Atlas (Fecha: Hoy)...");

        $levels = ['Rojo', 'Naranja', 'Amarillo', 'Verde', 'Azul'];
        $specs = ['Urgencias', 'Pediatría', 'Cirugía General', 'Medicina Interna', 'Traumatología'];

        for ($i = 0; $i < $count; $i++) {
            MongoTriageLog::create([
                'patient_id' => 'PAC-' . rand(10000, 99999),
                'triage_level' => $levels[array_rand($levels)],
                'age' => rand(1, 95),
                'specialty' => $specs[array_rand($specs)],
                'vitals_fc' => rand(40, 200),
                'vitals_temp' => round(mt_rand(350, 420) / 10, 1),
                'vitals_spo2' => rand(70, 100),
                'assigned_doctor_id' => 'DOC-' . rand(1, 50),
                'is_derived' => (bool)rand(0, 1),
                'timestamp' => Carbon::today()->addHours(rand(0, 23))->addMinutes(rand(0, 59))
            ]);

            // Mostrar progreso cada 500
            if ((($i + 1) % 500 === 0) || ($i + 1 === (int)$count)) {
                $this->line("-> " . ($i + 1) . " registros insertados...");
            }
        }

        $this->info("\nEXITO: Se insertaron {$count} registros en Atlas con fecha de hoy.");
        $this->warn("Recarga tu panel de Enfermería o Urgencias para ver los datos en vivo.");
    }
}
