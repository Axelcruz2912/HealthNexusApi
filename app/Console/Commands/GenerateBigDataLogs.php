<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\MongoTriageLog;
use Carbon\Carbon;

class GenerateBigDataLogs extends Command {
    protected $signature = 'bigdata:generate-logs {--records=3000}';
    protected $description = 'Genera registros masivos con horario realista de Urgencias';

    public function handle() {
        $totalRecords = $this->option('records');
        $this->info("Generando {$totalRecords} registros de Triage en MongoDB Atlas...");

        $doctores = ['Médico A', 'Médico A', 'Médico A', 'Médico B', 'Médico B', 'Médico C'];
        $especialidades = ['Cardiología', 'Urgencias', 'Pediatría', 'Cirugía General', 'Medicina Interna'];
        $triageLevels = ['Rojo', 'Naranja', 'Amarillo', 'Verde', 'Azul'];
        
        $data = [];
        for ($i = 0; $i < $totalRecords; $i++) {
            $edad = rand(1, 95);
            $triage = $triageLevels[array_rand($triageLevels)];
            
            $fc = $triage === 'Rojo' ? rand(110, 160) : rand(60, 100);
            $temp = $triage === 'Rojo' ? rand(375, 400) / 10 : rand(360, 375) / 10;
            $spo2 = $triage === 'Rojo' ? rand(80, 92) : rand(93, 100);

            // MAGIA HOSPITALARIA: 70% de probabilidad de que el paciente llegue de día (8 AM - 8 PM)
            $hora = rand(0, 100) < 70 ? rand(8, 20) : rand(0, 7);

            $randomDate = Carbon::now()
                ->subDays(rand(0, 180))
                ->setTime($hora, rand(0, 59), rand(0, 59));

            $data[] = [
                'patient_id' => 'PAC-' . rand(1000, 9999),
                'triage_level' => $triage,
                'age' => $edad,
                'specialty' => $especialidades[array_rand($especialidades)],
                'vitals_fc' => $fc,
                'vitals_temp' => $temp,
                'vitals_spo2' => $spo2,
                'assigned_doctor_id' => $doctores[array_rand($doctores)],
                'is_derived' => (bool)rand(0, 1),
                'timestamp' => $randomDate->toDateTimeString()
            ];
        }

        foreach (array_chunk($data, 500) as $chunk) {
            MongoTriageLog::insert($chunk);
        }

        $this->info("¡Generación completada! Total insertados: {$totalRecords}");
    }
}
