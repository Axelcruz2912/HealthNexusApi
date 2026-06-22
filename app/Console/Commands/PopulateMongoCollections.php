<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\MongoTriageLog;
use App\Models\DwhMongoStat;
use App\Models\MongoHospitalizationLog;
use App\Models\MongoMedicationLog;
use App\Models\MongoDoctorLog;
use App\Models\MongoBillingLog;
use Carbon\Carbon;

class PopulateMongoCollections extends Command {
    protected $signature = 'bigdata:populate {--records=500}';
    protected $description = 'Puebla 4 colecciones adicionales en MongoDB Atlas con datos sintéticos';

    public function handle() {
        $limit = $this->option('records');
        $this->info("Generando {$limit} registros en 4 colecciones nuevas de Atlas...");

        $medicamentos = ['Paracetamol', 'Ibuprofeno', 'Amoxicilina', 'Omeprazol', 'Losartán', 'Metformina'];
        $acciones = ['Consulta', 'Cirugía Menor', 'Receta', 'Alta', 'Revisión'];
        $estatus = ['Pagada', 'Pendiente', 'Seguro Aplicado', 'Cancelada'];
        $camas = ['ICU-01', 'A-12', 'B-04', 'C-22', 'D-08', 'UCI-05'];

        // Hospitalizations
        $hospData = [];
        for ($i=0; $i < $limit; $i++) {
            $hospData[] = [
                'patient_id' => 'PAC-' . rand(1000, 9999),
                'bed_number' => $camas[array_rand($camas)],
                'admission_date' => Carbon::now()->subDays(rand(0, 90))->toDateTimeString(),
                'discharge_date' => rand(0,1) ? Carbon::now()->subDays(rand(0, 30))->toDateTimeString() : null,
                'status' => rand(0,1) ? 'Activa' : 'Dada de alta',
            ];
        }
        foreach (array_chunk($hospData, 200) as $chunk) { MongoHospitalizationLog::insert($chunk); }
        $this->info("✔ hospitalization_logs poblada ({$limit})");

        // Medications
        $medData = [];
        for ($i=0; $i < $limit; $i++) {
            $medData[] = [
                'patient_id' => 'PAC-' . rand(1000, 9999),
                'medication_name' => $medicamentos[array_rand($medicamentos)],
                'dosage' => rand(1, 3) . ' tableta(s)',
                'administered_at' => Carbon::now()->subDays(rand(0, 90))->toDateTimeString(),
                'nurse_id' => 'ENF-' . rand(10, 50),
            ];
        }
        foreach (array_chunk($medData, 200) as $chunk) { MongoMedicationLog::insert($chunk); }
        $this->info("✔ medication_dispense_logs poblada ({$limit})");

        // Doctor Activity
        $docData = [];
        for ($i=0; $i < $limit; $i++) {
            $docData[] = [
                'doctor_id' => 'DOC-' . rand(1, 20),
                'action' => $acciones[array_rand($acciones)],
                'patient_id' => 'PAC-' . rand(1000, 9999),
                'timestamp' => Carbon::now()->subDays(rand(0, 90))->toDateTimeString(),
                'specialty' => 'Especialidad-' . rand(1, 5),
            ];
        }
        foreach (array_chunk($docData, 200) as $chunk) { MongoDoctorLog::insert($chunk); }
        $this->info("✔ doctor_activity_logs poblada ({$limit})");

        // Billing
        $billData = [];
        for ($i=0; $i < $limit; $i++) {
            $billData[] = [
                'invoice_id' => 'INV-' . rand(10000, 99999),
                'patient_id' => 'PAC-' . rand(1000, 9999),
                'amount' => rand(500, 50000) / 100 * 100,
                'status' => $estatus[array_rand($estatus)],
                'timestamp' => Carbon::now()->subDays(rand(0, 90))->toDateTimeString(),
            ];
        }
        foreach (array_chunk($billData, 200) as $chunk) { MongoBillingLog::insert($chunk); }
        $this->info("✔ billing_events poblada ({$limit})");

        $this->info("¡Poblamiento masivo completado en Atlas!");
    }
}
