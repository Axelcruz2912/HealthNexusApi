<?php
namespace Database\Seeders;
use App\Models\Triage;
use Illuminate\Database\Seeder;
class TriageSeeder extends Seeder {
    public function run(): void {
        Triage::create(['patient_name' => 'Juan Pérez', 'age' => 45, 'triage_level' => 'Rojo', 'symptoms' => 'Dolor torácico agudo, dificultad para respirar.', 'status' => 'En Atención']);
        Triage::create(['patient_name' => 'María López', 'age' => 8, 'triage_level' => 'Amarillo', 'symptoms' => 'Fiebre de 38.5°C, dolor abdominal moderado.', 'status' => 'En Espera']);
        Triage::create(['patient_name' => 'Carlos Sánchez', 'age' => 29, 'triage_level' => 'Verde', 'symptoms' => 'Corte superficial en mano derecha.', 'status' => 'En Espera']);
        Triage::create(['patient_name' => 'Ana Gómez', 'age' => 67, 'triage_level' => 'Naranja', 'symptoms' => 'Confusión severa, fiebre alta.', 'status' => 'En Atención']);
    }
}
